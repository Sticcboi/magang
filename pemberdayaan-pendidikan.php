<?php
// 1. Panggil Koneksi Database
require_once 'db_connect.php'; 

$page_title = 'Bidang Pendidikan - Kelurahan Kedungpane';
include 'includes/header.php';

// 2. Query JOIN untuk mengambil berita pendidikan beserta nama pengunggahnya
$query_pendidikan = mysqli_query($conn, "SELECT b.*, u.nama as pengupload 
    FROM berita b 
    LEFT JOIN users u ON b.penulis_id = u.id 
    WHERE b.kategori = 'pendidikan' AND b.is_published = 1 AND b.tanggal <= CURDATE() 
    ORDER BY b.tanggal DESC LIMIT 4");
?>

<style>
    /* Styling Dasar */
    .text-maroon { color: #8b0000; }
    
    .hero-section {
        background: linear-gradient(135deg, rgba(139,0,0,0.85), rgba(90,0,0,0.9)), url('https://placehold.co/1200x400/333/fff?text=Pendidikan+Kedungpane') no-repeat center center;
        background-size: cover;
        color: white;
        border-radius: 20px;
        padding: 50px 30px;
        margin-top: 20px;
        margin-bottom: 40px;
        box-shadow: 0 10px 30px rgba(139,0,0,0.15);
    }

    .card-custom {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        background: #fff;
        margin-bottom: 24px;
    }
    
    .school-card {
        border-left: 4px solid #8b0000;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        background: #fdfdfd;
        transition: 0.3s;
        cursor: pointer;
    }
    .school-card:hover { transform: translateX(6px); box-shadow: 0 5px 15px rgba(0,0,0,0.08); }
    .school-card.selected { background: #fff6f6; border-color: #b30000; }

    /* === STYLE KABAR PENDIDIKAN RINGKAS === */
    .news-compact {
        display: flex;
        align-items: flex-start;
        background: #fff;
        border: 1px solid #f0f0f0;
        border-radius: 12px;
        padding: 12px;
        margin-bottom: 12px;
        transition: all 0.2s ease;
        text-decoration: none;
        color: inherit;
    }
    .news-compact:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(139,0,0,0.08);
        border-color: #ffcccc;
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
        padding: 2px 8px;
        border-radius: 4px;
        text-transform: uppercase;
        font-weight: bold;
        display: inline-block;
        margin-bottom: 5px;
    }
    .news-compact-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 4px;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
        line-height: 1.2;
    }
    .news-compact-teaser {
        font-size: 0.8rem;
        color: #666;
        margin-bottom: 8px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        line-height: 1.4;
    }
    .news-meta {
        font-size: 0.7rem;
        color: #999;
        display: flex;
        gap: 10px;
    }
    .news-compact:hover .news-compact-title { color: #8b0000; }

    .btn-view-all {
        border: 1px solid #8b0000;
        color: #8b0000;
        border-radius: 50px;
        padding: 8px 15px;
        width: 100%;
        font-size: 0.85rem;
        font-weight: 600;
        text-align: center;
        display: block;
        text-decoration: none;
        transition: 0.3s;
    }
    .btn-view-all:hover { background-color: #8b0000; color: white; }
</style>

<div class="container my-4">
    <div class="hero-section text-center">
        <h1 class="fw-bold mb-3">Pendidikan Kelurahan Kedungpane</h1>
        <p class="fs-5 opacity-75 mb-0 mx-auto" style="max-width: 700px;">
            Akses informasi layanan dan kabar pendidikan terbaru di lingkungan Kedungpane.
        </p>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card card-custom">
                <div class="card-body p-4">
                    <h2 class="h5 fw-bold mb-4 border-bottom pb-3"><i class="bi bi-building text-maroon me-2"></i>Daftar Sekolah</h2>
                    
                    <?php

                    $jenjang_list = ['SD', 'SMP', 'SMA/SMK'];
                    $query_sekolah = mysqli_query($conn, "SELECT * FROM sekolah_pendidikan WHERE is_active = 1 ORDER BY FIELD(jenjang,'SD','SMP','SMA/SMK'), urutan ASC, id ASC");
                    $sekolah_data = [];
                    if ($query_sekolah) {
                        while ($s = mysqli_fetch_assoc($query_sekolah)) {
                            $sekolah_data[$s['jenjang']][] = $s;
                        }
                    }
                    
                    $has_any = false;
                    foreach ($jenjang_list as $jenjang):
                        if (!empty($sekolah_data[$jenjang])):
                            $has_any = true;
                            $is_last = ($jenjang === end($jenjang_list));
                    ?>
                    <div class="<?= $is_last ? 'mb-2' : 'mb-4' ?>">
                        <h3 class="h6 fw-bold text-maroon mb-3">Jenjang <?= htmlspecialchars($jenjang) ?></h3>
                        <?php foreach ($sekolah_data[$jenjang] as $sekolah): ?>
                        <div class="school-card" data-map="<?= htmlspecialchars($sekolah['data_map'] ?: $sekolah['nama_sekolah'] . ', Kedungpane, Semarang', ENT_QUOTES) ?>">
                            <h4><?= htmlspecialchars($sekolah['nama_sekolah']) ?></h4>
                            <?php if (!empty($sekolah['alamat'])): ?>
                            <p><i class="bi bi-geo-alt me-1"></i> <?= htmlspecialchars($sekolah['alamat']) ?></p>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php 
                        endif;
                    endforeach;
                    
                    if (!$has_any): ?>
                    <div class="alert alert-light border text-center p-4 rounded-4">
                        <h6 class="fw-bold text-secondary">Belum ada data sekolah.</h6>
                        <p class="mb-0">Administrator dapat menambahkan data melalui halaman admin.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <h2 class="h5 fw-bold mb-4"><i class="bi bi-newspaper me-2 text-maroon"></i>Kabar Pendidikan</h2>

            <?php 
            if($query_pendidikan && mysqli_num_rows($query_pendidikan) > 0) {
                while($berita = mysqli_fetch_assoc($query_pendidikan)):
                    $img_src = !empty($berita['gambar']) ? "uploads/berita/".$berita['gambar'] : "https://placehold.co/150x150/8b0000/FFFFFF?text=Berita";
            ?>
            <a href="detail-berita.php?slug=<?= $berita['slug'] ?>" class="news-compact">
                <img src="<?= $img_src ?>" class="news-compact-img" alt="Foto Berita">
                <div class="news-compact-body">
                    <span class="badge-kat"><?= htmlspecialchars($berita['kategori']) ?></span>
                    <div class="news-compact-title"><?= htmlspecialchars($berita['judul']) ?></div>
                    <div class="news-compact-teaser"><?= htmlspecialchars($berita['ringkasan']) ?></div>
                    <div class="news-meta">
                        <span><i class="bi bi-calendar3 me-1"></i> <?= date('d M Y', strtotime($berita['tanggal'])) ?></span>
                        <span><i class="bi bi-person-fill me-1"></i> <?= htmlspecialchars($berita['pengupload'] ?? 'Admin') ?></span>
                    </div>
                </div>
            </a>
            <?php 
                endwhile; 
                echo '<a href="berita.php?kategori=pendidikan" class="btn-view-all mt-3">Cek Berita Selengkapnya <i class="bi bi-arrow-right ms-1"></i></a>';
            } else { 
            ?>
            <div class="alert alert-light border text-center p-4 rounded-4">
                <h6 class="fw-bold text-secondary">Belum Ada Kabar Pendidikan</h6>
            </div>
            <?php } ?>

            <div class="card card-custom mt-4 overflow-hidden border-0">
                <div class="card-body p-0">
                    <div class="ratio ratio-4x3">
                        <iframe id="pendidikanMap" src="https://maps.google.com/maps?q=Kelurahan+Kedungpane,+Mijen,+Semarang&t=&z=13&ie=UTF8&iwloc=&output=embed?q=Kelurahan+Kedungpane,+Mijen,+Semarang&t=&z=14&ie=UTF8&iwloc=&output=embed" style="border: 0" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            </div>
        </div> 
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mapFrame = document.getElementById('pendidikanMap');
        const schoolCards = document.querySelectorAll('.school-card[data-map]');

        schoolCards.forEach((card) => {
            card.addEventListener('click', function() {
                // Encode query dari atribut data-map
                const query = encodeURIComponent(this.dataset.map);
                
                // Update src iframe ke lokasi yang dipilih
                mapFrame.src = `https://maps.google.com/maps?q=${query}&t=&z=15&ie=UTF8&iwloc=&output=embed`;
                
                // Ubah style state card yang dipilih
                schoolCards.forEach((item) => item.classList.remove('selected'));
                this.classList.add('selected');
                
                // Scroll agar peta terlihat
                const mapContainer = mapFrame.closest('.ratio');
                if (mapContainer) {
                    mapContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });
        });
    });
</script>

<?php include 'includes/footer.php'; ?>