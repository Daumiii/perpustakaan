<?php
session_start();
if(!isset($_SESSION['login'])) {
    header("Location: ../index.php");
    exit();
}
include '../../koneksi.php';  // Naik 2 tingkat karena di dalam form/buku/

$id = $_GET['id'];
$query = "SELECT * FROM buku WHERE id_buku = '$id'";
$result = mysqli_query($koneksi, $query);
$data = mysqli_fetch_assoc($result);

$pesan = '';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = $_POST['judul'];
    $penulis = $_POST['penulis'];
    $penerbit = $_POST['penerbit'];
    $tahun = $_POST['tahun'];
    $stok = $_POST['stok'];
    
    $query_update = "UPDATE buku SET 
                    judul = '$judul',
                    penulis = '$penulis',
                    penerbit = '$penerbit',
                    tahun_terbit = '$tahun',
                    stok = '$stok'
                    WHERE id_buku = '$id'";
    
    if(mysqli_query($koneksi, $query_update)) {
        $pesan = "<div style='background:#4CAF50;color:white;padding:10px;'>Buku berhasil diupdate!</div>";
    } else {
        $pesan = "<div style='background:#f44336;color:white;padding:10px;'>Error: " . mysqli_error($koneksi) . "</div>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Buku</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input { width: 100%; padding: 8px; box-sizing: border-box; }
        .btn { padding: 10px 20px; background: #2196F3; color: white; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <h2>✏️ EDIT BUKU</h2>
    
    <?php echo $pesan; ?>
    
    <form method="POST">
        <div class="form-group">
            <label>ID Buku</label>
            <input type="text" value="<?php echo $data['id_buku']; ?>" disabled>
        </div>
        
        <div class="form-group">
            <label>Judul Buku</label>
            <input type="text" name="judul" value="<?php echo $data['judul']; ?>" required>
        </div>
        
        <div class="form-group">
            <label>Penulis</label>
            <input type="text" name="penulis" value="<?php echo $data['penulis']; ?>">
        </div>
        
        <div class="form-group">
            <label>Penerbit</label>
            <input type="text" name="penerbit" value="<?php echo $data['penerbit']; ?>">
        </div>
        
        <div class="form-group">
            <label>Tahun Terbit</label>
            <input type="date" name="tahun" value="<?php echo $data['tahun_terbit']; ?>">
        </div>
        
        <div class="form-group">
            <label>Stok</label>
            <input type="number" name="stok" value="<?php echo $data['stok']; ?>">
        </div>
        
        <button type="submit" class="btn">Update</button>
        <a href="data.php" style="margin-left: 10px;">Batal</a>
    </form>
</body>
</html>