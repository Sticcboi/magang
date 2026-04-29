<?php
require_once __DIR__ . '/auth.php'; // Memastikan admin sudah login dan koneksi database aktif

// --- LOGIKA UPDATE DATA ---
if (isset($_POST['update_profil'])) {
    $nama_lurah    = mysqli_real_escape_string($conn, $_POST['nama_lurah']);
    $jabatan_lurah = mysqli_real_escape_string($conn, $_POST['jabatan_lurah']);
    $teks_sambutan = mysqli_real_escape_string($conn, $_POST['teks_sambutan']);
    $jml_penduduk  = $_POST['jml_penduduk'];
    $jml_rw        = $_POST['jml_rw'];
    $jml_rt        = $_POST['jml_rt'];
    $luas_wilayah  = $_POST['luas_wilayah'];
    $iframe_map    = mysqli_real_escape_string($conn, $_POST['iframe_map']);
    $batas_utara   = mysqli_real_escape_string($conn, $_POST['batas_utara']);
    $batas_selatan = mysqli_real_escape_string($conn, $_POST['batas_selatan']);
    $batas_timur   = mysqli_real_escape_string($conn, $_POST['batas_timur']);
    $batas_barat   = mysqli_real_escape_string($conn, $_POST['batas_barat']);

    // Logika Upload Foto Lurah
    $foto_lurah = $_POST['foto_lama'];
    if ($_FILES['foto_lurah']['name'] != "") {
        $nama_file = time() . '_' . $_FILES['foto_lurah']['name'];
        $tmp_file  = $_FILES['foto_lurah']['tmp_name'];
        $path      = "../img/" . $nama_file; // Folder img di root

        if (move_uploaded_file($tmp_file, $path)) {
            $foto_lurah = "img/" . $nama_file;
            // Hapus foto lama jika bukan foto default
            if ($_POST['foto_lama'] != "img/lurah.png" && file_exists("../" . $_POST['foto_lama'])) {
                unlink("../" . $_POST['foto_lama']);
            }
        }
    }

    $sql = "UPDATE profil_kelurahan SET 
            nama_lurah='$nama_lurah', jabatan_lurah='$jabatan_lurah', foto_lurah='$foto_lurah', 
            teks_sambutan='$teks_sambutan', jml_penduduk='$jml_penduduk', jml_rw='$jml_rw', 
            jml_rt='$jml_rt', luas_wilayah='$luas_wilayah', iframe_map='$iframe_map', 
            batas_utara='$batas_utara', batas_selatan='$batas_selatan', 
            batas_timur='$batas_timur', batas_barat='$batas_barat' 
            WHERE id=1";

    if (mysqli_query($conn, $sql)) {
        $msg = "Profil berhasil diperbarui!";
    } else {
        $err = "Gagal memperbarui: " . mysqli_error($conn);
    }
}

// Ambil data profil saat ini
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM profil_kelurahan WHERE id=1"));

$page_title = 'Kelola Profil & Wilayah';
$current_page = 'admin_profil';
include 'admin_header.php';
?>

<div class="page-header">
    <h1>Pengaturan Profil Kelurahan</h1>
    <p>Ubah informasi sambutan, statistik penduduk, dan peta wilayah.</p>
</div>

<?php if(isset($msg)) echo "<div class='alert alert-success'>$msg</div>"; ?>
<?php if(isset($err)) echo "<div class='alert alert-danger'>$err</div>"; ?>

<form action="" method="POST" enctype="multipart/form-data">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-bold">Konten Sambutan & Data Lurah</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap Lurah</label>
                        <input type="text" name="nama_lurah" class="form-control" value="<?= $data['nama_lurah'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jabatan</label>
                        <input type="text" name="jabatan_lurah" class="form-control" value="<?= $data['jabatan_lurah'] ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Teks Sambutan (Mendukung Tag HTML)</label>
                        <textarea name="teks_sambutan" class="form-control" rows="10"><?= $data['teks_sambutan'] ?></textarea>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold">Konfigurasi Peta & Batas Wilayah</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Iframe Google Maps</label>
                        <textarea name="iframe_map" class="form-control" rows="3"><?= $data['iframe_map'] ?></textarea>
                        <small class="text-muted">Tempelkan kode <code>&lt;iframe&gt;</code> dari Google Maps Share.</small>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3"><label>Batas Utara</label><input type="text" name="batas_utara" class="form-control" value="<?= $data['batas_utara'] ?>"></div>
                        <div class="col-md-6 mb-3"><label>Batas Selatan</label><input type="text" name="batas_selatan" class="form-control" value="<?= $data['batas_selatan'] ?>"></div>
                        <div class="col-md-6 mb-3"><label>Batas Timur</label><input type="text" name="batas_timur" class="form-control" value="<?= $data['batas_timur'] ?>"></div>
                        <div class="col-md-6 mb-3"><label>Batas Barat</label><input type="text" name="batas_barat" class="form-control" value="<?= $data['batas_barat'] ?>"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-bold">Foto Profil Lurah</div>
                <div class="card-body text-center">
                    <img src="../<?= $data['foto_lurah'] ?>" class="img-thumbnail rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                    <input type="file" name="foto_lurah" class="form-control">
                    <input type="hidden" name="foto_lama" value="<?= $data['foto_lurah'] ?>">
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold">Statistik Wilayah</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label>Jumlah Penduduk</label>
                        <input type="number" name="jml_penduduk" class="form-control" value="<?= $data['jml_penduduk'] ?>">
                    </div>
                    <div class="mb-3">
                        <label>Jumlah RW</label>
                        <input type="number" name="jml_rw" class="form-control" value="<?= $data['jml_rw'] ?>">
                    </div>
                    <div class="mb-3">
                        <label>Jumlah RT</label>
                        <input type="number" name="jml_rt" class="form-control" value="<?= $data['jml_rt'] ?>">
                    </div>
                    <div class="mb-3">
                        <label>Luas Wilayah (Ha)</label>
                        <input type="number" name="luas_wilayah" class="form-control" value="<?= $data['luas_wilayah'] ?>">
                    </div>
                </div>
            </div>

            <button type="submit" name="update_profil" class="btn btn-danger w-100 mt-4 py-2 fw-bold">SIMPAN PERUBAHAN</button>
        </div>
    </div>
</form>

<?php include 'admin_footer.php'; ?>