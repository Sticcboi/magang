-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 26, 2026 at 08:46 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_kedungpane`
--

-- --------------------------------------------------------

--
-- Table structure for table `artikel_publik`
--

CREATE TABLE `artikel_publik` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `ringkasan` text NOT NULL,
  `konten` longtext NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `is_published` tinyint(1) DEFAULT 1,
  `tanggal` date NOT NULL,
  `penulis_id` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `artikel_publik`
--

INSERT INTO `artikel_publik` (`id`, `judul`, `slug`, `ringkasan`, `konten`, `gambar`, `is_published`, `tanggal`, `penulis_id`) VALUES
(1, 'gghmghmhmmgh', 'gghmghmhmmgh', 'mhghgmghmgh', 'mhgmghmghmghmghmghm', '', 1, '2026-04-08', 1);

-- --------------------------------------------------------

--
-- Table structure for table `banner_beranda`
--

CREATE TABLE `banner_beranda` (
  `id` int(11) NOT NULL,
  `gambar` varchar(255) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `banner_beranda`
--

INSERT INTO `banner_beranda` (`id`, `gambar`, `is_active`, `created_at`) VALUES
(5, 'img/1777227768_banner_Tambahkan judul.png', 1, '2026-04-26 18:22:48'),
(7, 'img/1777228629_banner_2.png', 1, '2026-04-26 18:37:09');

-- --------------------------------------------------------

--
-- Table structure for table `berita`
--

CREATE TABLE `berita` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `isi` text NOT NULL,
  `ringkasan` varchar(500) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `kategori` enum('umum','kesehatan','pembangunan','keamanan','pendidikan','ekonomi') DEFAULT 'umum',
  `penulis_id` int(11) DEFAULT NULL,
  `is_published` tinyint(1) DEFAULT 0,
  `is_pinned` tinyint(1) NOT NULL DEFAULT 0,
  `tanggal` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `berita`
--

INSERT INTO `berita` (`id`, `judul`, `slug`, `isi`, `ringkasan`, `gambar`, `kategori`, `penulis_id`, `is_published`, `is_pinned`, `tanggal`, `created_at`, `updated_at`) VALUES
(1, 'Musrenbang Kelurahan Kedungpane', 'ytuthjhjgjgj-1776311730', '<p>Musrenbang Kelurahan kedungpane tahun 2020 dalam rangka Penyusunan RKPD Kota Semarang 2021 berjalan dengan lancar tanpa ada gangguan satu hal pun, musrenbang dipimpin langsung Bapak Camat Mijen Moh Agus Junaidi, S.Kom</p><p>Disana Bu Lurah Rina menyampaikan paparan kegiatan pembangunan fisik dan non fisik yang akan dilaksanakan pada tahun 2021, didalamnya bu lurah juga menyampaikan hasil-hasil pekerjaan jalan tahun 2019 dan rencana pekerjaan yang kan dilaksanakan pada tahun 2020 ini (29/01/2020)</p>', 'Musrenbang Kelurahan kedungpane tahun 2020 dalam rangka Penyusunan RKPD Kota Semarang 2021 berjalan dengan lancar tanpa ada gangguan satu hal pun, mus...', '1776950727_bc00f6db814986ff.jpg', 'umum', 1, 1, 0, '2026-04-24', '2026-04-16 03:55:30', '2026-04-24 03:11:37'),
(2, 'Pemberantasan Sarang Nyamuk Bulan Februari', 'why-do-we-use-it--1776314406', '<p>Pemberantasan Sarang Nyamuk bulan Februari berjalan dengan lancar. Bu Lurah yang dibantu Babinsa dan Babinkabtibmas terjun lansung kelapangan guna mencari jentik-jentik nyamuk yang bersarang dirumah-rumah warga</p><p>Masih ada warga yang kesadarannya masih kurang tentang adanya bahaya Penyakit yang ditimbulkan oleh nyamuk, oleh karena itu kita meminta partisipasi warga untuk rajin membersihkan lingkungan tempat tinggal mereka.</p>', 'Pemberantasan Sarang Nyamuk bulan Februari berjalan dengan lancar. Bu Lurah yang dibantu Babinsa dan Babinkabtibmas terjun lansung kelapangan guna men...', '1776950676_9d4fd7a9cccf6c3c.jpg', 'kesehatan', 1, 1, 0, '2026-04-24', '2026-04-16 04:40:06', '2026-04-24 03:11:43'),
(4, 'SEKOLAH BOLA \"SSB SATRIA KEDUNGPANE SEMARANG\"', 'sekolah-bola-ssb-satria-kedungpane-semarang--1776950833', '<p>Hai Loopers, adakah diantara kamu yang berniat untuk menjadi pemain sepakbola? Jika ia, mulai sekarang kamu bisa memilih&nbsp;SSB (Sekolah Sepak Bola) Satria Kedungpane SMG.&nbsp;Memilih SSB (Sekolah Sepak Bola) yang tepat akan bikin kamu sukses di masa depan. Makanya itu, jangan sampai salah milik<a href=\"http://www.myindischool.com/\">&nbsp;Sekolah Sepak Bola</a>&nbsp;yang tepat buat masa depanmu. Ini dia beberapa sekolah sepak bola terbaik yang bisa kamu pilih. Kalau kamu berjaya, disinilah tempatnya!&nbsp;&nbsp;</p>', 'Hai Loopers, adakah diantara kamu yang berniat untuk menjadi pemain sepakbola? Jika ia, mulai sekarang kamu bisa memilih&nbsp;SSB (Sekolah Sepak Bola)...', '1776950833_cd72430e30a3ca9a.jpg', 'pendidikan', 1, 1, 0, '2026-04-24', '2026-04-23 13:27:13', '2026-04-24 03:11:25');

-- --------------------------------------------------------

--
-- Table structure for table `destinasi_wisata`
--

CREATE TABLE `destinasi_wisata` (
  `id` int(11) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `maps_url` varchar(500) DEFAULT NULL,
  `jam_buka` varchar(100) DEFAULT NULL,
  `harga_tiket` varchar(100) DEFAULT NULL,
  `kategori` enum('alam','budaya','religi','kuliner','buatan') DEFAULT 'alam',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dokumen_rakyat`
