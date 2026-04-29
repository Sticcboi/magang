<?php
/**
 * TDD: Test Mobile Responsive Admin Panel
 * Menguji kompatibilitas mobile pada seluruh situs admin
 * Jalankan: http://localhost/Magang/admin/tests/test_mobile_responsive.php
 */

$pass = 0;
$fail = 0;
$results = [];

function test($label, $condition, &$pass, &$fail, &$results) {
    if ($condition) {
        $pass++;
        $results[] = ['status' => 'PASS', 'label' => $label];
    } else {
        $fail++;
        $results[] = ['status' => 'FAIL', 'label' => $label];
    }
}

// =============================================
// 1. TEST: Meta Viewport tag exists in admin_header.php
// =============================================
$header_content = file_get_contents(__DIR__ . '/../admin_header.php');
test(
    'admin_header.php memiliki meta viewport',
    strpos($header_content, 'viewport') !== false && strpos($header_content, 'width=device-width') !== false,
    $pass, $fail, $results
);

// =============================================
// 2. TEST: Sidebar toggle button exists (d-md-none)
// =============================================
test(
    'admin_header.php memiliki tombol toggle sidebar (d-md-none)',
    strpos($header_content, 'sidebarToggle') !== false && strpos($header_content, 'd-md-none') !== false,
    $pass, $fail, $results
);

// =============================================
// 3. TEST: Sidebar overlay element exists
// =============================================
test(
    'admin_header.php memiliki elemen sidebarOverlay',
    strpos($header_content, 'sidebarOverlay') !== false,
    $pass, $fail, $results
);

// =============================================
// 4. TEST: admin_styles.css has mobile breakpoints
// =============================================
$css_content = file_get_contents(__DIR__ . '/../admin_styles.css');

test(
    'CSS memiliki breakpoint tablet (max-width: 991px)',
    strpos($css_content, 'max-width: 991px') !== false,
    $pass, $fail, $results
);

test(
    'CSS memiliki breakpoint mobile (max-width: 576px)',
    strpos($css_content, 'max-width: 576px') !== false,
    $pass, $fail, $results
);

// =============================================
// 5. TEST: Sidebar uses transform for slide (not left offset)
// =============================================
test(
    'Sidebar menggunakan transform translateX untuk animasi slide',
    strpos($css_content, 'translateX(-100%)') !== false && strpos($css_content, 'translateX(0)') !== false,
    $pass, $fail, $results
);

// =============================================
// 6. TEST: Content does NOT push when sidebar opens on mobile
// =============================================
// Cari bahwa body.sidebar-open #content tetap margin-left: 0
preg_match('/body\.sidebar-open\s+#content\s*\{[^}]*margin-left:\s*0/s', $css_content, $matches);
test(
    'Konten tidak bergeser saat sidebar terbuka di mobile',
    !empty($matches),
    $pass, $fail, $results
);

// =============================================
// 7. TEST: Modal padding-left reset on mobile
// =============================================
test(
    'Modal tidak ter-offset oleh sidebar di mobile (padding-left: 0)',
    strpos($css_content, 'padding-left: 0 !important') !== false,
    $pass, $fail, $results
);

// =============================================
// 8. TEST: Nav tabs scrollable on mobile
// =============================================
test(
    'Nav tabs bisa di-scroll horizontal di mobile',
    strpos($css_content, 'flex-wrap: nowrap') !== false && strpos($css_content, 'overflow-x: auto') !== false,
    $pass, $fail, $results
);

// =============================================
// 9. TEST: Card body padding reduces on mobile
// =============================================
test(
    'Card body padding berkurang di layar kecil',
    preg_match('/\.card-body\s*\{[^}]*padding:\s*16px\s+12px/s', $css_content) === 1,
    $pass, $fail, $results
);

// =============================================
// 10. TEST: Table responsive utility exists
// =============================================
test(
    'CSS memiliki .table-responsive dengan overflow-x: auto',
    preg_match('/\.table-responsive\s*\{[^}]*overflow-x:\s*auto/s', $css_content) === 1,
    $pass, $fail, $results
);

// =============================================
// 11. TEST: SweetAlert2 compact on mobile
// =============================================
test(
    'SweetAlert2 popup responsive di mobile (width: 90%)',
    strpos($css_content, '.swal2-popup') !== false && strpos($css_content, 'width: 90%') !== false,
    $pass, $fail, $results
);

// =============================================
// 12. TEST: admin_footer.php has sidebar toggle JS
// =============================================
$footer_content = file_get_contents(__DIR__ . '/../admin_footer.php');
test(
    'admin_footer.php memiliki fungsi openSidebar/closeSidebar',
    strpos($footer_content, 'openSidebar') !== false && strpos($footer_content, 'closeSidebar') !== false,
    $pass, $fail, $results
);

// =============================================
// 13. TEST: Semua halaman admin utama menggunakan admin_header.php
// =============================================
$admin_pages = [
    'admin_halaman.php', 'admin_profil.php', 'admin_pendidikan.php',
    'admin_pariwisata.php', 'admin_umkm.php', 'admin_kesehatan.php',
    'admin_kamtibmas.php', 'admin_users.php', 'admin_sdm.php',
    'admin_struktur.php', 'admin_kelembagaan_lpmk.php', 'admin_kelembagaan_bkm.php',
    'admin_layanan_nav.php', 'admin-informasi-publik.php'
];

