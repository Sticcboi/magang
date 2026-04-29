<?php
// Hubungkan database
require_once 'db_connect.php'; 

// Tangkap 'slug' dari URL. Jika kosong, tendang balik ke halaman berita
$slug = isset($_GET['slug']) ? mysqli_real_escape_string($conn, $_GET['slug']) : '';
if (empty($slug)) {
    header("location: berita.php");
    exit;
}

// Ambil data berita yang dipilih beserta nama penulisnya
$query = "SELECT b.*, u.nama as penulis FROM berita b LEFT JOIN users u ON b.penulis_id = u.id WHERE b.slug = '$slug' AND b.is_published = 1 AND b.tanggal <= CURDATE()";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

// Jika berita tidak ditemukan atau statusnya Private / Draft
if (!$data) {
    echo "<script>alert('Berita tidak ditemukan atau belum diterbitkan!'); window.location='berita.php';</script>";
    exit;
}

// Set Title Halaman menjadi Judul Berita
$page_title = htmlspecialchars($data['judul']) . ' - Kelurahan Kedungpane';
include 'includes/header.php';

// Pengecekan nama kolom isi berita
$isi_berita = '';
if (!empty($data['isi'])) { 
    $isi_berita = $data['isi']; 
} elseif (!empty($data['konten'])) { 
    $isi_berita = $data['konten']; 
} elseif (!empty($data['deskripsi'])) {
    $isi_berita = $data['deskripsi'];
} else {
    $isi_berita = '<div class="alert alert-warning text-center mt-4">
        <i class="fa-solid fa-triangle-exclamation fs-3 text-warning mb-2"></i><br>
        <b>Isi berita kosong atau tidak ditemukan!</b>
    </div>';
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    /* Styling khusus untuk isi berita agar gambar/video responsif */
    .text-justify-custom img, .text-justify-custom iframe {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin-bottom: 15px;
    }
    
    /* === GAYA BARU SIDEBAR KABAR TERBARU (Sesuai Desain Gambar) === */
    .news-compact {
        display: flex;
        align-items: flex-start;
        background: #fff;
        border: 1px solid #f0f0f0;
        border-radius: 12px;
        padding: 12px;
        margin-bottom: 15px;
        transition: all 0.2s ease;
        text-decoration: none;
        color: inherit;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    }
    .news-compact:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(117,0,0,0.08);
        border-color: #f5c2c2;
    }
    .news-compact-img {
        width: 100px;
        height: 100px;
        border-radius: 8px;
        object-fit: cover;
        flex-shrink: 0;
    }
    .news-compact-body {
        padding-left: 15px;
        flex-grow: 1;
        overflow: hidden;
    }
    .badge-kat {
        font-size: 0.65rem;
        background: #8b0000;
        color: #fff;
        padding: 3px 8px;
        border-radius: 4px;
        text-transform: uppercase;
        font-weight: bold;
        display: inline-block;
        margin-bottom: 5px;
    }
    .news-compact-title {
        font-size: 0.95rem;
        font-weight: 800;
        color: #222;
        margin-bottom: 4px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        line-height: 1.3;
    }
    .news-compact-teaser {
        font-size: 0.8rem;
        color: #666;
        margin-bottom: 8px;
        display: -webkit-box;
        -webkit-line-clamp: 2; /* Batasi bocoran 2 baris */
        -webkit-box-orient: vertical;
        overflow: hidden;
        line-height: 1.4;
    }
    .news-meta {
        font-size: 0.75rem;
        color: #888;
        display: flex;
        gap: 12px;
    }
    .news-meta i {
        color: #aaa;
    }
    .news-compact:hover .news-compact-title { 
        color: #8b0000; 
    }

    /* Tombol Cek Berita Selengkapnya (Outline) */
    .btn-view-all-outline {
        border: 1.5px solid #8b0000;
        color: #8b0000;
        border-radius: 50px;
        padding: 10px 15px;
        width: 100%;
        font-size: 0.95rem;
        font-weight: 600;
        text-align: center;
        display: block;
        text-decoration: none;
        transition: 0.3s;
        background: transparent;
        margin-top: 5px;
    }
    .btn-view-all-outline:hover { 
        background-color: #8b0000; 
        color: white; 
    }
</style>

