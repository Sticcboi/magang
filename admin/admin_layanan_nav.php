<?php
require_once __DIR__ . '/auth.php'; 

// Otomatis mendeteksi nama file agar redirect akurat
$current_file = basename($_SERVER['PHP_SELF']); 

// --- 1. LOGIKA UPDATE URUTAN (DRAG & DROP) ---
if (isset($_POST['update_urutan'])) {
    $order = json_decode($_POST['order'], true);
    foreach ($order as $index => $id) {
        $urutan_baru = $index + 1;
        $id = (int)$id;
        mysqli_query($conn, "UPDATE navbar_layanan SET urutan = $urutan_baru WHERE id = $id");
    }
    exit('Success'); 
}

// --- 2. LOGIKA TAMBAH DATA ---
if (isset($_POST['tambah_layanan'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_layanan']);
    $url = mysqli_real_escape_string($conn, $_POST['url']);
    $cek = mysqli_fetch_assoc(mysqli_query($conn, "SELECT MAX(urutan) as max_urut FROM navbar_layanan"));
    $urutan_baru = ($cek['max_urut'] ?? 0) + 1;

    mysqli_query($conn, "INSERT INTO navbar_layanan (nama_layanan, url, urutan) VALUES ('$nama', '$url', $urutan_baru)");
    header("Location: $current_file?status=terhapus");
    exit;
}

// --- 3. LOGIKA EDIT DATA ---
if (isset($_POST['edit_layanan'])) {
    $id = (int)$_POST['id'];
    $nama = mysqli_real_escape_string($conn, $_POST['nama_layanan']);
    $url = mysqli_real_escape_string($conn, $_POST['url']);
    mysqli_query($conn, "UPDATE navbar_layanan SET nama_layanan='$nama', url='$url' WHERE id=$id");
    header("Location: $current_file?status=terhapus");
    exit;
}

// --- 4. LOGIKA HAPUS DATA ---
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    mysqli_query($conn, "DELETE FROM navbar_layanan WHERE id = $id");
    header("Location: $current_file?status=terhapus");
    exit;
}

$page_title = 'Kelola Layanan Navbar';
$current_page = 'admin_layanan_nav';
include 'admin_header.php';

// Ambil data untuk tabel
$data_layanan = [];
$query = mysqli_query($conn, "SELECT * FROM navbar_layanan ORDER BY urutan ASC");
while($row = mysqli_fetch_assoc($query)) {
    $data_layanan[] = $row;
}
?>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<style>
    .drag-handle { cursor: grab; color: #ccc; padding: 10px; }
    .drag-handle:active { cursor: grabbing; }
    .sortable-ghost { opacity: 0.3; background-color: #f8f9fa; }
    
    /* Memastikan modal selalu berada paling depan */
    .modal { z-index: 1055 !important; }
    .modal-backdrop { z-index: 1050 !important; }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0">Kelola Link Layanan Navbar</h2>
    <button class="btn btn-danger rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#tambahModal">
        <i class="fa-solid fa-plus me-2"></i>Tambah Link Navbar
    </button>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4" width="50">Geser</th>
                    <th>Nama Menu (Navbar)</th>
                    <th>URL Tujuan</th>
                    <th class="text-center" width="150">Aksi</th>
                </tr>
            </thead>
            <tbody id="list-layanan">
                <?php foreach($data_layanan as $row): ?>
                <tr data-id="<?= $row['id']; ?>">
                    <td class="ps-4"><i class="fa-solid fa-grip-vertical drag-handle fs-5"></i></td>
                    <td class="fw-bold"><?= htmlspecialchars($row['nama_layanan']); ?></td>
                    <td class="small text-muted"><?= htmlspecialchars($row['url']); ?></td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-light rounded-circle shadow-sm me-1" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id']; ?>">
                            <i class="fa-solid fa-pencil text-primary"></i>
                        </button>
                        <a href="?hapus=<?= $row['id']; ?>" class="btn btn-sm btn-light rounded-circle shadow-sm btn-hapus" title="Hapus">
                            <i class="fa-solid fa-trash text-danger"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="tambahModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Tambah Link Navbar</h5>
                <button type="button" class="btn-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Teks di Navbar</label>
                        <input type="text" name="nama_layanan" class="form-control" placeholder="Contoh: Pengantar KTP" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Link URL</label>
                        <input type="url" name="url" class="form-control" placeholder="https://..." required>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-dismiss="modal" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="tambah_layanan" class="btn btn-danger rounded-pill px-4">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php foreach($data_layanan as $row): ?>
<div class="modal fade" id="editModal<?= $row['id']; ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Edit Link Navbar</h5>
                <button type="button" class="btn-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST">
                <input type="hidden" name="id" value="<?= $row['id']; ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Layanan</label>
                        <input type="text" name="nama_layanan" class="form-control" value="<?= htmlspecialchars($row['nama_layanan']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">URL Tujuan</label>
                        <input type="url" name="url" class="form-control" value="<?= htmlspecialchars($row['url']); ?>" required>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-dismiss="modal" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="edit_layanan" class="btn btn-danger rounded-pill px-4">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    
    // 1. PINDAHKAN SEMUA MODAL KE <body>
    // Ini adalah solusi ajaib agar modal tidak terhalang layer transparan (backdrop)
    const modals = document.querySelectorAll('.modal');
    modals.forEach(function(modal) {
        document.body.appendChild(modal);
    });

    // 2. INISIALISASI FITUR SERET (SORTABLE)
    const el = document.getElementById('list-layanan');
    if(el) {
        Sortable.create(el, {
            handle: '.drag-handle',
            animation: 150,
            ghostClass: 'sortable-ghost',
            onEnd: function () {
                let order = [];
                el.querySelectorAll('tr').forEach(row => {
                    order.push(row.getAttribute('data-id'));
                });
                let formData = new FormData();
                formData.append('update_urutan', '1');
                formData.append('order', JSON.stringify(order));
                fetch('<?= $current_file ?>', { method: 'POST', body: formData });
            }
        });
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php include 'admin_footer.php'; ?>