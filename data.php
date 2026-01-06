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
    <title>Data Peminjaman</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        h2 { color: #333; }
        .btn { padding: 8px 15px; background: #FF9800; color: white; text-decoration: none; border-radius: 4px; }
        .btn-kembali { background: #4CAF50; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f2f2f2; }
        tr:hover { background-color: #f5f5f5; }
        .status-dipinjam { background: #FFF3CD; color: #856404; padding: 5px 10px; border-radius: 3px; }
        .status-kembali { background: #D4EDDA; color: #155724; padding: 5px 10px; border-radius: 3px; }
    </style>
</head>
<body>
    <h2>📖 DATA PEMINJAMAN</h2>
    <a href="tambah.php" class="btn">+ Pinjam Buku</a>
    <a href="../menu.php" style="margin-left: 10px;">← Kembali</a>
    
    <br><br>
    
    <table>
        <tr>
            <th>No</th>
            <th>ID Pinjam</th>
            <th>Anggota</th>
            <th>Buku</th>
            <th>Tgl Pinjam</th>
            <th>Tgl Kembali</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        
        <?php
        $query = "SELECT p.*, a.nama as nama_anggota, b.judul 
                 FROM peminjaman p
                 JOIN anggota a ON p.id_anggota = a.id_anggota
                 JOIN buku b ON p.id_buku = b.id_buku
                 ORDER BY p.id_pinjam DESC";
        $result = mysqli_query($koneksi, $query);
        $no = 1;
        
        while($row = mysqli_fetch_assoc($result)) {
            $status_class = ($row['status'] == 'dipinjam') ? 'status-dipinjam' : 'status-kembali';
            
            echo "<tr>";
            echo "<td>$no</td>";
            echo "<td>{$row['id_pinjam']}</td>";
            echo "<td>{$row['nama_anggota']}</td>";
            echo "<td>{$row['judul']}</td>";
            echo "<td>" . date('d/m/Y', strtotime($row['tanggal_pinjam'])) . "</td>";
            echo "<td>" . date('d/m/Y', strtotime($row['tanggal_kembali'])) . "</td>";
            echo "<td><span class='$status_class'>" . ucfirst($row['status']) . "</span></td>";
            echo "<td>";
            if($row['status'] == 'dipinjam') {
                echo "<a href='kembali.php?id={$row['id_pinjam']}' class='btn btn-kembali'>Kembalikan</a>";
            } else {
                echo "<span style='color:#999;'>Selesai</span>";
            }
            echo "</td>";
            echo "</tr>";
            $no++;
        }
        ?>
    </table>
</body>
</html>