--

CREATE TABLE `dokumen_rakyat` (
  `id` int(11) NOT NULL,
  `nama_dokumen` varchar(255) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `file_url` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_umkm`
--

CREATE TABLE `event_umkm` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `tanggal` date NOT NULL,
  `lokasi` varchar(150) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fasilitas_kesehatan`
--

CREATE TABLE `fasilitas_kesehatan` (
  `id` int(11) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `jenis` enum('puskesmas','klinik','apotek','dokter_mandiri','rumah_sakit') NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `telepon` varchar(50) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `maps_url` varchar(500) DEFAULT NULL,
  `jam_buka` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `galeri`
--

CREATE TABLE `galeri` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `gambar` varchar(255) NOT NULL,
  `halaman` varchar(50) NOT NULL,
  `urutan` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `informasi_publik`
--

CREATE TABLE `informasi_publik` (
  `id` int(11) NOT NULL,
  `tipe_info` enum('berkala','setiap_saat','serta_merta') NOT NULL,
  `kategori_spesifik` varchar(100) DEFAULT NULL,
  `judul` varchar(255) NOT NULL,
  `file_dokumen` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `ikon` varchar(50) DEFAULT 'bi-file-earmark-pdf-fill',
  `tanggal` date NOT NULL DEFAULT current_timestamp(),
  `status` enum('published','draft') DEFAULT 'published'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `informasi_publik`
--

INSERT INTO `informasi_publik` (`id`, `tipe_info`, `kategori_spesifik`, `judul`, `file_dokumen`, `deskripsi`, `file_path`, `ikon`, `tanggal`, `status`) VALUES
(1, 'berkala', 'dcscddsc', 'dcdscdscs', '1777146076_Kotbah25april26omNathan.pdf', 'dcsdscdcdscds', NULL, 'bi-file-earmark-pdf-fill', '2026-04-25', 'published'),
(2, 'serta_merta', 'ggfdgfg', 'gdfgdfgdfg', '', 'dfdgfdgfdg', NULL, 'bi-file-earmark-pdf-fill', '2026-04-25', 'published');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_layanan`
--

CREATE TABLE `jadwal_layanan` (
  `id` int(11) NOT NULL,
  `program` varchar(150) NOT NULL,
  `hari` varchar(20) NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `lokasi` varchar(150) NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `kategori` enum('posyandu','dokter','administrasi','imunisasi','lainnya') DEFAULT 'lainnya',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kelembagaan_pages`
--

CREATE TABLE `kelembagaan_pages` (
  `id` int(11) NOT NULL,
  `page` varchar(50) NOT NULL,
  `overview` text DEFAULT NULL,
  `visi` text DEFAULT NULL,
  `misi` text DEFAULT NULL,
  `legal_basis` text DEFAULT NULL,
  `work_area` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kelembagaan_pages`
--

INSERT INTO `kelembagaan_pages` (`id`, `page`, `overview`, `visi`, `misi`, `legal_basis`, `work_area`, `notes`, `updated_at`) VALUES
(1, 'bkm', 'hfghfghfgh', 'hgfhfghgf', 'hggfhfgh', 'fhfghgfh', 'fghfghgfh', 'gfhfhgfhf', '2026-04-26 16:23:31');

-- --------------------------------------------------------

--
-- Table structure for table `kelembagaan_programs`
--

CREATE TABLE `kelembagaan_programs` (
  `id` int(11) NOT NULL,
  `page` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `order_no` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kelembagaan_staff`
--

CREATE TABLE `kelembagaan_staff` (
  `id` int(11) NOT NULL,
  `page` varchar(50) NOT NULL,
  `name` varchar(150) NOT NULL,
  `role` varchar(150) DEFAULT NULL,
  `contact` varchar(100) DEFAULT NULL,
  `order_no` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kelembagaan_staff`
--

INSERT INTO `kelembagaan_staff` (`id`, `page`, `name`, `role`, `contact`, `order_no`, `created_at`) VALUES
(1, 'bkm', 'hgfhfhfg', 'fghgfhfh', 'fghfghfgh', 0, '2026-04-26 16:23:51');

-- --------------------------------------------------------

--
-- Table structure for table `kelembagaan_units`
--

CREATE TABLE `kelembagaan_units` (
  `id` int(11) NOT NULL,
  `page` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `order_no` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kontak_darurat`
--

CREATE TABLE `kontak_darurat` (
  `id` int(11) NOT NULL,
  `label` varchar(100) NOT NULL,
  `nama` varchar(150) DEFAULT NULL,
  `nomor` varchar(50) NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `urutan` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kontak_pariwisata`
--

CREATE TABLE `kontak_pariwisata` (
  `id` int(11) NOT NULL,
  `pengelola_destinasi` varchar(255) DEFAULT NULL,
  `kontak_pengelola` varchar(255) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `nomor_penting` varchar(255) DEFAULT NULL,
  `informasi_singkat` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kontak_pariwisata`
--

INSERT INTO `kontak_pariwisata` (`id`, `pengelola_destinasi`, `kontak_pengelola`, `instagram`, `facebook`, `nomor_penting`, `informasi_singkat`, `updated_at`) VALUES
(1, 'tyrytr', 'bnvnvb', 'vnbnvb', 'bvnbvnvbn', 'trtretere', 'nbvnbn', '2026-04-24 08:36:09');

-- --------------------------------------------------------

--
-- Table structure for table `layanan_administrasi`
--

CREATE TABLE `layanan_administrasi` (
  `id` int(11) NOT NULL,
  `nama_layanan` varchar(255) NOT NULL,
  `link_url` text NOT NULL,
  `ikon_gambar` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `layanan_administrasi`
--

INSERT INTO `layanan_administrasi` (`id`, `nama_layanan`, `link_url`, `ikon_gambar`) VALUES
(1, 'Akta Lahir', 'https://sidnok.semarangkota.go.id/data-dukung', 'img/aktalahir.png'),
(2, 'Akta Kematian', 'https://sidnok.semarangkota.go.id/data-dukung', 'img/aktakematian.png'),
(3, 'Buku Nikah', 'https://ppid.semarangkota.go.id/?s=pengantar+nikah', 'img/nikah.png'),
(4, 'Pengantar SKCK', 'https://ppid.semarangkota.go.id/kb/buat-skck-harus-bawa-surat-pengantar-rt-rw-kelurahan-ini-penjelasannya/', 'img/skck.png'),
(5, 'Surat Pindah', 'https://sidnok.semarangkota.go.id/data-dukung', 'img/pindah.png'),
(6, 'Pajak PBB', 'https://e-pbb.semarangkota.go.id/', 'img/pbb.png');

-- --------------------------------------------------------

--
-- Table structure for table `navbar_layanan`
--

CREATE TABLE `navbar_layanan` (
  `id` int(11) NOT NULL,
  `nama_layanan` varchar(255) NOT NULL,
  `url` text NOT NULL,
  `urutan` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `navbar_layanan`
--

INSERT INTO `navbar_layanan` (`id`, `nama_layanan`, `url`, `urutan`) VALUES
(1, 'Permohonan Pembuatan KTP Elektronik', 'https://sidnok.semarangkota.go.id/data-dukung', 1),
(2, 'Permohonan Pembuatan Kartu Keluarga (KK)', 'https://ppid.semarangkota.go.id/kb/pembuatan-kartu-keluarga/', 2),
(3, 'Permohonan Pembuatan Akte Kelahiran', 'https://sidnok.semarangkota.go.id/data-dukung', 3),
(4, 'Permohonan Pembuatan Akte Kematian', 'https://sidnok.semarangkota.go.id/data-dukung', 4),
(5, 'Permohonan Pembuatan Domisili Tempat Tinggal', 'https://ppid.semarangkota.go.id/?s=domisili', 5),
(6, 'Pengantar Pindah', 'https://sidnok.semarangkota.go.id/data-dukung', 6),
(7, 'Pengantar Pindah Datang', 'https://sidnok.semarangkota.go.id/data-dukung', 7),
(8, 'Surat Keterangan Domisili Usaha', 'https://ppid.semarangkota.go.id/?s=domisili+usaha', 8),
(9, 'Permohonan Pengantar Nikah Laki-Laki', 'https://ppid.semarangkota.go.id/?s=pengantar+nikah', 9),
(10, 'Permohonan Pengantar Nikah Perempuan', 'https://ppid.semarangkota.go.id/?s=pengantar+nikah', 10),
(11, 'Surat Keterangan Tidak Mampu', 'https://ppid.semarangkota.go.id/?s=tidak+mampu', 11),
(12, 'Surat Keterangan Ahli Waris', 'https://ppid.semarangkota.go.id/?s=ahli+waris', 12),
(13, 'IUMK (Izin Usaha Mikro Kecil)', 'https://ppid.semarangkota.go.id/?s=iumk', 13);

-- --------------------------------------------------------

--
-- Table structure for table `pegawai`
--

CREATE TABLE `pegawai` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jabatan` varchar(150) NOT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `nip` varchar(30) DEFAULT NULL,
  `pangkat_gol` varchar(50) DEFAULT NULL,
  `pendidikan` varchar(100) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `urutan` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pegawai`
--

INSERT INTO `pegawai` (`id`, `nama`, `jabatan`, `kategori`, `nip`, `pangkat_gol`, `pendidikan`, `foto`, `urutan`, `is_active`, `created_at`, `updated_at`) VALUES
(3, 'Nama Lurah, S.IP., M.Si.', 'Lurah Kedungpane', 'Top Management', '198001012005011001', '', 'S3 Ilmu Hukum', 'img/1776959439_lurah.png', 1, 1, '2026-04-23 15:34:57', '2026-04-23 16:06:07'),
(4, 'Wakil Lurah', 'Wakil Lurah Kedungpane', 'Top Management', '198502022010012002', '', 'S1 Akutansi', 'img/1776959450_3.png', 2, 1, '2026-04-23 15:34:57', '2026-04-23 16:06:44'),
(5, 'Sekretaris Lurah', 'Sekretaris Lurah', 'Administrasi & Keuangan', '198803032015031003', '', 'S1 Teknik Informatika', 'img/1776959456_2.png', 3, 1, '2026-04-23 15:34:57', '2026-04-23 16:07:06'),
(6, 'Nama Kasi Kesra', 'Kasi Kesejahteraan Rakyat', 'Anggota / Staf', '199004042020012004', '', 'S1 Management', 'img/1776960220_1.png', 5, 1, '2026-04-23 15:34:57', '2026-04-23 16:09:00'),
(7, 'Bendahara Lurah', 'Bendahara Lurah', 'Administrasi & Keuangan', '', '', 'S1 Keuangan', 'img/1776960466_1.png', 4, 1, '2026-04-23 16:07:46', '2026-04-23 16:08:51');

-- --------------------------------------------------------

--
-- Table structure for table `pengaturan_kamtibmas`
--

CREATE TABLE `pengaturan_kamtibmas` (
  `id` int(11) NOT NULL,
  `nomor_whatsapp` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengaturan_kamtibmas`
--

INSERT INTO `pengaturan_kamtibmas` (`id`, `nomor_whatsapp`, `created_at`, `updated_at`) VALUES
(1, '+6285876797111', '2026-04-24 07:51:28', '2026-04-24 07:55:10');

-- --------------------------------------------------------

--
-- Table structure for table `profil_kelurahan`
--

CREATE TABLE `profil_kelurahan` (
  `id` int(11) NOT NULL,
  `nama_lurah` varchar(100) DEFAULT NULL,
  `jabatan_lurah` varchar(100) DEFAULT NULL,
  `foto_lurah` varchar(255) DEFAULT NULL,
  `teks_sambutan` text DEFAULT NULL,
  `jml_penduduk` int(11) DEFAULT NULL,
  `jml_rw` int(11) DEFAULT NULL,
  `jml_rt` int(11) DEFAULT NULL,
  `luas_wilayah` int(11) DEFAULT NULL,
  `iframe_map` text DEFAULT NULL,
  `batas_utara` varchar(100) DEFAULT NULL,
  `batas_selatan` varchar(100) DEFAULT NULL,
  `batas_timur` varchar(100) DEFAULT NULL,
  `batas_barat` varchar(100) DEFAULT NULL,
  `jml_kk` int(11) DEFAULT 0,
  `penduduk_l` int(11) DEFAULT 0,
  `penduduk_p` int(11) DEFAULT 0,
  `mata_pencaharian` text DEFAULT NULL,
  `fas_sd` int(11) DEFAULT 0,
  `fas_ibadah` int(11) DEFAULT 0,
  `fas_puskesmas` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profil_kelurahan`
--

INSERT INTO `profil_kelurahan` (`id`, `nama_lurah`, `jabatan_lurah`, `foto_lurah`, `teks_sambutan`, `jml_penduduk`, `jml_rw`, `jml_rt`, `luas_wilayah`, `iframe_map`, `batas_utara`, `batas_selatan`, `batas_timur`, `batas_barat`, `jml_kk`, `penduduk_l`, `penduduk_p`, `mata_pencaharian`, `fas_sd`, `fas_ibadah`, `fas_puskesmas`) VALUES
(1, 'Nama Lurah, S.IP., M.Si.', 'Kepala Kelurahan Kedungpane', 'img/1776997611_1776959439_lurah.png', '<p>Assalamualaikum Warahmatullahi Wabarakaatuh,</p><p>Puji syukur kita panjatkan kehadirat Tuhan Yang Maha Esa. Selamat datang di portal resmi Kelurahan Kedungpane, Kecamatan Mijen, Kota Semarang. Website ini merupakan wujud nyata komitmen kami dalam mengimplementasikan tata kelola pemerintahan yang baik <i>(Good Governance)</i> serta mewujudkan transparansi informasi publik.</p><p>Melalui platform ini, kami berharap warga dapat dengan mudah mengakses berbagai informasi terkait program pembangunan, pemberdayaan masyarakat, serta prosedur layanan administrasi kependudukan. Mari bersama-sama kita bangun Kelurahan Kedungpane menjadi lingkungan yang aman, sejahtera, dan berbudaya.</p>', 124501, 13, 75, 350, '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3959.7304761528967!2d110.3353831740375!3d-7.040928068986028!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e708a7326741db1%3A0x82046ec7dad6f0b0!2sKantor%20Kelurahan%20Kedungpane!5e0!3m2!1sid!2sid!4v1777224844082!5m2!1sid!2sid\" width=\"600\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', 'Kelurahan Kedungpane', 'Kel. Jatibarang', 'Kelurahan Bambankerep', 'Kec. Boja', 0, 0, 0, '', 5, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `profil_pariwisata`
--

CREATE TABLE `profil_pariwisata` (
  `id` int(11) NOT NULL,
  `deskripsi_singkat` text DEFAULT NULL,
  `sejarah_cerita_unik` text DEFAULT NULL,
  `visi` text DEFAULT NULL,
  `misi` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profil_pariwisata`
--

INSERT INTO `profil_pariwisata` (`id`, `deskripsi_singkat`, `sejarah_cerita_unik`, `visi`, `misi`, `updated_at`) VALUES
(1, 'fhfghfghf', NULL, 'fghgfhfgh', 'fghfhfghfg', '2026-04-24 08:36:59');

-- --------------------------------------------------------

--
-- Table structure for table `program_kamtibmas`
--

CREATE TABLE `program_kamtibmas` (
  `id` int(11) NOT NULL,
  `nama_program` varchar(255) NOT NULL,
  `penanggung_jawab` varchar(150) DEFAULT NULL,
  `waktu` varchar(100) DEFAULT NULL,
  `lokasi` varchar(150) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `program_kamtibmas`
--

INSERT INTO `program_kamtibmas` (`id`, `nama_program`, `penanggung_jawab`, `waktu`, `lokasi`, `keterangan`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'hkjjkhjkhj', 'jkhkhkjhk', '7657567', 'ghjgghjg', 'hgjghjghjghjg', 1, '2026-04-26 16:45:23', '2026-04-26 16:45:23');

-- --------------------------------------------------------

--
-- Table structure for table `sekolah_pendidikan`
--


CREATE TABLE IF NOT EXISTS sekolah_pendidikan (
id int(11) NOT NULL AUTO_INCREMENT,
jenjang enum('SD','SMP','SMA/SMK') NOT NULL DEFAULT 'SD',
nama_sekolah varchar(255) NOT NULL,
alamat varchar(255) DEFAULT NULL,
data_map varchar(500) DEFAULT NULL,
is_active tinyint(1) DEFAULT 1,
urutan int(11) DEFAULT 0,
created_at timestamp NOT NULL DEFAULT current_timestamp(),
PRIMARY KEY (id) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
 --------------------------------------------------------

--
-- Table structure for table `regulasi`
--

CREATE TABLE `regulasi` (
  `id` int(11) NOT NULL,
  `kategori` varchar(100) NOT NULL,
  `judul_dokumen` varchar(255) NOT NULL,
  `file_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sdm_kelurahan`
--

CREATE TABLE `sdm_kelurahan` (
  `id` int(11) NOT NULL,
  `tipe` enum('aparatur','lkk') NOT NULL,
  `judul` varchar(100) NOT NULL,
  `nilai` varchar(50) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `ikon` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sdm_kelurahan`
--

INSERT INTO `sdm_kelurahan` (`id`, `tipe`, `judul`, `nilai`, `deskripsi`, `ikon`) VALUES
(1, 'lkk', 'dcddf', '21', 'fdsdfds', 'dsfdsfsdsdasdsa');

-- --------------------------------------------------------

--
-- Table structure for table `statistik_kelurahan`
--

CREATE TABLE `statistik_kelurahan` (
  `id` int(11) NOT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `label` varchar(100) NOT NULL,
  `nilai` varchar(50) NOT NULL,
  `ikon` varchar(50) DEFAULT NULL,
  `urutan` int(11) DEFAULT 0,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `statistik_kelurahan`
--

INSERT INTO `statistik_kelurahan` (`id`, `kategori`, `label`, `nilai`, `ikon`, `urutan`, `updated_at`) VALUES
(1, 'usia', '0-14 Tahun', '2500', NULL, 1, '2026-04-25 19:07:59'),
(2, 'usia', '15-64 Tahun', '8500', NULL, 2, '2026-04-25 19:07:59'),
(3, 'usia', '> 64 Tahun', '1500', NULL, 3, '2026-04-25 19:07:59'),
(4, 'pendidikan', 'SD', '1200', NULL, 1, '2026-04-25 19:07:59'),
(5, 'pendidikan', 'SMP', '2100', NULL, 2, '2026-04-25 19:07:59'),
(6, 'pendidikan', 'SMA/SMK', '5400', NULL, 3, '2026-04-25 19:07:59'),
(7, 'pendidikan', 'D3/S1', '3600', NULL, 4, '2026-04-25 19:07:59'),
(8, 'pendidikan', 'S2/S3', '200', NULL, 5, '2026-04-25 19:07:59'),
(9, 'pekerjaan', 'Swasta', '4500', NULL, 1, '2026-04-25 19:26:26'),
(10, 'pekerjaan', 'Wiraswasta', '2100', NULL, 2, '2026-04-25 19:07:59'),
(11, 'pekerjaan', 'ASN/TNI/Polri', '800', NULL, 3, '2026-04-25 19:07:59'),
(12, 'pekerjaan', 'Buruh', '1200', NULL, 4, '2026-04-25 19:07:59'),
(13, 'pekerjaan', 'Lainnya', '3900', NULL, 5, '2026-04-25 19:27:11');

-- --------------------------------------------------------

--
-- Table structure for table `struktur_organisasi`
--

CREATE TABLE `struktur_organisasi` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jabatan` varchar(100) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `urutan` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `umkm`
--

CREATE TABLE `umkm` (
  `id` int(11) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `kategori` varchar(100) DEFAULT NULL,
  `pengelola` varchar(100) DEFAULT NULL,
  `kontak` varchar(50) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Super Admin','Admin','Editor') DEFAULT 'Admin',
  `alamat` text DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jabatan` varchar(100) DEFAULT NULL,
  `foto_profil` varchar(255) DEFAULT NULL,
  `nama` varchar(100) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `alamat`, `tanggal_lahir`, `jabatan`, `foto_profil`, `nama`, `foto`, `last_login`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'admin', '001132e60c304ea9df4a0f51fcc908ae', 'Admin', '-', '2026-04-24', 'IT STAFF', 'admin_1777019320.png', 'Administrator', NULL, '2026-04-27 00:12:13', 1, '2026-04-16 02:51:26', '2026-04-26 17:12:13'),
(2, 'admin2', '$2y$10$ReudQv1kOdv15j0Qxppaj.rOI.xyeOZrF6Cj5bgGw43QE.pUSyXoO', 'Admin', 'yjjtyyfjgfjgjgfgj', '2026-04-26', 'gfdgdgd', '', 'hjgjghjghjgh', NULL, '2026-04-26 22:59:13', 1, '2026-04-26 15:59:00', '2026-04-26 15:59:13');

-- --------------------------------------------------------

--
-- Table structure for table `wisata_kuliner`
--

CREATE TABLE `wisata_kuliner` (
  `id` int(11) NOT NULL,
  `jenis` varchar(100) NOT NULL,
  `contoh` varchar(255) DEFAULT NULL,
  `lokasi_catatan` text DEFAULT NULL,
  `urutan` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `artikel_publik`
--
ALTER TABLE `artikel_publik`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `banner_beranda`
--
ALTER TABLE `banner_beranda`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `berita`
--
ALTER TABLE `berita`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `penulis_id` (`penulis_id`);

--
-- Indexes for table `destinasi_wisata`
--
ALTER TABLE `destinasi_wisata`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dokumen_rakyat`
--
ALTER TABLE `dokumen_rakyat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_umkm`
--
ALTER TABLE `event_umkm`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fasilitas_kesehatan`
--
ALTER TABLE `fasilitas_kesehatan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `galeri`
--
ALTER TABLE `galeri`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `informasi_publik`
--
ALTER TABLE `informasi_publik`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jadwal_layanan`
--
ALTER TABLE `jadwal_layanan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kelembagaan_pages`
--
ALTER TABLE `kelembagaan_pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `page` (`page`);

--
-- Indexes for table `kelembagaan_programs`
--
ALTER TABLE `kelembagaan_programs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kelembagaan_staff`
--
ALTER TABLE `kelembagaan_staff`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kelembagaan_units`
--
ALTER TABLE `kelembagaan_units`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kontak_darurat`
--
ALTER TABLE `kontak_darurat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kontak_pariwisata`
--
ALTER TABLE `kontak_pariwisata`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `layanan_administrasi`
--
ALTER TABLE `layanan_administrasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `navbar_layanan`
--
ALTER TABLE `navbar_layanan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pegawai`
--
ALTER TABLE `pegawai`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pengaturan_kamtibmas`
--
ALTER TABLE `pengaturan_kamtibmas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `profil_kelurahan`
--
ALTER TABLE `profil_kelurahan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `profil_pariwisata`
--
ALTER TABLE `profil_pariwisata`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `program_kamtibmas`
--
ALTER TABLE `program_kamtibmas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sekolah_pendidikan`
--
ALTER TABLE `sekolah_pendidikan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `regulasi`
--
ALTER TABLE `regulasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sdm_kelurahan`
--
ALTER TABLE `sdm_kelurahan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `statistik_kelurahan`
--
ALTER TABLE `statistik_kelurahan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `struktur_organisasi`
--
ALTER TABLE `struktur_organisasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `umkm`
--
ALTER TABLE `umkm`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `wisata_kuliner`
--
ALTER TABLE `wisata_kuliner`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `artikel_publik`
--
ALTER TABLE `artikel_publik`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `banner_beranda`
--
ALTER TABLE `banner_beranda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `berita`
--
ALTER TABLE `berita`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `destinasi_wisata`
--
ALTER TABLE `destinasi_wisata`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dokumen_rakyat`
--
ALTER TABLE `dokumen_rakyat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_umkm`
--
ALTER TABLE `event_umkm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fasilitas_kesehatan`
--
ALTER TABLE `fasilitas_kesehatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `galeri`
--
ALTER TABLE `galeri`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `informasi_publik`
--
ALTER TABLE `informasi_publik`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `jadwal_layanan`
--
ALTER TABLE `jadwal_layanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kelembagaan_pages`
--
ALTER TABLE `kelembagaan_pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `kelembagaan_programs`
--
ALTER TABLE `kelembagaan_programs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kelembagaan_staff`
--
ALTER TABLE `kelembagaan_staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `kelembagaan_units`
--
ALTER TABLE `kelembagaan_units`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kontak_darurat`
--
ALTER TABLE `kontak_darurat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kontak_pariwisata`
--
ALTER TABLE `kontak_pariwisata`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `layanan_administrasi`
--
ALTER TABLE `layanan_administrasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `navbar_layanan`
--
ALTER TABLE `navbar_layanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `pegawai`
--
ALTER TABLE `pegawai`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `pengaturan_kamtibmas`
--
ALTER TABLE `pengaturan_kamtibmas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `profil_pariwisata`
--
ALTER TABLE `profil_pariwisata`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `program_kamtibmas`
--
ALTER TABLE `program_kamtibmas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `program_pendidikan`
--
ALTER TABLE `program_pendidikan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `regulasi`
--
ALTER TABLE `regulasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sdm_kelurahan`
--
ALTER TABLE `sdm_kelurahan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `statistik_kelurahan`
--
ALTER TABLE `statistik_kelurahan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `struktur_organisasi`
--
ALTER TABLE `struktur_organisasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `umkm`
--
ALTER TABLE `umkm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `wisata_kuliner`
--
ALTER TABLE `wisata_kuliner`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `berita`
--
ALTER TABLE `berita`
  ADD CONSTRAINT `berita_ibfk_1` FOREIGN KEY (`penulis_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
