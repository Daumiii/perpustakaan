<?php
session_start();
if(!isset($_SESSION['login'])) {
    header("Location: ../index.php");
    exit();
}
include '../../koneksi.php';  // Naik 2 tingkat karena di dalam form/buku/

$pesan = '';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_anggota = $_POST['id_anggota'];
    $id_buku = $_POST['id_buku'];
    $tanggal_pinjam = $_POST['tanggal_pinjam'];
    $tanggal_kembali = $_POST['tanggal_kembali'];
    
    // Cek stok buku
    $cek_stok = mysqli_query($koneksi, "SELECT stok FROM buku WHERE id_buku = '$id_buku'");
    $stok_data = mysqli_fetch_assoc($cek_stok);
    
    if($stok_data['stok'] > 0) {
        // Kurangi stok
        mysqli_query($koneksi, "UPDATE buku SET stok = stok - 1 WHERE id_buku = '$id_buku'");
        
        // Insert peminjaman
        $query = "INSERT INTO peminjaman (id_anggota, id_buku, tanggal_pinjam, tanggal_kembali, status) 
                  VALUES ('$id_anggota', '$id_buku', '$tanggal_pinjam', '$tanggal_kembali', 'dipinjam')";
        
        if(mysqli_query($koneksi, $query)) {
            $pesan = "<div style='background:#4CAF50;color:white;padding:10px;'>Peminjaman berhasil!</div>";
        } else {
            $pesan = "<div style='background:#f44336;color:white;padding:10px;'>Error: " . mysqli_error($koneksi) . "</div>";
        }
    } else {
        $pesan = "<div style='background:#f44336;color:white;padding:10px;'>Stok buku habis!</div>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Pinjam Buku</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        select, input { width: 100%; padding: 8px; box-sizing: border-box; }
        .btn { padding: 10px 20px; background: #FF9800; color: white; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <h2>📖 PINJAM BUKU</h2>
    
    <?php echo $pesan; ?>
    
    <form method="POST">
        <div class="form-group">
            <label>Pilih Anggota</label>
            <select name="id_anggota" required>
                <option value="">- Pilih Anggota -</option>
                <?php
                $query_anggota = mysqli_query($koneksi, "SELECT * FROM anggota");
                while($anggota = mysqli_fetch_assoc($query_anggota)) {
                    echo "<option value='{$anggota['id_anggota']}'>{$anggota['nama']} ({$anggota['id_anggota']})</option>";
                }
                ?>
            </select>
        </div>
        
        <div class="form-group">
            <label>Pilih Buku</label>
            <select name="id_buku" required>
                <option value="">- Pilih Buku -</option>
                <?php
                $query_buku = mysqli_query($koneksi, "SELECT * FROM buku WHERE stok > 0");
                while($buku = mysqli_fetch_assoc($query_buku)) {
                    echo "<option value='{$buku['id_buku']}'>{$buku['judul']} (Stok: {$buku['stok']})</option>";
                }
                ?>
            </select>
        </div>
        
        <div class="form-group">
            <label>Tanggal Pinjam</label>
            <input type="date" name="tanggal_pinjam" value="<?php echo date('Y-m-d'); ?>" required>
        </div>
        
        <div class="form-group">
            <label>Tanggal Kembali</label>
            <input type="date" name="tanggal_kembali" value="<?php echo date('Y-m-d', strtotime('+7 days')); ?>" required>
        </div>
        
        <button type="submit" class="btn">Simpan Peminjaman</button>
        <a href="data.php" style="margin-left: 10px;">Batal</a>
    </form>
</body>
</html>