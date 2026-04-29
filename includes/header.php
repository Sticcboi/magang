<?php
include __DIR__ . '/../db_connect.php'; // Panggil database dulu
?>
<!doctype html>
<html lang="id">
  <head>
<?php 
// Tentukan base URL untuk favicon
$base_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']);
// Hilangkan /includes dari path
$base_url = str_replace('/includes', '', $base_url);
?>
<link rel="icon" type="image/x-icon" href="<?php echo $base_url; ?>/favicon.ico?v=1.0">

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo isset($page_title) ? $page_title . ' | Kelurahan Kedungpane' : 'Kelurahan Kedungpane'; ?></title>

    <!-- Bootstrap 5 -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="style.css" />
  </head>
  <body>
<?php include __DIR__ . '/../navbar.php'; ?>
