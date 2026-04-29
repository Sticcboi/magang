<?php
require_once __DIR__ . '/auth.php';

$page_title = 'Manajemen Kesehatan';
$current_page = 'admin_kesehatan';
$msg = '';

// Pastikan kolom maps_url dan urutan tersedia
mysqli_query($conn, "ALTER TABLE kontak_darurat ADD COLUMN IF NOT EXISTS maps_url varchar(500) DEFAULT NULL AFTER keterangan");
mysqli_query($conn, "ALTER TABLE jadwal_layanan ADD COLUMN IF NOT EXISTS urutan int(11) DEFAULT 0 AFTER is_active");

if (isset($_POST['save_contact'])) {
    $contact_id = (int)($_POST['contact_id'] ?? 0);
    $label = mysqli_real_escape_string($conn, $_POST['label']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $nomor = mysqli_real_escape_string($conn, $_POST['nomor']);
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);
    $maps_url = mysqli_real_escape_string($conn, $_POST['maps_url'] ?? '');
    $active = isset($_POST['is_active']) ? 1 : 0;

    if ($contact_id) {
        mysqli_query($conn, "UPDATE kontak_darurat SET label='$label', nama='$nama', nomor='$nomor', keterangan='$keterangan', maps_url='$maps_url', is_active=$active WHERE id=$contact_id");
        $msg = 'Kontak darurat berhasil diperbarui.';
    } else {
        mysqli_query($conn, "INSERT INTO kontak_darurat (label, nama, nomor, keterangan, maps_url, is_active) VALUES ('$label', '$nama', '$nomor', '$keterangan', '$maps_url', $active)");
        $msg = 'Kontak darurat baru berhasil ditambahkan.';
    }
}

if (isset($_GET['delete_contact'])) {
    $id = (int)$_GET['delete_contact'];
    mysqli_query($conn, "DELETE FROM kontak_darurat WHERE id = $id");
    $msg = 'Kontak darurat berhasil dihapus.';
}

