<?php
/**
 * Admin Sidebar Include
 * Usage: <?php $current_page = 'admin_dashboard'; include 'admin_sidebar.php'; ?>
 */
if (!isset($current_page) || empty($current_page)) {
    $current_page = str_replace('-', '_', basename($_SERVER['PHP_SELF'], '.php'));
}
$profilPages = ['admin_halaman', 'admin_profil', 'admin_monografi', 'admin_regulasi', 'admin_struktur', 'admin_sdm'];
$kelembagaanPages = ['admin_kelembagaan_lpmk', 'admin_umkm', 'admin_kelembagaan_bkm', 'admin_kelembagaan_pkk'];
$pemberdayaanPages = ['admin_kamtibmas', 'admin_kesehatan', 'admin_wisata', 'admin_pendidikan', 'admin_pariwisata'];
$pariwisataPages = ['admin_pariwisata'];
?>
<div id="sidebar">
    <div class="sidebar-header">
        <img src="../img/logo.png" alt="Logo Kedungpane" style="width: 200px; margin-bottom: 10px;" onerror="this.src='/img/logo-semarangkota.png'">
        <div class="text-white-50" style="font-size: 0.85rem;">Sistem Manajemen</div>
    </div>
    <ul>
        <li class="<?= ($current_page === 'admin_dashboard') ? 'active' : '' ?>">
            <a href="index.php"><i class="fa-solid fa-house"></i> Dashboard</a>
        </li>

        <li class="<?= ($current_page === 'admin_users') ? 'active' : '' ?>">
            <a href="admin_users.php"><i class="fa-solid fa-users"></i> Data User</a>
        </li>

        <li class="<?= in_array($current_page, $profilPages) ? 'active' : '' ?>">
            <a class="collapsed" data-bs-toggle="collapse" href="#profilAdminMenu" aria-expanded="<?= in_array($current_page, $profilPages) ? 'true' : 'false' ?>">
                <span><i class="fa-solid fa-user-gear"></i> Profil Kelurahan</span>
                <i class="fa-solid fa-chevron-down chevron"></i>
            </a>
            <ul class="sidebar-submenu collapse <?= in_array($current_page, $profilPages) ? 'show' : '' ?>" id="profilAdminMenu">
                <li class="<?= ($current_page === 'admin_halaman') ? 'active' : '' ?>"><a href="admin_halaman.php">Halaman Utama</a></li>
                <li class="<?= ($current_page === 'admin_profil') ? 'active' : '' ?>"><a href="admin_profil.php">Profil Kelurahan</a></li>
                <li class="<?= ($current_page === 'admin_monografi') ? 'active' : '' ?>"><a href="admin_monografi.php">Monografi Kelurahan</a></li>
                <li class="<?= ($current_page === 'admin_regulasi') ? 'active' : '' ?>"><a href="admin_regulasi.php">Regulasi</a></li>
                <li class="<?= ($current_page === 'admin_struktur') ? 'active' : '' ?>"><a href="admin_struktur.php">Struktur Organisasi</a></li>
                <li class="<?= ($current_page === 'admin_sdm') ? 'active' : '' ?>"><a href="admin_sdm.php">Sumber Daya Manusia</a></li>
            </ul>
        </li>

        <li class="<?= ($current_page === 'admin_informasi_publik') ? 'active' : '' ?>">
            <a href="admin-informasi-publik.php"><i class="fa-solid fa-bullhorn"></i> Informasi Publik</a>
        </li>

        <li class="<?= ($current_page === 'admin_layanan_nav') ? 'active' : '' ?>">
            <a href="admin_layanan_nav.php"><i class="fa-solid fa-link"></i> Navbar Layanan</a>
        </li>

        <li class="<?= in_array($current_page, $kelembagaanPages) ? 'active' : '' ?>">
            <a class="collapsed" data-bs-toggle="collapse" href="#kelembagaanAdminMenu" aria-expanded="<?= in_array($current_page, $kelembagaanPages) ? 'true' : 'false' ?>">
                <span><i class="fa-solid fa-building"></i> Kelembagaan</span>
                <i class="fa-solid fa-chevron-down chevron"></i>
            </a>
            <ul class="sidebar-submenu collapse <?= in_array($current_page, $kelembagaanPages) ? 'show' : '' ?>" id="kelembagaanAdminMenu">
                <li class="<?= ($current_page === 'admin_kelembagaan_lpmk') ? 'active' : '' ?>"><a href="admin_kelembagaan_lpmk.php">LPMK</a></li>
                <li class="<?= ($current_page === 'admin_umkm') ? 'active' : '' ?>"><a href="admin_umkm.php">UMKM</a></li>
                <li class="<?= ($current_page === 'admin_kelembagaan_bkm') ? 'active' : '' ?>"><a href="admin_kelembagaan_bkm.php">BKM</a></li>
                <li class="<?= ($current_page === 'admin_kelembagaan_pkk') ? 'active' : '' ?>"><a href="admin_kelembagaan_pkk.php">PKK</a></li>
            </ul>
        </li>

        <li class="<?= in_array($current_page, $pemberdayaanPages) ? 'active' : '' ?>">
            <a class="collapsed" data-bs-toggle="collapse" href="#pemberdayaanAdminMenu" aria-expanded="<?= in_array($current_page, $pemberdayaanPages) ? 'true' : 'false' ?>">
                <span><i class="fa-solid fa-people-group"></i> Pemberdayaan</span>
                <i class="fa-solid fa-chevron-down chevron"></i>
            </a>
            <ul class="sidebar-submenu collapse <?= in_array($current_page, $pemberdayaanPages) ? 'show' : '' ?>" id="pemberdayaanAdminMenu">
                <li class="<?= ($current_page === 'admin_kamtibmas') ? 'active' : '' ?>"><a href="admin_kamtibmas.php">Bidang Kamtibmas</a></li>
                <li class="<?= ($current_page === 'admin_kesehatan') ? 'active' : '' ?>"><a href="admin_kesehatan.php">Fasilitas Kesehatan</a></li>
                <li class="<?= ($current_page === 'admin_pariwisata') ? 'active' : '' ?>"><a href="admin_pariwisata.php">Wisata Lokal</a></li>
                <li class="<?= ($current_page === 'admin_pendidikan') ? 'active' : '' ?>"><a href="admin_pendidikan.php">Bidang Pendidikan</a></li>
            </ul>
        </li>

        <li class="<?= ($current_page === 'admin_berita') ? 'active' : '' ?>">
            <a href="admin_berita.php"><i class="fa-solid fa-newspaper"></i> Berita & Artikel</a>
        </li>

        <li class="mt-4"><a href="logout.php" class="text-warning"><i class="fa-solid fa-power-off"></i> Keluar Sistem</a></li>
    </ul>
</div>