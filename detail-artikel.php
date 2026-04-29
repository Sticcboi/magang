<?php
require_once 'db_connect.php'; 

// Ambil slug dari URL
$slug = isset($_GET['slug']) ? mysqli_real_escape_string($conn, $_GET['slug']) : '';

// Cari artikel berdasarkan slug
$query = mysqli_query($conn, "SELECT * FROM artikel_publik WHERE slug = '$slug' AND is_published = 1");
$artikel = mysqli_fetch_assoc($query);

// Jika artikel tidak ditemukan, redirect atau tampilkan pesan
if(!$artikel) {
    echo "<script>alert('Artikel tidak ditemukan!'); window.location.href='informasi-publik.php';</script>";
    exit;
}

// Set Judul Halaman dinamis
$page_title = $artikel['judul'] . ' | Kelurahan Kedungpane';
include 'includes/header.php';
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none" style="color: #8B0000;">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="informasi-publik.php" class="text-decoration-none" style="color: #8B0000;">Informasi Publik</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Detail Artikel</li>
                </ol>
            </nav>

            <h1 class="fw-bold mb-3" style="line-height: 1.4;"><?= htmlspecialchars($artikel['judul']) ?></h1>
            
            <div class="d-flex align-items-center gap-3 text-muted small mb-4 pb-3 border-bottom">
                <span><i class="bi bi-calendar3 me-1"></i> <?= date('d F Y', strtotime($artikel['tanggal'])) ?></span>
                <span><i class="bi bi-person-circle me-1"></i> Admin Kelurahan</span>
                <span class="badge" style="background-color: #8B0000;">Informasi Publik</span>
            </div>

            <?php if(!empty($artikel['gambar'])): ?>
                <div class="mb-4">
                    <img src="uploads/artikel/<?= htmlspecialchars($artikel['gambar']) ?>" class="img-fluid rounded-4 shadow-sm" alt="<?= htmlspecialchars($artikel['judul']) ?>" style="width: 100%; max-height: 450px; object-fit: cover;">
                </div>
            <?php endif; ?>

            <div class="artikel-konten" style="line-height: 1.8; font-size: 1.1rem; color: #444;">
                <?= $artikel['konten'] ?>
            </div>

            <div class="mt-5 border-top pt-4">
                <a href="informasi-publik.php" class="btn text-white rounded-pill px-4 py-2" style="background-color: #8B0000;">
                    <i class="bi bi-arrow-left me-2"></i> Kembali ke Daftar Informasi
                </a>
            </div>

        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>