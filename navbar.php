<?php
/**
 * Navbar Component - Revised for Stability
 * Tetap menggunakan style asli Anda namun lebih aman dari error blank.
 */
?>

<style>
  /* Style asli Anda tetap dipertahankan */
  html,
  body {
    background-color: #f4f6f9;
    min-height: 100vh;
    margin: 0;
  }

  #navbar-container {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1050; /* Ditingkatkan agar selalu di atas elemen lain */
  }

  body {
    padding-top: 0;
  }

  #navbar-container .navbar {
    border-bottom: 10px solid #750000;
    width: 100%;
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  #navbar-container .btn-menu {
    background: #fff;
    border: 1px solid #ddd;
    color: #800000;
  }

  #navbar-container .offcanvas-end {
    width: 260px;
    transition: transform 0.4s ease-in-out;
  }

  #navbar-container .nav-link {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 15px;
    border-radius: 10px;
    transition: all 0.3s;
    color: #333;
  }

  #navbar-container .nav-link:hover {
    background-color: #f1f1f1;
    transform: translateX(5px);
    color: #800000;
  }

  #navbar-container .nav-link.active {
    background-color: #750000;
    color: white !important;
  }

  #navbar-container .submenu {
    padding-left: 30px;
    font-size: 14px;
  }

  #navbar-container .submenu-item {
    display: block !important;
    margin: 6px 0 !important;
    padding: 8px 10px !important;
    border-radius: 8px !important;
    color: #333 !important;
    text-decoration: none !important;
    transition: all 0.3s ease;
    font-size: 0.95rem;
  }

  #navbar-container .submenu-item:hover {
    background-color: #f8d7da !important;
    transform: translateX(5px) !important;
    color: #800000 !important;
  }

  #navbar-container .submenu-item.active {
    background-color: #f8d7da !important;
    color: #800000 !important;
  }
</style>

<div id="navbar-container">
  <nav class="navbar navbar-dark bg-danger shadow-sm" style="background-color: #750000 !important;">
    <div class="container-fluid d-flex align-items-center">
      <a class="navbar-brand d-flex align-items-center" href="index.php">
        <img
          src="./img/logo.png"
          width="189"
          height="40"
          class="me-2 ms-3 my-2"
          alt="Logo Kedungpane"
        />
      </a>
      <div class="d-flex align-items-center ms-auto">
        <button
          class="btn btn-menu shadow-none"
          data-bs-toggle="offcanvas"
          data-bs-target="#sidebar"
        >
          <i class="bi bi-list"></i>
        </button>
      </div>
    </div>
  </nav>

  <div class="offcanvas offcanvas-end" tabindex="-1" id="sidebar" data-bs-scroll="false" data-bs-backdrop="true" data-bs-keyboard="true">
    <div class="offcanvas-header border-bottom">
      <h5 class="offcanvas-title fw-bold">Menu Navigasi</h5>
      <button type="button" class="btn-close shadow-none" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="index.php"><i class="bi bi-house"></i> Beranda</a>
        </li>

        <li class="nav-item">
          <a class="nav-link d-flex justify-content-between" data-bs-toggle="collapse" data-bs-target="#profilMenu" href="#profilMenu" role="button" aria-expanded="false" aria-controls="profilMenu">
            <span><i class="bi bi-person"></i> Profil Kelurahan</span>
            <i class="bi bi-chevron-down"></i>
          </a>
          <div class="collapse submenu" id="profilMenu">
            <a class="submenu-item" href="profil-struktur-organisasi.php">Struktur Organisasi</a>
            <a class="submenu-item" href="profil-sumber-daya-manusia.php">Sumber Daya Manusia</a>
            <a class="submenu-item" href="profil-regulasi.php">Regulasi</a>
            <a class="submenu-item" href="profil-monografi-kelurahan.php">Monografi Kelurahan</a>
          </div>
        </li>

        <li class="nav-item">
          <a class="nav-link d-flex justify-content-between" data-bs-toggle="collapse" data-bs-target="#layananMenu" href="#layananMenu" role="button" aria-expanded="false" aria-controls="layananMenu">
            <span><i class="bi bi-gear"></i> Layanan</span>
            <i class="bi bi-chevron-down"></i>
          </a>
          <div class="collapse submenu" id="layananMenu">
            <?php
            // PENGECEKAN AMAN: Jika database belum dipanggil, halaman tidak akan crash
            if (isset($conn)) {
                $query_nav = mysqli_query($conn, "SELECT * FROM navbar_layanan ORDER BY urutan ASC");
                if ($query_nav && mysqli_num_rows($query_nav) > 0) {
                    while ($nav = mysqli_fetch_assoc($query_nav)) {
                        echo '<a class="submenu-item" href="' . htmlspecialchars($nav['url']) . '" target="_blank">';
                        echo htmlspecialchars($nav['nama_layanan']) . ' <i class="bi bi-box-arrow-up-right small"></i>';
                        echo '</a>';
                    }
                } else {
                    echo '<span class="submenu-item text-muted small">Belum ada layanan</span>';
                }
            } else {
                echo '<span class="submenu-item text-muted small italic">Koneksi Database tidak ditemukan</span>';
            }
            ?>
          </div>
        </li>

        <li class="nav-item">
          <a class="nav-link d-flex justify-content-between" data-bs-toggle="collapse" data-bs-target="#infoMenu" href="#infoMenu" role="button" aria-expanded="false" aria-controls="infoMenu">
            <span><i class="bi bi-info-circle"></i> Informasi Publik</span>
            <i class="bi bi-chevron-down"></i>
          </a>
          <div class="collapse submenu" id="infoMenu">
            <a class="submenu-item" href="informasi-publik.php">Daftar Informasi Publik</a>
          </div>
        </li>

        <li class="nav-item">
          <a class="nav-link d-flex justify-content-between" data-bs-toggle="collapse" data-bs-target="#lembagaMenu" href="#lembagaMenu" role="button" aria-expanded="false" aria-controls="lembagaMenu">
            <span><i class="bi bi-building"></i> Kelembagaan</span>
            <i class="bi bi-chevron-down"></i>
          </a>
          <div class="collapse submenu" id="lembagaMenu">
            <a class="submenu-item" href="kelembagaan-lpmk.php">LPMK (Lembaga Pemberdayaan Masyarakat Kelurahan)</a>
            <a class="submenu-item" href="kelembagaan-umkm.php">UMKM (Usaha Mikro Kecil dan Menengah)</a>
            <a class="submenu-item" href="kelembagaan-bkm.php">BKM (Badan Keswadayaan Masyarakat)</a>
            <a class="submenu-item" href="kelembagaan-pkk.php">PKK (Pemberdayaan Kesejahteraan Keluarga)</a>
          </div>
        </li>

        <li class="nav-item">
          <a class="nav-link d-flex justify-content-between" data-bs-toggle="collapse" data-bs-target="#pemberdayaanMenu" href="#pemberdayaanMenu" role="button" aria-expanded="false" aria-controls="pemberdayaanMenu">
            <span><i class="bi bi-people"></i> Pemberdayaan</span>
            <i class="bi bi-chevron-down"></i>
          </a>
          <div class="collapse submenu" id="pemberdayaanMenu">
            <a class="submenu-item" href="pemberdayaan-kamtibmas.php">Bidang Kamtibmas (Keamanan dan Ketertiban Masyarakat)</a>
            <a class="submenu-item" href="pemberdayaan-kesehatan.php">Bidang Kesehatan</a>
            <a class="submenu-item" href="pemberdayaan-pariwisata.php">Bidang Pariwisata</a>
            <a class="submenu-item" href="pemberdayaan-pendidikan.php">Bidang Pendidikan</a>
          </div>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="berita.php"><i class="bi bi-newspaper"></i> Berita</a>
        </li>
      </ul>
    </div>
  </div>
