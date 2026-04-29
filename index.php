<?php
// Error reporting untuk development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Memanggil file koneksi database
include 'db_connect.php'; 

$page_title = 'Beranda Kelurahan Kedungpane';
include 'includes/header.php';

// --- MENGAMBIL DATA PROFIL KELURAHAN ---
$query_profil = mysqli_query($conn, "SELECT * FROM profil_kelurahan WHERE id = 1");
if (!$query_profil) {
    die("Error fetching profile: " . mysqli_error($conn));
}
$profil = mysqli_fetch_assoc($query_profil);

// --- MENGAMBIL DATA BANNER SLIDER ---
$query_banner = mysqli_query($conn, "SELECT * FROM banner_beranda WHERE is_active = 1 ORDER BY urutan ASC, id ASC");
if (!$query_banner) {
    die("Error fetching banners: " . mysqli_error($conn));
}
$banners = [];
while($row = mysqli_fetch_assoc($query_banner)) {
    $banners[] = $row;
}
// Jika tabel kosong, set banner bawaan
if(empty($banners)) {
    $banners[] = ['gambar' => 'img/banner.jpeg'];
}
?>

    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
      
      <div class="carousel-indicators">
        <?php foreach($banners as $index => $banner): ?>
          <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="<?= $index ?>" class="<?= $index == 0 ? 'active' : '' ?>" aria-current="<?= $index == 0 ? 'true' : 'false' ?>" aria-label="Slide <?= $index + 1 ?>"></button>
        <?php endforeach; ?>
      </div>

      <div class="carousel-inner">
        <?php foreach($banners as $index => $banner): ?>
          <div class="carousel-item <?= $index == 0 ? 'active' : '' ?>" data-bs-interval="3000">
            <img src="<?= htmlspecialchars($banner['gambar']) ?>" alt="Banner Kelurahan">
          </div>
        <?php endforeach; ?>
      </div>

      <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon bg-dark rounded-circle p-3 bg-opacity-50" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      
      <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon bg-dark rounded-circle p-3 bg-opacity-50" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>

    <div class="container-fluid px-4 px-lg-5 mt-4 mb-5 flex-grow-1">
      
      <div id="layanan" class="text-center">
        <div class="custom-section-header">Layanan Administrasi Terpadu</div>
        <p class="text-muted mb-4">Pilih layanan yang Anda butuhkan untuk melihat persyaratan dan prosedur pengajuan.</p>
      </div>
      <div class="custom-card-box mb-5">
        <div class="row text-center g-4 justify-content-center">
          <?php
          // Mengambil data untuk IKON BERANDA
          $query_layanan = mysqli_query($conn, "SELECT * FROM layanan_administrasi");
          if ($query_layanan) {
              while($layanan = mysqli_fetch_assoc($query_layanan)):
          ?>
          <div class="col-6 col-md-4 col-lg-2 service-item">
            <a href="<?= htmlspecialchars($layanan['link_url']) ?>" target="_blank" rel="noopener noreferrer" class="text-decoration-none text-dark">
              <img src="<?= htmlspecialchars($layanan['ikon_gambar']) ?>" class="img-fluid custom-img-layanan mb-3" alt="<?= htmlspecialchars($layanan['nama_layanan']) ?>" style="width: 110px; height: 110px; object-fit: contain;" />
              <h6 class="fw-bold" style="font-size: 14px;"><?= htmlspecialchars($layanan['nama_layanan']) ?></h6>
            </a>
          </div>
          <?php 
              endwhile; 
          }
          ?>
        </div>
      </div>

      <div class="text-center">
        <div class="custom-section-header">Sambutan Kepala Kelurahan</div>
      </div>
      <div class="custom-card-box mb-5">
        <div class="row align-items-center">
          <div class="col-md-3 text-center mb-4 mb-md-0">
            <img src="<?= htmlspecialchars($profil['foto_lurah'] ?? 'img/lurah.png') ?>" class="rounded-circle img-fluid shadow border border-3 border-danger" alt="Foto Lurah" style="width:150px; height:150px; object-fit:cover;">
            <h6 class="fw-bold mt-3 mb-0" style="color: #750000;"><?= htmlspecialchars($profil['nama_lurah'] ?? 'Nama Lurah') ?></h6>
            <p class="text-muted small"><?= htmlspecialchars($profil['jabatan_lurah'] ?? 'Kepala Kelurahan') ?></p>
          </div>
          <div class="col-md-9 text-justify-custom px-md-4">
            <?= $profil['teks_sambutan'] ?? '' ?>
          </div>
        </div>
      </div>

      <div class="text-center">
        <div class="custom-section-header">Sekilas Kedungpane</div>
      </div>
      <div class="row g-4 mb-5">
        <div class="col-md-3 col-6">
          <div class="stat-card h-100 flex-column justify-content-center text-center p-3">
            <h3 class="stat-value mb-1"><?= number_format($profil['jml_penduduk'] ?? 0, 0, ',', '.') ?></h3>
            <span class="text-muted fw-bold">Jumlah Penduduk</span>
          </div>
        </div>
        <div class="col-md-3 col-6">
          <div class="stat-card h-100 flex-column justify-content-center text-center p-3">
            <h3 class="stat-value mb-1"><?= htmlspecialchars($profil['jml_rw'] ?? 0) ?></h3>
            <span class="text-muted fw-bold">Rukun Warga (RW)</span>
          </div>
        </div>
        <div class="col-md-3 col-6">
          <div class="stat-card h-100 flex-column justify-content-center text-center p-3">
            <h3 class="stat-value mb-1"><?= htmlspecialchars($profil['jml_rt'] ?? 0) ?></h3>
            <span class="text-muted fw-bold">Rukun Tetangga (RT)</span>
          </div>
        </div>
        <div class="col-md-3 col-6">
          <div class="stat-card h-100 flex-column justify-content-center text-center p-3">
            <h3 class="stat-value mb-1"><?= htmlspecialchars($profil['luas_wilayah'] ?? 0) ?></h3>
            <span class="text-muted fw-bold">Luas Wilayah (Ha)</span>
          </div>
        </div>
      </div>

      <div class="row g-4 mb-5">
        
        <div class="col-lg-6">
          <div class="text-center text-lg-start">
            <div class="custom-section-header mt-0">Peta Wilayah</div>
          </div>
          <div class="custom-card-box mb-0 d-flex flex-column" style="padding: 25px;">
            <div class="ratio ratio-4x3 rounded overflow-hidden shadow-sm mb-3">
                <?= $profil['iframe_map'] ?? '' ?>
            </div>
            <ul class="list-group list-group-flush small mt-auto">
              <li class="list-group-item px-0">
                  <strong>Utara:</strong> <?= htmlspecialchars($profil['batas_utara'] ?? '') ?> | 
                  <strong>Selatan:</strong> <?= htmlspecialchars($profil['batas_selatan'] ?? '') ?>
              </li>
              <li class="list-group-item px-0">
                  <strong>Timur:</strong> <?= htmlspecialchars($profil['batas_timur'] ?? '') ?> | 
                  <strong>Barat:</strong> <?= htmlspecialchars($profil['batas_barat'] ?? '') ?>
              </li>
            </ul>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="text-center text-lg-start">
            <div class="custom-section-header mt-0">Kabar Kelurahan</div>
          </div>
          <div class="d-flex flex-column gap-3">
            
            <?php
            // Mencegah berita terjadwal muncul
            $query_berita = mysqli_query($conn, "SELECT * FROM berita WHERE is_published = 1 AND tanggal <= CURDATE() ORDER BY tanggal DESC, id DESC LIMIT 3");
            
            if($query_berita && mysqli_num_rows($query_berita) > 0) {
                while($row = mysqli_fetch_assoc($query_berita)) {
                    $gambar = !empty($row['gambar']) ? 'uploads/berita/'.$row['gambar'] : 'https://placehold.co/150x150/750000/FFFFFF?text=Berita';
                    $tanggal_indo = date('d F Y', strtotime($row['tanggal']));
                    $ringkasan = substr(strip_tags($row['konten'] ?? $row['isi'] ?? ''), 0, 120) . '...';
            ?>
                <div class="card shadow-sm border-0 d-flex flex-column flex-md-row overflow-hidden news-card position-relative">
                  <img src="<?= $gambar ?>" class="news-img-mobile" alt="<?= htmlspecialchars($row['judul']) ?>" onerror="this.src='https://placehold.co/150x150/750000/FFFFFF?text=Image'">
                  <div class="card-body p-3 w-100">
                    <p class="text-danger small mb-1 fw-bold"><i class="bi bi-calendar3"></i> <?= $tanggal_indo ?></p>
                    <h6 class="fw-bold mb-1 hover-danger">
                      <a href="detail-berita.php?slug=<?= $row['slug'] ?>" class="text-dark text-decoration-none d-block stretched-link"><?= htmlspecialchars($row['judul']) ?></a>
                    </h6>
                    <p class="small text-muted mb-0 text-clamp-2"><?= $ringkasan ?></p>
                  </div>
                </div>
            <?php 
                } 
            } else {
                echo '<p class="text-center text-muted">Belum ada berita terbaru.</p>';
            }
            ?>

            <a href="berita.php" class="btn btn-outline-danger fw-bold w-100 py-2" style="border-radius: 10px;">Lihat Semua Kabar Kelurahan →</a>

          </div>
        </div>

      </div> 
    </div> 

