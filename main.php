<?php
session_start();
if(!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit();
}
include 'koneksi.php';

// HITUNG STATISTIK
$buku = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM buku"))['total'];
$anggota = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM anggota"))['total'];
$pinjam = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM peminjaman WHERE status='dipinjam'"))['total'];
$kembali = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM peminjaman WHERE status='kembali'"))['total'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>DASHBOARD</title>
    <style>
        body {
            font-family: Arial;
            margin: 0;
            background: #f5f7fa;
        }
        .header {
            background: linear-gradient(to right, #3498db, #2c3e50);
            color: white;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            padding: 30px;
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            text-align: center;
        }
        .stat-number {
            font-size: 36px;
            font-weight: bold;
            color: #2c3e50;
            margin: 10px 0;
        }
        .stat-label {
            color: #7f8c8d;
            font-size: 14px;
        }
        .recent-table {
            background: white;
            margin: 20px 30px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background: #f8f9fa;
            padding: 15px;
            text-align: left;
            border-bottom: 2px solid #eee;
        }
        td {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }
        .back-btn {
            display: inline-block;
            margin: 20px 30px;
            padding: 10px 20px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📊 DASHBOARD PERPUSTAKAAN</h1>
        <p>Selamat datang, <?php echo $_SESSION['username']; ?>!</p>
    </div>
    
    <a href="menu.php" class="back-btn">← Kembali ke Menu</a>
    
    <div class="stats">
        <div class="stat-card">
            <div class="stat-label">TOTAL BUKU</div>
            <div class="stat-number"><?php echo $buku; ?></div>
            <div>📚 Koleksi buku</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-label">TOTAL ANGGOTA</div>
            <div class="stat-number"><?php echo $anggota; ?></div>
            <div>👥 Anggota terdaftar</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-label">SEDANG DIPINJAM</div>
            <div class="stat-number"><?php echo $pinjam; ?></div>
            <div>📖 Buku dipinjam</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-label">TELAH DIKEMBALIKAN</div>
            <div class="stat-number"><?php echo $kembali; ?></div>
            <div>✅ Transaksi selesai</div>
        </div>
    </div>
    
    <div class="recent-table">
        <h3>📋 PEMINJAMAN TERBARU</h3>
        <?php
        $query = "SELECT p.*, a.nama as nama_anggota, b.judul 
                 FROM peminjaman p
                 JOIN anggota a ON p.id_anggota = a.id_anggota
                 JOIN buku b ON p.id_buku = b.id_buku
                 ORDER BY p.id_pinjam DESC LIMIT 5";
        $result = mysqli_query($koneksi, $query);
        
        if(mysqli_num_rows($result) > 0):
        ?>
        <table>
            <tr>
                <th>No</th>
                <th>Anggota</th>
                <th>Buku</th>
                <th>Tanggal Pinjam</th>
                <th>Status</th>
            </tr>
            <?php $no=1; while($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo $row['nama_anggota']; ?></td>
                <td><?php echo $row['judul']; ?></td>
                <td><?php echo date('d/m/Y', strtotime($row['tanggal_pinjam'])); ?></td>
                <td>
                    <?php 
                    if($row['status'] == 'dipinjam') {
                        echo '<span style="color:orange;">● Dipinjam</span>';
                    } else {
                        echo '<span style="color:green;">● Kembali</span>';
                    }
                    ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
        <?php else: ?>
        <p style="color:#999; text-align:center;">Belum ada data peminjaman</p>
        <?php endif; ?>
    </div>
</body>
</html>