</div>

<script>
  function adjustBodyPadding() {
    const navContainer = document.getElementById('navbar-container');
    if (navContainer) {
      document.body.style.paddingTop = navContainer.offsetHeight + 'px';
    }
  }

  function closeSidebarOnLinkClick() {
    const sidebarEl = document.getElementById('sidebar');
    if (!sidebarEl || typeof bootstrap === 'undefined') {
      return;
    }

    const offcanvasInstance = bootstrap.Offcanvas.getOrCreateInstance(sidebarEl);
    document.querySelectorAll('#sidebar .nav-link:not([data-bs-toggle]), #sidebar .submenu-item').forEach((link) => {
      link.addEventListener('click', () => {
        if (offcanvasInstance) {
          offcanvasInstance.hide();
        }
      });
    });
  }

  function updateCollapseToggleState(targetEl, isOpen) {
    const toggle = document.querySelector(
      `#sidebar .nav-link[data-bs-target="#${targetEl.id}"], #sidebar .nav-link[href="#${targetEl.id}"]`
    );
    if (!toggle) {
      return;
    }

    toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');

    const chevron = toggle.querySelector('.bi-chevron-down, .bi-chevron-up');
    if (chevron) {
      chevron.classList.toggle('bi-chevron-up', isOpen);
      chevron.classList.toggle('bi-chevron-down', !isOpen);
    }
  }

  function initSidebarCollapseToggles() {
    if (typeof bootstrap === 'undefined') {
      return;
    }

    document.querySelectorAll('#sidebar .collapse').forEach((collapseEl) => {
      collapseEl.addEventListener('show.bs.collapse', () => updateCollapseToggleState(collapseEl, true));
      collapseEl.addEventListener('hide.bs.collapse', () => updateCollapseToggleState(collapseEl, false));

      // Set initial state if already visible
      updateCollapseToggleState(collapseEl, collapseEl.classList.contains('show'));
    });
  }

  function setActiveNavItems() {
    const currentPage = window.location.pathname.split('/').pop() || 'index.php';

    document.querySelectorAll('.nav-link').forEach((link) => {
      const href = link.getAttribute('href');
      if (href === currentPage || (currentPage === '' && href === 'index.php')) {
        link.classList.add('active');
      } else {
        link.classList.remove('active');
      }
    });

    document.querySelectorAll('.submenu-item').forEach((link) => {
      const href = link.getAttribute('href');
      if (href === currentPage) {
        link.classList.add('active');
        const parentSubmenu = link.closest('.submenu');
        if (parentSubmenu) {
          const collapse = bootstrap.Collapse.getOrCreateInstance(parentSubmenu, { toggle: false });
          collapse.show();
        }
      } else {
        link.classList.remove('active');
      }
    });
  }

  window.addEventListener('load', () => {
    adjustBodyPadding();
    if (typeof bootstrap !== 'undefined') {
      initSidebarCollapseToggles();
      setActiveNavItems();
      closeSidebarOnLinkClick();
    }
  });

  window.addEventListener('resize', adjustBodyPadding);
</script>