<?php
session_start();
if(!isset($_SESSION['login'])) {
    header("Location: ../index.php");
    exit();
}
include '../../koneksi.php';  // Naik 2 tingkat karena di dalam form/buku/

$id_pinjam = $_GET['id'];

// Ambil data peminjaman
$query = "SELECT p.*, a.nama as nama_anggota, b.judul 
         FROM peminjaman p
         JOIN anggota a ON p.id_anggota = a.id_anggota
         JOIN buku b ON p.id_buku = b.id_buku
         WHERE p.id_pinjam = '$id_pinjam'";
$result = mysqli_query($koneksi, $query);
$data = mysqli_fetch_assoc($result);

$pesan = '';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $denda = $_POST['denda'];
    
    // Update status peminjaman
    mysqli_query($koneksi, "UPDATE peminjaman SET status = 'kembali' WHERE id_pinjam = '$id_pinjam'");
    
    // Tambah stok buku
    mysqli_query($koneksi, "UPDATE buku SET stok = stok + 1 WHERE id_buku = '{$data['id_buku']}'");
    
    // Insert ke pengembalian
    mysqli_query($koneksi, "INSERT INTO pengembalian (id_pinjam, tanggal_dikembalikan, denda) 
                           VALUES ('$id_pinjam', NOW(), '$denda')");
    
    $pesan = "<div style='background:#4CAF50;color:white;padding:10px;'>Buku berhasil dikembalikan!</div>";
    echo "<script>setTimeout(function(){ window.location='data.php'; }, 2000);</script>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Pengembalian Buku</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        .info-box { background: #E3F2FD; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input { padding: 8px; width: 200px; }
        .btn { padding: 10px 20px; background: #4CAF50; color: white; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <h2>🔄 PENGEMBALIAN BUKU</h2>
    
    <?php echo $pesan; ?>
    
    <div class="info-box">
        <h3>Detail Peminjaman:</h3>
        <p><strong>Anggota:</strong> <?php echo $data['nama_anggota']; ?></p>
        <p><strong>Buku:</strong> <?php echo $data['judul']; ?></p>
        <p><strong>Tanggal Pinjam:</strong> <?php echo date('d/m/Y', strtotime($data['tanggal_pinjam'])); ?></p>
        <p><strong>Harus Kembali:</strong> <?php echo date('d/m/Y', strtotime($data['tanggal_kembali'])); ?></p>
    </div>
    
    <form method="POST">
        <div class="form-group">
            <label>Denda (Rp) - Jika terlambat</label>
            <input type="number" name="denda" value="0" min="0">
        </div>
        
        <button type="submit" class="btn">Proses Pengembalian</button>
        <a href="data.php" style="margin-left: 10px;">Batal</a>
    </form>
</body>
</html>