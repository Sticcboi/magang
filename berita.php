<?php
// 1. PANGGIL KONEKSI DATABASE TERLEBIH DAHULU
try {
    require_once 'db_connect.php'; 
    if (!$conn) {
        throw new Exception("Variabel koneksi database tidak ditemukan.");
    }
} catch (Exception $e) {
    die("<div style='margin-top:100px; padding:20px; text-align:center; color:red;'><b>Error Koneksi DB:</b> " . $e->getMessage() . "</div>");
}

// 2. SETELAH DATABASE TERHUBUNG, BARU PANGGIL HEADER & NAVBAR
$page_title = 'Berita & Informasi Terkini';
include 'includes/header.php';

$db_error = '';
$query_headline = false;
$query_list = false;

// =========================================================================
// LOGIKA FILTER & PENCARIAN
// =========================================================================
$kategori_filter = isset($_GET['kategori']) ? mysqli_real_escape_string($conn, $_GET['kategori']) : '';
$keyword_filter = isset($_GET['keyword']) ? mysqli_real_escape_string($conn, $_GET['keyword']) : '';

// Cek apakah user sedang melakukan pencarian atau filter
$is_filtering = (!empty($kategori_filter) && $kategori_filter != 'semua') || !empty($keyword_filter);

// Query dasar: Harus dipublikasikan dan tanggal sudah lewat/hari ini
$where_clause = "b.is_published = 1 AND b.tanggal <= CURDATE()";

// LOGIKA PENTING: Jika TIDAK sedang memfilter, sembunyikan berita PINNED dari list bawah 
// (karena sudah muncul di Slider/Carousel atas agar tidak dobel).
// Jika SEDANG memfilter, biarkan semua muncul agar tidak ada hasil 'kosong' yang membingungkan.
if (!$is_filtering) {
    $where_clause .= " AND b.is_pinned = 0";
}

// Jika ada filter kategori
if (!empty($kategori_filter) && $kategori_filter != 'semua') {
    $where_clause .= " AND b.kategori = '$kategori_filter'";
}

// Jika ada pencarian kata kunci
if (!empty($keyword_filter)) {
    $where_clause .= " AND (b.judul LIKE '%$keyword_filter%' OR b.ringkasan LIKE '%$keyword_filter%')";
}
// =========================================================================

