<?php
require_once __DIR__ . '/auth.php'; 

function ensureColumnExists($conn, $table, $column, $definition) {
    $check = mysqli_query($conn, "SHOW COLUMNS FROM `$table` LIKE '$column'");
    if ($check && mysqli_num_rows($check) === 0) {
        mysqli_query($conn, "ALTER TABLE `$table` ADD COLUMN $definition");
    }
}

function ensureTableExists($conn, $table, $createSql) {
    $check = mysqli_query($conn, "SHOW TABLES LIKE '$table'");
    if ($check && mysqli_num_rows($check) === 0) {
        mysqli_query($conn, $createSql);
    }
}

function ensureAutoIncrementId($conn, $table, $column = 'id') {
    $colCheck = mysqli_query($conn, "SHOW COLUMNS FROM `$table` LIKE '$column'");
    if ($colCheck && mysqli_num_rows($colCheck) > 0) {
        $colData = mysqli_fetch_assoc($colCheck);
        if (strpos($colData['Extra'], 'auto_increment') === false) {
            mysqli_query($conn, "ALTER TABLE `$table` MODIFY `$column` int(11) NOT NULL AUTO_INCREMENT");
        }
    }
    $keyCheck = mysqli_query($conn, "SHOW KEYS FROM `$table` WHERE Key_name = 'PRIMARY'");
    if ($keyCheck && mysqli_num_rows($keyCheck) === 0) {
        mysqli_query($conn, "ALTER TABLE `$table` ADD PRIMARY KEY (`$column`)");
    }
}

