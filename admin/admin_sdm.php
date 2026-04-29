<?php
require_once __DIR__ . '/auth.php'; 

// ==========================================
// 1. PROSES ACTION (SDM & STATISTIK)
// ==========================================
if (isset($_POST['tambah_sdm'])) {
    $tipe = mysqli_real_escape_string($conn, $_POST['tipe']);
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $nilai = mysqli_real_escape_string($conn, $_POST['nilai']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $ikon = mysqli_real_escape_string($conn, $_POST['ikon']);
    mysqli_query($conn, "INSERT INTO sdm_kelurahan (tipe, judul, nilai, deskripsi, ikon) VALUES ('$tipe', '$judul', '$nilai', '$deskripsi', '$ikon')");
    header("Location: admin_sdm.php?status=sukses"); exit;
}

if (isset($_POST['update_sdm'])) {
    $id = $_POST['id'];
    $tipe = mysqli_real_escape_string($conn, $_POST['tipe']);
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $nilai = mysqli_real_escape_string($conn, $_POST['nilai']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $ikon = mysqli_real_escape_string($conn, $_POST['ikon']);
    mysqli_query($conn, "UPDATE sdm_kelurahan SET tipe='$tipe', judul='$judul', nilai='$nilai', deskripsi='$deskripsi', ikon='$ikon' WHERE id='$id'");
    header("Location: admin_sdm.php?status=updated"); exit;
}

if (isset($_POST['update_statistik'])) {
    $id = $_POST['id'];
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $label = mysqli_real_escape_string($conn, $_POST['label']);
    $nilai = mysqli_real_escape_string($conn, $_POST['nilai']);
    $urutan = mysqli_real_escape_string($conn, $_POST['urutan']);
    mysqli_query($conn, "UPDATE statistik_kelurahan SET kategori='$kategori', label='$label', nilai='$nilai', urutan='$urutan' WHERE id='$id'");
    header("Location: admin_sdm.php?status=updated"); exit;
}

if (isset($_GET['hapus_sdm'])) {
    $id = $_GET['hapus_sdm'];
    mysqli_query($conn, "DELETE FROM sdm_kelurahan WHERE id='$id'");
    header("Location: admin_sdm.php?status=deleted"); exit;
}

if (isset($_GET['hapus_stat'])) {
    $id = $_GET['hapus_stat'];
    mysqli_query($conn, "DELETE FROM statistik_kelurahan WHERE id='$id'");
    header("Location: admin_sdm.php?status=deleted"); exit;
}

// Ambil Data untuk Looping
$data_sdm = mysqli_query($conn, "SELECT * FROM sdm_kelurahan ORDER BY tipe DESC");
$data_stat = mysqli_query($conn, "SELECT * FROM statistik_kelurahan ORDER BY kategori ASC, urutan ASC");

$page_title = 'Kelola SDM & Statistik';
$current_page = 'admin_sdm';
include 'admin_header.php';
?>

<div class="container-fluid mb-5">
    <div class="row g-4">
        
        <div class="col-md-7">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0">Lembaga (LKK/Aparatur)</h6>
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahSDM">
                        <i class="fa-solid fa-plus"></i> Tambah
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead><tr><th>Judul</th><th>Nilai</th><th class="text-end">Aksi</th></tr></thead>
                        <tbody>
                            <?php while($r1 = mysqli_fetch_assoc($data_sdm)): ?>
                            <tr>
                                <td><b><?= $r1['judul'] ?></b></td>
                                <td><?= $r1['nilai'] ?></td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-light text-primary" data-bs-toggle="modal" data-bs-target="#modalEditSDM<?= $r1['id'] ?>"><i class="fa-solid fa-pen"></i></button>
                                    <a href="?hapus_sdm=<?= $r1['id'] ?>" class="btn btn-sm btn-light text-danger btn-hapus" title="Hapus"><i class="fa-solid fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0">Statistik Chart</h6>
                    <button class="btn btn-sm text-white" style="background-color: #8B0000;" data-bs-toggle="modal" data-bs-target="#modalTambahStat">
                        <i class="fa-solid fa-plus"></i> Tambah
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead><tr><th>Label</th><th>Nilai</th><th class="text-end">Aksi</th></tr></thead>
                        <tbody>
                            <?php 
                            mysqli_data_seek($data_stat, 0); 
                            while($r2 = mysqli_fetch_assoc($data_stat)): ?>
                            <tr>
                                <td><?= $r2['label'] ?></td>
                                <td><?= $r2['nilai'] ?></td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-light text-primary" data-bs-toggle="modal" data-bs-target="#modalEditStat<?= $r2['id'] ?>"><i class="fa-solid fa-pen"></i></button>
                                    <a href="?hapus_stat=<?= $r2['id'] ?>" class="btn btn-sm btn-light text-danger btn-hapus" title="Hapus"><i class="fa-solid fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="modal fade" id="modalTambahSDM" tabindex="-1" data-bs-backdrop="false" style="background-color: rgba(0,0,0,0.6);">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <form action="" method="POST">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold">Tambah Lembaga</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tipe</label>
                        <select name="tipe" class="form-select" required>
                            <option value="lkk">LKK (RT/RW/PKK dll)</option>
                            <option value="aparatur">Aparatur</option>
                        </select>
                    </div>
                    <div class="mb-3"><label class="form-label fw-bold">Judul</label><input type="text" name="judul" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label fw-bold">Deskripsi</label><textarea name="deskripsi" class="form-control" rows="2" required></textarea></div>
                    <div class="mb-3"><label class="form-label fw-bold">Nilai / Jumlah</label><input type="text" name="nilai" class="form-control"></div>
                    <div class="mb-3"><label class="form-label fw-bold">Ikon (bi bi-people)</label><input type="text" name="ikon" class="form-control" required></div>
                </div>
                <div class="modal-footer"><button type="submit" name="tambah_sdm" class="btn btn-primary w-100">Simpan Data</button></div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambahStat" tabindex="-1" data-bs-backdrop="false" style="background-color: rgba(0,0,0,0.6);">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <form action="" method="POST">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold">Tambah Data Chart</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Kategori</label>
                        <select name="kategori" class="form-select" required>
                            <option value="usia">Usia</option>
                            <option value="pendidikan">Pendidikan</option>
                            <option value="pekerjaan">Pekerjaan</option>
                        </select>
                    </div>
                    <div class="mb-3"><label class="form-label fw-bold">Label</label><input type="text" name="label" class="form-control" required></div>
                    <div class="row">
                        <div class="col-8 mb-3"><label class="form-label fw-bold">Nilai (Jiwa)</label><input type="number" name="nilai" class="form-control" required></div>
                        <div class="col-4 mb-3"><label class="form-label fw-bold">Urutan</label><input type="number" name="urutan" class="form-control" value="1" required></div>
                    </div>
                </div>
                <div class="modal-footer"><button type="submit" name="tambah_statistik" class="btn text-white w-100" style="background-color: #8B0000;">Simpan Chart</button></div>
            </form>
        </div>
    </div>
</div>

<?php 
mysqli_data_seek($data_sdm, 0); 
while($r1 = mysqli_fetch_assoc($data_sdm)): 
?>
<div class="modal fade" id="modalEditSDM<?= $r1['id'] ?>" tabindex="-1" data-bs-backdrop="false" style="background-color: rgba(0,0,0,0.6);">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <form action="" method="POST">
                <input type="hidden" name="id" value="<?= $r1['id'] ?>">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold">Edit Lembaga</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tipe</label>
                        <select name="tipe" class="form-select">
                            <option value="lkk" <?= $r1['tipe']=='lkk'?'selected':'' ?>>LKK</option>
                            <option value="aparatur" <?= $r1['tipe']=='aparatur'?'selected':'' ?>>Aparatur</option>
                        </select>
                    </div>
                    <div class="mb-3"><label class="form-label fw-bold">Judul</label><input type="text" name="judul" class="form-control" value="<?= $r1['judul'] ?>" required></div>
                    <div class="mb-3"><label class="form-label fw-bold">Deskripsi</label><textarea name="deskripsi" class="form-control" rows="2" required><?= $r1['deskripsi'] ?></textarea></div>
                    <div class="mb-3"><label class="form-label fw-bold">Nilai</label><input type="text" name="nilai" class="form-control" value="<?= $r1['nilai'] ?>"></div>
                    <div class="mb-3"><label class="form-label fw-bold">Ikon</label><input type="text" name="ikon" class="form-control" value="<?= $r1['ikon'] ?>" required></div>
                </div>
                <div class="modal-footer"><button type="submit" name="update_sdm" class="btn btn-success w-100">Update Data</button></div>
            </form>
        </div>
    </div>
</div>
<?php endwhile; ?>

<?php 
mysqli_data_seek($data_stat, 0); 
while($r2 = mysqli_fetch_assoc($data_stat)): 
?>
<div class="modal fade" id="modalEditStat<?= $r2['id'] ?>" tabindex="-1" data-bs-backdrop="false" style="background-color: rgba(0,0,0,0.6);">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <form action="" method="POST">
                <input type="hidden" name="id" value="<?= $r2['id'] ?>">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold">Edit Chart</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Kategori</label>
                        <select name="kategori" class="form-select">
                            <option value="usia" <?= $r2['kategori']=='usia'?'selected':'' ?>>Usia</option>
                            <option value="pendidikan" <?= $r2['kategori']=='pendidikan'?'selected':'' ?>>Pendidikan</option>
                            <option value="pekerjaan" <?= $r2['kategori']=='pekerjaan'?'selected':'' ?>>Pekerjaan</option>
                        </select>
                    </div>
                    <div class="mb-3"><label class="form-label fw-bold">Label</label><input type="text" name="label" class="form-control" value="<?= $r2['label'] ?>" required></div>
                    <div class="row">
                        <div class="col-8 mb-3"><label class="form-label fw-bold">Nilai</label><input type="number" name="nilai" class="form-control" value="<?= $r2['nilai'] ?>" required></div>
                        <div class="col-4 mb-3"><label class="form-label fw-bold">Urutan</label><input type="number" name="urutan" class="form-control" value="<?= $r2['urutan'] ?>" required></div>
                    </div>
                </div>
                <div class="modal-footer"><button type="submit" name="update_statistik" class="btn btn-success w-100">Update Chart</button></div>
            </form>
        </div>
    </div>
</div>
<?php endwhile; ?>

<?php include 'admin_footer.php'; ?>