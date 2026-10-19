<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $user_id = $_SESSION['user_id'];
    
    // Hapus hanya jika id catatan cocok dan milik user_id yang sedang login
    mysqli_query($koneksi, "DELETE FROM catatan WHERE id=$id AND user_id=$user_id");
}

header("Location: index.php");
exit();
?>