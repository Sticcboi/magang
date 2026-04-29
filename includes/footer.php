<?php
/**
 * Footer Include - Used by all pages
 * Include this at the bottom of each PHP page before closing body
 */
?>

    <!-- Partner Links Bar -->
    <section class="partner-links-bar py-4" style="background-color: #750000;">
      <div class="container-fluid px-4 px-lg-5">
        <div class="d-flex flex-wrap justify-content-center align-items-center gap-4" style="column-gap: 6rem !important; row-gap: 2rem !important;">
          <a href="https://semarangkota.go.id" target="_blank" rel="noopener noreferrer" class="partner-logo-link" title="Portal Pemerintah Kota Semarang">
            <img src="img/partner-semarangkota.png" alt="semarangkota.go.id" class="partner-logo" />
          </a>
          <a href="https://smartcity.semarangkota.go.id" target="_blank" rel="noopener noreferrer" class="partner-logo-link" title="Semarang Smart City">
            <img src="img/partner-smartcity.png?v=1" alt="Semarang Smart City" class="partner-logo" />
          </a>
          <a href="https://data.semarangkota.go.id/" target="_blank" rel="noopener noreferrer" class="partner-logo-link" title="Satu Data Kota Semarang">
            <img src="img/partner-satudata.png?v=1" alt="Satu Data Kota Semarang" class="partner-logo" />
          </a>
          <a href="https://ppid.semarangkota.go.id" target="_blank" rel="noopener noreferrer" class="partner-logo-link" title=" PPID Kota Semarang">
            <img src="img/partner-ppid.png?v=1" alt="PPID Kota Semarang" class="partner-logo" />
          </a>
        </div>
      </div>
    </section>

    <!-- Main Footer -->
    <footer class="mt-auto text-white pt-5 pb-3" style="background-color: #1a1a1a; border-top: 5px solid #750000;">
      <div class="container-fluid px-4 px-lg-5">
        <div class="row g-4 mb-4">
          <div class="col-lg-5 col-md-6">
            <h4 class="fw-bold text-uppercase mb-3" style="color: #f5c2c2;">Kelurahan Kedungpane</h4>
            <p class="text-light opacity-75 small mb-3">
              Portal resmi pelayanan administrasi dan informasi masyarakat Kelurahan Kedungpane, Kecamatan Mijen, Kota Semarang.
            </p>
            <div class="d-flex flex-column gap-2 small text-light opacity-75">
              <span><i class="bi bi-geo-alt-fill me-2 text-danger"></i> Jl. Untung suropati, Kedungpane, Semarang, Kota Semarang, Jawa Tengah</span>
              <span><i class="bi bi-telephone-fill me-2 text-danger"></i> (024) 7711292</span>
              <span><i class="bi bi-envelope-fill me-2 text-danger"></i> kelurahankedungpane@yahoo.com</span>
              <span><i class="bi bi-whatsapp me-2 text-danger"></i> 082324229236 (Hotline)</span>
              <span><i class="bi bi-facebook me-2 text-danger"></i> Kelurahan Kedungpane</span>
              <span><i class="bi bi-twitter-x me-2 text-danger"></i> @Kel_Kedungpane</span>
            </div>
          </div>
          
          

          <div class="col-lg-4 col-md-12 ms-auto">
            <h6 class="fw-bold text-uppercase mb-3">Jam Pelayanan Kantor</h6>
            <div class="bg-dark p-3 rounded border border-secondary border-opacity-25 small opacity-75">
              <div class="d-flex justify-content-between border-bottom border-secondary pb-2 mb-2">
                <span>Senin - Kamis</span>
                <span class="fw-bold text-white">08:00 - 15:00 WIB</span>
              </div>
              <div class="d-flex justify-content-between border-bottom border-secondary pb-2 mb-2">
                <span>Jumat</span>
                <span class="fw-bold text-white">08:00 - 11:30 WIB</span>
              </div>
              <div class="d-flex justify-content-between text-danger fw-bold">
                <span>Sabtu, Minggu, Libur Nasional</span>
                <span>TUTUP</span>
              </div>
            </div>
          </div>
        </div>
        
        <hr class="border-secondary mb-3">
        <div class="text-center small opacity-50">
          <p class="mb-0">© 2026 Pemerintah Kota Semarang - Kelurahan Kedungpane. All Rights Reserved.</p>
          <p class="mb-0" style="font-size: 0.7rem;">Dikembangkan oleh Mahasiswa Magang Diskominfo</p>
        </div>
      </div>
    </footer>

    <style>
      /* Partner Links Bar */
      .partner-logo {
        height: 55px; /* Sedikit diperbesar */
        width: auto;
        max-width: 180px;
        object-fit: contain;
        transition: transform 0.3s ease;
      }

      .partner-logo-link:hover .partner-logo {
        transform: scale(1.1);
      }

      @media (max-width: 768px) {
        .partner-logo {
          height: 45px;
          max-width: 140px;
        }
      }

      @media (max-width: 400px) {
        .partner-logo {
          height: 35px;
          max-width: 110px;
        }
      }
    </style>

    <!-- Bootstrap Bundle JS (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Initialize Carousel -->
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        // Initialize the main carousel
        const heroCarousel = document.getElementById('heroCarousel');
        if (heroCarousel) {
          const carousel = new bootstrap.Carousel(heroCarousel, {
            interval: 3000,
            wrap: true,
            pause: 'hover'
          });
          
          console.log('Carousel initialized successfully');
        }
      });
    </script>
  </body>
</html>
