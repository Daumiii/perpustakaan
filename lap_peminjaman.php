<?php
session_start();
if(!isset($_SESSION['login'])) {
    header("Location: ../../index.php");
    exit();
}
include '../../koneksi.php';

// Filter tanggal
$filter = "";
if(isset($_GET['dari']) && isset($_GET['sampai'])) {
    $dari = $_GET['dari'];
    $sampai = $_GET['sampai'];
    if(!empty($dari) && !empty($sampai)) {
        $filter = "WHERE p.tanggal_pinjam BETWEEN '$dari' AND '$sampai'";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Laporan Peminjaman</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        h2 { color: #333; }
        .btn { padding: 10px 20px; background: #4CAF50; color: white; text-decoration: none; border-radius: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f2f2f2; }
        .filter-box { background: #F5F5F5; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .total-row { background-color: #E8F5E9; font-weight: bold; }
        .status-dipinjam { background: #FFF3CD; color: #856404; padding: 5px 10px; border-radius: 3px; }
        .status-kembali { background: #D4EDDA; color: #155724; padding: 5px 10px; border-radius: 3px; }
    </style>
</head>
<body>
    <h2>📊 LAPORAN PEMINJAMAN BUKU</h2>
    
    <div class="filter-box">
        <form method="GET">
            <label>Filter Tanggal:</label><br>
            <input type="date" name="dari" value="<?php echo isset($_GET['dari']) ? $_GET['dari'] : ''; ?>">
            <span>s/d</span>
            <input type="date" name="sampai" value="<?php echo isset($_GET['sampai']) ? $_GET['sampai'] : ''; ?>">
            <button type="submit">Terapkan Filter</button>
            <a href="lap_peminjaman.php">Reset</a>
        </form>
    </div>
    
    <?php
    $cetak_url = "cetak_peminjaman.php";
    if(isset($_GET['dari']) && isset($_GET['sampai'])) {
        $cetak_url .= "?dari=" . $_GET['dari'] . "&sampai=" . $_GET['sampai'];
    }
    ?>
    
    <a href="<?php echo $cetak_url; ?>" target="_blank" class="btn">🖨️ Cetak Laporan</a>
    <a href="../../menu.php" style="margin-left: 10px;">← Kembali ke Menu</a>
    
    <br><br>
    
    <?php
    $query = "SELECT p.*, a.nama as nama_anggota, b.judul, peng.denda
              FROM peminjaman p
              JOIN anggota a ON p.id_anggota = a.id_anggota
              JOIN buku b ON p.id_buku = b.id_buku
              LEFT JOIN pengembalian peng ON p.id_pinjam = peng.id_pinjam
              $filter
              ORDER BY p.tanggal_pinjam DESC";
    $result = mysqli_query($koneksi, $query);
    ?>
    
    <table>
        <tr>
            <th>No</th>
            <th>ID Pinjam</th>
            <th>Tanggal</th>
            <th>Anggota</th>
            <th>Buku</th>
            <th>Status</th>
            <th>Denda (Rp)</th>
        </tr>
        
        <?php
        $no = 1;
        $total_denda = 0;
        while($row = mysqli_fetch_assoc($result)) {
            $status_class = ($row['status'] == 'dipinjam') ? 'status-dipinjam' : 'status-kembali';
            
            echo "<tr>";
            echo "<td>$no</td>";
            echo "<td>{$row['id_pinjam']}</td>";
            echo "<td>" . date('d/m/Y', strtotime($row['tanggal_pinjam'])) . "</td>";
            echo "<td>{$row['nama_anggota']}</td>";
            echo "<td>{$row['judul']}</td>";
            echo "<td><span class='$status_class'>" . ucfirst($row['status']) . "</span></td>";
            echo "<td>" . number_format($row['denda'], 0, ',', '.') . "</td>";
            echo "</tr>";
            
            $total_denda += $row['denda'];
            $no++;
        }
        ?>
        
        <tr class="total-row">
            <td colspan="6" align="right"><strong>TOTAL DENDA:</strong></td>
            <td><strong>Rp <?php echo number_format($total_denda, 0, ',', '.'); ?></strong></td>
        </tr>
    </table>
    
    <br>
    <p><strong>Jumlah Data: <?php echo ($no-1); ?> transaksi</strong></p>
</body>
</html>