ensureColumnExists($conn, 'destinasi_wisata', 'alamat', "alamat varchar(255) DEFAULT NULL");
ensureColumnExists($conn, 'kontak_pariwisata', 'email', "email varchar(255) DEFAULT NULL");
ensureTableExists($conn, 'wisata_kuliner', "CREATE TABLE `wisata_kuliner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(150) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `jam_buka` varchar(100) DEFAULT NULL,
  `harga_tiket` varchar(100) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `maps_url` varchar(500) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");

ensureColumnExists($conn, 'wisata_kuliner', 'nama', "nama varchar(150) NOT NULL");
ensureColumnExists($conn, 'wisata_kuliner', 'deskripsi', "deskripsi text DEFAULT NULL");
ensureColumnExists($conn, 'wisata_kuliner', 'alamat', "alamat varchar(255) DEFAULT NULL");
ensureColumnExists($conn, 'wisata_kuliner', 'jam_buka', "jam_buka varchar(100) DEFAULT NULL");
ensureColumnExists($conn, 'wisata_kuliner', 'harga_tiket', "harga_tiket varchar(100) DEFAULT NULL");
ensureColumnExists($conn, 'wisata_kuliner', 'gambar', "gambar varchar(255) DEFAULT NULL");
ensureColumnExists($conn, 'wisata_kuliner', 'maps_url', "maps_url varchar(500) DEFAULT NULL");
ensureAutoIncrementId($conn, 'wisata_kuliner');

// ==========================================
// HANDLE UPDATE DATA PROFIL
// ==========================================
if (isset($_POST['update_pariwisata'])) {
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi_singkat']);
    $visi = mysqli_real_escape_string($conn, $_POST['visi']);
    $misi = mysqli_real_escape_string($conn, $_POST['misi']);
    
    mysqli_query($conn, "UPDATE profil_pariwisata SET deskripsi_singkat='$deskripsi', visi='$visi', misi='$misi' WHERE id=1");
    header("Location: admin_pariwisata.php?status=profil_updated");
    exit;
}

// ==========================================
// HANDLE UPDATE KONTAK & INFO
// ==========================================
if (isset($_POST['update_kontak'])) {
    $pengelola = mysqli_real_escape_string($conn, $_POST['pengelola_destinasi']);
    $kontak_p = mysqli_real_escape_string($conn, $_POST['kontak_pengelola']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $ig = mysqli_real_escape_string($conn, $_POST['instagram']);
    $fb = mysqli_real_escape_string($conn, $_POST['facebook']);
    $darurat = mysqli_real_escape_string($conn, $_POST['nomor_penting']);
    $info_singkat = mysqli_real_escape_string($conn, $_POST['informasi_singkat']);

    mysqli_query($conn, "UPDATE kontak_pariwisata SET 
        pengelola_destinasi='$pengelola', 
        kontak_pengelola='$kontak_p', 
        email='$email',
        instagram='$ig', 
        facebook='$fb', 
        nomor_penting='$darurat',
        informasi_singkat='$info_singkat' 
        WHERE id=1");
    
    header("Location: admin_pariwisata.php?status=kontak_updated");
    exit;
}

// ==========================================
// HANDLE TAMBAH DESTINASI WISATA
// ==========================================
if (isset($_POST['tambah_destinasi'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $jam_buka = mysqli_real_escape_string($conn, $_POST['jam_buka']);
    $harga_tiket = mysqli_real_escape_string($conn, $_POST['harga_tiket']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $gambar = mysqli_real_escape_string($conn, $_POST['gambar']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $maps_url = mysqli_real_escape_string($conn, $_POST['maps_url']);

    $query = "INSERT INTO destinasi_wisata (nama, kategori, jam_buka, harga_tiket, alamat, gambar, deskripsi, maps_url) 
              VALUES ('$nama', '$kategori', '$jam_buka', '$harga_tiket', '$alamat', '$gambar', '$deskripsi', '$maps_url')";
    mysqli_query($conn, $query);
    header("Location: admin_pariwisata.php?status=destinasi_ditambah");
    exit;
}

// ==========================================
// HANDLE UPDATE DESTINASI WISATA
// ==========================================
if (isset($_POST['update_destinasi'])) {
    $id = intval($_POST['destinasi_id']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $jam_buka = mysqli_real_escape_string($conn, $_POST['jam_buka']);
    $harga_tiket = mysqli_real_escape_string($conn, $_POST['harga_tiket']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $gambar = mysqli_real_escape_string($conn, $_POST['gambar']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $maps_url = mysqli_real_escape_string($conn, $_POST['maps_url']);

    mysqli_query($conn, "UPDATE destinasi_wisata SET 
        nama='$nama', 
        kategori='$kategori', 
        jam_buka='$jam_buka', 
        harga_tiket='$harga_tiket', 
        alamat='$alamat', 
        gambar='$gambar', 
        deskripsi='$deskripsi', 
        maps_url='$maps_url' 
        WHERE id=$id");

    header("Location: admin_pariwisata.php?status=destinasi_diupdate");
    exit;
}

// ==========================================
// HANDLE TAMBAH WISATA KULINER
// ==========================================
if (isset($_POST['tambah_kuliner'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $jam_buka = mysqli_real_escape_string($conn, $_POST['jam_buka']);
    $harga_tiket = mysqli_real_escape_string($conn, $_POST['harga_tiket']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $gambar = mysqli_real_escape_string($conn, $_POST['gambar']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $maps_url = mysqli_real_escape_string($conn, $_POST['maps_url']);

    $query = "INSERT INTO wisata_kuliner (nama, jam_buka, harga_tiket, alamat, gambar, deskripsi, maps_url) 
              VALUES ('$nama', '$jam_buka', '$harga_tiket', '$alamat', '$gambar', '$deskripsi', '$maps_url')";
    mysqli_query($conn, $query);
    header("Location: admin_pariwisata.php?status=kuliner_ditambah");
    exit;
}

// ==========================================
// HANDLE UPDATE WISATA KULINER
// ==========================================
if (isset($_POST['update_kuliner'])) {
    $id = intval($_POST['kuliner_id']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $jam_buka = mysqli_real_escape_string($conn, $_POST['jam_buka']);
    $harga_tiket = mysqli_real_escape_string($conn, $_POST['harga_tiket']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $gambar = mysqli_real_escape_string($conn, $_POST['gambar']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $maps_url = mysqli_real_escape_string($conn, $_POST['maps_url']);

    mysqli_query($conn, "UPDATE wisata_kuliner SET 
        nama='$nama', 
        jam_buka='$jam_buka', 
        harga_tiket='$harga_tiket', 
        alamat='$alamat', 
        gambar='$gambar', 
        deskripsi='$deskripsi', 
        maps_url='$maps_url' 
        WHERE id=$id");

    header("Location: admin_pariwisata.php?status=kuliner_diupdate");
    exit;
}

// ==========================================
// HANDLE HAPUS DESTINASI WISATA
// ==========================================
if (isset($_GET['hapus_destinasi'])) {
    $id = intval($_GET['hapus_destinasi']);
    mysqli_query($conn, "DELETE FROM destinasi_wisata WHERE id = $id");
    header("Location: admin_pariwisata.php?status=destinasi_dihapus");
    exit;
}

// ==========================================
// HANDLE HAPUS WISATA KULINER
// ==========================================
if (isset($_GET['hapus_kuliner'])) {
    $id = intval($_GET['hapus_kuliner']);
    mysqli_query($conn, "DELETE FROM wisata_kuliner WHERE id = $id");
    header("Location: admin_pariwisata.php?status=kuliner_dihapus");
    exit;
}

// Ambil data
$profil = mysqli_query($conn, "SELECT * FROM profil_pariwisata WHERE id=1");
$d = mysqli_fetch_assoc($profil);

$kontak = mysqli_query($conn, "SELECT * FROM kontak_pariwisata WHERE id=1");
$k = mysqli_fetch_assoc($kontak);

$edit_destinasi = null;
if (isset($_GET['edit_destinasi'])) {
    $edit_id = intval($_GET['edit_destinasi']);
    $result_edit = mysqli_query($conn, "SELECT * FROM destinasi_wisata WHERE id = $edit_id");
    if ($result_edit && mysqli_num_rows($result_edit) > 0) {
        $edit_destinasi = mysqli_fetch_assoc($result_edit);
    }
}

$query_destinasi = mysqli_query($conn, "SELECT * FROM destinasi_wisata ORDER BY id DESC");

$edit_kuliner = null;
if (isset($_GET['edit_kuliner'])) {
    $edit_id = intval($_GET['edit_kuliner']);
    $result_edit = mysqli_query($conn, "SELECT * FROM wisata_kuliner WHERE id = $edit_id");
    if ($result_edit && mysqli_num_rows($result_edit) > 0) {
        $edit_kuliner = mysqli_fetch_assoc($result_edit);
    }
}

$query_kuliner = mysqli_query($conn, "SELECT * FROM wisata_kuliner ORDER BY id DESC");

$page_title = 'Manajemen Pariwisata';
$current_page = 'admin_pariwisata';
include 'admin_header.php';
?>

<style>
    .form-control, .form-select {
        border: 1px solid #e9ecef !important;
        background-color: #ffffff !important;
        transition: all 0.2s ease;
    }
    .form-control:focus, .form-select:focus {
        border-color: #8B0000 !important;
        box-shadow: 0 0 0 0.25rem rgba(139, 0, 0, 0.05) !important;
    }
    .nav-tabs .nav-link {
        color: #6c757d;
        border: none;
        font-weight: 600;
        padding: 1rem 1.5rem;
    }
    .nav-tabs .nav-link.active {
        color: #8B0000;
        border-bottom: 3px solid #8B0000;
        background: none;
    }
    .card-custom {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    }
    .swal2-container {
        z-index: 10000 !important;
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1">Manajemen Pariwisata</h3>
        <p class="text-secondary small">Kelola informasi objek wisata dan destinasi lokal secara terpadu.</p>
    </div>
    <div class="card border-0 shadow-sm px-3 py-2 bg-primary text-white rounded-4 d-flex flex-row align-items-center" style="background-color: #8B0000 !important;">
        <i class="fa-solid fa-map-location-dot fs-3 me-3"></i>
        <div>
            <h4 class="mb-0 fw-bold"><?= mysqli_num_rows($query_destinasi) ?></h4>
            <small class="opacity-75">Destinasi Aktif</small>
        </div>
    </div>
</div>

<div class="card card-custom bg-white">
    <div class="card-header bg-white border-0 p-0">
        <ul class="nav nav-tabs border-bottom" id="pariwisataTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#profil"><i class="fa-solid fa-file-lines me-2"></i>Profil Pariwisata</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#destinasi"><i class="fa-solid fa-map-pin me-2"></i>Destinasi Wisata</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#kuliner"><i class="fa-solid fa-utensils me-2"></i>Wisata Kuliner</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#kontak"><i class="fa-solid fa-phone me-2"></i>Kontak & Info</a>
            </li>
        </ul>
    </div>
    
    <div class="card-body p-4">
        <div class="tab-content">
            
            <div class="tab-pane fade show active" id="profil">
                <form action="" method="POST">
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-secondary">Deskripsi Singkat Pariwisata</label>
                        <textarea name="deskripsi_singkat" class="form-control" rows="4" placeholder="Jelaskan potensi wisata Kelurahan Kedungpane..."><?= $d['deskripsi_singkat'] ?? '' ?></textarea>
                    </div>
                    
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary">Visi Pariwisata</label>
                            <textarea name="visi" class="form-control" rows="4"><?= $d['visi'] ?? '' ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary">Misi Pariwisata</label>
                            <textarea name="misi" class="form-control" rows="4"><?= $d['misi'] ?? '' ?></textarea>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                        <button type="submit" name="update_pariwisata" class="btn px-4 fw-bold shadow-sm" style="background-color: #8B0000; color: white; border-radius: 8px;">
                            <i class="fa-solid fa-check-circle me-2"></i>Simpan Profil
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="tab-pane fade" id="destinasi">
                <div class="row g-4">
                    <div class="col-lg-4">
                        <div class="card border border-light shadow-sm rounded-4 bg-light">
                            <div class="card-header bg-light py-3 border-bottom border-white">
                                <h6 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-plus-circle text-danger me-2"></i>Tambah Destinasi Baru</h6>
                            </div>
                            <div class="card-body p-3">
                                <?php if ($edit_destinasi): ?>
                                    <div class="mb-3">
                                        <h6 class="fw-bold text-dark">Edit Destinasi</h6>
                                        <p class="text-secondary small mb-3">Ubah data destinasi wisata lalu klik Update.</p>
                                    </div>
                                <?php endif; ?>
                                <form action="" method="POST">
                                    <?php if ($edit_destinasi): ?>
                                        <input type="hidden" name="destinasi_id" value="<?= $edit_destinasi['id'] ?>">
                                    <?php endif; ?>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-secondary">Nama Destinasi</label>
                                        <input type="text" name="nama" class="form-control" required value="<?= htmlspecialchars($edit_destinasi['nama'] ?? '') ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-secondary">Alamat</label>
                                        <input type="text" name="alamat" class="form-control" placeholder="Jl. contoh no. 1" value="<?= htmlspecialchars($edit_destinasi['alamat'] ?? '') ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-secondary">Kategori</label>
                                        <select name="kategori" class="form-select" required>
                                            <option value="alam" <?= (isset($edit_destinasi['kategori']) && $edit_destinasi['kategori'] === 'alam') ? 'selected' : '' ?>>Wisata Alam</option>
                                            <option value="budaya" <?= (isset($edit_destinasi['kategori']) && $edit_destinasi['kategori'] === 'budaya') ? 'selected' : '' ?>>Wisata Budaya</option>
                                            <option value="religi" <?= (isset($edit_destinasi['kategori']) && $edit_destinasi['kategori'] === 'religi') ? 'selected' : '' ?>>Wisata Religi</option>
                                            <option value="kuliner" <?= (isset($edit_destinasi['kategori']) && $edit_destinasi['kategori'] === 'kuliner') ? 'selected' : '' ?>>Wisata Kuliner</option>
                                            <option value="buatan" <?= (isset($edit_destinasi['kategori']) && $edit_destinasi['kategori'] === 'buatan') ? 'selected' : '' ?>>Wisata Buatan</option>
                                        </select>
                                    </div>
                                    <div class="row g-2 mb-3">
                                        <div class="col-6">
                                            <label class="form-label small fw-bold text-secondary">Jam Buka</label>
                                            <input type="text" name="jam_buka" class="form-control" placeholder="08:00 - 17:00" value="<?= htmlspecialchars($edit_destinasi['jam_buka'] ?? '') ?>">
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label small fw-bold text-secondary">Harga Tiket</label>
                                            <input type="text" name="harga_tiket" class="form-control" placeholder="Rp 10.000" value="<?= htmlspecialchars($edit_destinasi['harga_tiket'] ?? '') ?>">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-secondary">Link Gambar (URL)</label>
                                        <input type="text" name="gambar" class="form-control" placeholder="https://..." value="<?= htmlspecialchars($edit_destinasi['gambar'] ?? '') ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-secondary">Link Google Maps</label>
                                        <input type="text" name="maps_url" class="form-control" placeholder="https://maps.google..." value="<?= htmlspecialchars($edit_destinasi['maps_url'] ?? '') ?>">
                                        <small class="text-muted d-block mt-1">Gunakan URL lengkap, koordinat (misal: -7.03, 110.3), atau klik <strong>Bagikan > Sematkan Peta</strong>. Hindari link pendek (goo.gl) agar peta bisa dimuat.</small>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label small fw-bold text-secondary">Deskripsi</label>
                                        <textarea name="deskripsi" class="form-control" rows="3"><?= htmlspecialchars($edit_destinasi['deskripsi'] ?? '') ?></textarea>
                                    </div>
                                    <?php if ($edit_destinasi): ?>
                                        <button type="submit" name="update_destinasi" class="btn w-100 fw-bold shadow-sm" style="background-color: #8B0000; color: white; border-radius: 8px;">
                                            Update Destinasi
                                        </button>
                                        <a href="admin_pariwisata.php" class="btn btn-outline-secondary w-100 mt-2" style="border-radius: 8px;">Batal</a>
                                    <?php else: ?>
                                        <button type="submit" name="tambah_destinasi" class="btn w-100 fw-bold shadow-sm" style="background-color: #8B0000; color: white; border-radius: 8px;">
                                            Simpan Destinasi
                                        </button>
                                    <?php endif; ?>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <div class="table-responsive border border-light rounded-4">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-secondary small">
                                    <tr>
                                        <th class="ps-3 py-3 border-0 fw-semibold">Nama Destinasi</th>
                                        <th class="py-3 border-0 fw-semibold">Kategori</th>
                                        <th class="py-3 border-0 fw-semibold">Info</th>
                                        <th class="py-3 border-0 text-end pe-3 fw-semibold">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(mysqli_num_rows($query_destinasi) > 0): ?>
                                        <?php while($row = mysqli_fetch_assoc($query_destinasi)): ?>
                                        <tr>
                                            <td class="ps-3 py-3 fw-bold text-dark">
                                                <?= $row['nama']; ?><br>
                                                <small class="text-muted fw-normal"><?= substr($row['deskripsi'], 0, 50); ?>...</small>
                                            </td>
                                            <td class="py-3">
                                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1">
                                                    <?= ucfirst($row['kategori']); ?>
                                                </span>
                                            </td>
                                            <td class="py-3 small text-secondary">
                                                <i class="fa-regular fa-clock me-1"></i> <?= $row['jam_buka']; ?><br>
                                                <i class="fa-solid fa-ticket me-1"></i> <?= $row['harga_tiket']; ?>
                                            </td>
                                            <td class="text-end pe-3 py-3">
                                                <a href="admin_pariwisata.php?edit_destinasi=<?= $row['id'] ?>" class="btn btn-sm btn-light rounded-circle text-primary me-2" title="Edit">
                                                    <i class="fa-solid fa-pencil"></i>
                                                </a>
                                                <a href="admin_pariwisata.php?hapus_destinasi=<?= $row['id'] ?>" class="btn btn-sm btn-light rounded-circle text-danger btn-hapus" title="Hapus">
                                                    <i class="fa-solid fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="text-center py-5 text-muted border-0">Belum ada destinasi wisata.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="kuliner">
                <div class="row g-4">
                    <div class="col-lg-4">
                        <div class="card border border-light shadow-sm rounded-4 bg-light">
                            <div class="card-header bg-light py-3 border-bottom border-white">
                                <h6 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-utensils text-danger me-2"></i><?= $edit_kuliner ? 'Edit Wisata Kuliner' : 'Tambah Wisata Kuliner' ?></h6>
                            </div>
                            <div class="card-body p-3">
                                <form action="" method="POST">
                                    <?php if ($edit_kuliner): ?>
                                        <input type="hidden" name="kuliner_id" value="<?= $edit_kuliner['id'] ?>">
                                    <?php endif; ?>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-secondary">Nama Kuliner</label>
                                        <input type="text" name="nama" class="form-control" required value="<?= htmlspecialchars($edit_kuliner['nama'] ?? '') ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-secondary">Alamat</label>
                                        <input type="text" name="alamat" class="form-control" placeholder="Jl. Contoh No. 1" value="<?= htmlspecialchars($edit_kuliner['alamat'] ?? '') ?>">
                                    </div>
                                    <div class="row g-2 mb-3">
                                        <div class="col-6">
                                            <label class="form-label small fw-bold text-secondary">Jam Buka</label>
                                            <input type="text" name="jam_buka" class="form-control" placeholder="08:00 - 20:00" value="<?= htmlspecialchars($edit_kuliner['jam_buka'] ?? '') ?>">
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label small fw-bold text-secondary">Harga</label>
                                            <input type="text" name="harga_tiket" class="form-control" placeholder="Rp 10.000" value="<?= htmlspecialchars($edit_kuliner['harga_tiket'] ?? '') ?>">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-secondary">Link Gambar (URL)</label>
                                        <input type="text" name="gambar" class="form-control" placeholder="https://..." value="<?= htmlspecialchars($edit_kuliner['gambar'] ?? '') ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-secondary">Link Google Maps</label>
                                        <input type="text" name="maps_url" class="form-control" placeholder="https://maps.google..." value="<?= htmlspecialchars($edit_kuliner['maps_url'] ?? '') ?>">
                                        <small class="text-muted d-block mt-1">Gunakan URL lengkap, koordinat (misal: -7.03, 110.3), atau klik <strong>Bagikan > Sematkan Peta</strong>. Hindari link pendek (goo.gl) agar peta bisa dimuat.</small>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label small fw-bold text-secondary">Deskripsi</label>
                                        <textarea name="deskripsi" class="form-control" rows="3"><?= htmlspecialchars($edit_kuliner['deskripsi'] ?? '') ?></textarea>
                                    </div>
                                    <?php if ($edit_kuliner): ?>
                                        <button type="submit" name="update_kuliner" class="btn w-100 fw-bold shadow-sm" style="background-color: #8B0000; color: white; border-radius: 8px;">
                                            Update Kuliner
                                        </button>
                                        <a href="admin_pariwisata.php#kuliner" class="btn btn-outline-secondary w-100 mt-2" style="border-radius: 8px;">Batal</a>
                                    <?php else: ?>
                                        <button type="submit" name="tambah_kuliner" class="btn w-100 fw-bold shadow-sm" style="background-color: #8B0000; color: white; border-radius: 8px;">
                                            Simpan Kuliner
                                        </button>
                                    <?php endif; ?>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <div class="table-responsive border border-light rounded-4">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-secondary small">
                                    <tr>
                                        <th class="ps-3 py-3 border-0 fw-semibold">Nama Kuliner</th>
                                        <th class="py-3 border-0 fw-semibold">Alamat</th>
                                        <th class="py-3 border-0 fw-semibold">Jam / Harga</th>
                                        <th class="py-3 border-0 text-end pe-3 fw-semibold">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(mysqli_num_rows($query_kuliner) > 0): ?>
                                        <?php while($row = mysqli_fetch_assoc($query_kuliner)): ?>
                                        <tr>
                                            <td class="ps-3 py-3 fw-bold text-dark">
                                                <?= htmlspecialchars($row['nama']); ?><br>
                                                <small class="text-muted fw-normal"><?= htmlspecialchars(substr($row['deskripsi'], 0, 60)); ?>...</small>
                                            </td>
                                            <td class="py-3 small text-secondary"><?= htmlspecialchars($row['alamat'] ?? '-') ?></td>
                                            <td class="py-3 small text-secondary">
                                                <?= htmlspecialchars($row['jam_buka'] ?? '-') ?><br>
                                                <?= htmlspecialchars($row['harga_tiket'] ?? '-') ?>
                                            </td>
                                            <td class="text-end pe-3 py-3">
                                                <a href="admin_pariwisata.php?edit_kuliner=<?= $row['id'] ?>" class="btn btn-sm btn-light rounded-circle text-primary me-2" title="Edit">
                                                    <i class="fa-solid fa-pencil"></i>
                                                </a>
                                                <a href="admin_pariwisata.php?hapus_kuliner=<?= $row['id'] ?>" class="btn btn-sm btn-light rounded-circle text-danger btn-hapus" title="Hapus">
                                                    <i class="fa-solid fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="text-center py-5 text-muted border-0">Belum ada wisata kuliner.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="kontak">
                <form action="" method="POST">
                    <div class="row g-4">
                        <div class="col-md-12">
                            <label class="form-label small fw-bold text-secondary">Informasi Singkat (Slogan Pariwisata)</label>
                            <input type="text" name="informasi_singkat" class="form-control" value="<?= htmlspecialchars($k['informasi_singkat'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-secondary">Pengelola Destinasi</label>
                            <input type="text" name="pengelola_destinasi" class="form-control" value="<?= htmlspecialchars($k['pengelola_destinasi'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-secondary">Kontak / Telepon</label>
                            <input type="text" name="kontak_pengelola" class="form-control" value="<?= htmlspecialchars($k['kontak_pengelola'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-secondary">Email Pengelola</label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($k['email'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary">Instagram</label>
                            <input type="text" name="instagram" class="form-control" value="<?= htmlspecialchars($k['instagram'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary">Facebook</label>
                            <input type="text" name="facebook" class="form-control" value="<?= htmlspecialchars($k['facebook'] ?? '') ?>">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-bold text-secondary">Nomor Darurat (Penting)</label>
                            <input type="text" name="nomor_penting" class="form-control text-danger fw-bold" value="<?= htmlspecialchars($k['nomor_penting'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                        <button type="submit" name="update_kontak" class="btn px-4 fw-bold shadow-sm" style="background-color: #8B0000; color: white; border-radius: 8px;">
                            <i class="fa-solid fa-save me-2"></i>Simpan Kontak
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Tangani Notifikasi Berdasarkan URL Parameter
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');
    
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    if (status === 'profil_updated') {
        Toast.fire({ icon: 'success', title: 'Profil Pariwisata disimpan!' })
        .then(() => window.history.replaceState(null, null, window.location.pathname));
    } else if (status === 'kontak_updated') {
        Toast.fire({ icon: 'success', title: 'Data Kontak & Info disimpan!' })
        .then(() => window.history.replaceState(null, null, window.location.pathname));
        var triggerEl = document.querySelector('a[href="#kontak"]');
        var tab = new bootstrap.Tab(triggerEl);
        tab.show();
    } else if (status === 'destinasi_ditambah') {
        Toast.fire({ icon: 'success', title: 'Destinasi wisata baru ditambahkan!' })
        .then(() => window.history.replaceState(null, null, window.location.pathname));
        var triggerEl = document.querySelector('a[href="#destinasi"]');
        var tab = new bootstrap.Tab(triggerEl);
        tab.show();
    } else if (status === 'destinasi_diupdate') {
        Toast.fire({ icon: 'success', title: 'Destinasi wisata berhasil diupdate!' })
        .then(() => window.history.replaceState(null, null, window.location.pathname));
        var triggerEl = document.querySelector('a[href="#destinasi"]');
        var tab = new bootstrap.Tab(triggerEl);
        tab.show();
    } else if (status === 'destinasi_dihapus') {
        Toast.fire({ icon: 'success', title: 'Destinasi wisata berhasil dihapus' })
        .then(() => window.history.replaceState(null, null, window.location.pathname));
        var triggerEl = document.querySelector('a[href="#destinasi"]');
        var tab = new bootstrap.Tab(triggerEl);
        tab.show();
    } else if (status === 'kuliner_ditambah') {
        Toast.fire({ icon: 'success', title: 'Wisata kuliner baru ditambahkan!' })
        .then(() => window.history.replaceState(null, null, window.location.pathname));
        var triggerEl = document.querySelector('a[href="#kuliner"]');
        var tab = new bootstrap.Tab(triggerEl);
        tab.show();
    } else if (status === 'kuliner_diupdate') {
        Toast.fire({ icon: 'success', title: 'Wisata kuliner berhasil diupdate!' })
        .then(() => window.history.replaceState(null, null, window.location.pathname));
        var triggerEl = document.querySelector('a[href="#kuliner"]');
        var tab = new bootstrap.Tab(triggerEl);
        tab.show();
    } else if (status === 'kuliner_dihapus') {
        Toast.fire({ icon: 'success', title: 'Wisata kuliner berhasil dihapus' })
        .then(() => window.history.replaceState(null, null, window.location.pathname));
        var triggerEl = document.querySelector('a[href="#kuliner"]');
        var tab = new bootstrap.Tab(triggerEl);
        tab.show();
    }

    if (urlParams.has('edit_destinasi')) {
        var triggerEl = document.querySelector('a[href="#destinasi"]');
        var tab = new bootstrap.Tab(triggerEl);
        tab.show();
    }
    if (urlParams.has('edit_kuliner')) {
        var triggerEl = document.querySelector('a[href="#kuliner"]');
        var tab = new bootstrap.Tab(triggerEl);
        tab.show();
    }

    // 2. Tangani Konfirmasi Hapus
    const deleteButtons = document.querySelectorAll('.btn-hapus');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault(); 
            const deleteUrl = this.getAttribute('href');
            Swal.fire({
                title: 'Hapus Destinasi?',
                text: "Data yang dihapus tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) { window.location.href = deleteUrl; }
            });
        });
    });
});
</script>

<?php include 'admin_footer.php'; ?>