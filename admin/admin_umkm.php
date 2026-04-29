<?php
require_once __DIR__ . '/auth.php';

$page_title = 'Manajemen UMKM';
$current_page = 'admin_umkm';

// --- PRG: All POST/GET actions redirect to prevent duplicate on refresh ---

// Pastikan kolom gmaps_url tersedia
mysqli_query($conn, "ALTER TABLE umkm ADD COLUMN IF NOT EXISTS gmaps_url varchar(500) DEFAULT NULL AFTER alamat");

// Fungsi untuk mengonversi URL mentah menjadi URL embed
function convert_to_embed_url($url) {
    if (empty($url)) return '';
    $url = trim($url);
    if (strpos(strtolower($url), '<iframe') !== false && preg_match('/src=["\'](.*?)["\']/', $url, $matches)) {
        return $matches[1];
    }
    if (strpos($url, 'embed') !== false || strpos($url, 'output=embed') !== false) {
        return $url;
    }
    // Ekstrak koordinat dari URL panjang
    if (preg_match('/@(-?\d+\.\d+),(-?\d+\.\d+)/', $url, $matches)) {
        return "https://maps.google.com/maps?q=" . $matches[1] . "," . $matches[2] . "&output=embed";
    }
    // Ekstrak nama tempat
    if (preg_match('/place\/([^\/]+)/', $url, $matches)) {
        $place = str_replace('+', ' ', $matches[1]);
        return "https://maps.google.com/maps?q=" . urlencode($place) . "&output=embed";
    }
    return $url;
}

