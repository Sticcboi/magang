<?php
// Konfigurasi Koneksi Database
$host = "127.0.0.1";
$user = "root";
$pass = "";
$db   = "db_kedungpane";

$conn = mysqli_connect($host, $user, $pass, $db);

// Cek koneksi
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>