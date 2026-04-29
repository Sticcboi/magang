<?php
$page_title = 'Regulasi & Kebijakan | Kelurahan Kedungpane';
include 'includes/header.php';

// Pastikan file koneksi database sudah di-include di header, 
// atau include manual di sini jika belum: require_once 'koneksi.php';

// Fetch semua data regulasi dan kelompokkan berdasarkan kategori
$query = mysqli_query($conn, "SELECT * FROM regulasi ORDER BY id DESC");
$data_regulasi = [
    'dasar_hukum' => [],
    'perda_perwali' => [],
    'sop' => [],
    'maklumat' => [],
    'perencanaan' => []
];

while($row = mysqli_fetch_assoc($query)) {
    if(array_key_exists($row['kategori'], $data_regulasi)) {
        $data_regulasi[$row['kategori']][] = $row;
    }
}
?>

<style>
    .text-maroon { color: #8b0000; }
    
    .hero-section {
        background: linear-gradient(135deg, rgba(139,0,0,0.85), rgba(90,0,0,0.9)), url('https://placehold.co/1200x400/333/fff?text=Regulasi+Kedungpane') no-repeat center center;
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
    
    .info-card-link {
        display: block;
        text-decoration: none;
        color: inherit;
        border-left: 4px solid #8b0000;
        border-radius: 8px;
        padding: 16px 20px;
        margin-bottom: 10px;
        background: #fdfdfd;
        border-top: 1px solid #f0f0f0;
        border-right: 1px solid #f0f0f0;
        border-bottom: 1px solid #f0f0f0;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .info-card-link:hover { 
        box-shadow: 0 4px 12px rgba(139,0,0,0.05);
        background: #fffafa;
    }
    .info-card-link[aria-expanded="true"] {
        border-left-color: #dc3545;
        background: #fff;
    }
    .icon-collapse {
        transition: transform 0.3s ease;
        color: #8b0000;
    }
    .info-card-link[aria-expanded="true"] .icon-collapse {
        transform: rotate(180deg);
    }
    .collapse-content-box {
        background: #f8f9fa;
        border-left: 3px solid #ccc;
        border-radius: 0 8px 8px 0;
        padding: 15px;
        margin-bottom: 20px;
        margin-left: 10px;
    }
    .file-link {
        color: #444;
        text-decoration: none;
        display: flex;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px dashed #ddd;
        transition: 0.2s;
        font-size: 0.95rem;
    }
    .file-link:hover {
        color: #8b0000;
        padding-left: 5px;
    }
    .file-link:last-child {
        border-bottom: none;
    }
    
    .maklumat-box {
        background-color: #fffafb;
        border: 1px solid #ffcccc;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        margin-top: 10px;
    }
</style>

<div class="container my-4">
    <div class="hero-section text-center">
        <h1 class="fw-bold mb-3">Regulasi & Kebijakan</h1>
        <p class="fs-5 opacity-75 mb-0 mx-auto" style="max-width: 700px;">
            Pusat informasi dokumen resmi, dasar hukum, serta prosedur pelayanan untuk mewujudkan tata kelola Pemerintahan Kelurahan Kedungpane yang transparan dan akuntabel.
        </p>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card card-custom">
                <div class="card-body p-4 p-md-5">
                    <h2 class="h5 fw-bold mb-4 border-bottom pb-3"><i class="bi bi-bank text-maroon me-2"></i>Katalog Regulasi Kelurahan</h2>
                    
                    <div class="mb-4">
                        
                        <a data-bs-toggle="collapse" href="#collapseHukum" role="button" aria-expanded="false" class="info-card-link">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h4 class="h6 fw-bold text-dark mb-0">1. Dasar Hukum Pembentukan</h4>
                                <i class="bi bi-chevron-down fs-5 icon-collapse"></i>
                            </div>
                            <p class="mb-0 text-secondary" style="font-size: 0.85rem;">Aturan-aturan tertinggi yang mendasari berdirinya kelurahan dan pemerintahan daerah.</p>
                        </a>
                        <div class="collapse" id="collapseHukum">
                            <div class="collapse-content-box">
                                <?php if(empty($data_regulasi['dasar_hukum'])): ?>
                                    <span class="text-muted small fst-italic">Belum ada dokumen tersedia.</span>
                                <?php else: ?>
                                    <?php foreach($data_regulasi['dasar_hukum'] as $doc): ?>
                                        <a href="<?= htmlspecialchars($doc['file_url']) ?>" target="_blank" class="file-link">
                                            <i class="bi bi-file-earmark-pdf-fill text-danger me-3 fs-5"></i>
                                            <?= htmlspecialchars($doc['judul_dokumen']) ?>
                                        </a>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>

                        <a data-bs-toggle="collapse" href="#collapsePerda" role="button" aria-expanded="false" class="info-card-link">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h4 class="h6 fw-bold text-dark mb-0">2. Peraturan Daerah (Perda) & Peraturan Wali Kota (Perwali)</h4>
                                <i class="bi bi-chevron-down fs-5 icon-collapse"></i>
                            </div>
                            <p class="mb-0 text-secondary" style="font-size: 0.85rem;">Rujukan kebijakan terkait lingkungan, struktur SOTK, dan ketertiban umum.</p>
                        </a>
                        <div class="collapse" id="collapsePerda">
                            <div class="collapse-content-box">
                                <?php if(empty($data_regulasi['perda_perwali'])): ?>
                                    <span class="text-muted small fst-italic">Belum ada dokumen tersedia.</span>
                                <?php else: ?>
                                    <?php foreach($data_regulasi['perda_perwali'] as $doc): ?>
                                        <a href="<?= htmlspecialchars($doc['file_url']) ?>" target="_blank" class="file-link">
                                            <i class="bi bi-file-earmark-pdf-fill text-danger me-3 fs-5"></i>
                                            <div><strong><?= htmlspecialchars($doc['judul_dokumen']) ?></strong></div>
                                        </a>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>

                        <a data-bs-toggle="collapse" href="#collapseSOP" role="button" aria-expanded="false" class="info-card-link">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h4 class="h6 fw-bold text-dark mb-0">3. Prosedur Pelayanan Publik (SOP)</h4>
                                <i class="bi bi-chevron-down fs-5 icon-collapse"></i>
                            </div>
                            <p class="mb-0 text-secondary" style="font-size: 0.85rem;">Alur birokrasi, syarat pengurusan berkas, dan layanan pengaduan secara transparan.</p>
                        </a>
                        <div class="collapse" id="collapseSOP">
                            <div class="collapse-content-box">
                                <?php if(empty($data_regulasi['sop'])): ?>
                                    <span class="text-muted small fst-italic">Belum ada dokumen tersedia.</span>
                                <?php else: ?>
                                    <?php foreach($data_regulasi['sop'] as $doc): ?>
                                        <a href="<?= htmlspecialchars($doc['file_url']) ?>" target="_blank" class="file-link">
                                            <i class="bi bi-file-earmark-pdf-fill text-danger me-3 fs-5"></i>
                                            <div><strong><?= htmlspecialchars($doc['judul_dokumen']) ?></strong></div>
                                        </a>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>

                        <a data-bs-toggle="collapse" href="#collapseMaklumat" role="button" aria-expanded="false" class="info-card-link">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h4 class="h6 fw-bold text-dark mb-0">4. Maklumat Pelayanan</h4>
                                <i class="bi bi-chevron-down fs-5 icon-collapse"></i>
                            </div>
                            <p class="mb-0 text-secondary" style="font-size: 0.85rem;">Pernyataan resmi komitmen kelurahan dalam memberikan pelayanan sesuai standar.</p>
                        </a>
                        <div class="collapse" id="collapseMaklumat">
                            <div class="collapse-content-box">
                                <div class="maklumat-box">
                                    <i class="bi bi-shield-check text-maroon fs-1 mb-2 d-block"></i>
                                    <h5 class="fw-bold">Maklumat Pelayanan Kelurahan</h5>
                                    <p class="text-secondary fst-italic mb-3">"Dengan ini, kami menyatakan sanggup menyelenggarakan pelayanan sesuai standar pelayanan yang telah ditetapkan dan apabila tidak menepati janji ini, kami siap menerima sanksi sesuai peraturan perundang-undangan yang berlaku."</p>
                                    
                                    <?php if(!empty($data_regulasi['maklumat'])): ?>
                                        <?php 
                                        // Mengambil 1 dokumen terbaru untuk tombol unduh maklumat
                                        $docMaklumat = $data_regulasi['maklumat'][0]; 
                                        ?>
                                        <a href="<?= htmlspecialchars($docMaklumat['file_url']) ?>" target="_blank" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-download me-1"></i> <?= htmlspecialchars($docMaklumat['judul_dokumen']) ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <a data-bs-toggle="collapse" href="#collapsePerencanaan" role="button" aria-expanded="false" class="info-card-link">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h4 class="h6 fw-bold text-dark mb-0">5. Dokumen Perencanaan (Arsip Publik)</h4>
                                <i class="bi bi-chevron-down fs-5 icon-collapse"></i>
                            </div>
                            <p class="mb-0 text-secondary" style="font-size: 0.85rem;">Arsip hasil perencanaan pembangunan dan transparansi anggaran kelurahan.</p>
                        </a>
                        <div class="collapse" id="collapsePerencanaan">
                            <div class="collapse-content-box">
                                <?php if(empty($data_regulasi['perencanaan'])): ?>
                                    <span class="text-muted small fst-italic">Belum ada dokumen tersedia.</span>
                                <?php else: ?>
                                    <?php foreach($data_regulasi['perencanaan'] as $doc): ?>
                                        <a href="<?= htmlspecialchars($doc['file_url']) ?>" target="_blank" class="file-link">
                                            <i class="bi bi-file-earmark-pdf-fill text-danger me-3 fs-5"></i>
                                            <div><strong><?= htmlspecialchars($doc['judul_dokumen']) ?></strong></div>
                                        </a>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>