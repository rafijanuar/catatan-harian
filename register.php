<?php
session_start();
include 'koneksi.php';

// Jika sudah login, lempar ke index
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$pesan = '';

if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($koneksi, trim($_POST['username']));
    $password = $_POST['password'];

    // Cek apakah username sudah dipakai
    $cek = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
    if (mysqli_num_rows($cek) > 0) {
        $pesan = 'Username sudah terdaftar! Gunakan username lain.';
    } else {
        // Encrypt/Hash Password demi keamanan
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        $query = "INSERT INTO users (username, password) VALUES ('$username', '$password_hash')";
        if (mysqli_query($koneksi, $query)) {
            header("Location: login.php?pesan=registered");
            exit();
        } else {
            $pesan = 'Gagal mendaftar, coba lagi.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Catatan Harian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #F7F3E9; color: #4A3E3D; }
        .card-auth { max-width: 400px; margin: 80px auto; border-radius: 12px; }
        .btn-cokelat-susu { background-color: #8C6239; color: #fff; border: none; }
        .btn-cokelat-susu:hover { background-color: #6F4E2D; color: #fff; }
        .text-cokelat { color: #8C6239 !important; }
    </style>
</head>
<body>

<div class="container">
    <div class="card border-0 shadow-sm card-auth p-4">
        <h4 class="fw-bold text-center text-cokelat mb-3">Buat Akun Baru</h4>
        
        <?php if ($pesan): ?>
            <div class="alert alert-danger py-2" style="font-size: 0.9rem;"><?= $pesan; ?></div>
        <?php endif; ?>

        <form action="register.php" method="POST">
            <div class="mb-3">
                <label class="form-label small fw-semibold">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-semibold">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" name="register" class="btn btn-cokelat-susu w-100 fw-semibold mb-3">Daftar</button>
        </form>

        <small class="text-center text-muted">Sudah punya akun? <a href="login.php" class="text-cokelat fw-bold">Login disini</a></small>
    </div>
</div>

</body>
</html>