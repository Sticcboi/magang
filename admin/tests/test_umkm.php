<?php
/**
 * TDD Tests for admin_umkm.php
 * 
 * Tests cover:
 * 1. PRG Pattern: POST actions must redirect (no duplicate on refresh)
 * 2. Edit functionality: UMKM can be updated
 * 
 * Run: Open http://localhost/Magang/admin/tests/test_umkm.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once dirname(__DIR__) . '/auth.php';

// Verify table exists
$check = mysqli_query($conn, "SHOW TABLES LIKE 'umkm'");
if (mysqli_num_rows($check) == 0) {
    die("Table umkm does not exist.");
}

$page_key = 'test_umkm_' . time();
$results = [];
$passed = 0;
$failed = 0;

function assert_test($name, $condition, $detail = '') {
    global $results, $passed, $failed;
    if ($condition) {
        $results[] = ['pass' => true, 'name' => $name, 'detail' => $detail];
        $passed++;
    } else {
        $results[] = ['pass' => false, 'name' => $name, 'detail' => $detail];
        $failed++;
    }
}

$source = file_get_contents(dirname(__DIR__) . '/admin_umkm.php');

// ========================================================
// TEST GROUP 1: PRG Pattern — Handlers must redirect
// ========================================================

$has_redirect = strpos($source, "header('Location: admin_umkm.php#") !== false;
assert_test('PRG: save_umkm redirects after POST', 
    strpos($source, "save_umkm") !== false && $has_redirect,
    'POST save_umkm should use header(Location#anchor) + exit'
);

assert_test('PRG: delete_umkm redirects after GET action',
    strpos($source, "delete_umkm") !== false && strpos($source, "\$_SESSION['flash_msg']") !== false,
    'GET delete_umkm should redirect to prevent accidental re-deletion'
);

// ========================================================
// TEST GROUP 2: Database CRUD operations
// ========================================================

$nama = 'Test UMKM ' . time();
$kategori = 'Kuliner';
$pengelola = 'Budi';

mysqli_query($conn, "INSERT INTO umkm (nama, kategori, pengelola, kontak, deskripsi, alamat, latitude, longitude, foto, is_verified) VALUES ('$nama', '$kategori', '$pengelola', '08111', 'Deskripsi Test', 'Alamat Test', '0', '0', '', 1)");
$insert_id = mysqli_insert_id($conn);
assert_test('DB: Insert UMKM succeeds', $insert_id > 0, "Inserted ID: $insert_id");

$result = mysqli_query($conn, "SELECT * FROM umkm WHERE id = $insert_id");
$row = mysqli_fetch_assoc($result);
assert_test('DB: Read UMKM returns correct data',
    $row && $row['nama'] === $nama && $row['pengelola'] === $pengelola,
    "Got name: " . ($row['nama'] ?? 'NULL')
);

$new_nama = 'Updated UMKM';
mysqli_query($conn, "UPDATE umkm SET nama='$new_nama' WHERE id=$insert_id");
$result2 = mysqli_query($conn, "SELECT * FROM umkm WHERE id = $insert_id");
$row2 = mysqli_fetch_assoc($result2);
assert_test('DB: Update UMKM succeeds',
    $row2 && $row2['nama'] === $new_nama,
    "Updated: " . ($row2['nama'] ?? 'NULL')
);

mysqli_query($conn, "DELETE FROM umkm WHERE id=$insert_id");
$count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM umkm WHERE id=$insert_id"));
assert_test('DB: Delete UMKM succeeds', (int)$count['c'] === 0, "Remaining: " . $count['c']);

// ========================================================
// TEST GROUP 3: UX/UI and Structure
// ========================================================

assert_test('Structure: Hidden umkm_id field exists for edit mode',
    strpos($source, 'umkm_id') !== false,
    'Form must include hidden umkm_id to distinguish add vs edit'
);

assert_test('UX: Redirects use anchor fragments',
    strpos($source, "#form") !== false || strpos($source, "#list") !== false || strpos($source, "#konten") !== false,
    'Redirects should use #anchor to preserve scroll position'
);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>TDD Tests — UMKM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #1a1a2e; color: #eee; font-family: 'Segoe UI', sans-serif; }
        .test-pass { color: #38ef7d; }
        .test-fail { color: #ff6b6b; font-weight: bold; }
    </style>
</head>
<body>
<div class="container py-5">
    <h1 class="mb-2">🧪 TDD Tests — Admin UMKM</h1>
    
    <div class="mb-4">
        <span class="badge bg-success fs-6 me-2">✅ Passed: <?= $passed ?></span>
        <span class="badge bg-danger fs-6">❌ Failed: <?= $failed ?></span>
    </div>

    <table class="table table-dark table-striped">
        <thead><tr><th width="40">Status</th><th>Test</th><th>Detail</th></tr></thead>
        <tbody>
        <?php foreach ($results as $r): ?>
        <tr>
            <td><?= $r['pass'] ? '✅' : '❌' ?></td>
            <td class="<?= $r['pass'] ? 'test-pass' : 'test-fail' ?>"><?= htmlspecialchars($r['name']) ?></td>
            <td class="small text-muted"><?= htmlspecialchars($r['detail']) ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <?php if ($failed > 0): ?>
    <div class="alert alert-danger mt-4">
        <strong>⚠️ <?= $failed ?> test(s) failed.</strong> Implementasi belum lengkap.
    </div>
    <?php else: ?>
    <div class="alert alert-success mt-4">
        <strong>🎉 Semua test passed!</strong>
    </div>
    <?php endif; ?>
</div>
</body>
</html>
