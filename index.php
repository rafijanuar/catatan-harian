<?php
session_start();
include 'koneksi.php';

// Cek apakah user sudah login, jika belum arahkan ke login.php
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Proses simpan data (Sertakan user_id)
if (isset($_POST['submit'])) {
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $isi   = mysqli_real_escape_string($koneksi, $_POST['isi']);

    $query = "INSERT INTO catatan (user_id, judul, isi) VALUES ('$user_id', '$judul', '$isi')";
    mysqli_query($koneksi, $query);
    header("Location: index.php");
    exit();
}

// Ambil data hanya milik user yang sedang login
$result = mysqli_query($koneksi, "SELECT * FROM catatan WHERE user_id = '$user_id' ORDER BY tanggal DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catatan Harian Saya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        body { background-color: #F7F3E9 !important; color: #4A3E3D; }
        .navbar-cokelat-susu { background-color: #8C6239 !important; }
        .btn-cokelat-susu { background-color: #8C6239 !important; color: #ffffff !important; border: none !important; }
        .btn-cokelat-susu:hover { background-color: #6F4E2D !important; color: #ffffff !important; }
        .text-cokelat { color: #8C6239 !important; }
        .card-note { transition: transform 0.2s, box-shadow 0.2s; }
        .card-note:hover { transform: translateY(-3px); box-shadow: 0 0.5rem 1rem rgba(140, 98, 57, 0.15) !important; }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand navbar-cokelat-susu mb-4 shadow-sm">
        <div class="container d-flex justify-content-between">
            <a class="navbar-brand fw-bold text-white" href="#">
                <i class="bi bi-journal-bookmark-fill me-2"></i>Catatan Harian
            </a>
            <div class="text-white">
                <span class="me-3 small"><i class="bi bi-person-circle me-1"></i> Halo, <b><?= htmlspecialchars($username); ?></b></span>
                <a href="logout.php" class="btn btn-outline-light btn-sm fw-semibold">
                    <i class="bi bi-box-arrow-right me-1"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container pb-5">
        <div class="row g-4">
            
            <!-- Form Input -->
            <div class="col-md-5 col-lg-4">
                <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
                    <div class="card-body p-4">
                        <h5 class="card-title fw-bold mb-3 text-cokelat">
                            <i class="bi bi-pencil-square me-2"></i>Tulis Catatan
                        </h5>
                        <form action="index.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-semibold">Judul Catatan</label>
                                <input type="text" name="judul" class="form-control" placeholder="Contoh: Belajar Laragon" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-semibold">Isi Catatan</label>
                                <textarea name="isi" class="form-control" rows="5" placeholder="Tulis rencana atau kegiatanmu..." required></textarea>
                            </div>
                            <button type="submit" name="submit" class="btn btn-cokelat-susu w-100 fw-semibold">
                                <i class="bi bi-plus-circle me-1"></i> Simpan Catatan
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Daftar Catatan -->
            <div class="col-md-7 col-lg-8">
                <h5 class="fw-bold mb-3 text-cokelat">
                    <i class="bi bi-collection-fill me-2"></i>Daftar Catatan Anda
                </h5>

                <?php if (mysqli_num_rows($result) == 0): ?>
                    <div class="alert bg-white text-center border-0 shadow-sm text-muted" role="alert">
                        <i class="bi bi-info-circle me-2"></i>Belum ada catatan. Silakan tulis catatan pertama Anda!
                    </div>
                <?php else: ?>
                    <div class="row row-cols-1 row-cols-lg-2 g-3">
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <div class="col">
                                <div class="card h-100 border-0 shadow-sm card-note">
                                    <div class="card-body d-flex flex-column">
                                        <h6 class="card-title fw-bold text-dark mb-2">
                                            <?= htmlspecialchars($row['judul']); ?>
                                        </h6>
                                        <p class="card-text text-secondary flex-grow-1" style="font-size: 0.95rem;">
                                            <?= nl2br(htmlspecialchars($row['isi'])); ?>
                                        </p>
                                        <div class="pt-3 border-top d-flex justify-content-between align-items-center mt-auto">
                                            <small class="text-muted" style="font-size: 0.75rem;">
                                                <i class="bi bi-clock me-1"></i>
                                                <?= date('d M Y, H:i', strtotime($row['tanggal'])); ?>
                                            </small>
                                            <div>
                                                <!-- Tombol Edit -->
                                                <a href="edit.php?id=<?= $row['id']; ?>" 
                                                   class="btn btn-outline-warning btn-sm border-0 me-1" 
                                                   title="Edit Catatan">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <!-- Tombol Hapus -->
                                                <a href="hapus.php?id=<?= $row['id']; ?>" 
                                                   class="btn btn-outline-danger btn-sm border-0" 
                                                   onclick="return confirm('Yakin menghapus catatan ini?')"
                                                   title="Hapus Catatan">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php endif; ?>

            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>