<div class="container-fluid px-4 px-lg-5 mt-4 mb-5 flex-grow-1">
    
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none" style="color: #8b0000;"><i class="fa-solid fa-house"></i> Beranda</a></li>
            <li class="breadcrumb-item"><a href="berita.php" class="text-decoration-none" style="color: #8b0000;">Berita</a></li>
            <li class="breadcrumb-item active text-muted" aria-current="page">Detail</li>
        </ol>
    </nav>

    <div class="row g-5">
        
        <div class="col-lg-8">
            <div class="custom-card-box bg-white rounded-4 p-4 p-md-5 shadow-sm border">
                
                <span class="badge mb-3 text-uppercase px-3 py-2 rounded-pill shadow-sm" style="background-color: #8b0000; font-size: 0.8rem;">
                    <?= htmlspecialchars($data['kategori'] ?? 'Umum'); ?>
                </span>
                
                <h1 class="fw-bold mb-3 text-dark" style="font-size: 2.2rem; line-height: 1.3;">
                    <?= htmlspecialchars($data['judul']); ?>
                </h1>
                
                <div class="d-flex align-items-center text-muted small mb-4 pb-3 border-bottom">
                    <span class="me-4"><i class="fa-regular fa-calendar-days" style="color: #8b0000;"></i> <?= date('d M Y', strtotime($data['tanggal'])); ?></span>
                    <span><i class="fa-solid fa-user-pen" style="color: #8b0000;"></i> <?= htmlspecialchars($data['penulis'] ?? 'Administrator'); ?></span>
                </div>

                <?php
                $img_detail = !empty($data['gambar']) ? "uploads/berita/".$data['gambar'] : "https://placehold.co/1200x600/8b0000/FFFFFF?text=Berita";
                ?>
                <img src="<?= $img_detail ?>" class="img-fluid rounded-4 shadow-sm mb-4 w-100" style="max-height: 450px; object-fit: cover;" alt="<?= htmlspecialchars($data['judul']) ?>" onerror="this.src='https://placehold.co/1200x600/8b0000/FFFFFF?text=Gambar+Tidak+Tersedia'">

                <div class="lh-lg text-justify-custom" style="color: #333; font-size: 1.05rem;">
                    <?= $isi_berita; ?>
                </div>

                <div class="mt-5 pt-4 border-top">
                    <span class="fw-bold me-3 text-dark">Bagikan Kabar:</span>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']) ?>" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3 me-2">
                        <i class="fa-brands fa-facebook"></i> Facebook
                    </a>
                    <a href="https://api.whatsapp.com/send?text=<?= urlencode('Baca berita menarik ini: '. $data['judul'] .' di http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']) ?>" target="_blank" class="btn btn-sm btn-outline-success rounded-pill px-3 me-2">
                        <i class="fa-brands fa-whatsapp"></i> WhatsApp
                    </a>
                    <button onclick="navigator.clipboard.writeText(window.location.href); alert('Link berita berhasil disalin!');" class="btn btn-sm btn-outline-dark rounded-pill px-3">
                        <i class="fa-solid fa-link"></i> Salin Link
                    </button>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="text-center text-lg-start">
                <div class="py-2 px-4 fs-6 fw-bold text-white rounded-top shadow-sm" style="background-color: #8b0000;">
                    Kabar Terbaru Lainnya
                </div>
            </div>
            
            <div class="bg-light border border-top-0 rounded-bottom p-3 shadow-sm mb-4">
                
                <?php
                // Query JOIN untuk mengambil nama penulis dan KECUALIKAN berita yang sedang dibaca
                $query_side = mysqli_query($conn, "SELECT b.*, u.nama as penulis FROM berita b LEFT JOIN users u ON b.penulis_id = u.id WHERE b.is_published = 1 AND b.tanggal <= CURDATE() AND b.id != ".$data['id']." ORDER BY b.tanggal DESC LIMIT 4");
                
                if(mysqli_num_rows($query_side) > 0){
                    
                    while($side = mysqli_fetch_assoc($query_side)):
                        $img_side = !empty($side['gambar']) ? "uploads/berita/".$side['gambar'] : "https://placehold.co/150x150/8b0000/FFFFFF?text=Berita";
                ?>
                    <a href="detail-berita.php?slug=<?= $side['slug'] ?>" class="news-compact">
                        <img src="<?= $img_side ?>" class="news-compact-img" alt="Thumbnail" onerror="this.src='https://placehold.co/150x150/8b0000/FFFFFF?text=No+Img'">
                        <div class="news-compact-body">
                            <span class="badge-kat"><?= htmlspecialchars($side['kategori']) ?></span>
                            <div class="news-compact-title"><?= htmlspecialchars($side['judul']) ?></div>
                            <div class="news-compact-teaser"><?= htmlspecialchars($side['ringkasan']) ?></div>
                            <div class="news-meta">
                                <span><i class="fa-regular fa-calendar-days"></i> <?= date('d M Y', strtotime($side['tanggal'])) ?></span>
                                <span><i class="fa-solid fa-user"></i> <?= htmlspecialchars($side['penulis'] ?? 'Administrator') ?></span>
                            </div>
                        </div>
                    </a>
                <?php 
                    endwhile; 
                ?>
                    
                    <a href="berita.php" class="btn-view-all-outline">
                        Cek Berita Selengkapnya <i class="fa-solid fa-arrow-right ms-1"></i>
                    </a>

                <?php
                } else {
                    echo '
                    <div class="text-center bg-white rounded py-4 border">
                        <i class="fa-regular fa-folder-open text-muted fs-1 mb-2 opacity-25"></i>
                        <p class="text-muted small mb-0 fw-bold">Belum ada berita lainnya.</p>
                    </div>';
                }
                ?>
                
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php include 'includes/footer.php'; ?>