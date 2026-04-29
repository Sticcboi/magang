<?php
require_once __DIR__ . '/auth.php';

$page_title = 'Manajemen Bidang Pendidikan';
$current_page = 'admin_pendidikan';
$msg = '';

// Buat tabel sekolah jika belum ada
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS sekolah_pendidikan (
  id int(11) NOT NULL AUTO_INCREMENT,
  jenjang enum('SD','SMP','SMA/SMK') NOT NULL DEFAULT 'SD',
  nama_sekolah varchar(255) NOT NULL,
  alamat varchar(255) DEFAULT NULL,
  data_map varchar(500) DEFAULT NULL,
  maps_url varchar(500) DEFAULT NULL,
  is_active tinyint(1) DEFAULT 1,
  urutan int(11) DEFAULT 0,
  created_at timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");

// Pastikan kolom maps_url ada jika database lama
mysqli_query($conn, "ALTER TABLE sekolah_pendidikan ADD COLUMN IF NOT EXISTS maps_url varchar(500) DEFAULT NULL AFTER data_map");

// Cek apakah tabel masih kosong, jika iya isi data default dari template
$cek = mysqli_query($conn, "SELECT COUNT(*) as total FROM sekolah_pendidikan");
$total = mysqli_fetch_assoc($cek)['total'];
if ($total == 0) {
    $defaults = [
        ['SD', 'SD Negeri Kedungpane 01', 'Jl. Dawung RT 04 / RW 03', 'SD Negeri Kedungpane 01, Jl. Dawung RT 04 / RW 03, Semarang, Jawa Tengah, Indonesia', 1],
        ['SD', 'SD Negeri Kedungpane 02', 'Jl. Untung Surapati', 'SD Negeri Kedungpane 02, Jl. Untung Surapati, Semarang, Jawa Tengah, Indonesia', 2],
        ['SMP', 'SMP Islam Al Azhar 29', 'Jl. RM Hadisoebeno Sosrowardoyo BSB, Kedungpane', 'SMP Islam Al Azhar 29 Semarang, Jl. RM Hadisoebeno Sosrowardoyo BSB, Kedungpane, Mijen, Semarang', 1],
        ['SMP', 'SMP Negeri 23 Semarang', 'Jl. RM. Hadi Soebeno (Zonasi Utama Kedungpane)', 'SMP Negeri 23 Semarang, Jl. RM. Hadi Soebeno, Mijen, Semarang', 2],
        ['SMA/SMK', 'SMA Islam Al Azhar 16', 'Jl. RM Hadi Soebeno S Komplek BSB, Kedungpane', 'SMA Islam Al Azhar 16 Semarang, Jl. RM Hadi Soebeno S Komplek BSB, Kedungpane, Mijen, Semarang', 1],
        ['SMA/SMK', 'SMK Palapa', 'Jl. Untung Surapati, Kelurahan Kedungpane', 'SMK Palapa Semarang, Jl. Untung Surapati, Kedungpane, Mijen, Semarang', 2],
    ];
    foreach ($defaults as $d) {
        $j = mysqli_real_escape_string($conn, $d[0]);
        $n = mysqli_real_escape_string($conn, $d[1]);
        $a = mysqli_real_escape_string($conn, $d[2]);
        $m = mysqli_real_escape_string($conn, $d[3]);
        $u = (int)$d[4];
        mysqli_query($conn, "INSERT INTO sekolah_pendidikan (jenjang, nama_sekolah, alamat, data_map, urutan) VALUES ('$j','$n','$a','$m',$u)");
    }
}

// AJAX: Update urutan via drag & drop
if (isset($_POST['action']) && $_POST['action'] === 'update_order') {
    header('Content-Type: application/json');
    $order = json_decode($_POST['order'] ?? '[]', true);
    if (is_array($order)) {
        foreach ($order as $pos => $id) {
            $id = (int)$id;
            $urutan = (int)$pos;
            mysqli_query($conn, "UPDATE sekolah_pendidikan SET urutan=$urutan WHERE id=$id");
        }
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
    exit;
}

// PROSES SIMPAN (Tambah / Edit)
if (isset($_POST['save_sekolah'])) {
    $sekolah_id = (int)($_POST['sekolah_id'] ?? 0);
    $jenjang = mysqli_real_escape_string($conn, $_POST['jenjang']);
    $nama_sekolah = mysqli_real_escape_string($conn, $_POST['nama_sekolah']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $data_map = mysqli_real_escape_string($conn, $_POST['data_map']);
    $maps_url = mysqli_real_escape_string($conn, $_POST['maps_url'] ?? '');
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if ($sekolah_id) {
        $q = mysqli_query($conn, "UPDATE sekolah_pendidikan SET jenjang='$jenjang', nama_sekolah='$nama_sekolah', alamat='$alamat', data_map='$data_map', maps_url='$maps_url', is_active=$is_active WHERE id=$sekolah_id");
        $msg = $q ? 'Data sekolah berhasil diperbarui.' : 'GAGAL: ' . mysqli_error($conn);
    } else {
        // Urutan otomatis = posisi terakhir + 1
        $last = mysqli_fetch_assoc(mysqli_query($conn, "SELECT MAX(urutan) as mx FROM sekolah_pendidikan WHERE jenjang='$jenjang'"));
        $new_urutan = ($last['mx'] ?? 0) + 1;
        $q = mysqli_query($conn, "INSERT INTO sekolah_pendidikan (jenjang, nama_sekolah, alamat, data_map, maps_url, is_active, urutan) VALUES ('$jenjang','$nama_sekolah','$alamat','$data_map','$maps_url',$is_active,$new_urutan)");
        $msg = $q ? 'Sekolah baru berhasil ditambahkan.' : 'GAGAL: ' . mysqli_error($conn);
    }
}

// PROSES HAPUS
if (isset($_GET['delete_sekolah'])) {
    $id = (int)$_GET['delete_sekolah'];
    mysqli_query($conn, "DELETE FROM sekolah_pendidikan WHERE id = $id");
    $msg = 'Data sekolah berhasil dihapus.';
}

// Ambil semua data sekolah diurutkan per jenjang
$sekolah_list = [];
$result = mysqli_query($conn, "SELECT * FROM sekolah_pendidikan ORDER BY FIELD(jenjang,'SD','SMP','SMA/SMK'), urutan ASC, id ASC");
while ($row = mysqli_fetch_assoc($result)) {
    $sekolah_list[] = $row;
}

include 'admin_header.php';
?>

<style>
    .sortable-ghost { opacity: 0.4; background: #fff3cd !important; }
    .sortable-chosen { box-shadow: 0 4px 15px rgba(0,0,0,0.15); }
    .drag-handle { cursor: grab; color: #aaa; transition: color 0.2s; }
    .drag-handle:hover { color: #333; }
    .drag-handle:active { cursor: grabbing; }
    .sortable-item { 
        display: flex; align-items: center; gap: 12px;
        padding: 12px 15px; background: #fff;
        border: 1px solid #eee; border-radius: 10px;
        margin-bottom: 8px; transition: all 0.2s;
    }
    .sortable-item:hover { border-color: #ddd; box-shadow: 0 2px 8px rgba(0,0,0,0.04); }
    .sortable-item .item-info { flex: 1; min-width: 0; }
    .sortable-item .item-name { font-weight: 600; font-size: 0.95rem; }
    .sortable-item .item-addr { font-size: 0.8rem; color: #888; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .sortable-item .item-actions { display: flex; gap: 6px; flex-shrink: 0; }
    .order-saved { 
        display: none; position: fixed; bottom: 30px; right: 30px; z-index: 9999;
        background: #198754; color: #fff; padding: 12px 24px; border-radius: 50px;
        box-shadow: 0 5px 20px rgba(25,135,84,0.4); font-weight: 600; font-size: 0.9rem;
        animation: slideUp 0.3s ease;
    }
    @keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
</style>

<div class="page-header mb-4">
    <h2 class="fw-bold text-dark"><?= $page_title ?></h2>
    <p class="text-muted">Kelola data sekolah yang ditampilkan pada halaman Pemberdayaan Pendidikan. <strong>Seret</strong> untuk mengubah urutan.</p>
</div>

<?php if ($msg): ?>
<div class="alert <?= strpos($msg, 'GAGAL') !== false ? 'alert-danger' : 'alert-success' ?> alert-dismissible fade show border-0 shadow-sm rounded-3" role="alert">
    <i class="fa-solid <?= strpos($msg, 'GAGAL') !== false ? 'fa-circle-xmark' : 'fa-circle-check' ?> me-2"></i> <?= htmlspecialchars($msg) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 rounded-4 position-sticky" style="top: 20px;">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4 text-danger border-bottom pb-2">
                    <i class="fa-solid fa-plus-circle me-2"></i>Tambah / Edit Sekolah
                </h5>
                <form method="POST" id="formSekolah">
                    <input type="hidden" name="sekolah_id" id="sekolah_id" value="">
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Jenjang <span class="text-danger">*</span></label>
                        <select name="jenjang" id="jenjang" class="form-select" required>
                            <option value="SD">SD</option>
                            <option value="SMP">SMP</option>
                            <option value="SMA/SMK">SMA / SMK</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Nama Sekolah <span class="text-danger">*</span></label>
                        <input type="text" name="nama_sekolah" id="nama_sekolah" class="form-control" required placeholder="Contoh: SD Negeri Kedungpane 01">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Alamat</label>
                        <input type="text" name="alamat" id="alamat" class="form-control" placeholder="Contoh: Jl. Dawung RT 04 / RW 03">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Query Google Maps</label>
                        <textarea name="data_map" id="data_map" rows="2" class="form-control" style="font-size: 0.85rem;" placeholder="Contoh: SD Negeri Kedungpane 01, Jl. Dawung, Semarang"></textarea>
                        <small class="text-muted">Teks pencarian untuk menunjukkan lokasi sekolah di Google Maps (jika URL peta tidak diisi).</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">URL Peta (Opsional)</label>
                        <input type="text" name="maps_url" id="maps_url" class="form-control" placeholder="https://maps.google...">
                        <small class="text-muted d-block mt-1">Gunakan URL lengkap, koordinat (misal: -7.03, 110.3), atau klik <strong>Bagikan > Sematkan Peta</strong>. Hindari link pendek (goo.gl) agar peta bisa dimuat. Jika dikosongkan, akan otomatis menggunakan data di atas.</small>
                    </div>
                    
                    <div class="form-check mb-3">
                        <input type="checkbox" name="is_active" id="is_active" class="form-check-input" checked>
                        <label class="form-check-label" for="is_active">Tampilkan di halaman</label>
                    </div>
                    
                    <button type="submit" name="save_sekolah" class="btn btn-danger w-100 fw-bold py-2 rounded-pill">
                        <i class="fa-solid fa-floppy-disk me-2"></i>Simpan
                    </button>
                    <button type="button" id="btnReset" class="btn btn-outline-secondary w-100 mt-2 rounded-pill" style="display:none;" onclick="resetForm()">
                        <i class="fa-solid fa-xmark me-1"></i>Batal Edit
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <?php
        $jenjang_list = ['SD', 'SMP', 'SMA/SMK'];
        $jenjang_icons = ['SD' => 'fa-school', 'SMP' => 'fa-school-flag', 'SMA/SMK' => 'fa-graduation-cap'];
        $jenjang_colors = ['SD' => '#2196F3', 'SMP' => '#FF9800', 'SMA/SMK' => '#4CAF50'];
        
        foreach ($jenjang_list as $jenjang):
            $filtered = array_values(array_filter($sekolah_list, fn($s) => $s['jenjang'] === $jenjang));
            $jenjang_slug = str_replace('/', '-', $jenjang);
        ?>
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3" style="color: <?= $jenjang_colors[$jenjang] ?>">
                    <i class="fa-solid <?= $jenjang_icons[$jenjang] ?> me-2"></i>Jenjang <?= $jenjang ?>
                    <span class="badge rounded-pill text-bg-light border ms-2"><?= count($filtered) ?> sekolah</span>
                </h5>
                
                <?php if (count($filtered) > 0): ?>
                <div class="sortable-list" id="sortable-<?= $jenjang_slug ?>" data-jenjang="<?= htmlspecialchars($jenjang) ?>">
                    <?php foreach ($filtered as $row): ?>
                    <div class="sortable-item" data-id="<?= $row['id'] ?>">
                        <div class="drag-handle" title="Seret untuk mengubah urutan">
                            <i class="fa-solid fa-grip-vertical fa-lg"></i>
                        </div>
                        <div class="item-info">
                            <div class="item-name"><?= htmlspecialchars($row['nama_sekolah']) ?></div>
                            <div class="item-addr"><i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($row['alamat']) ?></div>
                        </div>
                        <?= $row['is_active'] 
                            ? '<span class="badge bg-success rounded-pill">Aktif</span>' 
                            : '<span class="badge bg-secondary rounded-pill">Off</span>' ?>
                        <div class="item-actions">
                            <button type="button" class="btn btn-sm btn-light text-primary rounded-circle shadow-sm edit-sekolah"
                                data-id="<?= $row['id'] ?>"
                                data-jenjang="<?= htmlspecialchars($row['jenjang'], ENT_QUOTES) ?>"
                                data-nama_sekolah="<?= htmlspecialchars($row['nama_sekolah'], ENT_QUOTES) ?>"
                                data-alamat="<?= htmlspecialchars($row['alamat'], ENT_QUOTES) ?>"
                                data-data_map="<?= htmlspecialchars($row['data_map'], ENT_QUOTES) ?>"
                                data-maps_url="<?= htmlspecialchars($row['maps_url'], ENT_QUOTES) ?>"
                                data-active="<?= $row['is_active'] ?>"
                                title="Edit">
                                <i class="fa-solid fa-pencil"></i>
                            </button>
                            <a href="?delete_sekolah=<?= $row['id'] ?>" class="btn btn-sm btn-light text-danger rounded-circle shadow-sm btn-hapus" title="Hapus">
                                <i class="fa-solid fa-trash-can"></i>
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <p class="text-muted text-center py-3 mb-0"><em>Belum ada data sekolah jenjang <?= $jenjang ?>.</em></p>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Toast notifikasi -->
<div class="order-saved" id="orderSavedToast">
    <i class="fa-solid fa-check-circle me-2"></i>Urutan berhasil disimpan!
</div>
<div class="order-saved" id="orderErrorToast" style="background:#dc3545; box-shadow: 0 5px 20px rgba(220,53,69,0.4);">
    <i class="fa-solid fa-circle-xmark me-2"></i>Tidak bisa memindahkan ke jenjang berbeda!
</div>

<!-- SortableJS CDN -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>

<script>
    // ====== DRAG & DROP (terkunci per jenjang) ======
    let moveCancelled = false; // Flag untuk menandai drag yang ditolak

    document.querySelectorAll('.sortable-list').forEach(list => {
        new Sortable(list, {
            handle: '.drag-handle',
            animation: 250,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            group: 'sekolah',
            onStart: function() {
                moveCancelled = false; // Reset flag saat mulai drag
            },
            onMove: function(evt) {
                const fromJenjang = evt.from.dataset.jenjang;
                const toJenjang = evt.to.dataset.jenjang;
                if (fromJenjang !== toJenjang) {
                    moveCancelled = true;
                    const errorToast = document.getElementById('orderErrorToast');
                    errorToast.style.display = 'block';
                    setTimeout(() => { errorToast.style.display = 'none'; }, 2500);
                    return false;
                }
                moveCancelled = false;
            },
            onEnd: function(evt) {
                // Jangan simpan jika drag ditolak, pindah list, atau posisi tidak berubah
                if (moveCancelled || evt.from !== evt.to || evt.oldIndex === evt.newIndex) {
                    moveCancelled = false;
                    return;
                }
                
                const ids = Array.from(evt.from.querySelectorAll('.sortable-item')).map(el => el.dataset.id);
                
                const formData = new FormData();
                formData.append('action', 'update_order');
                formData.append('order', JSON.stringify(ids));

                fetch('admin_pendidikan.php', { method: 'POST', body: formData })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            const toast = document.getElementById('orderSavedToast');
                            toast.style.display = 'block';
                            setTimeout(() => { toast.style.display = 'none'; }, 2000);
                        }
                    })
                    .catch(err => console.error('Gagal menyimpan urutan:', err));
            }
        });
    });

    // ====== EDIT FORM ======
    document.querySelectorAll('.edit-sekolah').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('sekolah_id').value = this.dataset.id;
            document.getElementById('jenjang').value = this.dataset.jenjang;
            document.getElementById('nama_sekolah').value = this.dataset.nama_sekolah;
            document.getElementById('alamat').value = this.dataset.alamat;
            document.getElementById('data_map').value = this.dataset.data_map;
            document.getElementById('maps_url').value = this.dataset.maps_url || '';
            document.getElementById('is_active').checked = this.dataset.active === '1';
            document.getElementById('btnReset').style.display = 'block';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    });

    // ====== RESET FORM ======
    function resetForm() {
        document.getElementById('formSekolah').reset();
        document.getElementById('sekolah_id').value = '';
        document.getElementById('is_active').checked = true;
        document.getElementById('btnReset').style.display = 'none';
    }
</script>

<?php include 'admin_footer.php'; ?>