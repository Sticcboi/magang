-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 29, 2026 at 06:04 PM
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

-- --------------------------------------------------------

--
-- Table structure for table `banner_beranda`
--

CREATE TABLE `banner_beranda` (
  `id` int(11) NOT NULL,
  `gambar` varchar(255) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `urutan` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `banner_beranda`
--

INSERT INTO `banner_beranda` (`id`, `gambar`, `is_active`, `urutan`, `created_at`) VALUES
(15, 'img/1777450133_banner_Tambahkan judul (5).png', 1, 1, '2026-04-29 08:08:53'),
(16, 'img/1777451288_banner_Tambahkan judul (7).png', 1, 0, '2026-04-29 08:28:08');

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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `alamat` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `destinasi_wisata`
--

INSERT INTO `destinasi_wisata` (`id`, `nama`, `deskripsi`, `gambar`, `maps_url`, `jam_buka`, `harga_tiket`, `kategori`, `is_active`, `created_at`, `updated_at`, `alamat`) VALUES
(3, 'Desa Wisata Jamalsari', 'Ada wisata tracking, naik kapal muter muter waduk, ada pasar makanan juga.\r\n', 'https://jadesta.com/imgpost/75353.jpg', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3959.7711949838626!2d110.3505732!3d-7.0361560999999995!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e708babe5f8f59b%3A0x86d99d57ebb81a8f!2sWaduk%20Jatibarang%20Mijen!5e0!3m2!1sid!2sid!4v1777452999157!5m2!1sid!2sid\" width=\"600\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', '08.00-15.00', '3.000-5.000', 'budaya', 1, '2026-04-29 06:52:20', '2026-04-29 08:57:31', 'Jl. Jamalsari Tim. 1, RT.05/RW.02, Kedungpane, Kec. Mijen, Kota Semarang, Jawa Tengah'),
(4, 'Waduk Jatibarang', 'Waduk jatibarang kala senja, mau buat mancing kok gelap. Waduk yg akhirnya membelah pulau Goa Kreo ini diisi air mulai 2014 dan resmi operais 2015. Fungsi utamanya pengendali banjir Kota Semarang karena seringkali terjadi banjir besar mulai tahun 1970an. Debit sebesar 1.050 liter/detik. Sekarang destinasi wisata ada speedboat, cafe dan tentunay wisata sejarah Goa Kreo diatas, meski sepi.', 'https://busdiscovery.id/wp-content/uploads/2023/10/Waduk-Jatibarang.jpeg', 'Jl. Jamalsari Tim. 1, Kedungpane, Kec. Mijen, Kota Semarang, Jawa Tengah 50211', '24 jam ', 'Rp3.000 hingga Rp10.000 per orang', 'alam', 1, '2026-04-29 07:09:17', '2026-04-29 08:59:40', 'Jl. Jamalsari Tim. 1, Kedungpane, Kec. Mijen, Kota Semarang, Jawa Tengah '),
(6, 'Masjid Kapal Semarang', 'Sudah beberapa kali kesini setiap anak-anak libur sekolah atau weekend. Sambil menunggu anak bermain, jangan lupa untuk membeli makanan khas dari Masjid Kapal. Seperti gendar pecel, onde-onde pelangi, tape besek, es serut pelangi dan masih banyak lagi.', 'https://visitjawatengah.jatengprov.go.id/assets/images/ebed151e-27c8-48a8-bcdf-19ade1299c5e.jpg', 'Jl. Kyai Padak, Podorejo, Kec. Ngaliyan, Kota Semarang, Jawa Tengah 50214', '04.00-20.00', 'Rp3.000 per orang', 'religi', 1, '2026-04-29 08:42:26', '2026-04-29 08:42:26', 'Jl. Kyai Padak, Podorejo, Kec. Ngaliyan, Kota Semarang, Jawa Tengah 50214');

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
  `urutan` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fasilitas_kesehatan`
--

INSERT INTO `fasilitas_kesehatan` (`id`, `nama`, `jenis`, `alamat`, `telepon`, `latitude`, `longitude`, `maps_url`, `jam_buka`, `urutan`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Puskemas Kedungpane ', 'puskesmas', ' Jl. Wismasari Raya No.18D', '024-760-8795', NULL, NULL, 'google.com/maps?vet=10CAAQoqAOahcKEwiIwdbOuY2UAxUAAAAAHQAAAAAQDA..i&sca_esv=c28e6a7449b65c90&pvq=CgwvZy8xaG00cDh5MDIiDwoJcHVza2VzbWFzEAIYAw&lqi=ChRwdXNrZXNtYXMga2VkdW5ncGFuZUiC6NHKzI-AgAhaGhAAGAAiFHB1c2tlc21hcyBrZWR1bmdwYW5lkgEVcHVibGljX21lZGljYWxfY2VudGVy&fvr=1&cs=1&um=1&ie=UTF-8&fb=1&gl=id&sa=X&ftid=0x2e708abdec96d5ff:0xaecbdd4062a953cc', '', 3, 1, '2026-04-27 06:57:55', '2026-04-29 06:13:51'),
(2, 'Klinik Pratama Mutiara Bunda Semarang', 'klinik', ' Jl. Prof. Dr. Hamka No.100, Tambakaji, Kec. Ngaliyan', '0813-3252-7527 ', NULL, NULL, 'https://www.google.com/maps/place/Klinik+Pratama+Mutiara+Bunda+Semarang/@-6.9934864,110.350897,17z/data=!3m1!4b1!4m6!3m5!1s0x2e708ab9318b9135:0x3a5435561e538933!8m2!3d-6.9934864!4d110.350897!16s%2Fg%2F1hm3f3j83?entry=ttu&g_ep=EgoyMDI2MDQyMi4wIKXMDSoASAFQAw%3D%3D', '08.00 - 19.00 ', 2, 1, '2026-04-27 07:05:16', '2026-04-28 06:45:08'),
(3, 'Apotek Nusantara Kedungpane ', 'apotek', 'Jl. Raya Ngaliyan No.A4, Tambakaji, Kec. Ngaliyan, Kota Semarang', '024-762-3517', NULL, NULL, 'https://www.google.com/maps/place/Apotik+Nusantara/@-6.9919634,110.3499498,17z/data=!3m1!4b1!4m6!3m5!1s0x2e708ab8efc4c2c5:0x85d531368172c0e1!8m2!3d-6.9919634!4d110.3525247!16s%2Fg%2F1hm1vxs72?entry=ttu&g_ep=EgoyMDI2MDQyMi4wIKXMDSoASAFQAw%3D%3D', '07.30-21.00', 4, 1, '2026-04-27 07:08:29', '2026-04-29 06:13:51'),
(4, 'Praktik Dokter Mandiri dr. Vemy Melinda', 'dokter_mandiri', 'Perum Permata Puri Jl. Bukit Dingin VI No.1 Blok C-10, RT./RW/RW.007/008, Bringin, Kec. Ngaliyan, Kota Semarang', '0821-3032-2016', NULL, NULL, 'https://www.google.com/maps/place/Praktik+Dokter+Mandiri+dr.+Vemy+Melinda/@-7.0032503,110.1908783,12z/data=!4m10!1m2!2m1!1spraktik+dokter+mandiri+kedungpane!3m6!1s0x2e708b99053823f1:0xe22c4491f0138346!8m2!3d-7.0032503!4d110.3350739!15sCiFwcmFrdGlrIGRva3RlciBtYW5kaXJpIGtlZHVuZ3BhbmVaIyIhcHJha3RpayBkb2t0ZXIgbWFuZGlyaSBrZWR1bmdwYW5lkgEObWVkaWNhbF9vZmZpY2WaASNDaFpEU1VoTk1HOW5TMFZKUTBGblNVUmZOa3REVDB0QkVBReABAPoBBAgAECQ!16s%2Fg%2F11x1gl0hhj?entry=ttu&g_ep=EgoyMDI2MDQyMi4wIKXMDSoASAFQAw%3D%3D', '16.00-21.00', 1, 1, '2026-04-27 07:13:01', '2026-04-28 06:45:06');

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
  `urutan` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jadwal_layanan`
--

INSERT INTO `jadwal_layanan` (`id`, `program`, `hari`, `jam_mulai`, `jam_selesai`, `lokasi`, `keterangan`, `kategori`, `is_active`, `urutan`, `created_at`, `updated_at`) VALUES
(1, 'Posyandu Balita ', 'Selasa', '09:00:00', '11:00:00', 'Balai RW 05 ', '', 'posyandu', 1, 1, '2026-04-27 07:14:20', '2026-04-29 06:13:46'),
(2, 'Posyandu Remaja ', 'Kamis', '13:00:00', '15:00:00', 'Aula Kelurahan', '', 'posyandu', 1, 2, '2026-04-27 07:15:39', '2026-04-29 06:17:47'),
(3, 'Posyandu Lansia ', 'Sabtu', '08:00:00', '10:00:00', 'Posbindu', '', 'posyandu', 1, 5, '2026-04-27 07:16:40', '2026-04-29 06:11:49'),
(4, 'Kunjungan Dokter ', 'Rabu', '10:00:00', '12:00:00', 'Kantor Kelurahan', '', 'dokter', 1, 3, '2026-04-27 07:17:30', '2026-04-29 06:17:47'),
(5, 'Pelayanan Administrasi ', 'Jumat', '14:00:00', '16:00:00', 'Kantor Kelurahan ', '', 'administrasi', 1, 4, '2026-04-27 07:18:26', '2026-04-29 06:11:49');

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
(1, 'bkm', 'lembaga pimpinan kolektif masyarakat sipil yang berfungsi sebagai wadah penanggulangan kemiskinan dan pembangunan wilayah secara mandiri dan partisipatif.', 'Terwujudnya kemandirian masyarakat dan lembaga masyarakat yang mampu mengatasi persoalan kemiskinan secara berkelanjutan demi tercapainya kesejahteraan warga.', 'Membangun lembaga masyarakat yang dipercaya, akuntabel, dan transparan.\r\n\r\nMeningkatkan partisipasi aktif warga dalam setiap tahapan pembangunan (perencanaan hingga pengawasan).\r\n\r\nMenggalang potensi sumber daya lokal untuk penanggulangan kemiskinan.\r\n\r\nMenjalin kemitraan dengan berbagai pihak (Pemerintah, Swasta, dan LSM).', 'Anggaran Dasar dan Anggaran Rumah Tangga (AD/ART) BKM yang disahkan melalui Notaris dan Berita Acara Rembug Warga.', 'satu wilayah Kelurahan atau Desa secara administratif. BKM tidak bekerja lintas kelurahan, melainkan fokus pada basis lingkungan di tingkat RT dan RW di dalam kelurahan tersebut.', 'gfhfhgfhf', '2026-04-29 09:43:45'),
(2, 'lpmk', 'LPMK adalah wadah yang dibentuk atas prakarsa masyarakat sebagai mitra Pemerintah Kelurahan Kedungpane dalam menampung aspirasi dan kebutuhan pembangunan', '', '', '', '', NULL, '2026-04-29 05:58:07'),
(4, 'pkk', 'Pemberdayaan Kesejahteraan Keluarga (PKK) merupakan gerakan pembangunan masyarakat yang berawal dari keluarga. PKK Kelurahan Kedungpane aktif dalam membina kesejahteraan keluarga melalui 10 Program Pokok PKK demi terwujudnya keluarga yang mandiri dan sejahtera.', 'Terwujudnya keluarga yang beriman dan bertaqwa kepada Tuhan Yang Maha Esa, berakhlak mulia dan berbudi luhur, sehat sejahtera lahir dan batin.', 'Meningkatkan mental spiritual, perilaku hidup dengan menghayati dan mengamalkan Pancasila serta meningkatkan kualitas dan kuantitas pelayanan PKK kepada masyarakat.', 'Keputusan Menteri Dalam Negeri Nomor 1 Tahun 2013 tentang Pemberdayaan Masyarakat Melalui Gerakan Pemberdayaan dan Kesejahteraan Keluarga.', 'Seluruh wilayah administratif Kelurahan Kedungpane, Kecamatan Mijen, yang mencakup pembinaan di tingkat RW dan RT melalui kelompok Dasawisma.', 'Pertemuan rutin dilaksanakan setiap bulan pada minggu kedua.', '2026-04-29 10:18:47');

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

--
-- Dumping data for table `kelembagaan_programs`
--

INSERT INTO `kelembagaan_programs` (`id`, `page`, `title`, `description`, `order_no`, `created_at`) VALUES
(2, 'lpmk', 'Pelatihan Kewirausahaan dan Digital Marketing', 'Program pelatihan rutin selama 3 bulan bagi pelaku UMKM warga sekitar untuk meningkatkan keterampilan pemasaran secara online', 2, '2026-04-29 09:33:54'),
(3, 'bkm', 'Unit Pengelola Keuangan (UPK)', 'Pemberian pinjaman modal usaha untuk kelompok swadaya masyarakat (KSM), edukasi literasi keuangan, dan pengelolaan dana bergulir.', 1, '2026-04-29 09:47:34'),
(4, 'pkk', 'PKBN', 'Pembinaan Kesadaran Bela Negara untuk meningkatkan rasa nasionalisme keluarga.', 1, '2026-04-29 10:18:48'),
(5, 'pkk', 'PAREDI', 'Pola Asuh Anak dan Remaja di Era Digital (Program Transisi).', 2, '2026-04-29 10:18:48'),
(6, 'pkk', 'UP2K', 'Upaya Peningkatan Pendapatan Keluarga melalui UMKM binaan PKK.', 3, '2026-04-29 10:18:48'),
(7, 'pkk', 'HATINYA PKK', 'Halaman Asri Teratur Indah dan Nyaman sebagai ketahanan pangan keluarga.', 4, '2026-04-29 10:18:48');

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
(30, 'lpmk', 'Contoh Nama ', 'Ketua ', '08956746289', 1, '2026-04-29 09:28:46'),
(31, 'bkm', 'pak budi ', 'ketua ', '083474647489', 1, '2026-04-29 09:44:19'),
(33, 'pkk', 'Ny. Sri Rahayu', 'Ketua TP PKK', '0812-xxxx-xxxx', 1, '2026-04-29 10:18:47'),
(34, 'pkk', 'Ny. Siti Aminah', 'Sekretaris', '0813-xxxx-xxxx', 2, '2026-04-29 10:18:47'),
(35, 'pkk', 'Ny. Wahyuni', 'Bendahara', '0814-xxxx-xxxx', 3, '2026-04-29 10:18:47'),
(36, 'pkk', 'Ny. Kartika', 'Ketua Pokja I', '0815-xxxx-xxxx', 4, '2026-04-29 10:18:47');

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

--
-- Dumping data for table `kelembagaan_units`
--

INSERT INTO `kelembagaan_units` (`id`, `page`, `title`, `description`, `order_no`, `created_at`) VALUES
(2, 'lpmk', 'Bidang Pembangunan dan Ekonomi', 'Bertugas merencanakan, melaksanakan, dan mengawasi kegiatan pembangunan fisik serta pemberdayaan ekonomi masyarakat kelurahan, termasuk pembinaan UMKM.', 0, '2026-04-29 09:32:25'),
(3, 'bkm', 'Unit Pengelola Sosial (UPS)', 'Santunan anak yatim/piatu, bantuan pendidikan (beasiswa), perbaikan Rumah Tidak Layak Huni (RTLH), serta pelatihan keterampilan kerja.', 1, '2026-04-29 09:46:41'),
(4, 'pkk', 'Pokja I', 'Bidang Penghayatan dan Pengamalan Pancasila & Gotong Royong.', 1, '2026-04-29 10:18:47'),
(5, 'pkk', 'Pokja II', 'Bidang Pendidikan dan Keterampilan & Pengembangan Kehidupan Berkoperasi.', 2, '2026-04-29 10:18:47'),
(6, 'pkk', 'Pokja III', 'Bidang Pangan, Sandang, Perumahan dan Tata Laksana Rumah Tangga.', 3, '2026-04-29 10:18:48'),
(7, 'pkk', 'Pokja IV', 'Bidang Kesehatan, Kelestarian Lingkungan Hidup & Perencanaan Sehat.', 4, '2026-04-29 10:18:48');

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
  `maps_url` varchar(500) DEFAULT NULL,
  `urutan` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kontak_darurat`
--

INSERT INTO `kontak_darurat` (`id`, `label`, `nama`, `nomor`, `keterangan`, `maps_url`, `urutan`, `is_active`, `created_at`, `updated_at`) VALUES
(2, 'Ambulans', 'NU JATISARI', '0895-3595-80756 ', 'Ambulans 24 jam ', NULL, 1, 1, '2026-04-27 06:31:53', '2026-04-29 06:17:42'),
(4, 'Puskemas Terdekat ', 'Puskesmas Ngaliyan', '(024) 7608795 ', 'Puskemas 24 jam ', NULL, 3, 1, '2026-04-27 06:46:28', '2026-04-28 06:00:35'),
(5, 'Rumah Sakit Rujukan ', 'RS Permata Medika Semarang ', '024-764-38070 ', 'Rumah Sakit Rujukan 24 jam ', NULL, 2, 1, '2026-04-27 06:50:25', '2026-04-29 06:17:42'),
(6, 'Satgas Kesehatan Kelurahan ', 'Satgas Kesehatan ', '0895-3768-60088 ', 'satgas kesehatan kota ', NULL, 4, 1, '2026-04-27 06:54:17', '2026-04-28 05:48:00');

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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kontak_pariwisata`
--

INSERT INTO `kontak_pariwisata` (`id`, `pengelola_destinasi`, `kontak_pengelola`, `instagram`, `facebook`, `nomor_penting`, `informasi_singkat`, `updated_at`, `email`) VALUES
(1, 'Kelurahan Kedungpane', '(024) 7711292', '@kelurahankedungpane', 'Kelurahan Kedungpane', '112', 'Menjelajahi harmoni alam dan kekayaan budaya lokal di wilayah Kedungpane. ', '2026-04-29 08:34:50', 'kelurahankedungpane@yahoo.com');

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
(1, 'Nama Lurah, S.IP., M.Si.', 'Kepala Kelurahan Kedungpane', 'img/1776997611_1776959439_lurah.png', '<p>Assalamualaikum Warahmatullahi Wabarakaatuh,</p><p>Puji syukur kita panjatkan kehadirat Tuhan Yang Maha Esa. Selamat datang di portal resmi Kelurahan Kedungpane, Kecamatan Mijen, Kota Semarang. Website ini merupakan wujud nyata komitmen kami dalam mengimplementasikan tata kelola pemerintahan yang baik <i>(Good Governance)</i> serta mewujudkan transparansi informasi publik.</p><p>Melalui platform ini, kami berharap warga dapat dengan mudah mengakses berbagai informasi terkait program pembangunan, pemberdayaan masyarakat, serta prosedur layanan administrasi kependudukan. Mari bersama-sama kita bangun Kelurahan Kedungpane menjadi lingkungan yang aman, sejahtera, dan berbudaya.</p>', 124501, 13, 43, 58306, '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3959.7304761528967!2d110.3353831740375!3d-7.040928068986028!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e708a7326741db1%3A0x82046ec7dad6f0b0!2sKantor%20Kelurahan%20Kedungpane!5e0!3m2!1sid!2sid!4v1777224844082!5m2!1sid!2sid\" width=\"600\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', 'Kelurahan Bambankerep ', 'Kel. Jatibarang', ' Kelurahan Kandri', 'Kelurahan Pesantren', 1961, 4023, 4029, '', 10, 21, 1);

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
(1, 'Kelurahan Kedungpane memiliki kombinasi wisata alam di sepanjang waduk, situs budaya tradisional, dan berbagai spot edukatif yang populer bagi pelancong lokal. ', NULL, 'Mewujudkan pariwisata berbasis komunitas yang mandiri dan berkelanjutan. ', 'Pelestarian aset budaya, pengembangan infrastruktur wisata terpadu, dan pemberdayaan ekonomi kreatif UMKM. ', '2026-04-27 05:50:23');

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
(2, 'Mitigasi Konflik dan Lingkungan', 'masyrakat dan satpam ', '19.30-00.00/setiap malam minggu ', 'Balai RW 05 ', 'Wadah untuk menyelesaikan masalah warga (seperti perselisihan antar tetangga) melalui jalan musyawarah atau restorative justice tingkat kelurahan sebelum dibawa ke jalur hukum.', 1, '2026-04-29 09:52:49', '2026-04-29 09:52:49');

-- --------------------------------------------------------

--
-- Table structure for table `program_pendidikan`
--

CREATE TABLE `program_pendidikan` (
  `id` int(11) NOT NULL,
  `nama_program` varchar(255) NOT NULL,
  `target_peserta` varchar(150) DEFAULT NULL,
  `waktu` varchar(100) DEFAULT NULL,
  `lokasi` varchar(150) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `regulasi`
--

CREATE TABLE `regulasi` (
  `id` int(11) NOT NULL,
  `kategori` varchar(100) NOT NULL,
  `judul_dokumen` varchar(255) NOT NULL,
  `file_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `regulasi`
--

INSERT INTO `regulasi` (`id`, `kategori`, `judul_dokumen`, `file_url`) VALUES
(2, 'dasar_hukum', 'Keputusan lurah kedungpane.', 'https://jdih.semarangkota.go.id/assets/public/data_dokumen/Keputusan_lurah_kedungpane.pdf');

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
(1, 'lkk', 'lembaga aparatur ', '0', '-', 'dsfdsfsdsdasdsa');

-- --------------------------------------------------------

--
-- Table structure for table `sekolah_pendidikan`
--

CREATE TABLE `sekolah_pendidikan` (
  `id` int(11) NOT NULL,
  `jenjang` enum('SD','SMP','SMA/SMK') NOT NULL DEFAULT 'SD',
  `nama_sekolah` varchar(255) NOT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `data_map` varchar(500) DEFAULT NULL,
  `maps_url` varchar(500) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `urutan` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sekolah_pendidikan`
--

INSERT INTO `sekolah_pendidikan` (`id`, `jenjang`, `nama_sekolah`, `alamat`, `data_map`, `maps_url`, `is_active`, `urutan`, `created_at`) VALUES
(1, 'SD', 'SD Negeri Kedungpane 01', 'Jl. Dawung RT 04 / RW 03', ' SD Negeri Kedungpane 01, Jl. Dawung, Kedungpane, Kec. Mijen, Kota Semarang, Jawa Tengah 50211', NULL, 1, 0, '2026-04-27 06:41:58'),
(2, 'SD', 'SD Negeri Kedungpane 02', 'Jl. Untung Surapati', 'SD Negeri Kedungpane 02, Jl. Untung Surapati, Semarang, Jawa Tengah, Indonesia', NULL, 1, 1, '2026-04-27 06:41:58'),
(3, 'SMP', 'SMP Islam Al Azhar 29', 'Jl. RM Hadisoebeno Sosrowardoyo BSB, Kedungpane', 'SMP Islam Al Azhar 29 Semarang, Jl. RM Hadisoebeno Sosrowardoyo BSB, Kedungpane, Mijen, Semarang', NULL, 1, 1, '2026-04-27 06:41:58'),
(4, 'SMP', 'SMP Negeri 23 Semarang', 'Jl. RM. Hadi Soebeno (Zonasi Utama Kedungpane)', 'SMP Negeri 23 Semarang, Jl. RM. Hadi Soebeno, Mijen, Semarang', NULL, 1, 0, '2026-04-27 06:41:58'),
(5, 'SMA/SMK', 'SMA Islam Al Azhar 16', 'Jl. RM Hadi Soebeno S Komplek BSB, Kedungpane', 'SMA Islam Al Azhar 16 Semarang, Jl. RM Hadi Soebeno S Komplek BSB, Kedungpane, Mijen, Semarang', NULL, 1, 1, '2026-04-27 06:41:58'),
(6, 'SMA/SMK', 'SMK Palapa', 'Jl. Untung Surapati, Kelurahan Kedungpane', 'SMK Palapa Semarang, Jl. Untung Surapati, Kedungpane, Mijen, Semarang', NULL, 1, 0, '2026-04-27 06:41:58');

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
  `gmaps_url` varchar(500) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `umkm`
--

INSERT INTO `umkm` (`id`, `nama`, `kategori`, `pengelola`, `kontak`, `deskripsi`, `alamat`, `gmaps_url`, `latitude`, `longitude`, `foto`, `is_verified`, `is_active`, `created_at`, `updated_at`) VALUES
(4, 'Soto Ayam pak Run', 'kuliner', 'pak run', '00000', '', 'Jl. Dawung RT 04 / RW 03', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d253412.34013595167!2d110.36280539697235!3d-7.059958873159553!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e708a6fdcb60cf5%3A0x7930d94dcee0814b!2sSoto%20ayam%20Pak%20Run!5e0!3m2!1sid!2sid!4v1777446573249!5m2!1sid!2sid', NULL, NULL, NULL, 1, 1, '2026-04-29 07:08:58', '2026-04-29 09:18:07');

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
(1, 'admin', '001132e60c304ea9df4a0f51fcc908ae', 'Admin', '-', '2026-04-24', 'IT STAFF', 'admin_1777019320.png', 'Administrator', NULL, '2026-04-29 16:51:04', 1, '2026-04-16 02:51:26', '2026-04-29 09:51:04'),
(3, 'raff', '$2y$10$eTSliT6F.1Lzh4Ko1Ps3E.0nmm9ZZeKKGwKFq/092tqy8sE/rbZ2a', 'Editor', '', '2026-04-08', '', '', 'rafly super ', NULL, '2026-04-29 16:13:10', 1, '2026-04-29 04:13:22', '2026-04-29 09:13:39');

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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `nama` varchar(150) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `jam_buka` varchar(100) DEFAULT NULL,
  `harga_tiket` varchar(100) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `maps_url` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wisata_kuliner`
--

INSERT INTO `wisata_kuliner` (`id`, `jenis`, `contoh`, `lokasi_catatan`, `urutan`, `is_active`, `created_at`, `updated_at`, `nama`, `deskripsi`, `alamat`, `jam_buka`, `harga_tiket`, `gambar`, `maps_url`) VALUES
(1, '', NULL, NULL, 0, 1, '2026-04-29 08:25:01', '2026-04-29 09:00:36', 'Joglo Langit', 'Tempat ini merupakan angkringan modern dengan konsep bangunan joglo yang megah dan terletak tepat di Kelurahan Kedungpane. Suasananya sangat cocok untuk bersantai di sore atau malam hari sambil menikmati pemandangan dari ketinggian.\r\n\r\nMenyediakan menu khas angkringan yang dikemas lebih eksklusif dan higienis.\r\n\r\nMemiliki area duduk yang luas dengan arsitektur Jawa yang kental.\r\n\r\nMenjadi salah satu titik favorit untuk menikmati sunset di area Semarang Barat.', 'Jl. Gg. Bharadaksa II Jl. Untung Suropati, Kedungpane, Kec. Mijen, Kota Semarang, Jawa Tengah 50211', '08.00', '25.000-90.000', 'https://lh3.googleusercontent.com/gps-cs-s/APNQkAGFxo61BCx8-Yi3Q6_D3M5Y7m66E8CVYhTqzj2YymtZYlXjm5B9ksa-32mi5wj_zcPXhQ3KUBnxvBJFtJKPXLXqwoG0_JSl62-x0hzNFziJu00FHnHnhY0mno_T6Tdcj7vOqhim=w408-h306-k-no', 'Jl. Gg. Bharadaksa II Jl. Untung Suropati, Kedungpane, Kec. Mijen, Kota Semarang, Jawa Tengah 50211');

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
-- Indexes for table `program_pendidikan`
--
ALTER TABLE `program_pendidikan`
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
-- Indexes for table `sekolah_pendidikan`
--
ALTER TABLE `sekolah_pendidikan`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `berita`
--
ALTER TABLE `berita`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `destinasi_wisata`
--
ALTER TABLE `destinasi_wisata`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `galeri`
--
ALTER TABLE `galeri`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `informasi_publik`
--
ALTER TABLE `informasi_publik`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jadwal_layanan`
--
ALTER TABLE `jadwal_layanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `kelembagaan_pages`
--
ALTER TABLE `kelembagaan_pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `kelembagaan_programs`
--
ALTER TABLE `kelembagaan_programs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `kelembagaan_staff`
--
ALTER TABLE `kelembagaan_staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `kelembagaan_units`
--
ALTER TABLE `kelembagaan_units`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `kontak_darurat`
--
ALTER TABLE `kontak_darurat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `program_pendidikan`
--
ALTER TABLE `program_pendidikan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `regulasi`
--
ALTER TABLE `regulasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sdm_kelurahan`
--
ALTER TABLE `sdm_kelurahan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sekolah_pendidikan`
--
ALTER TABLE `sekolah_pendidikan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `wisata_kuliner`
--
ALTER TABLE `wisata_kuliner`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
