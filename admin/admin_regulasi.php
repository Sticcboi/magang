<?php
require_once __DIR__ . '/auth.php'; 

// ==========================================
// HANDLE HAPUS DATA
// ==========================================
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    mysqli_query($conn, "DELETE FROM regulasi WHERE id = $id");
    header("Location: admin_regulasi.php?status=terhapus");
    exit;
}

// ==========================================
// HANDLE TAMBAH DATA
// ==========================================
if (isset($_POST['tambah_regulasi'])) {
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $judul = mysqli_real_escape_string($conn, $_POST['judul_regulasi']);
    $url = mysqli_real_escape_string($conn, $_POST['file_url']);

    mysqli_query($conn, "INSERT INTO regulasi (kategori, judul_dokumen, file_url) VALUES ('$kategori', '$judul', '$url')");
    header("Location: admin_regulasi.php?status=sukses");
    exit;
}

$query_reg = mysqli_query($conn, "SELECT * FROM regulasi ORDER BY kategori ASC, id DESC");
$page_title = 'Kelola Regulasi';
$current_page = 'admin_regulasi';
include 'admin_header.php';
?>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border border-light shadow-sm rounded-4 sticky-top bg-white" style="top: 20px;">
            <div class="card-header bg-white py-3 border-bottom border-light">
                <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-file-circle-plus text-danger me-2"></i>Tambah Regulasi</h5>
            </div>
            <div class="card-body p-4">
                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Kategori Regulasi</label>
                        <select name="kategori" class="form-select shadow-none border-1 bg-white" required>
                            <option value="" selected disabled>-- Pilih Kategori --</option>
                            <option value="dasar_hukum">Dasar Hukum Pembentukan</option>
                            <option value="perda_perwali">Perda & Perwali</option>
                            <option value="sop">SOP Pelayanan Publik</option>
                            <option value="maklumat">Maklumat Pelayanan</option>
                            <option value="perencanaan">Dokumen Perencanaan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Judul Dokumen</label>
                        <input type="text" name="judul_regulasi" class="form-control shadow-none border-1 bg-white" placeholder="Contoh: Perda tentang RT/RW" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-secondary">Tautan File (Google Drive / URL)</label>
                        <input type="url" name="file_url" class="form-control shadow-none border-1 bg-white" placeholder="https://..." required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" name="tambah_regulasi" class="btn fw-bold shadow-sm py-2" style="background-color: #8B0000; color: #ffffff; border-radius: 8px;">
                            <i class="fa-solid fa-cloud-arrow-up me-2"></i>Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border border-light shadow-sm rounded-4 bg-white">
            <div class="card-header bg-white py-3 border-bottom border-light d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-dark">Daftar Dokumen Regulasi</h5>
                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill px-3 py-2">
                    <?= mysqli_num_rows($query_reg) ?> Dokumen Terdaftar
                </span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 border-white">
                    <thead class="bg-white text-secondary small border-bottom border-light">
                        <tr>
                            <th class="ps-4 py-3 border-0 fw-semibold">Kategori</th>
                            <th class="py-3 border-0 fw-semibold">Judul Dokumen</th>
                            <th class="py-3 border-0 fw-semibold">Tautan</th>
                            <th class="py-3 border-0 text-end pe-4 fw-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        <?php if(mysqli_num_rows($query_reg) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($query_reg)): 
                                $label_kategori = ucwords(str_replace('_', ' ', $row['kategori']));
                            ?>
                            <tr>
                                <td class="ps-4 py-3 border-light">
                                    <span class="badge bg-light text-dark border px-2 py-1 fw-normal">
                                        <?= $label_kategori; ?>
                                    </span>
                                </td>
                                <td class="fw-medium text-dark py-3 border-light"><?= $row['judul_dokumen']; ?></td>
                                <td class="py-3 border-light">
                                    <a href="<?= $row['file_url']; ?>" target="_blank" class="btn btn-sm btn-outline-secondary rounded-pill py-1 px-3" style="font-size: 0.8rem;">
                                        <i class="fa-solid fa-up-right-from-square me-1"></i> Buka
                                    </a>
                                </td>
                                <td class="text-end pe-4 py-3 border-light">
                                    <a href="?hapus=<?= $row['id'] ?>" class="btn btn-sm btn-light rounded-circle shadow-sm text-danger btn-hapus" title="Hapus">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted border-0">
                                    <i class="fa-regular fa-folder-open fs-2 mb-2"></i><br>
                                    Belum ada data regulasi.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Menangani Notifikasi Sukses / Hapus dari URL Parameters
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');
    
    if (status === 'sukses') {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Dokumen regulasi baru telah ditambahkan.',
            confirmButtonColor: '#8B0000',
            background: '#ffffff'
        }).then(() => {
            // Membersihkan URL agar popup tidak muncul lagi saat di-refresh
            window.history.replaceState(null, null, window.location.pathname);
        });
    } else if (status === 'terhapus') {
        Swal.fire({
            icon: 'success',
            title: 'Terhapus!',
            text: 'Dokumen berhasil dihapus dari sistem.',
            confirmButtonColor: '#8B0000',
            background: '#ffffff'
        }).then(() => {
            window.history.replaceState(null, null, window.location.pathname);
        });
    }

    // 2. Menangani Konfirmasi Hapus Data
    const deleteButtons = document.querySelectorAll('.btn-hapus');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault(); 
            const deleteUrl = this.getAttribute('href');
            
            Swal.fire({
                title: 'Hapus Dokumen?',
                text: "Dokumen yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                background: '#ffffff'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = deleteUrl;
                }
            });
        });
    });
});
</script>

<?php include 'admin_footer.php'; ?>