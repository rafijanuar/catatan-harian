<?php
session_start();
include 'koneksi.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil ID catatan dari URL
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = mysqli_real_escape_string($koneksi, $_GET['id']);

// Ambil data catatan berdasarkan ID dan user_id
$query = "SELECT * FROM catatan WHERE id = '$id' AND user_id = '$user_id'";
$result = mysqli_query($koneksi, $query);

if (mysqli_num_rows($result) == 0) {
    header("Location: index.php");
    exit();
}

$data = mysqli_fetch_assoc($result);

// Proses Update Data saat form disubmit
if (isset($_POST['update'])) {
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $isi   = mysqli_real_escape_string($koneksi, $_POST['isi']);

    $update_query = "UPDATE catatan SET judul = '$judul', isi = '$isi' WHERE id = '$id' AND user_id = '$user_id'";
    mysqli_query($koneksi, $update_query);

    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Catatan - Catatan Harian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        body { background-color: #F7F3E9 !important; color: #4A3E3D; }
        .navbar-cokelat-susu { background-color: #8C6239 !important; }
        .btn-cokelat-susu { background-color: #8C6239 !important; color: #ffffff !important; border: none !important; }
        .btn-cokelat-susu:hover { background-color: #6F4E2D !important; color: #ffffff !important; }
        .text-cokelat { color: #8C6239 !important; }
        .card-edit { max-width: 600px; margin: 40px auto; border-radius: 12px; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand navbar-cokelat-susu mb-4 shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-white" href="index.php">
                <i class="bi bi-journal-bookmark-fill me-2"></i>Catatan Harian
            </a>
        </div>
    </nav>

    <div class="container">
        <div class="card border-0 shadow-sm card-edit p-4">
            <h5 class="card-title fw-bold mb-3 text-cokelat">
                <i class="bi bi-pencil-square me-2"></i>Edit Catatan
            </h5>
            
            <form action="" method="POST">
                <div class="mb-3">
                    <label class="form-label text-muted small fw-semibold">Judul Catatan</label>
                    <input type="text" name="judul" class="form-control" value="<?= htmlspecialchars($data['judul']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted small fw-semibold">Isi Catatan</label>
                    <textarea name="isi" class="form-control" rows="6" required><?= htmlspecialchars($data['isi']); ?></textarea>
                </div>
                
                <div class="d-flex justify-content-between align-items-center">
                    <a href="index.php" class="btn btn-outline-secondary fw-semibold">
                        <i class="bi bi-arrow-left me-1"></i> Batal
                    </a>
                    <button type="submit" name="update" class="btn btn-cokelat-susu fw-semibold">
                        <i class="bi bi-check-circle me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>