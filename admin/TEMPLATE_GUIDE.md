# Admin Template System - Panduan Penggunaan

## File-file Komponen

### 1. **admin_styles.css** - Stylesheet Terpusat
File CSS yang berisi styling untuk semua halaman admin. Include di semua halaman:
```php
<link rel="stylesheet" href="admin_styles.css">
```

### 2. **admin_header.php** - Header Include
Membuka struktur HTML admin dengan navbar dan sidebar otomatis. 
**Cara penggunaan:**
```php
<?php
require_once __DIR__ . '/auth.php';

// Set variabel sebelum include header
$page_title = 'Nama Halaman';
$current_page = 'admin_dashboard'; // (admin_umkm, admin_wisata, dll)

include 'admin_header.php';
?>
```

**Variabel yang diperlukan:**
- `$page_title` - Judul halaman yang ditampilkan di navbar
- `$current_page` - ID halaman untuk highlight sidebar (dari list di `admin_sidebar.php`)

###  3. **admin_sidebar.php** - Sidebar Dinamis
Include otomatis di `admin_header.php`. Menggenerate menu sidebar berdasarkan `$current_page`.

### 4. **admin_footer.php** - Footer Include
Menutup strukturHTML dan menambahkan script JavaScript. Include di akhir setiap halaman:
```php
<?php include 'admin_footer.php'; ?>
```

### 5. **auth.php** - Authentication Include
Include pertama kali di setiap halaman admin untuk:
- Memeriksa session login
- Include db_connect.php
- Menyediakan helper CSRF: `csrf_token()`, `csrf_field()`, `verify_csrf()`

## Struktur Template Minimal

```php
<?php
// 1. Include auth (validasi login + koneksi DB)
require_once __DIR__ . '/auth.php';

// 2. Logic halaman (query database, dll)
$data = []...

// 3. Set variabel template
$page_title = 'Nama Halaman';
$current_page = 'admin_dashboard';

// 4. Include header (buka HTML, sidebar, navbar)
include 'admin_header.php';
?>

<!-- 5.Konten halaman di sini -->
<div class="page-header">
    <h1>Judul</h1>
    <p>Deskripsi</p>
</div>

<div class="card-table">
    <!-- Content -->
</div>

<?php 
// 6. Include footer (tutup HTML + script)
include 'admin_footer.php'; 
?>
```

## Sidebar Links
Daftar halaman yang tersedia (dari `admin_sidebar.php`):
- `admin_dashboard` - Dashboard
- `admin_berita` - Berita & Artikel
- `admin_umkm` - UMKM Kedungpane
- `admin_wisata` - Wisata Lokal
- `admin_event` - Event Kelurahan
- `admin_kesehatan` - Fasilitas Kesehatan

## CSS Classes Umum

### Buttons
- `.btn-maroon` - Tombol warna maroon (hover effect included)
- `.btn-action` - Tombol kecil action table

### Cards
- `.stat-card` - Kartu statistik dengan icon
- `.card-table` - Kartu tabel data
- `.card-form` - Kartu form

### Badges
- `.badge-published` - Status published/published
- `.badge-draft` - Status draft
- `.badge-status` - Status badge generic

### Page Elements
- `.page-header` - Header halaman dengan h1 dan deskripsi
- `.content-padding` - Padding konten utama

## Halaman yang Sudah Diupdate
✅ admin_dashboard.php
✅ admin_umkm.php
✅ admin_wisata.php
✅ admin_event.php
✅ admin_kesehatan.php

## Halaman yang Perlu Diupdate
Masih menggunakan struktur lama:
- admin_berita.php
- admin_berita_tambah.php
- admin_berita_edit.php

## Cleanup Notes
- **admin_dashboard_new.php** — DEPRECATED, sudah ter-merge ke admin_dashboard.php. Bisa dihapus.
- **admin_berita_tambah.php** — Sudah diupdate menggunakan template terpusat (auth.php, admin_header.php, admin_footer.php)
