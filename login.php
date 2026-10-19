<?php
session_start();
include 'koneksi.php';

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$error = '';

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, trim($_POST['username']));
    $password = $_POST['password'];

    $result = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
    
    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            header("Location: index.php");
            exit();
        }
    }
    
    $error = 'Username atau password salah!';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Catatan Harian</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Animate.css CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <style>
        body {
            background: linear-gradient(-45deg, #F7F3E9, #EFE3D3, #E3D2C0, #FAF6F0);
            background-size: 400% 400%;
            animation: gradientBG 12s ease infinite;
            color: #4A3E3D;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow: hidden;
            position: relative;
        }

        /* Animasi Background Gradasi Bergerak */
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Partikel Gelembung Melayang */
        .particle {
            position: absolute;
            border-radius: 50%;
            background: rgba(11, 6, 1, 0.1);
            animation: float 8s infinite ease-in-out;
            z-index: 0;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); opacity: 0.4; }
            50% { transform: translateY(-30px) rotate(180deg); opacity: 0.8; }
        }

        .login-card {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(10px);
            width: 100%;
            max-width: 420px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(140, 98, 57, 0.15);
            padding: 40px 30px;
            border: 1px solid rgba(255, 255, 255, 0.6);
            position: relative;
            z-index: 1;
        }

        .brand-icon {
            width: 65px;
            height: 65px;
            background-color: #8C6239;
            color: #ffffff;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            margin: 0 auto 15px auto;
            box-shadow: 0 8px 20px rgba(140, 98, 57, 0.3);
            transition: transform 0.4s ease;
        }

        .brand-icon:hover {
            transform: scale(1.1) rotate(5deg);
        }

        .btn-cokelat-susu {
            background-color: #8C6239 !important;
            color: #ffffff !important;
            border: none !important;
            padding: 12px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-cokelat-susu:hover {
            background-color: #6F4E2D !important;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(111, 78, 45, 0.35);
        }

        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            border: 1px solid #E0D7C6;
            background-color: #FAFAFA;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #8C6239;
            box-shadow: 0 0 0 0.25rem rgba(140, 98, 57, 0.15);
            background-color: #ffffff;
        }

        .input-group-text {
            border-radius: 10px 0 0 10px;
            background-color: #FAFAFA;
            border: 1px solid #E0D7C6;
            border-right: none;
            color: #8C6239;
        }

        .input-group .form-control {
            border-radius: 0 10px 10px 0;
        }

        .text-cokelat {
            color: #8C6239 !important;
        }
    </style>
</head>
<body>

<!-- Partikel Animasi Background -->
<div class="particle" style="width: 80px; height: 80px; top: 10%; left: 15%; animation-duration: 9s;"></div>
<div class="particle" style="width: 120px; height: 120px; bottom: 15%; right: 10%; animation-duration: 11s;"></div>
<div class="particle" style="width: 50px; height: 50px; top: 60%; left: 8%; animation-duration: 7s;"></div>

<div class="container d-flex justify-content-center">
    <!-- Tambahan Class Animate.css (animate__animated animate__fadeInDown) -->
    <div class="login-card animate__animated animate__fadeInDown">
        
        <div class="text-center mb-4">
            <div class="brand-icon">
                <i class="bi bi-journal-bookmark-fill"></i>
            </div>
            <h4 class="fw-bold text-cokelat mb-1">Selamat Datang</h4>
            <p class="text-muted small">Masuk untuk mengelola catatan harian Anda</p>
        </div>
        
        <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'registered'): ?>
            <div class="alert alert-success border-0 shadow-sm py-2 mb-3 text-center animate__animated animate__bounceIn" style="font-size: 0.85rem; border-radius: 10px;">
                <i class="bi bi-check-circle-fill me-1"></i> Akun berhasil dibuat! Silakan login.
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger border-0 shadow-sm py-2 mb-3 text-center animate__animated animate__shakeX" style="font-size: 0.85rem; border-radius: 10px;">
                <i class="bi bi-exclamation-triangle-fill me-1"></i> <?= $error; ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="mb-3">
                <label class="form-label small fw-semibold text-secondary">Username</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input type="text" name="username" class="form-control" placeholder="Masukkan username" required autofocus>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label small fw-semibold text-secondary">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" id="passwordInput" class="form-control" placeholder="Masukkan password" required>
                    <button class="btn btn-outline-secondary border-start-0" type="button" id="togglePassword" style="border-color: #E0D7C6; border-radius: 0 10px 10px 0;">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <button type="submit" name="login" class="btn btn-cokelat-susu w-100 mb-3">
                <i class="bi bi-box-arrow-in-right me-1"></i> Masuk Sekarang
            </button>
        </form>

        <div class="text-center mt-3 pt-3 border-top">
            <small class="text-muted">Belum memiliki akun? 
                <a href="register.php" class="text-cokelat fw-bold text-decoration-none">Daftar sekarang</a>
            </small>
        </div>
    </div>
</div>

<script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#passwordInput');
    const eyeIcon = document.querySelector('#eyeIcon');

    if (togglePassword) {
        togglePassword.addEventListener('click', function () {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            eyeIcon.classList.toggle('bi-eye');
            eyeIcon.classList.toggle('bi-eye-slash');
        });
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>