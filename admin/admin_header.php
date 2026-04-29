<?php
/**
 * Admin Header Include
 * Usage: <?php $current_page = 'admin_dashboard'; include 'admin_header.php'; ?>
 */
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <?php 
// Tentukan base URL untuk favicon
$base_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']);
// Hilangkan /includes atau /admin dari path
$base_url = str_replace(array('/includes', '/admin'), '', $base_url);
?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Admin Panel' ?> - SIM Kelurahan Kedungpane</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="admin_styles.css">
    <link rel="icon" type="image/x-icon" href="<?php echo $base_url; ?>/favicon.ico?v=1.0">
</head>
<body>
    <?php include 'admin_sidebar.php'; ?>
    <div id="sidebarOverlay"></div>

    <div id="content">
        <nav class="top-navbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-maroon d-md-none me-2" id="sidebarToggle" type="button">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div class="fw-bold text-muted">
                    <?= $page_title ?? 'Admin Panel' ?>
                </div>
            </div>
            <div class="user-profile">
                <div class="text-end d-none d-md-block">
                    <div class="small fw-bold text-maroon"><?= $_SESSION['nama'] ?? 'User'; ?></div>
                    <div class="small text-muted" style="font-size: 10px;"><?= strtoupper($_SESSION['role'] ?? 'Admin'); ?></div>
                </div>
                <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['nama'] ?? 'User') ?>&background=800000&color=fff" alt="User Avatar">
            </div>
        </nav>
        
        <div class="content-padding">
