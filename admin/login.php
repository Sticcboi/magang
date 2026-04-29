<?php
session_start();

// Panggil file koneksi database
require_once '../db_connect.php';

// Cek jika ada Cookie "remember" untuk mengisi otomatis
$cookie_user = isset($_COOKIE['remember_user']) ? $_COOKIE['remember_user'] : "";
$cookie_pass = isset($_COOKIE['remember_pass']) ? $_COOKIE['remember_pass'] : "";

$error = "";

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);

    // Query ke database
    $query = "SELECT * FROM users WHERE username = '$username' AND is_active = 1";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        
        // Cek kecocokan password
        if ($password === $row['password'] || md5($password) === $row['password'] || password_verify($password, $row['password'])) {
            
            // Logika Ingat Saya (Cookie 30 hari)
            if ($remember) {
                setcookie('remember_user', $username, time() + (86400 * 30), "/");
                setcookie('remember_pass', $password, time() + (86400 * 30), "/");
            } else {
                setcookie('remember_user', '', time() - 3600, "/");
                setcookie('remember_pass', '', time() - 3600, "/");
            }

            // Set Session
            $_SESSION['status'] = "login";
            $_SESSION['id_user'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['nama'] = $row['nama'];
            $_SESSION['role'] = $row['role'];

            // Update waktu login terakhir
            mysqli_query($conn, "UPDATE users SET last_login = NOW() WHERE id = " . $row['id']);

            header("location: index.php");
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan atau akun tidak aktif!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Kelurahan Kedungpane</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #800000 0%, #4a0000 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0,0,0,0.5);
        }
        .card-header {
            background: #600000;
            padding: 30px;
            text-align: center;
            border-bottom: 5px solid #ffd700; /* Aksen emas */
        }
        .logo-kedungpane {
            width: 80px;
            height: auto;
            margin-bottom: 10px;
        }
        .btn-maroon {
            background: #800000;
            color: white;
            border: none;
            padding: 12px;
            font-weight: 600;
            transition: 0.3s;
        }
        .btn-maroon:hover {
            background: #a00000;
            color: white;
            transform: translateY(-2px);
        }
        .text-maroon {
            color: #800000 !important;
        }
        .input-group-text {
            cursor: pointer;
            background: transparent;
            border-left: none;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #800000;
        }
        .password-toggle:hover {
            color: #800000;
        }
        .form-check-input:checked {
            background-color: #800000;
            border-color: #800000;
        }
    </style>
</head>
<body>

<div class="login-card mx-3">
    <div class="card-header">
        <img src="../img/logo.png" alt="Logo Kedungpane" class="logo-kedungpane">
        <h5 class="text-white fw-bold mb-0">KELURAHAN KEDUNGPANE</h5>
        <small class="text-white-50">Sistem Informasi Manajemen</small>
    </div>

    <div class="card-body p-4">
        <?php if($error): ?>
            <div class="alert alert-danger py-2 text-center small">
                <i class="fa-solid fa-circle-exclamation me-2"></i><?= $error ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label small fw-bold text-muted">USERNAME</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="fa-solid fa-user text-maroon"></i></span>
                    <input type="text" name="username" class="form-control bg-light" placeholder="Masukkan username" value="<?= htmlspecialchars($cookie_user) ?>" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold text-muted">PASSWORD</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="fa-solid fa-lock text-maroon"></i></span>
                    <input type="password" name="password" id="passwordField" class="form-control bg-light border-end-0" placeholder="Masukkan password" value="<?= htmlspecialchars($cookie_pass) ?>" required>
                    <span class="input-group-text bg-light border-start-0" onclick="togglePassword()">
                        <i class="fa-solid fa-eye password-toggle" id="eyeIcon"></i>
                    </span>
                </div>
            </div>

            <div class="mb-4 form-check">
                <input type="checkbox" name="remember" class="form-check-input" id="remember" <?= $cookie_user ? 'checked' : '' ?>>
                <label class="form-check-label small fw-bold text-muted" for="remember">Ingat Saya</label>
            </div>

            <button type="submit" name="login" class="btn btn-maroon w-100 rounded-pill shadow-sm">
                MASUK <i class="fa-solid fa-paper-plane ms-2"></i>
            </button>
        </form>
    </div>
    
    <div class="card-footer bg-white border-0 text-center pb-4">
        <p class="text-muted small mb-0">&copy; 2026 Pemerintahan Kota Semarang</p>
    </div>
</div>

<script>
    function togglePassword() {
        const passwordField = document.getElementById('passwordField');
        const eyeIcon = document.getElementById('eyeIcon');
        
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    }
</script>

</body>
</html>