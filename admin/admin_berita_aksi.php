<?php
require_once __DIR__ . '/auth.php';

// =======================================================
// AUTO-FIX: Memperbarui Aturan Kategori di Database
// =======================================================
// Perintah ini akan memaksa database untuk mengenali dan menerima kategori baru.
mysqli_query($conn, "ALTER TABLE berita MODIFY COLUMN kategori ENUM('umum', 'kesehatan', 'pembangunan', 'keamanan', 'pendidikan', 'ekonomi') DEFAULT 'umum'");
// =======================================================

// KODE CERDAS: Bikin folder otomatis jika belum ada
$target_dir = __DIR__ . "/../uploads/berita/";
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0777, true);
}

// Function untuk handle image upload dengan validasi yang lebih bersahabat
function handle_image_upload($file) {
    global $target_dir;
    $maxSize = 2 * 1024 * 1024; // Maksimal 2MB
    
    // Alih-alih die() dan halaman mati/putih, kita tendang balik ke halaman admin membawa alert merah
    if ($file['size'] > $maxSize) {
        header("location: admin_berita.php?pesan=gagal_ukuran");
        exit;
    }
    
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    $allowed_mimes = ['image/jpeg' => 'jpg', 'image/png' => 'png'];
    
    if (!isset($allowed_mimes[$mime])) {
        header("location: admin_berita.php?pesan=gagal_format");
        exit;
    }
    
    $ext = $allowed_mimes[$mime];
    $filename = time() . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
    
    if (move_uploaded_file($file['tmp_name'], $target_dir . $filename)) {
        return $filename;
    }
    return null;
}

// Aksi Tambah Berita
if (isset($_POST['tambah'])) {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        die("CSRF token tidak valid");
    }
    
    $judul = $_POST['judul'];
    $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $judul)) . '-' . time();
    $kategori = strtolower(trim($_POST['kategori'])); // Memastikan format string bersih
    $penulis_id = $_SESSION['id_user'] ?? 1;
    
    $status_publikasi = $_POST['status_publikasi'];
    if ($status_publikasi == '1') {
        $is_published = 1;
        $tanggal = date('Y-m-d');
    } elseif ($status_publikasi == 'terjadwal') {
        $is_published = 1;
        $tanggal = $_POST['tanggal'];
    } else {
        $is_published = 0;
        $tanggal = date('Y-m-d');
    }
    
    $isi_raw = $_POST['isi'];
    $ringkasan_teks = strip_tags($isi_raw);
    $ringkasan_potong = mb_substr($ringkasan_teks, 0, 150);
    if (mb_strlen($ringkasan_teks) > 150) {
        $ringkasan_potong .= '...';
    }
    $ringkasan = $ringkasan_potong;
    
    // Process upload
    $gambar = "";
    if (isset($_FILES['gambar']) && $_FILES['gambar']['name'] != "") {
        $gambar = handle_image_upload($_FILES['gambar']);
    }
    
    $stmt = mysqli_prepare($conn, "INSERT INTO berita (judul, slug, isi, ringkasan, gambar, kategori, penulis_id, is_published, tanggal) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
              
    mysqli_stmt_bind_param($stmt, 'ssssssiis', $judul, $slug, $isi_raw, $ringkasan, $gambar, $kategori, $penulis_id, $is_published, $tanggal);
    
    if (mysqli_stmt_execute($stmt)) {
        header("location: admin_berita.php?pesan=berhasil_tambah");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Aksi Edit Berita
if (isset($_POST['edit'])) {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        die("CSRF token tidak valid");
    }
    
    $id = (int)$_POST['id'];
    $judul = $_POST['judul'];
    $kategori = strtolower(trim($_POST['kategori'])); // Memastikan format string bersih
    
    $status_publikasi = $_POST['status_publikasi'];
    if ($status_publikasi == '1') {
        $is_published = 1;
        $tanggal = date('Y-m-d');
    } elseif ($status_publikasi == 'terjadwal') {
        $is_published = 1;
        $tanggal = $_POST['tanggal'];
    } else {
        $is_published = 0;
        $tanggal = date('Y-m-d');
    }
    
    $isi_raw = $_POST['isi'];
    $ringkasan_teks = strip_tags($isi_raw);
    $ringkasan_potong = mb_substr($ringkasan_teks, 0, 150);
    if (mb_strlen($ringkasan_teks) > 150) {
        $ringkasan_potong .= '...';
    }
    $ringkasan = $ringkasan_potong;
    
    // Handle image
    $gambar = null;
    if (isset($_FILES['gambar']) && $_FILES['gambar']['name'] != "") {
        $gambar = handle_image_upload($_FILES['gambar']);
        
        // Delete old image jika foto baru berhasil diproses
        $stmt = mysqli_prepare($conn, "SELECT gambar FROM berita WHERE id = ?");
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $data_lama = mysqli_fetch_assoc($result);
        
        if ($data_lama['gambar'] && file_exists($target_dir . $data_lama['gambar'])) {
            unlink($target_dir . $data_lama['gambar']);
        }
        
        $stmt = mysqli_prepare($conn, "UPDATE berita SET judul=?, isi=?, ringkasan=?, gambar=?, kategori=?, is_published=?, tanggal=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, 'sssssisi', $judul, $isi_raw, $ringkasan, $gambar, $kategori, $is_published, $tanggal, $id);
    } else {
        $stmt = mysqli_prepare($conn, "UPDATE berita SET judul=?, isi=?, ringkasan=?, kategori=?, is_published=?, tanggal=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, 'ssssisi', $judul, $isi_raw, $ringkasan, $kategori, $is_published, $tanggal, $id);
    }
    
    if (mysqli_stmt_execute($stmt)) {
        header("location: admin_berita.php?pesan=berhasil_edit");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Aksi Pin (Sorotan Utama) Berita
if (isset($_POST['pin'])) {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        die("CSRF token tidak valid");
    }
    
    $id = (int)$_POST['pin'];
    
    $stmt = mysqli_prepare($conn, "SELECT is_pinned FROM berita WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);
    
    $status_baru = ($data['is_pinned'] == 1) ? 0 : 1;
    
    $stmt = mysqli_prepare($conn, "UPDATE berita SET is_pinned = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'ii', $status_baru, $id);
    mysqli_stmt_execute($stmt);
    
    header("location: admin_berita.php?pesan=berhasil_pin");
    exit;
}

// Aksi Hapus Berita Permanen
if (isset($_POST['hapus'])) {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        die("CSRF token tidak valid");
    }
    
    $id = (int)$_POST['hapus'];
    
    $stmt = mysqli_prepare($conn, "SELECT gambar FROM berita WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);
    
    if ($data['gambar'] && file_exists($target_dir . $data['gambar'])) {
        unlink($target_dir . $data['gambar']);
    }
    
    $stmt = mysqli_prepare($conn, "DELETE FROM berita WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    
    header("location: admin_berita.php?pesan=berhasil_hapus");
    exit;
}
?>