if (isset($_POST['save_facility'])) {
    $facility_id = (int)($_POST['facility_id'] ?? 0);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $jenis = mysqli_real_escape_string($conn, $_POST['jenis']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $telepon = mysqli_real_escape_string($conn, $_POST['telepon']);
    $maps_url = mysqli_real_escape_string($conn, $_POST['maps_url']);
    $jam_buka = mysqli_real_escape_string($conn, $_POST['jam_buka']);
    $active = isset($_POST['facility_active']) ? 1 : 0;

    if ($facility_id) {
        mysqli_query($conn, "UPDATE fasilitas_kesehatan SET nama='$nama', jenis='$jenis', alamat='$alamat', telepon='$telepon', maps_url='$maps_url', jam_buka='$jam_buka', is_active=$active WHERE id=$facility_id");
        $msg = 'Fasilitas kesehatan berhasil diperbarui.';
    } else {
        mysqli_query($conn, "INSERT INTO fasilitas_kesehatan (nama, jenis, alamat, telepon, maps_url, jam_buka, is_active) VALUES ('$nama', '$jenis', '$alamat', '$telepon', '$maps_url', '$jam_buka', $active)");
        $msg = 'Fasilitas kesehatan baru berhasil ditambahkan.';
    }
}

if (isset($_GET['delete_facility'])) {
    $id = (int)$_GET['delete_facility'];
    mysqli_query($conn, "DELETE FROM fasilitas_kesehatan WHERE id = $id");
    $msg = 'Fasilitas kesehatan berhasil dihapus.';
}

if (isset($_POST['save_schedule'])) {
    $schedule_id = (int)($_POST['schedule_id'] ?? 0);
    $program = mysqli_real_escape_string($conn, $_POST['program']);
    $hari = mysqli_real_escape_string($conn, $_POST['hari']);
    $jam_mulai = mysqli_real_escape_string($conn, $_POST['jam_mulai']);
    $jam_selesai = mysqli_real_escape_string($conn, $_POST['jam_selesai']);
    $lokasi = mysqli_real_escape_string($conn, $_POST['lokasi']);
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan_schedule'] ?? '');
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori'] ?? '');
    $active = isset($_POST['schedule_active']) ? 1 : 0;

    if ($schedule_id) {
        mysqli_query($conn, "UPDATE jadwal_layanan SET program='$program', hari='$hari', jam_mulai='$jam_mulai', jam_selesai='$jam_selesai', lokasi='$lokasi', keterangan='$keterangan', kategori='$kategori', is_active=$active WHERE id=$schedule_id");
        $msg = 'Jadwal layanan berhasil diperbarui.';
    } else {
        // Insert jadwal baru dan set urutan = max(urutan)+1 agar item baru otomatis di akhir daftar
        mysqli_query($conn, "INSERT INTO jadwal_layanan (program, hari, jam_mulai, jam_selesai, lokasi, keterangan, kategori, is_active, urutan) SELECT '$program', '$hari', '$jam_mulai', '$jam_selesai', '$lokasi', '$keterangan', '$kategori', $active, COALESCE(MAX(urutan),0)+1 FROM jadwal_layanan");
        $msg = 'Jadwal layanan baru berhasil ditambahkan.';
    }
}

if (isset($_GET['delete_schedule'])) {
    $id = (int)$_GET['delete_schedule'];
    mysqli_query($conn, "DELETE FROM jadwal_layanan WHERE id = $id");
    $msg = 'Jadwal layanan berhasil dihapus.';
}

// Handler AJAX untuk Update Urutan (Kontak, Fasilitas, dan Jadwal)
if (isset($_POST['update_contact_order'])) {
    $order = json_decode($_POST['order'], true);
    if (is_array($order)) {
        foreach ($order as $position => $id) {
            mysqli_query($conn, "UPDATE kontak_darurat SET urutan = " . ($position + 1) . " WHERE id = " . (int)$id);
        }
        echo json_encode(['success' => true]);
        exit;
    }
}

if (isset($_POST['update_facility_order'])) {
    $order = json_decode($_POST['order'], true);
    if (is_array($order)) {
        foreach ($order as $position => $id) {
            mysqli_query($conn, "UPDATE fasilitas_kesehatan SET urutan = " . ($position + 1) . " WHERE id = " . (int)$id);
        }
        echo json_encode(['success' => true]);
        exit;
    }
}

if (isset($_POST['update_schedule_order'])) {
    $order = json_decode($_POST['order'], true);
    if (is_array($order)) {
        foreach ($order as $position => $id) {
            mysqli_query($conn, "UPDATE jadwal_layanan SET urutan = " . ($position + 1) . " WHERE id = " . (int)$id);
        }
        echo json_encode(['success' => true]);
        exit;
    }
}

$contacts = [];
$result = mysqli_query($conn, "SELECT * FROM kontak_darurat ORDER BY urutan ASC, id DESC");
while ($row = mysqli_fetch_assoc($result)) { $contacts[] = $row; }

$facilities = [];
$result = mysqli_query($conn, "SELECT * FROM fasilitas_kesehatan ORDER BY urutan ASC, id DESC");
while ($row = mysqli_fetch_assoc($result)) { $facilities[] = $row; }

$schedules = [];
// Query diubah agar mengutamakan kolom urutan agar fitur drag and drop bekerja permanen
$result = mysqli_query($conn, "SELECT * FROM jadwal_layanan ORDER BY urutan ASC, FIELD(hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'), jam_mulai ASC");
while ($row = mysqli_fetch_assoc($result)) { $schedules[] = $row; }

include 'admin_header.php';
?>

<style>
    .drag-handle {
        cursor: grab;
        color: #6c757d;
        transition: color 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: #f8f9fa;
    }
    .drag-handle:hover { color: #212529; background: #e9ecef; }
    .drag-handle:active { cursor: grabbing; }
    .sortable-ghost { opacity: 0.5 !important; background: #fff3cd !important; }
    .sortable-chosen { box-shadow: 0 4px 15px rgba(0,0,0,0.15); }
    .sortable-list { display: grid; gap: 0.85rem; }
    .sortable-item {
        display: grid;
        grid-template-columns: auto 1fr auto auto;
        gap: 1rem;
        align-items: center;
        padding: 1rem;
        border: 1px solid #edf2f7;
        border-radius: 1rem;
        background: #ffffff;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .sortable-item:hover { transform: translateY(-1px); box-shadow: 0 8px 24px rgba(0,0,0,0.06); }
    .item-info { min-width: 0; }
    .item-name { font-weight: 600; font-size: 0.95rem; }
    .item-detail { font-size: 0.85rem; color: #6c757d; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .item-actions { display: flex; gap: 0.5rem; align-items: center; justify-content: flex-end; }
    .order-saved {
        display: none; position: fixed; bottom: 24px; right: 24px; z-index: 9999;
        background: #198754; color: #fff; padding: 0.85rem 1.2rem; border-radius: 999px;
        box-shadow: 0 8px 20px rgba(25,135,84,0.25); font-weight: 600; font-size: 0.9rem;
        width: max-content; max-width: 300px; left: auto;
    }
</style>

<div class="page-header">
    <h1><?= $page_title ?></h1>
    <p>Kelola Kontak Darurat, Fasilitas, dan Jadwal Layanan Kesehatan.</p>
</div>

<?php if ($msg): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <?= htmlspecialchars($msg) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white fw-bold">Tambah / Edit Kontak Darurat</div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="contact_id" id="contact_id">
                    <div class="mb-3"><label class="form-label">Label</label><input type="text" name="label" id="label" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Nama / Unit</label><input type="text" name="nama" id="nama" class="form-control"></div>
                    <div class="mb-3"><label class="form-label">Nomor Telepon</label><input type="text" name="nomor" id="nomor" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Keterangan</label><input type="text" name="keterangan" id="keterangan" class="form-control"></div>
                    <div class="mb-3"><label class="form-label">URL Peta</label><input type="text" name="maps_url" id="maps_url" class="form-control"></div>
                    <div class="mb-3 form-check"><input type="checkbox" name="is_active" id="is_active" class="form-check-input me-2" checked><label class="form-check-label">Aktif</label></div>
                    <button type="submit" name="save_contact" class="btn btn-danger w-100">Simpan Kontak</button>
                </form>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white fw-bold">Tambah / Edit Fasilitas Kesehatan</div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="facility_id" id="facility_id">
                    <div class="mb-3"><label class="form-label">Nama Fasilitas</label><input type="text" name="nama" id="facility_nama" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Jenis</label><select name="jenis" id="facility_jenis" class="form-select"><option value="puskesmas">Puskesmas</option><option value="klinik">Klinik</option><option value="apotek">Apotek</option><option value="dokter_mandiri">Dokter Mandiri</option><option value="rumah_sakit">Rumah Sakit</option></select></div>
                    <div class="mb-3"><label class="form-label">Alamat</label><input type="text" name="alamat" id="facility_alamat" class="form-control"></div>
                    <div class="mb-3"><label class="form-label">Telepon</label><input type="text" name="telepon" id="facility_telepon" class="form-control"></div>
                    <div class="mb-3"><label class="form-label">URL Peta</label><input type="text" name="maps_url" id="facility_maps_url" class="form-control"></div>
                    <div class="mb-3"><label class="form-label">Jam Buka</label><input type="text" name="jam_buka" id="facility_jam_buka" class="form-control"></div>
                    <div class="mb-3 form-check"><input type="checkbox" name="facility_active" id="facility_active" class="form-check-input me-2" checked><label class="form-check-label">Aktif</label></div>
                    <button type="submit" name="save_facility" class="btn btn-danger w-100">Simpan Fasilitas</button>
                </form>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white fw-bold">Tambah / Edit Jadwal Layanan Rutin</div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="schedule_id" id="schedule_id">
                    <div class="mb-3"><label class="form-label">Program Layanan</label><input type="text" name="program" id="schedule_program" class="form-control" required></div>
                    <div class="mb-3">
                        <label class="form-label">Hari</label>
                        <select name="hari" id="schedule_hari" class="form-select" required>
                            <option value="">-- Pilih Hari --</option>
                            <?php $days = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu']; foreach($days as $d) echo "<option value='$d'>$d</option>"; ?>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jam Mulai</label>
                            <select name="jam_mulai" id="schedule_jam_mulai" class="form-select">
                                <?php for($i=0; $i<=23; $i++){ $t = str_pad($i, 2, '0', STR_PAD_LEFT).'.00'; echo "<option value='$t'>$t</option>"; } ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jam Selesai</label>
                            <select name="jam_selesai" id="schedule_jam_selesai" class="form-select">
                                <?php for($i=0; $i<=23; $i++){ $t = str_pad($i, 2, '0', STR_PAD_LEFT).'.00'; echo "<option value='$t'>$t</option>"; } ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3"><label class="form-label">Lokasi</label><input type="text" name="lokasi" id="schedule_lokasi" class="form-control" required></div>
                    <div class="mb-3 form-check"><input type="checkbox" name="schedule_active" id="schedule_active" class="form-check-input me-2" checked><label class="form-check-label">Aktif</label></div>
                    <button type="submit" name="save_schedule" class="btn btn-danger w-100">Simpan Jadwal</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white fw-bold">Daftar Kontak Darurat</div>
            <div class="card-body">
                <div class="sortable-list" id="contactsTbody">
                    <?php foreach ($contacts as $row): $map = "https://maps.google.com/maps?q=".rawurlencode(($row['nama'] ?: $row['label']).' Kedungpane Semarang')."&output=embed"; ?>
                    <div class="sortable-item contact-row" data-id="<?= $row['id'] ?>" data-maps="<?= htmlspecialchars($map) ?>">
                        <div class="drag-handle"><i class="fa-solid fa-grip-vertical"></i></div>
                        <div class="item-info"><div class="item-name"><?= htmlspecialchars($row['label']) ?></div><div class="item-detail"><?= htmlspecialchars($row['nomor']) ?></div></div>
                        <?= $row['is_active'] ? '<span class="badge bg-success rounded-pill">Aktif</span>' : '<span class="badge bg-secondary rounded-pill">Nonaktif</span>' ?>
                        <div class="item-actions">
                            <button type="button" class="btn btn-sm btn-light text-primary rounded-pill edit-contact" data-id="<?= $row['id'] ?>" data-label="<?= htmlspecialchars($row['label']) ?>" data-nama="<?= htmlspecialchars($row['nama']) ?>" data-nomor="<?= htmlspecialchars($row['nomor']) ?>" data-keterangan="<?= htmlspecialchars($row['keterangan']) ?>" data-maps_url="<?= htmlspecialchars($row['maps_url']) ?>" data-active="<?= $row['is_active'] ?>">Edit</button>
                            <a href="?delete_contact=<?= $row['id'] ?>" class="btn btn-sm btn-light text-danger rounded-pill btn-hapus">Hapus</a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white fw-bold">Daftar Fasilitas Kesehatan</div>
            <div class="card-body">
                <div class="sortable-list" id="facilitiesTbody">
                    <?php foreach ($facilities as $row): $map = !empty($row['maps_url']) ? $row['maps_url'] : "https://maps.google.com/maps?q=".rawurlencode($row['nama'].' '.$row['alamat'])."&output=embed"; ?>
                    <div class="sortable-item facility-row" data-id="<?= $row['id'] ?>" data-maps="<?= htmlspecialchars($map) ?>">
                        <div class="drag-handle"><i class="fa-solid fa-grip-vertical"></i></div>
                        <div class="item-info"><div class="item-name"><?= htmlspecialchars($row['nama']) ?></div><div class="item-detail"><?= htmlspecialchars($row['alamat']) ?></div></div>
                        <?= $row['is_active'] ? '<span class="badge bg-success rounded-pill">Aktif</span>' : '<span class="badge bg-secondary rounded-pill">Nonaktif</span>' ?>
                        <div class="item-actions">
                            <button type="button" class="btn btn-sm btn-light text-primary rounded-pill edit-facility" data-id="<?= $row['id'] ?>" data-nama="<?= htmlspecialchars($row['nama']) ?>" data-jenis="<?= htmlspecialchars($row['jenis']) ?>" data-alamat="<?= htmlspecialchars($row['alamat']) ?>" data-telepon="<?= htmlspecialchars($row['telepon']) ?>" data-maps_url="<?= htmlspecialchars($row['maps_url']) ?>" data-jam_buka="<?= htmlspecialchars($row['jam_buka']) ?>" data-active="<?= $row['is_active'] ?>">Edit</button>
                            <a href="?delete_facility=<?= $row['id'] ?>" class="btn btn-sm btn-light text-danger rounded-pill btn-hapus">Hapus</a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white fw-bold">Daftar Jadwal Layanan Rutin</div>
            <div class="card-body">
                <div class="sortable-list" id="schedulesTbody">
                    <?php foreach ($schedules as $row): ?>
                    <div class="sortable-item schedule-row" data-id="<?= $row['id'] ?>">
                        <div class="drag-handle" title="Seret untuk mengubah urutan"><i class="fa-solid fa-grip-vertical"></i></div>
                        <div class="item-info">
                            <div class="item-name"><?= htmlspecialchars($row['program']) ?></div>
                            <div class="item-detail"><?= htmlspecialchars($row['hari']) ?> (<?= htmlspecialchars($row['jam_mulai']) ?> - <?= htmlspecialchars($row['jam_selesai']) ?>)</div>
                            <div class="item-detail"><i class="fa-solid fa-location-dot me-1"></i> <?= htmlspecialchars($row['lokasi']) ?></div>
                        </div>
                        <?= $row['is_active'] ? '<span class="badge bg-success rounded-pill">Aktif</span>' : '<span class="badge bg-secondary rounded-pill">Nonaktif</span>' ?>
                        <div class="item-actions">
                            <button type="button" class="btn btn-sm btn-light text-primary rounded-pill edit-schedule" data-id="<?= $row['id'] ?>" data-program="<?= htmlspecialchars($row['program']) ?>" data-hari="<?= htmlspecialchars($row['hari']) ?>" data-jam_mulai="<?= htmlspecialchars($row['jam_mulai']) ?>" data-jam_selesai="<?= htmlspecialchars($row['jam_selesai']) ?>" data-lokasi="<?= htmlspecialchars($row['lokasi']) ?>" data-active="<?= $row['is_active'] ?>">Edit</button>
                            <a href="?delete_schedule=<?= $row['id'] ?>" class="btn btn-sm btn-light text-danger rounded-pill btn-hapus">Hapus</a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white fw-bold">Preview Peta</div>
            <div class="card-body p-0">
                <div class="ratio ratio-4x3">
                    <iframe id="adminMapPreview" src="https://maps.google.com/maps?q=Kelurahan+Kedungpane+Semarang&output=embed" style="border:0" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
    </div>
</div>

<div class="order-saved" id="orderSavedToast"><i class="fa-solid fa-check-circle me-2"></i>Urutan berhasil disimpan!</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>
<script>
    // Form Filling Functions
    function fillContactForm(b) {
        document.getElementById('contact_id').value = b.dataset.id;
        document.getElementById('label').value = b.dataset.label;
        document.getElementById('nama').value = b.dataset.nama;
        document.getElementById('nomor').value = b.dataset.nomor;
        document.getElementById('keterangan').value = b.dataset.keterangan;
        document.getElementById('maps_url').value = b.dataset.maps_url;
        document.getElementById('is_active').checked = b.dataset.active === '1';
        window.scrollTo({top:0, behavior:'smooth'});
    }
    function fillFacilityForm(b) {
        document.getElementById('facility_id').value = b.dataset.id;
        document.getElementById('facility_nama').value = b.dataset.nama;
        document.getElementById('facility_jenis').value = b.dataset.jenis;
        document.getElementById('facility_alamat').value = b.dataset.alamat;
        document.getElementById('facility_telepon').value = b.dataset.telepon;
        document.getElementById('facility_maps_url').value = b.dataset.maps_url;
        document.getElementById('facility_jam_buka').value = b.dataset.jam_buka;
        document.getElementById('facility_active').checked = b.dataset.active === '1';
        window.scrollTo({top:0, behavior:'smooth'});
    }
    function fillScheduleForm(b) {
        document.getElementById('schedule_id').value = b.dataset.id;
        document.getElementById('schedule_program').value = b.dataset.program;
        document.getElementById('schedule_hari').value = b.dataset.hari;
        document.getElementById('schedule_jam_mulai').value = b.dataset.jam_mulai;
        document.getElementById('schedule_jam_selesai').value = b.dataset.jam_selesai;
        document.getElementById('schedule_lokasi').value = b.dataset.lokasi;
        document.getElementById('schedule_active').checked = b.dataset.active === '1';
        window.scrollTo({top:0, behavior:'smooth'});
    }

    document.querySelectorAll('.edit-contact').forEach(btn => btn.onclick = () => fillContactForm(btn));
    document.querySelectorAll('.edit-facility').forEach(btn => btn.onclick = () => fillFacilityForm(btn));
    document.querySelectorAll('.edit-schedule').forEach(btn => btn.onclick = () => fillScheduleForm(btn));

    // Sortable Implementation
    document.addEventListener('DOMContentLoaded', function() {
        const createSortable = (el, updateFn) => {
            if (el) new Sortable(el, { handle:'.drag-handle', animation:250, onEnd: updateFn });
        };

        createSortable(document.getElementById('contactsTbody'), () => updateOrder('update_contact_order', '#contactsTbody .contact-row'));
        createSortable(document.getElementById('facilitiesTbody'), () => updateOrder('update_facility_order', '#facilitiesTbody .facility-row'));
        createSortable(document.getElementById('schedulesTbody'), () => updateOrder('update_schedule_order', '#schedulesTbody .schedule-row'));
    });

    function updateOrder(action, selector) {
        const order = Array.from(document.querySelectorAll(selector)).map(row => row.dataset.id);
        fetch(window.location.href, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: action + '=1&order=' + JSON.stringify(order)
        })
        .then(res => res.json())
        .then(data => { if(data.success) showToast(); });
    }

    function showToast() {
        const t = document.getElementById('orderSavedToast');
        t.style.display = 'block';
        setTimeout(() => t.style.display = 'none', 1800);
    }

    // Map Preview
    document.querySelectorAll('.contact-row, .facility-row').forEach(row => {
        row.onclick = (e) => {
            if (e.target.closest('button, a')) return;
            const m = row.dataset.maps;
            if(m) document.getElementById('adminMapPreview').src = m;
        };
    });
</script>
<?php include 'admin_footer.php'; ?>