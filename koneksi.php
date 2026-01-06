<?php
// KONEKSI.PHP - TANPA session_start()

$host = "localhost";
$username = "root";
$password = "";
$database = "db_perpustakaan";

$koneksi = mysqli_connect($host, $username, $password, $database);

if (!$koneksi) {
    die("❌ Koneksi database gagal: " . mysqli_connect_error());
}

mysqli_set_charset($koneksi, "utf8");

// TAMBAHKAN INI UNTUK DEBUG (OPTIONAL)
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
?>