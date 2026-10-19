<?php
$host = "localhost";
$user = "root";
$pass = ""; // Default Laragon password root kosong
$db   = "db_catatan";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>