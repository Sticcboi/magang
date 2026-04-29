<?php
require_once __DIR__ . '/auth.php';

// Logika Edit Layanan
if(isset($_POST['save_layanan'])){
    $id = $_POST['id'];
    $nama = mysqli_real_escape_string($conn, $_POST['nama_layanan']);
    $link = mysqli_real_escape_string($conn, $_POST['link_url']);
    
    mysqli_query($conn, "UPDATE layanan_administrasi SET nama_layanan='$nama', link_url='$link' WHERE id=$id");
    header("Location: admin_layanan.php?status=success");
}

$page_title = 'Kelola Layanan';
include 'admin_header.php';
?>

<div class="page-header">
    <h1>Manajemen Layanan Terpadu</h1>
    <p>Ubah nama dan link tujuan pada 6 tombol layanan di halaman depan.</p>
</div>

<div class="card-table">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Ikon</th>
                    <th>Nama Layanan</th>
                    <th>Link Tujuan</th>
                    <th width="150">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $q = mysqli_query($conn, "SELECT * FROM layanan_administrasi");
                while($l = mysqli_fetch_assoc($q)):
                ?>
                <form action="" method="POST">
                <tr>
                    <td><img src="../<?= $l['ikon_gambar'] ?>" width="40"></td>
                    <td><input type="text" name="nama_layanan" class="form-control form-control-sm" value="<?= $l['nama_layanan'] ?>"></td>
                    <td><input type="text" name="link_url" class="form-control form-control-sm" value="<?= $l['link_url'] ?>"></td>
                    <td>
                        <input type="hidden" name="id" value="<?= $l['id'] ?>">
                        <button type="submit" name="save_layanan" class="btn btn-sm btn-primary px-3">Update</button>
                    </td>
                </tr>
                </form>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'admin_footer.php'; ?>