if (isset($_POST['save_umkm'])) {
    $umkm_id = (int)($_POST['umkm_id'] ?? 0);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $pengelola = mysqli_real_escape_string($conn, $_POST['pengelola']);
    $kontak = mysqli_real_escape_string($conn, $_POST['kontak']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    
    // Konversi URL ke format embed sebelum disimpan
    $raw_gmaps_url = $_POST['gmaps_url'];
    $gmaps_url = mysqli_real_escape_string($conn, convert_to_embed_url($raw_gmaps_url));
    
    $verified = isset($_POST['is_verified']) ? 1 : 0;

    if ($umkm_id) {
        mysqli_query($conn, "UPDATE umkm SET nama='$nama', kategori='$kategori', pengelola='$pengelola', kontak='$kontak', deskripsi='$deskripsi', alamat='$alamat', gmaps_url='$gmaps_url', is_verified=$verified WHERE id=$umkm_id");
        $_SESSION['flash_msg'] = 'Data UMKM berhasil diperbarui.';
    } else {
        mysqli_query($conn, "INSERT INTO umkm (nama, kategori, pengelola, kontak, deskripsi, alamat, gmaps_url, is_verified) VALUES ('$nama', '$kategori', '$pengelola', '$kontak', '$deskripsi', '$alamat', '$gmaps_url', $verified)");
        $_SESSION['flash_msg'] = 'UMKM baru berhasil ditambahkan.';
    }
    header("Location: admin_umkm.php#list?status=terhapus");
    exit;
}

if (isset($_GET['delete_umkm'])) {
    $id = (int)$_GET['delete_umkm'];
    mysqli_query($conn, "DELETE FROM umkm WHERE id = $id");
    $_SESSION['flash_msg'] = 'UMKM berhasil dihapus.';
    header("Location: admin_umkm.php#list?status=terhapus");
    exit;
}

$msg = $_SESSION['flash_msg'] ?? null;
unset($_SESSION['flash_msg']);

$umkms = [];
$result = mysqli_query($conn, "SELECT * FROM umkm ORDER BY id DESC");
while ($row = mysqli_fetch_assoc($result)) {
    $umkms[] = $row;
}

$total_verified = count(array_filter($umkms, fn($u) => $u['is_verified'] == 1));

include 'admin_header.php';
?>

<!-- Hero Section -->
<div class="container-fluid mt-4">
    <div class="hero-umkm-admin text-center mb-5">
        <div style="position: relative; z-index: 2;">
            <span class="badge bg-light text-danger mb-3 px-3 py-2 rounded-pill shadow-sm" style="font-size: 0.9rem;">
                <i class="fa-solid fa-shop text-danger me-1"></i> Admin Panel
            </span>
            <h1 class="display-5 fw-bold mb-3 text-white">Kelola Direktori UMKM</h1>
            <p class="lead mx-auto text-white-50" style="max-width: 700px;">
                Tambahkan dan kelola profil Usaha Mikro, Kecil, dan Menengah dari warga Kedungpane.
            </p>
            <div class="row g-3 mt-4 justify-content-center">
                <div class="col-auto">
                    <div class="status-box-umkm">
                        <i class="fa-solid fa-store fs-5 text-danger me-2"></i>
                        <span class="fw-bold text-danger">Total Usaha</span>
                        <div class="fs-4 fw-bold text-dark mt-2"><?= count($umkms) ?></div>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="status-box-umkm">
                        <i class="fa-solid fa-check-circle fs-5 text-success me-2"></i>
                        <span class="fw-bold text-success">Terverifikasi</span>
                        <div class="fs-4 fw-bold text-dark mt-2"><?= $total_verified ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mb-5">

<?php if(isset($msg)): ?>
<div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
    <div class="d-flex align-items-center">
        <i class="fa-solid fa-circle-check text-success fs-4 me-3"></i>
        <div><strong>Berhasil!</strong> <?= htmlspecialchars($msg) ?></div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<div class="row g-4" id="form">
    <!-- Form Edit / Tambah -->
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 sticky-top" style="top:20px;">
            <div class="card-header bg-gradient-danger text-white border-0">
                <div class="d-flex align-items-center">
                    <i class="fa-solid fa-store-plus fs-5 me-3"></i>
                    <div>
                        <h5 class="mb-0">Tambah UMKM</h5>
                        <small class="opacity-75">Data Profil & Lokasi</small>
                    </div>
                </div>
            </div>
            <div class="card-body bg-light">
                <form method="POST" id="formUmkm">
                    <input type="hidden" name="umkm_id" id="umkm_id" value="">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-danger">Nama Usaha</label>
                        <input type="text" name="nama" id="nama" class="form-control" placeholder="Contoh: Kedai Makmur" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-danger">Kategori</label>
                        <input type="text" name="kategori" id="kategori" class="form-control" placeholder="Contoh: Kuliner, Jasa">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-danger">Nama Pengelola</label>
                        <input type="text" name="pengelola" id="pengelola" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-danger">Kontak (No. HP / WA)</label>
                        <input type="text" name="kontak" id="kontak" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-danger">Alamat Lengkap</label>
                        <input type="text" name="alamat" id="alamat" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-danger"><i class="fa-solid fa-map-location-dot me-2"></i>URL Google Maps (Opsional)</label>
                        <input type="text" name="gmaps_url" id="gmaps_url" class="form-control" placeholder="https://maps.google.com/...">
                        <small class="text-muted d-block mt-1">Gunakan URL lengkap, koordinat (misal: -7.03, 110.3), atau klik <strong>Bagikan > Sematkan Peta</strong>. Hindari link pendek (goo.gl) agar peta bisa dimuat.</small>
                    </div>
                    <div class="mb-3 p-2 bg-white border rounded d-flex align-items-center">
                        <div class="form-check form-switch mb-0 w-100 d-flex justify-content-between align-items-center">
                            <label class="form-check-label fw-bold text-dark" for="is_verified">Status Terverifikasi</label>
                            <input class="form-check-input" type="checkbox" role="switch" name="is_verified" id="is_verified" style="width: 40px; height: 20px;">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold text-danger">Deskripsi Singkat</label>
                        <textarea name="deskripsi" id="deskripsi" rows="3" class="form-control" placeholder="Produk andalan..."></textarea>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" name="save_umkm" id="btnSave" class="btn btn-danger fw-bold py-2">
                            <i class="fa-solid fa-plus me-2"></i>Tambah UMKM
                        </button>
                        <button type="button" id="btnReset" class="btn btn-outline-secondary py-2" style="display:none;" onclick="resetForm()">Batal Edit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar -->
    <div class="col-lg-8" id="list">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-bottom border-light d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0 fw-bold text-dark py-2">Daftar UMKM Terdaftar</h5>
                    <small class="text-muted">Kelurahan Kedungpane</small>
                </div>
                <div class="search-box">
                    <input type="text" id="searchInput" class="form-control form-control-sm rounded-pill px-3 py-2 bg-light border-0" placeholder="Cari UMKM..." style="width: 200px;">
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="umkmTable">
                        <thead class="table-light">
                            <tr>
                                <th class="py-3 px-4">Info Usaha</th>
                                <th>Kontak & Alamat</th>
                                <th class="text-center">Status</th>
                                <th class="text-end px-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($umkms)): foreach($umkms as $row): ?>
                            <tr>
                                <td class="px-4">
                                    <div class="d-flex align-items-center">
                                        <div class="d-flex align-items-center justify-content-center bg-danger text-white rounded me-3 shadow-sm" style="width: 40px; height: 40px; font-weight: bold;">
                                            <?= strtoupper(substr($row['nama'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark"><?= htmlspecialchars($row['nama']) ?></div>
                                            <div class="small text-muted"><span class="badge bg-light text-danger border border-danger-subtle me-1"><?= htmlspecialchars($row['kategori'] ?: 'Umum') ?></span><i class="fa-solid fa-user me-1"></i><?= htmlspecialchars($row['pengelola']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="small text-dark mb-1"><i class="fa-solid fa-phone text-muted me-1"></i><?= htmlspecialchars($row['kontak'] ?: '-') ?></div>
                                    <div class="small text-muted text-truncate" style="max-width: 150px;" title="<?= htmlspecialchars($row['alamat']) ?>"><i class="fa-solid fa-location-dot me-1"></i><?= htmlspecialchars($row['alamat'] ?: '-') ?></div>
                                </td>
                                <td class="text-center">
                                    <?= $row['is_verified'] ? '<span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill"><i class="fa-solid fa-check-circle me-1"></i>Terverifikasi</span>' : '<span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill"><i class="fa-solid fa-clock me-1"></i>Baru</span>' ?>
                                </td>
                                <td class="text-end px-4">
                                    <button class="btn btn-sm btn-light text-primary rounded-pill edit-umkm me-1" 
                                        data-id="<?= $row['id'] ?>" 
                                        data-nama="<?= htmlspecialchars($row['nama'], ENT_QUOTES) ?>" 
                                        data-kategori="<?= htmlspecialchars($row['kategori'], ENT_QUOTES) ?>" 
                                        data-pengelola="<?= htmlspecialchars($row['pengelola'], ENT_QUOTES) ?>" 
                                        data-kontak="<?= htmlspecialchars($row['kontak'], ENT_QUOTES) ?>" 
                                        data-alamat="<?= htmlspecialchars($row['alamat'], ENT_QUOTES) ?>" 
                                        data-gmaps_url="<?= htmlspecialchars($row['gmaps_url'] ?? '', ENT_QUOTES) ?>" 
                                        data-deskripsi="<?= htmlspecialchars($row['deskripsi'], ENT_QUOTES) ?>" 
                                        data-verified="<?= $row['is_verified'] ?>">
                                        <i class="fa-solid fa-pen"></i> Edit
                                    </button>
                                    <a href="?delete_umkm=<?= $row['id'] ?>" class="btn btn-sm btn-light text-danger rounded-pill btn-hapus">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; else: ?>
                            <tr><td colspan="4" class="text-center py-5 text-muted"><i class="fa-solid fa-store-slash fs-1 d-block mb-3"></i>Belum ada data UMKM.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<style>
.hero-umkm-admin {
    background: linear-gradient(135deg, rgba(220,53,69,0.92), rgba(176,42,55,0.88)), url('https://images.unsplash.com/photo-1556740714-a8395b3bf30f?auto=format&fit=crop&w=1200&q=80') center/cover no-repeat;
    color: white; padding: 60px 20px; border-radius: 15px; margin-top: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15); position: relative; overflow: hidden;
}
.hero-umkm-admin::after {
    content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0;
    background: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0IiBoZWlnaHQ9IjQiPjxyZWN0IHdpZHRoPSI0IiBoZWlnaHQ9IjQiIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iMC4wNSIvPjwvc3ZnPg==') repeat;
    pointer-events: none;
}
.status-box-umkm {
    background: #fff; border-radius: 15px; padding: 20px 28px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.06); text-align: center;
    border-top: 4px solid #dc3545; transition: transform 0.3s ease; min-width: 120px;
}
.status-box-umkm:hover { transform: translateY(-5px); }
.bg-gradient-danger { background: linear-gradient(135deg, #dc3545 0%, #b02a37 100%); }

.table-hover tbody tr:hover { background-color: #f8f9fa; }
</style>

<script>
    // Search Functionality
    document.getElementById('searchInput').addEventListener('input', function() {
        const filter = this.value.toLowerCase();
        const rows = document.querySelectorAll('#umkmTable tbody tr');
        
        rows.forEach(row => {
            if (row.querySelector('td.text-center.py-5')) return; // Skip empty message
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });

    // Form Edit Functionality
    document.querySelectorAll('.edit-umkm').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('umkm_id').value = this.dataset.id;
            document.getElementById('nama').value = this.dataset.nama;
            document.getElementById('kategori').value = this.dataset.kategori;
            document.getElementById('pengelola').value = this.dataset.pengelola;
            document.getElementById('kontak').value = this.dataset.kontak;
            document.getElementById('alamat').value = this.dataset.alamat;
            document.getElementById('gmaps_url').value = this.dataset.gmaps_url;
            document.getElementById('deskripsi').value = this.dataset.deskripsi;
            document.getElementById('is_verified').checked = this.dataset.verified === '1';
            
            document.getElementById('btnSave').innerHTML = '<i class="fa-solid fa-floppy-disk me-2"></i>Simpan Perubahan';
            document.getElementById('btnReset').style.display = 'block';
            document.getElementById('form').scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });

    function resetForm() {
        document.getElementById('formUmkm').reset();
        document.getElementById('umkm_id').value = '';
        document.getElementById('btnSave').innerHTML = '<i class="fa-solid fa-plus me-2"></i>Tambah UMKM';
        document.getElementById('btnReset').style.display = 'none';
    }
</script>

<?php include 'admin_footer.php'; ?>
