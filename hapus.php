<?php
session_start();
if(!isset($_SESSION['login'])) {
    header("Location: ../index.php");
    exit();
}
include '../../koneksi.php';  // Naik 2 tingkat karena di dalam form/buku/

$id = $_GET['id'];
$query = "DELETE FROM buku WHERE id_buku = '$id'";

if(mysqli_query($koneksi, $query)) {
    echo "<script>alert('Buku berhasil dihapus!'); window.location='data.php';</script>";
} else {
    echo "<script>alert('Error: " . mysqli_error($koneksi) . "'); window.location='data.php';</script>";
}
?>