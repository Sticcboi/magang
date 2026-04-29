<?php
require_once __DIR__ . '/auth.php'; 

// Ambil data statistik dinamis
$tot_berita = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM berita"))['t'] ?? 0;
$tot_umkm = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM umkm"))['t'] ?? 0;
$tot_wisata = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM destinasi_wisata"))['t'] ?? 0;
$tot_layanan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM layanan_administrasi"))['t'] ?? 0;

// Ambil cuplikan/bocoran teks berita
$query_berita = mysqli_query($conn, "SELECT b.id, b.judul, b.ringkasan, b.kategori, b.tanggal, b.is_published, u.nama as penulis 
                                    FROM berita b 
                                    LEFT JOIN users u ON b.penulis_id = u.id 
                                    ORDER BY b.tanggal DESC LIMIT 5");

$page_title = 'Dashboard Utama';
$current_page = 'admin_dashboard';
include 'admin_header.php';
?>

<style>
    /* Custom Styling untuk Dashboard */
    .welcome-banner {
        background: linear-gradient(135deg, #8B0000 0%, #cc0000 100%);
        color: white;
        padding: 30px;
        border-radius: 15px;
        margin-bottom: 30px;
        box-shadow: 0 4px 15px rgba(139, 0, 0, 0.2);
    }
    .stat-card-pro {
        border: none;
        border-radius: 15px;
        transition: transform 0.3s ease;
        overflow: hidden;
    }
    .stat-card-pro:hover {
        transform: translateY(-5px);
    }
    .quick-action-btn {
        transition: all 0.3s;
        border-radius: 12px;
        text-align: left;
        padding: 15px;
        display: flex;
        align-items: center;
        border: 1px solid #eee;
        background: #fff;
    }
    .quick-action-btn:hover {
        background: #f8f9fa;
        border-color: #8B0000;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    .icon-box {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        font-size: 20px;
    }
    .news-snippet {
        font-size: 0.8rem;
        color: #6c757d;
        display: -webkit-box;
        -webkit-line-clamp: 2; 
        -webkit-box-orient: vertical;
        overflow: hidden;
        margin-top: 4px;
        margin-bottom: 6px;
    }
</style>

<div class="welcome-banner d-flex justify-content-between align-items-center">
    <div>
        <h1 class="fw-bold mb-1">Halo, <?= $_SESSION['nama'] ?? 'Administrator'; ?>! 👋</h1>
        <p class="mb-0 opacity-75">Hari ini adalah <?= date('l, d F Y'); ?>. Pantau kondisi Kelurahan Kedungpane dalam satu layar.</p>
    </div>
    <div class="d-none d-md-block text-end">
        <a href="../index.php" target="_blank" class="btn btn-light btn-sm rounded-pill px-3 fw-bold shadow-sm">
            <i class="fa-solid fa-earth-asia me-1"></i> Lihat Web Utama
        </a>
    </div>
</div>

<div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="card stat-card-pro shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="icon-box bg-primary bg-opacity-10 text-primary">
                    <i class="fa-solid fa-newspaper"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0"><?= $tot_berita; ?></h3>
                    <p class="text-muted small mb-0">Total Berita</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card-pro shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="icon-box bg-success bg-opacity-10 text-success">
                    <i class="fa-solid fa-store"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0"><?= $tot_umkm; ?></h3>
                    <p class="text-muted small mb-0">Unit UMKM</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card-pro shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="icon-box bg-warning bg-opacity-10 text-warning">
                    <i class="fa-solid fa-map-location-dot"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0"><?= $tot_wisata; ?></h3>
                    <p class="text-muted small mb-0">Destinasi Wisata</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card-pro shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="icon-box bg-danger bg-opacity-10 text-danger">
                    <i class="fa-solid fa-list-check"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0"><?= $tot_layanan; ?></h3>
                    <p class="text-muted small mb-0">Jenis Layanan</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Aktivitas Berita Terkini</h5>
                <a href="admin_berita.php" class="btn btn-sm btn-danger rounded-pill px-3 fw-bold shadow-sm">
                    <i class="fa-solid fa-list-check me-1"></i> Kelola Berita
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-3 border-0" style="width: 70%;">Berita</th>
                            <th class="border-0">Kategori</th>
                            <th class="border-0">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(mysqli_num_rows($query_berita) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($query_berita)): ?>
                            <tr>
                                <td class="ps-3 py-3">
                                    <div class="fw-bold text-dark text-truncate" style="max-width: 350px;">
                                        <?= htmlspecialchars($row['judul']); ?>
                                    </div>
                                    <?php if(!empty($row['ringkasan'])): ?>
                                        <div class="news-snippet" title="<?= htmlspecialchars($row['ringkasan']); ?>">
                                            "<?= htmlspecialchars($row['ringkasan']); ?>"
                                        </div>
                                    <?php else: ?>
                                        <div class="news-snippet fst-italic">Tidak ada ringkasan.</div>
                                    <?php endif; ?>
                                    
                                    <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">
                                        <i class="fa-regular fa-calendar me-1"></i> <?= date('d M Y', strtotime($row['tanggal'])); ?> &nbsp;•&nbsp; 
                                        <i class="fa-solid fa-pen-nib me-1"></i> <?= htmlspecialchars($row['penulis'] ?? 'Admin'); ?>
                                    </small>
                                </td>
                                <td>
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary text-uppercase" style="font-size: 10px;">
                                        <?= htmlspecialchars($row['kategori']); ?>
                                    </span>
                                </td>
                                <td>
                                    <?= ($row['is_published'] == 1) 
                                        ? '<span class="badge rounded-pill bg-success" style="font-size: 10px;">Published</span>' 
                                        : '<span class="badge rounded-pill bg-warning text-dark" style="font-size: 10px;">Draft</span>'; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="3" class="text-center py-4 text-muted">Belum ada data berita.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <h5 class="fw-bold mb-3">Aksi Cepat</h5>
        <div class="d-grid gap-3">
            <a href="admin_berita.php" class="text-decoration-none quick-action-btn text-dark">
                <div class="icon-box bg-primary text-white shadow-sm"><i class="fa-solid fa-plus"></i></div>
                <div>
                    <div class="fw-bold">Buat Berita Baru</div>
                    <small class="text-muted">Publikasikan kabar terbaru kelurahan</small>
                </div>
            </a>
            <a href="admin_profil.php" class="text-decoration-none quick-action-btn text-dark">
                <div class="icon-box bg-maroon text-white shadow-sm" style="background-color: #8B0000 !important;"><i class="fa-solid fa-user-tie"></i></div>
                <div>
                    <div class="fw-bold">Update Profil Lurah</div>
                    <small class="text-muted">Ubah foto atau teks sambutan</small>
                </div>
            </a>
            <a href="admin_struktur.php" class="text-decoration-none quick-action-btn text-dark">
                <div class="icon-box bg-info text-white shadow-sm"><i class="fa-solid fa-sitemap"></i></div>
                <div>
                    <div class="fw-bold">Edit Struktur Organisasi</div>
                    <small class="text-muted">Perbarui susunan perangkat kelurahan</small>
                </div>
            </a>
            <a href="admin_layanan_nav.php" class="text-decoration-none quick-action-btn text-dark">
                <div class="icon-box bg-success text-white shadow-sm"><i class="fa-solid fa-link"></i></div>
                <div>
                    <div class="fw-bold">Kelola Link Layanan</div>
                    <small class="text-muted">Atur URL Sidnok / PBB / KK</small>
                </div>
            </a>
            <a href="admin_users.php" class="text-decoration-none quick-action-btn text-dark">
                <div class="icon-box bg-dark text-white shadow-sm"><i class="fa-solid fa-gears"></i></div>
                <div>
                    <div class="fw-bold">Pengaturan Akun</div>
                    <small class="text-muted">Ubah password atau profil admin</small>
                </div>
            </a>
        </div>
    </div>
</div>

<?php include 'admin_footer.php'; ?>