$all_include_header = true;
$missing_pages = [];
foreach ($admin_pages as $page) {
    $file_path = __DIR__ . '/../' . $page;
    if (file_exists($file_path)) {
        $content = file_get_contents($file_path);
        if (strpos($content, 'admin_header.php') === false) {
            $all_include_header = false;
            $missing_pages[] = $page;
        }
    }
}
test(
    'Semua halaman admin meng-include admin_header.php' . (!empty($missing_pages) ? ' (missing: ' . implode(', ', $missing_pages) . ')' : ''),
    $all_include_header,
    $pass, $fail, $results
);

// =============================================
// 14. TEST: Semua halaman admin utama menggunakan admin_footer.php
// =============================================
$all_include_footer = true;
$missing_footer = [];
foreach ($admin_pages as $page) {
    $file_path = __DIR__ . '/../' . $page;
    if (file_exists($file_path)) {
        $content = file_get_contents($file_path);
        if (strpos($content, 'admin_footer.php') === false) {
            $all_include_footer = false;
            $missing_footer[] = $page;
        }
    }
}
test(
    'Semua halaman admin meng-include admin_footer.php' . (!empty($missing_footer) ? ' (missing: ' . implode(', ', $missing_footer) . ')' : ''),
    $all_include_footer,
    $pass, $fail, $results
);

// =============================================
// 15. TEST: No inline "confirm()" left (all replaced with SweetAlert)
// =============================================
$pages_with_confirm = [];
foreach ($admin_pages as $page) {
    $file_path = __DIR__ . '/../' . $page;
    if (file_exists($file_path)) {
        $content = file_get_contents($file_path);
        if (preg_match('/onclick\s*=\s*["\']return\s+confirm\(/', $content)) {
            $pages_with_confirm[] = $page;
        }
    }
}
test(
    'Tidak ada sisa confirm() bawaan browser di halaman admin' . (!empty($pages_with_confirm) ? ' (found in: ' . implode(', ', $pages_with_confirm) . ')' : ''),
    empty($pages_with_confirm),
    $pass, $fail, $results
);

// =============================================
// 16. TEST: Modal full-width rule on mobile
// =============================================
test(
    'Modal dialog full-width di mobile (max-width: calc)',
    strpos($css_content, 'max-width: calc(100% - 1rem)') !== false,
    $pass, $fail, $results
);

// =============================================
// OUTPUT RESULTS
// =============================================
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TDD: Mobile Responsive Admin</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #1a1a2e; color: #fff; padding: 30px; }
        .container { max-width: 700px; margin: 0 auto; }
        h1 { text-align: center; margin-bottom: 8px; font-size: 1.5rem; }
        .subtitle { text-align: center; color: #888; margin-bottom: 30px; font-size: 0.9rem; }
        .summary { display: flex; justify-content: center; gap: 30px; margin-bottom: 25px; }
        .summary-box { text-align: center; padding: 15px 25px; border-radius: 12px; }
        .summary-box.pass { background: rgba(46, 204, 113, 0.15); border: 1px solid #2ecc71; }
        .summary-box.fail { background: rgba(231, 76, 60, 0.15); border: 1px solid #e74c3c; }
        .summary-box .count { font-size: 2rem; font-weight: bold; }
        .summary-box.pass .count { color: #2ecc71; }
        .summary-box.fail .count { color: #e74c3c; }
        .test-item { padding: 12px 16px; margin-bottom: 6px; border-radius: 8px; display: flex; align-items: center; gap: 12px; font-size: 0.88rem; }
        .test-item.pass { background: rgba(46, 204, 113, 0.08); border-left: 4px solid #2ecc71; }
        .test-item.fail { background: rgba(231, 76, 60, 0.1); border-left: 4px solid #e74c3c; }
        .badge { padding: 3px 10px; border-radius: 50px; font-size: 0.7rem; font-weight: bold; text-transform: uppercase; }
        .badge.pass { background: #2ecc71; color: #fff; }
        .badge.fail { background: #e74c3c; color: #fff; }
        .verdict { text-align: center; margin-top: 30px; padding: 20px; border-radius: 12px; font-size: 1.2rem; font-weight: bold; }
        .verdict.pass { background: rgba(46, 204, 113, 0.15); color: #2ecc71; border: 2px solid #2ecc71; }
        .verdict.fail { background: rgba(231, 76, 60, 0.15); color: #e74c3c; border: 2px solid #e74c3c; }
    </style>
</head>
<body>
    <div class="container">
        <h1>📱 TDD: Mobile Responsive Admin</h1>
        <p class="subtitle">Pengujian kompatibilitas mobile seluruh panel admin</p>

        <div class="summary">
            <div class="summary-box pass">
                <div class="count"><?= $pass ?></div>
                <div>Passed</div>
            </div>
            <div class="summary-box fail">
                <div class="count"><?= $fail ?></div>
                <div>Failed</div>
            </div>
        </div>

        <?php foreach ($results as $r): ?>
        <div class="test-item <?= strtolower($r['status']) ?>">
            <span class="badge <?= strtolower($r['status']) ?>"><?= $r['status'] ?></span>
            <span><?= htmlspecialchars($r['label']) ?></span>
        </div>
        <?php endforeach; ?>

        <div class="verdict <?= $fail === 0 ? 'pass' : 'fail' ?>">
            <?= $fail === 0 ? '✅ SEMUA TES LULUS — Admin siap untuk mobile!' : '❌ ' . $fail . ' TES GAGAL — Perlu perbaikan.' ?>
        </div>
    </div>
</body>
</html>
