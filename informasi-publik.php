<?php
require_once 'db_connect.php'; 

$page_title = 'Informasi Publik | Kelurahan Kedungpane';
include 'includes/header.php';

function getInfoPublik($conn, $tipe) {
    $query = "SELECT * FROM informasi_publik WHERE tipe_info = '$tipe' ORDER BY kategori_spesifik ASC, tanggal DESC";
    $result = mysqli_query($conn, $query);
    $data = [];
    if ($result) {
        while($row = mysqli_fetch_assoc($result)) {
            $data[$row['kategori_spesifik']][] = $row;
        }
    }
    return $data;
}

$info_berkala = getInfoPublik($conn, 'berkala');
$info_setiap_saat = getInfoPublik($conn, 'setiap_saat');

$query_serta_merta = mysqli_query($conn, "SELECT * FROM informasi_publik WHERE tipe_info = 'serta_merta' ORDER BY tanggal DESC LIMIT 3");

// REVISI: Dibatasi maksimal 5 artikel terbaru
$query_publik = mysqli_query($conn, "SELECT * FROM artikel_publik WHERE is_published = 1 AND tanggal <= CURDATE() ORDER BY tanggal DESC LIMIT 5");
?>

<style>
    /* CSS Khusus untuk merapikan Card Artikel Publikasi */
    .artikel-card {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        background: #ffffff;
        border: 1px solid #e9ecef;
        padding: 15px;
        border-radius: 12px;
        text-decoration: none !important;
        color: #333 !important;
        margin-bottom: 15px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .artikel-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(139, 0, 0, 0.1); /* Efek bayangan merah maroon tipis */
        border-color: rgba(139, 0, 0, 0.2);
    }
    .artikel-img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 8px;
        flex-shrink: 0;
    }
    .artikel-body {
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }
    .artikel-badge {
        font-size: 0.7rem;
        background-color: #8B0000;
        color: #fff;
        padding: 4px 8px;
        border-radius: 4px;
        align-self: flex-start;
        margin-bottom: 8px;
        font-weight: 600;
    }
    .artikel-title {
        font-size: 1rem;
        font-weight: bold;
        line-height: 1.3;
        margin-bottom: 5px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .artikel-teaser {
        font-size: 0.85rem;
        color: #6c757d;
        margin-bottom: 10px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .artikel-meta {
        font-size: 0.75rem;
        color: #adb5bd;
        display: flex;
        gap: 12px;
    }
    .btn-view-all {
        display: block;
        text-align: center;
        background: #f8f9fa;
        color: #8B0000;
        padding: 10px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: bold;
        border: 1px solid #e9ecef;
        transition: 0.3s;
    }
    .btn-view-all:hover {
        background: #8B0000;
        color: #fff;
    }
</style>

<div class="container my-4">
    <div class="hero-section text-center mb-5">
        <h1 class="fw-bold mb-3">Informasi Publik Kelurahan Kedungpane</h1>
        <p class="fs-5 opacity-75 mb-0 mx-auto" style="max-width: 700px;">
            Akses transparansi informasi, layanan masyarakat, dan dokumen publik terbaru di lingkungan Kedungpane, Kecamatan Mijen.
        </p>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    
                    <h2 class="h5 fw-bold mb-4 border-bottom pb-3"><i class="bi bi-journal-text text-maroon me-2" style="color:#8B0000;"></i>Daftar Informasi Berkala</h2>
                    <div class="mb-5">
                        <?php 
                        $i = 1;
                        if(empty($info_berkala)) echo '<p class="text-muted small">Belum ada dokumen berkala.</p>';
                        foreach($info_berkala as $kategori => $dokumens): 
                            $collapseId = "collapseBerkala" . $i;
                        ?>
                        <a data-bs-toggle="collapse" href="#<?= $collapseId ?>" role="button" aria-expanded="false" class="text-decoration-none d-block mb-2 p-2 bg-light rounded">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="h6 fw-bold text-dark mb-0"><?= htmlspecialchars($kategori) ?></h4>
                                <i class="bi bi-chevron-down text-dark"></i>
                            </div>
                        </a>
                        <div class="collapse mb-3" id="<?= $collapseId ?>">
                            <div class="p-3 border rounded bg-white">
                                <?php foreach($dokumens as $dok): ?>
                                <a href="uploads/dokumen/<?= htmlspecialchars($dok['file_dokumen'] ?? '') ?>" target="_blank" class="d-block text-decoration-none text-dark mb-2 border-bottom pb-2">
                                    <i class="bi bi-file-earmark-pdf text-danger me-2"></i><?= htmlspecialchars($dok['judul']) ?>
                                </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php $i++; endforeach; ?>
                    </div>

                    <h2 class="h5 fw-bold mb-4 border-bottom pb-3 mt-4"><i class="bi bi-archive text-maroon me-2" style="color:#8B0000;"></i>Daftar Informasi Setiap Saat</h2>
                    <div>
                        <?php 
                        $j = 1;
                        if(empty($info_setiap_saat)) echo '<p class="text-muted small">Belum ada dokumen setiap saat.</p>';
                        foreach($info_setiap_saat as $kategori => $dokumens): 
                            $collapseId = "collapseSetiapSaat" . $j;
                        ?>
                        <a data-bs-toggle="collapse" href="#<?= $collapseId ?>" role="button" aria-expanded="false" class="text-decoration-none d-block mb-2 p-2 bg-light rounded">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="h6 fw-bold text-dark mb-0"><?= htmlspecialchars($kategori) ?></h4>
                                <i class="bi bi-chevron-down text-dark"></i>
                            </div>
                        </a>
                        <div class="collapse mb-3" id="<?= $collapseId ?>">
                            <div class="p-3 border rounded bg-white">
                                <?php foreach($dokumens as $dok): ?>
                                <a href="uploads/dokumen/<?= htmlspecialchars($dok['file_dokumen'] ?? '') ?>" target="_blank" class="d-block text-decoration-none text-dark mb-2 border-bottom pb-2">
                                    <i class="bi bi-file-earmark-text text-primary me-2"></i><?= htmlspecialchars($dok['judul']) ?>
                                </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php $j++; endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <h2 class="h5 fw-bold mb-4"><i class="bi bi-megaphone-fill me-2" style="color:#8B0000;"></i>Informasi Serta Merta</h2>
            
            <?php 
            if($query_serta_merta && mysqli_num_rows($query_serta_merta) > 0):
                while($serta = mysqli_fetch_assoc($query_serta_merta)):
            ?>
            <div class="alert alert-danger border-0 shadow-sm" role="alert">
                <div class="d-flex gap-3">
                    <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                    <div>
                        <span class="badge bg-danger mb-1"><?= htmlspecialchars($serta['kategori_spesifik']) ?></span>
                        <h6 class="fw-bold mb-1"><?= htmlspecialchars($serta['judul']) ?></h6>
                        <p class="mb-0 small"><?= nl2br(htmlspecialchars($serta['deskripsi'] ?? '')) ?></p>
                    </div>
                </div>
            </div>
            <?php 
                endwhile;
            else:
                echo '<p class="text-muted small">Tidak ada informasi peringatan dini saat ini.</p>';
            endif; 
            ?>

            <h2 class="h5 fw-bold mb-4 mt-5"><i class="bi bi-newspaper me-2" style="color:#8B0000;"></i>Artikel Publikasi</h2>
            
            <?php 
            if($query_publik && mysqli_num_rows($query_publik) > 0) {
                while($artikel = mysqli_fetch_assoc($query_publik)):
                    $img_src = !empty($artikel['gambar']) ? "uploads/artikel/".$artikel['gambar'] : "https://placehold.co/150x150/8b0000/FFFFFF?text=Artikel";
            ?>
            <a href="detail-artikel.php?slug=<?= $artikel['slug'] ?>" class="artikel-card">
                <img src="<?= $img_src ?>" class="artikel-img" alt="Foto Artikel">
                <div class="artikel-body">
                    <span class="artikel-badge">Informasi Publik</span>
                    <div class="artikel-title"><?= htmlspecialchars($artikel['judul']) ?></div>
                    <div class="artikel-teaser"><?= htmlspecialchars($artikel['ringkasan']) ?></div>
                    <div class="artikel-meta">
                        <span><i class="bi bi-calendar3"></i> <?= date('d M Y', strtotime($artikel['tanggal'])) ?></span>
                        <span><i class="bi bi-person-fill"></i> Admin</span>
                    </div>
                </div>
            </a>
            <?php 
                endwhile; 
                // Tombol Spoiler untuk melihat semua artikel
                echo '<a href="#" class="btn-view-all mt-3">Lihat Semua Artikel Publikasi <i class="bi bi-arrow-right ms-1"></i></a>';
            } else { 
            ?>
            <div class="alert alert-light border text-center p-4 rounded-4">
                <i class="bi bi-inboxes text-muted fs-1 d-block mb-2"></i>
                <h6 class="text-secondary mb-0" style="font-size: 0.9rem;">Belum Ada Artikel Publikasi</h6>
            </div>
            <?php } ?>

        </div> 
    </div>
</div>

<?php include 'includes/footer.php'; ?>