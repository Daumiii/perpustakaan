<?php
session_start();
if(!isset($_SESSION['login'])) {
    header("Location: ../index.php");
    exit();
}
include '../../koneksi.php';  // Naik 2 tingkat karena di dalam form/buku/
?>
<!DOCTYPE html>
<html>
<head>
    <title>Laporan Buku</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        h2 { color: #333; }
        .btn { padding: 10px 20px; background: #2196F3; color: white; text-decoration: none; border-radius: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f2f2f2; }
        .total-row { background-color: #E3F2FD; font-weight: bold; }
    </style>
</head>
<body>
    <h2>📊 LAPORAN DATA BUKU</h2>
    
    <a href="cetak_buku.php" target="_blank" class="btn">🖨️ Cetak Laporan</a>
    <a href="../menu.php" style="margin-left: 10px;">← Kembali</a>
    
    <br><br>
    
    <?php
    $query = "SELECT * FROM buku ORDER BY id_buku";
    $result = mysqli_query($koneksi, $query);
    ?>
    
    <table>
        <tr>
            <th>No</th>
            <th>ID Buku</th>
            <th>Judul</th>
            <th>Penulis</th>
            <th>Penerbit</th>
            <th>Tahun</th>
            <th>Stok</th>
        </tr>
        
        <?php
        $no = 1;
        $total_stok = 0;
        while($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>$no</td>";
            echo "<td>{$row['id_buku']}</td>";
            echo "<td>{$row['judul']}</td>";
            echo "<td>{$row['penulis']}</td>";
            echo "<td>{$row['penerbit']}</td>";
            echo "<td>" . date('Y', strtotime($row['tahun_terbit'])) . "</td>";
            echo "<td>{$row['stok']}</td>";
            echo "</tr>";
            
            $total_stok += $row['stok'];
            $no++;
        }
        ?>
        
        <tr class="total-row">
            <td colspan="6" align="right"><strong>TOTAL STOK BUKU:</strong></td>
            <td><strong><?php echo $total_stok; ?> buku</strong></td>
        </tr>
    </table>
</body>
</html>