<script>
document.addEventListener("DOMContentLoaded", function() {
    var myCarouselElement = document.querySelector('#heroCarousel');
    if (!myCarouselElement) return;

    var carousel = new bootstrap.Carousel(myCarouselElement, {
        interval: 3000, 
        touch: true,
        ride: 'carousel'
    });

    // === DRAG & SWIPE SUPPORT ===
    var startX = 0;
    var isDragging = false;
    var threshold = 50; // Minimum px to trigger slide

    // Kursor visual feedback
    myCarouselElement.style.cursor = 'grab';

    // --- Mouse Events (Desktop) ---
    myCarouselElement.addEventListener('mousedown', function(e) {
        isDragging = true;
        startX = e.clientX;
        myCarouselElement.style.cursor = 'grabbing';
        carousel.pause(); // Pause auto-slide saat drag
    });

    document.addEventListener('mousemove', function(e) {
        if (!isDragging) return;
        e.preventDefault();
    });

    document.addEventListener('mouseup', function(e) {
        if (!isDragging) return;
        isDragging = false;
        myCarouselElement.style.cursor = 'grab';
        var diff = e.clientX - startX;
        if (Math.abs(diff) > threshold) {
            if (diff > 0) {
                carousel.prev();
            } else {
                carousel.next();
            }
        }
        carousel.cycle(); // Resume auto-slide
    });

    // --- Touch Events (Mobile) - enhanced ---
    myCarouselElement.addEventListener('touchstart', function(e) {
        startX = e.touches[0].clientX;
        carousel.pause();
    }, { passive: true });

    myCarouselElement.addEventListener('touchend', function(e) {
        var endX = e.changedTouches[0].clientX;
        var diff = endX - startX;
        if (Math.abs(diff) > threshold) {
            if (diff > 0) {
                carousel.prev();
            } else {
                carousel.next();
            }
        }
        carousel.cycle();
    });

    // Prevent image drag (ghost image)
    myCarouselElement.querySelectorAll('img').forEach(function(img) {
        img.setAttribute('draggable', 'false');
    });
});
</script>
    
<?php
include 'includes/footer.php';
?>