try {
    // 1. QUERY SOROTAN (HANYA MUNCUL JIKA TIDAK SEDANG MENCARI/FILTER)
    if (!$is_filtering) {
        $query_headline = mysqli_query($conn, "SELECT b.*, u.nama as penulis FROM berita b LEFT JOIN users u ON b.penulis_id = u.id WHERE b.is_published = 1 AND b.tanggal <= CURDATE() AND b.is_pinned = 1 ORDER BY b.tanggal DESC");
    }

    // 2. QUERY LIST BIASA
    $query_list = mysqli_query($conn, "SELECT b.*, u.nama as penulis FROM berita b LEFT JOIN users u ON b.penulis_id = u.id WHERE $where_clause ORDER BY b.tanggal DESC");
} catch (Exception $e) {
    $db_error = $e->getMessage();
}
?>
    <style>
      .hero-card { position: relative; border-radius: 15px; overflow: hidden; background-color: #eee; min-height: 450px; cursor: pointer; }
      .hero-card img { width: 100%; height: 450px; object-fit: cover; }
      .hero-card-overlay { position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(to top, rgba(117, 0, 0, 0.95) 10%, transparent); padding: 40px 30px 20px; color: white; }
      
      .news-card-hover { position: relative; transition: transform 0.3s ease, box-shadow 0.3s ease; border: 1px solid #eee; box-shadow: 0 4px 15px rgba(0,0,0,0.04); border-radius: 12px; cursor: pointer; }
      .news-card-hover:hover { transform: translateY(-8px); box-shadow: 0 12px 25px rgba(117, 0, 0, 0.15); }
      .news-card-hover .card-img-top { height: 220px; object-fit: cover; border-top-left-radius: 12px; border-top-right-radius: 12px; background-color: #f8f9fa; }
      
      .category-badge { position: absolute; top: 15px; left: 15px; background-color: #750000; color: white; padding: 6px 15px; border-radius: 20px; font-size: 0.8rem; font-weight: bold; box-shadow: 0 2px 5px rgba(0,0,0,0.2); z-index: 2; }
      
      .filter-pill { border-radius: 50px; padding: 8px 20px; color: #555; border: 1px solid #ddd; transition: all 0.3s ease; font-weight: 500; }
      .filter-pill:hover, .filter-pill.active { background-color: #750000; color: white; border-color: #750000; }
      .text-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    </style>

    <div class="container-fluid px-4 px-lg-5 mt-4 mb-5 flex-grow-1">
      <div class="page-intro text-center mb-5 mt-4">
        <h2 class="fw-bold" style="color: #750000;">Kabar Kelurahan Kedungpane</h2>
        <p class="text-muted mx-auto mt-2" style="max-width: 700px">
          Ikuti perkembangan terbaru dan informasi penting seputar Kelurahan Kedungpane.
        </p>
      </div>

      <?php if (!empty($db_error)): ?>
          <div class="alert alert-danger shadow-sm border-0 rounded-3 mb-5">
              <h5 class="fw-bold"><i class="fa-solid fa-triangle-exclamation"></i> Kesalahan Sistem</h5>
              <p class="mb-0"><?= htmlspecialchars($db_error) ?></p>
          </div>
      <?php endif; ?>

      <?php if(!$is_filtering && $query_headline && mysqli_num_rows($query_headline) > 0): ?>
      <div id="headlineCarousel" class="carousel slide mb-5 shadow-sm rounded-4 overflow-hidden" data-bs-ride="carousel">
        <div class="carousel-inner">
          <?php 
          $i = 0;
          while($headline = mysqli_fetch_assoc($query_headline)): 
              $img_headline = !empty($headline['gambar']) ? "uploads/berita/".$headline['gambar'] : "https://placehold.co/1200x500/750000/FFFFFF?text=Sorotan+Utama";
          ?>
          <div class="carousel-item <?= $i == 0 ? 'active' : '' ?>" data-bs-interval="5000">
            <div class="hero-card">
              <img src="<?= $img_headline ?>" alt="<?= htmlspecialchars($headline['judul']) ?>" onerror="this.src='https://placehold.co/1200x500/750000/FFFFFF?text=Gambar+Tidak+Tersedia'"/>
              <div class="hero-card-overlay">
                <span class="badge bg-warning text-dark mb-3 px-3 py-2 rounded-pill shadow-sm"><i class="fa-solid fa-star text-danger me-1"></i> Sorotan Utama</span>
                <h2 class="fw-bold mb-2"><?= htmlspecialchars($headline['judul']) ?></h2>
                <div class="mt-3 small">
                  <i class="fa-regular fa-calendar me-1"></i> <?= date('d M Y', strtotime($headline['tanggal'])); ?>
                  <a href="detail-berita.php?slug=<?= $headline['slug'] ?>" class="btn btn-sm btn-outline-light ms-3 rounded-pill px-3 stretched-link">Baca Selengkapnya</a>
                </div>
              </div>
            </div>
          </div>
          <?php $i++; endwhile; ?>
        </div>
      </div>
      <?php endif; ?>

      <div class="row align-items-center mb-4">
        <div class="col-lg-8 mb-3 mb-lg-0">
          <div class="d-flex flex-wrap gap-2">
            <?php
            // List kategori yang SAMA dengan ENUM di SQL
            $kategori_list = [
                'semua' => 'Semua Kabar',
                'umum' => 'Umum',
                'kesehatan' => 'Kesehatan',
                'pembangunan' => 'Pembangunan',
                'keamanan' => 'Keamanan',
                'pendidikan' => 'Pendidikan',
                'ekonomi' => 'Ekonomi'
            ];

            $kategori_aktif = empty($kategori_filter) ? 'semua' : strtolower($kategori_filter);
            $keyword_param = !empty($keyword_filter) ? "&keyword=".urlencode($keyword_filter) : "";

            foreach ($kategori_list as $kat_val => $kat_label) {
                $active_class = ($kategori_aktif == $kat_val) ? 'active' : '';
                $href = "?kategori=$kat_val" . $keyword_param;
                echo "<a href=\"$href\" class=\"text-decoration-none filter-pill $active_class\">$kat_label</a>";
            }
            ?>
          </div>
        </div>
        <div class="col-lg-4">
          <form class="d-flex" method="GET" action="berita.php">
            <?php if(!empty($kategori_filter) && $kategori_filter != 'semua'): ?>
                <input type="hidden" name="kategori" value="<?= htmlspecialchars($kategori_filter) ?>">
            <?php endif; ?>
            <input class="form-control rounded-pill me-2 shadow-sm" type="search" name="keyword" placeholder="Cari berita..." value="<?= htmlspecialchars($keyword_filter) ?>">
            <button class="btn rounded-pill px-4 shadow-sm text-white" type="submit" style="background-color: #750000;">Cari</button>
          </form>
        </div>
      </div>

      <div class="custom-card-box p-4 p-md-5 mb-5 bg-white rounded-4 shadow-sm border">
        <div class="row g-4">
          <?php 
          if($query_list && mysqli_num_rows($query_list) > 0){
              while($row = mysqli_fetch_assoc($query_list)): 
                  $img_list = !empty($row['gambar']) ? "uploads/berita/".$row['gambar'] : "https://placehold.co/600x400/750000/FFFFFF?text=Berita";
          ?>
          <div class="col-lg-4 col-md-6">
            <div class="card h-100 news-card-hover border-0">
              <div class="position-relative">
                <img src="<?= $img_list ?>" class="card-img-top" alt="<?= htmlspecialchars($row['judul']) ?>"/>
                <span class="category-badge text-uppercase"><?= htmlspecialchars($row['kategori']) ?></span>
              </div>
              <div class="card-body d-flex flex-column p-4">
                <div class="small mb-2 fw-bold text-danger">
                    <i class="fa-regular fa-calendar-days me-1"></i> <?= date('d F Y', strtotime($row['tanggal'])) ?>
                </div>
                <h5 class="card-title fw-bold text-dark"><?= htmlspecialchars($row['judul']) ?></h5>
                <p class="card-text text-muted small text-clamp-2 mb-4"><?= htmlspecialchars($row['ringkasan']); ?></p>
                <a href="detail-berita.php?slug=<?= $row['slug'] ?>" class="btn mt-auto align-self-start rounded-pill px-4 py-2 stretched-link" style="border: 1.5px solid #750000; color: #750000;">Baca Selengkapnya</a>
              </div>
            </div>
          </div>
          <?php endwhile; } else { ?>
              <div class="col-12 text-center py-5">
                  <h5 class="text-muted fw-bold">Belum ada berita di kategori ini.</h5>
                  <p class="text-muted small">Silakan pilih kategori lain atau hapus filter.</p>
                  <a href="berita.php" class="btn btn-sm btn-outline-secondary rounded-pill">Lihat Semua Berita</a>
              </div>
          <?php } ?>
        </div>
      </div>
    </div>
<?php include 'includes/footer.php'; ?>