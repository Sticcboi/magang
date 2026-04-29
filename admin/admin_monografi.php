<?php
require_once __DIR__ . '/auth.php'; 

// Pastikan koneksi database ($conn) sudah di-include di file auth.php atau secara terpisah

if (isset($_POST['update_monografi'])) {
    // Escape string untuk mencegah SQL Injection
    $luas = mysqli_real_escape_string($conn, $_POST['luas_wilayah']);
    $rt = mysqli_real_escape_string($conn, $_POST['jml_rt']);
    $rw = mysqli_real_escape_string($conn, $_POST['jml_rw']);
    
    // Demografi
    $penduduk = mysqli_real_escape_string($conn, $_POST['jml_penduduk']);
    $penduduk_l = mysqli_real_escape_string($conn, $_POST['penduduk_l']);
    $penduduk_p = mysqli_real_escape_string($conn, $_POST['penduduk_p']);
    $kk = mysqli_real_escape_string($conn, $_POST['jml_kk']);
    $mata_pencaharian = mysqli_real_escape_string($conn, $_POST['mata_pencaharian']);
    
    // Geografis
    $utara = mysqli_real_escape_string($conn, $_POST['batas_utara']);
    $selatan = mysqli_real_escape_string($conn, $_POST['batas_selatan']);
    $timur = mysqli_real_escape_string($conn, $_POST['batas_timur']);
    $barat = mysqli_real_escape_string($conn, $_POST['batas_barat']);
    
    // Fasilitas
    $sd = mysqli_real_escape_string($conn, $_POST['fas_sd']);
    $ibadah = mysqli_real_escape_string($conn, $_POST['fas_ibadah']);
    $puskesmas = mysqli_real_escape_string($conn, $_POST['fas_puskesmas']);

    $query_update = "UPDATE profil_kelurahan SET 
        luas_wilayah='$luas', jml_rt='$rt', jml_rw='$rw', 
        jml_penduduk='$penduduk', penduduk_l='$penduduk_l', penduduk_p='$penduduk_p', 
        jml_kk='$kk', mata_pencaharian='$mata_pencaharian',
        batas_utara='$utara', batas_selatan='$selatan', batas_timur='$timur', batas_barat='$barat',
        fas_sd='$sd', fas_ibadah='$ibadah', fas_puskesmas='$puskesmas' 
        WHERE id=1";

    if(mysqli_query($conn, $query_update)) {
        header("Location: admin_monografi.php?status=sukses");
        exit;
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}

// Mengambil data saat ini
$query = mysqli_query($conn, "SELECT * FROM profil_kelurahan WHERE id=1");
$data = $query ? mysqli_fetch_assoc($query) : [
    'luas_wilayah' => '', 'jml_rt' => '', 'jml_rw' => '', 
    'jml_penduduk' => '', 'penduduk_l' => '', 'penduduk_p' => '', 'jml_kk' => '', 'mata_pencaharian' => '',
    'batas_utara' => '', 'batas_selatan' => '', 'batas_timur' => '', 'batas_barat' => '',
    'fas_sd' => '', 'fas_ibadah' => '', 'fas_puskesmas' => ''
];

$page_title = 'Kelola Monografi';
$current_page = 'admin_monografi';
include 'admin_header.php';
?>

<?php if (isset($_GET['status']) && $_GET['status'] == 'sukses'): ?>
    <div class="alert alert-success alert-dismissible fade show border-0 rounded-3 shadow-sm bg-white text-success" role="alert">
        <i class="fa-solid fa-check-circle me-2"></i> Data monografi berhasil diperbarui!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<form action="" method="POST">
    <div class="row g-4">
        
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 mb-4 h-100">
                <div class="card-header bg-white pt-4 pb-2 border-0">
                    <h5 class="fw-bold mb-0"><i class="fa-solid fa-map text-primary me-2"></i>Data Wilayah & Batas</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label small fw-semibold text-secondary">Luas Wilayah (Ha)</label>
                            <input type="number" name="luas_wilayah" class="form-control" value="<?= htmlspecialchars($data['luas_wilayah']); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-semibold text-secondary">Jumlah RT</label>
                            <input type="number" name="jml_rt" class="form-control" value="<?= htmlspecialchars($data['jml_rt']); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-semibold text-secondary">Jumlah RW</label>
                            <input type="number" name="jml_rw" class="form-control" value="<?= htmlspecialchars($data['jml_rw']); ?>" required>
                        </div>
                    </div>
                    <hr>
                    <h6 class="fw-bold text-secondary mb-3">Batas Wilayah Geografis</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-semibold text-secondary">Batas Utara</label>
                            <input type="text" name="batas_utara" class="form-control" value="<?= htmlspecialchars($data['batas_utara']); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-semibold text-secondary">Batas Selatan</label>
                            <input type="text" name="batas_selatan" class="form-control" value="<?= htmlspecialchars($data['batas_selatan']); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-semibold text-secondary">Batas Timur</label>
                            <input type="text" name="batas_timur" class="form-control" value="<?= htmlspecialchars($data['batas_timur']); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-semibold text-secondary">Batas Barat</label>
                            <input type="text" name="batas_barat" class="form-control" value="<?= htmlspecialchars($data['batas_barat']); ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white pt-4 pb-2 border-0">
                    <h5 class="fw-bold mb-0"><i class="fa-solid fa-users text-success me-2"></i>Data Demografi Penduduk</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-semibold text-secondary">Total Penduduk (Jiwa)</label>
                            <input type="number" name="jml_penduduk" class="form-control" value="<?= htmlspecialchars($data['jml_penduduk']); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-semibold text-secondary">Kepala Keluarga (KK)</label>
                            <input type="number" name="jml_kk" class="form-control" value="<?= htmlspecialchars($data['jml_kk']); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-semibold text-secondary">Penduduk Laki-laki</label>
                            <input type="number" name="penduduk_l" class="form-control" value="<?= htmlspecialchars($data['penduduk_l']); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-semibold text-secondary">Penduduk Perempuan</label>
                            <input type="number" name="penduduk_p" class="form-control" value="<?= htmlspecialchars($data['penduduk_p']); ?>">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label small fw-semibold text-secondary">Mayoritas Mata Pencaharian</label>
                            <textarea name="mata_pencaharian" class="form-control" rows="2" placeholder="Contoh: Karyawan Swasta, Buruh, PNS"><?= htmlspecialchars($data['mata_pencaharian']); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white pt-3 pb-2 border-0">
                    <h5 class="fw-bold mb-0"><i class="fa-solid fa-building text-warning me-2"></i>Fasilitas Umum</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-semibold text-secondary">Jumlah SD</label>
                            <input type="number" name="fas_sd" class="form-control" value="<?= htmlspecialchars($data['fas_sd']); ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-semibold text-secondary">Tempat Ibadah</label>
                            <input type="number" name="fas_ibadah" class="form-control" value="<?= htmlspecialchars($data['fas_ibadah']); ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-semibold text-secondary">Puskesmas</label>
                            <input type="number" name="fas_puskesmas" class="form-control" value="<?= htmlspecialchars($data['fas_puskesmas']); ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    
    <div class="mt-2 text-end mb-5">
        <button type="submit" name="update_monografi" class="btn btn-primary px-5 py-2 fw-bold shadow-sm">
            <i class="fa-solid fa-save me-2"></i> Simpan Pembaruan
        </button>
    </div>
</form>

<?php include 'admin_footer.php'; ?>