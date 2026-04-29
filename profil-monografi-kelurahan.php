<?php
$page_title = 'Monografi Kelurahan Kedungpane';
include 'includes/header.php';

// Pastikan koneksi database tersedia di sini. 
// Jika belum ter-include via header.php, tambahkan: require_once 'koneksi.php';

$query = mysqli_query($conn, "SELECT * FROM profil_kelurahan WHERE id=1");
$data = mysqli_fetch_assoc($query);

// Fungsi bantu untuk memformat angka (contoh: 12500 menjadi 12.500)
function formatAngka($angka) {
    if ($angka === null || $angka === '' || $angka === 0) {
        return '-';
    }
    return number_format((int)$angka, 0, ',', '.');
}
?>

<style>
    /* Konsistensi Style dengan halaman Pariwisata */
    .hero-banner {
        background-color: #7b0000;
        color: white;
        padding: 60px 20px;
        border-radius: 20px;
        text-align: center;
        position: relative;
        overflow: hidden;
        margin-bottom: 40px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .hero-title {
        font-size: 2.8rem;
        font-weight: 800;
        position: relative;
        z-index: 2;
        margin-bottom: 10px;
    }

    .hero-subtitle {
        font-size: 1.1rem;
        position: relative;
        z-index: 2;
        opacity: 0.9;
        max-width: 700px;
        margin: 0 auto;
    }

    .hero-ghost-text {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -40%);
        font-size: 5rem;
        font-weight: 900;
        color: rgba(0, 0, 0, 0.07);
        white-space: nowrap;
        z-index: 1;
        pointer-events: none;
        text-transform: uppercase;
    }

    .section-title {
        border-left: 5px solid #7b0000;
        padding-left: 15px;
        margin-bottom: 25px;
        font-weight: bold;
        color: #333;
    }

    .stat-card {
        border-top: 4px solid #7b0000;
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .map-container {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        height: 400px;
    }
</style>

<div class="container my-5">
    
    <div class="hero-banner">
        <h1 class="hero-title">Monografi Kelurahan</h1>
        <p class="hero-subtitle">
            Data demografi, geografis, dan statistik infrastruktur terkini Kelurahan Kedungpane sebagai wujud transparansi informasi publik.
        </p>
        <div class="hero-ghost-text">Monografi Kedungpane</div>
    </div>

    <div class="row g-3 mb-5">
        <div class="col-md-3 col-6">
            <div class="card h-100 stat-card border-0 shadow-sm text-center py-3">
                <div class="card-body">
                    <h6 class="text-muted small text-uppercase">Luas Wilayah</h6>
                    <h3 class="fw-bold" style="color: #7b0000;">± <?= htmlspecialchars($data['luas_wilayah'] ?? '-'); ?> <span class="fs-6">Ha</span></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card h-100 stat-card border-0 shadow-sm text-center py-3">
                <div class="card-body">
                    <h6 class="text-muted small text-uppercase">Jumlah RT / RW</h6>
                    <h3 class="fw-bold" style="color: #7b0000;"><?= htmlspecialchars($data['jml_rt'] ?? '-'); ?> / <?= htmlspecialchars($data['jml_rw'] ?? '-'); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card h-100 stat-card border-0 shadow-sm text-center py-3">
                <div class="card-body">
                    <h6 class="text-muted small text-uppercase">Total Penduduk</h6>
                    <h3 class="fw-bold" style="color: #7b0000;"><?= formatAngka($data['jml_penduduk'] ?? null); ?> <span class="fs-6">Jiwa</span></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card h-100 stat-card border-0 shadow-sm text-center py-3">
                <div class="card-body">
                    <h6 class="text-muted small text-uppercase">Kepala Keluarga</h6>
                    <h3 class="fw-bold" style="color: #7b0000;"><?= formatAngka($data['jml_kk'] ?? null); ?> <span class="fs-6">KK</span></h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7">
            
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h3 class="section-title">A. Batas Wilayah (Geografis)</h3>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tbody>
                                <tr><th width="30%">Utara</th><td><?= htmlspecialchars($data['batas_utara'] ?? '-'); ?></td></tr>
                                <tr><th>Selatan</th><td><?= htmlspecialchars($data['batas_selatan'] ?? '-'); ?></td></tr>
                                <tr><th>Timur</th><td><?= htmlspecialchars($data['batas_timur'] ?? '-'); ?></td></tr>
                                <tr><th>Barat</th><td><?= htmlspecialchars($data['batas_barat'] ?? '-'); ?></td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h3 class="section-title">B. Data Demografi</h3>
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <p class="mb-1 text-muted small">Laki-laki</p>
                            <h5 class="fw-bold"><?= formatAngka($data['penduduk_l'] ?? null); ?> Jiwa</h5>
                        </div>
                        <div class="col-sm-6 border-start">
                            <p class="mb-1 text-muted small">Perempuan</p>
                            <h5 class="fw-bold"><?= formatAngka($data['penduduk_p'] ?? null); ?> Jiwa</h5>
                        </div>
                    </div>
                    <p class="mb-1 text-muted small">Mata Pencaharian Mayoritas:</p>
                    <p class="fw-semibold"><?= nl2br(htmlspecialchars($data['mata_pencaharian'] ?? '-')); ?></p>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h3 class="section-title">C. Fasilitas & Sarana Umum</h3>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded text-center">
                                <h4 class="fw-bold" style="color: #7b0000;"><?= htmlspecialchars($data['fas_sd'] ?? '-'); ?></h4>
                                <span class="small">Sekolah Dasar</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded text-center">
                                <h4 class="fw-bold" style="color: #7b0000;"><?= htmlspecialchars($data['fas_ibadah'] ?? '-'); ?></h4>
                                <span class="small">Tempat Ibadah</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded text-center">
                                <h4 class="fw-bold" style="color: #7b0000;"><?= htmlspecialchars($data['fas_puskesmas'] ?? '-'); ?></h4>
                                <span class="small">Puskesmas Pembantu</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php if (empty($data['penduduk_l']) && empty($data['penduduk_p']) && empty($data['jml_penduduk']) && empty($data['jml_kk']) && empty($data['mata_pencaharian']) && empty($data['fas_sd']) && empty($data['fas_ibadah']) && empty($data['fas_puskesmas'])): ?>
            <div class="alert alert-info border-0 rounded-3 shadow-sm">
                <strong>Info:</strong> Data monografi belum lengkap. Silakan lengkapi informasi melalui menu <em>Admin Monografi</em> agar tampilan statistik dan fasilitas dapat muncul dengan benar.
            </div>
            <?php endif; ?>
        </div>

<div class="col-lg-5">
            <h3 class="section-title">D. Lokasi Wilayah</h3>
            <div class="map-container">
                <iframe 
                    src="https://maps.google.com/maps?q=Kantor+Kelurahan+Kedungpane,+Semarang&t=&z=15&ie=UTF8&iwloc=&output=embed" 
                    width="100%" 
                    height="100%" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy">
                </iframe>
            </div>
            <div class="mt-3 p-3 bg-white shadow-sm rounded">
                <p class="small text-muted mb-0">
                    <i class="bi bi-geo-alt-fill text-danger me-1"></i> 
                    <strong>Kantor Kelurahan Kedungpane:</strong> <br>
                    Jalan Panjangan / Jl. Untung Suropati, Kedungpane, Kec. Mijen, Kota Semarang, Jawa Tengah 50211.
                </p>
                <hr class="my-2">
                <p class="small text-muted mb-0">
                    <i class="bi bi-clock-fill text-primary me-1"></i>
                    <strong>Jam Operasional:</strong> Senin - Jumat (08.00–15.00)
                </p>
            </div>
        </div>
    </div>
</div>

<?php
include 'includes/footer.php';
?>