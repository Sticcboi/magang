<?php
// admin/auth.php - centralized admin authentication, DB include and CSRF helpers
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// include DB connection
require_once __DIR__ . '/../db_connect.php';

// enforce login
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'login') {
    header('Location: login.php');
    exit;
}

// regenerate session id once per session to mitigate fixation
if (empty($_SESSION['initiated'])) {
    session_regenerate_id(true);
    $_SESSION['initiated'] = true;
}

// simple role checker (adjust roles as needed)
function is_admin() {
    return isset($_SESSION['role']) && in_array($_SESSION['role'], ['admin', 'editor', 'superadmin']);
}

// CSRF helpers
function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field() {
    $token = htmlspecialchars(csrf_token(), ENT_QUOTES);
    return '<input type="hidden" name="csrf_token" value="' . $token . '">';
}

function verify_csrf($token) {
    if (empty($_SESSION['csrf_token']) || empty($token)) return false;
    return hash_equals($_SESSION['csrf_token'], $token);
}

?>
