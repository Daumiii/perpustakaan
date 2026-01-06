<?php
session_start();
include 'koneksi.php';

// Cek login
if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit();
}

// Ambil data statistik
$query_buku = "SELECT COUNT(*) as total FROM buku";
$result_buku = mysqli_query($koneksi, $query_buku);
$total_buku = mysqli_fetch_assoc($result_buku)['total'];

$query_anggota = "SELECT COUNT(*) as total FROM anggota";
$result_anggota = mysqli_query($koneksi, $query_anggota);
$total_anggota = mysqli_fetch_assoc($result_anggota)['total'];

$query_pinjam = "SELECT COUNT(*) as total FROM peminjaman WHERE status = 'dipinjam'";
$result_pinjam = mysqli_query($koneksi, $query_pinjam);
$total_pinjam = mysqli_fetch_assoc($result_pinjam)['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistem Perpustakaan</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
            color: #333;
        }
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 250px;
            height: 100vh;
            background: linear-gradient(180deg, #2c3e50, #34495e);
            color: white;
            padding: 20px 0;
        }
        .logo {
            text-align: center;
            padding: 20px;
            font-size: 24px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
        }
        .logo span {
            font-size: 32px;
            display: block;
            margin-bottom: 5px;
        }
        .menu {
            list-style: none;
        }
        .menu li {
            padding: 15px 25px;
            transition: background 0.3s;
        }
        .menu li:hover {
            background: rgba(255,255,255,0.1);
        }
        .menu li.active {
            background: rgba(52, 152, 219, 0.3);
            border-left: 4px solid #3498db;
        }
        .menu a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        .menu i {
            margin-right: 10px;
            font-size: 18px;
        }
        .main-content {
            margin-left: 250px;
            padding: 30px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e0e0e0;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .user-avatar {
            width: 40px;
            height: 40px;
            background: #3498db;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            transition: transform 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
            font-size: 24px;
        }
        .stat-icon.buku { background: #e3f2fd; color: #1976d2; }
        .stat-icon.anggota { background: #f3e5f5; color: #7b1fa2; }
        .stat-icon.pinjam { background: #e8f5e9; color: #388e3c; }
        .stat-info h3 {
            font-size: 14px;
            color: #777;
            margin-bottom: 5px;
        }
        .stat-info .number {
            font-size: 28px;
            font-weight: bold;
            color: #333;
        }
        .recent-activity {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        .recent-activity h2 {
            margin-bottom: 20px;
            color: #333;
            font-size: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background: #f8f9fa;
            padding: 12px;
            text-align: left;
            color: #555;
            font-weight: 600;
            border-bottom: 2px solid #e0e0e0;
        }
        td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }
        tr:hover {
            background: #f9f9f9;
        }
        .status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .status.dipinjam { background: #fff3e0; color: #f57c00; }
        .status.kembali { background: #e8f5e9; color: #388e3c; }
        .btn-logout {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background 0.3s;
        }
        .btn-logout:hover {
            background: #c0392b;
        }
        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
            }
            .main-content {
                margin-left: 70px;
            }
            .menu span {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <span>📚</span>
            Perpustakaan
        </div>
        <ul class="menu">
            <li class="active"><a href="dashboard.php"><i>🏠</i> <span>Dashboard</span></a></li>
            <li><a href="buku/data.php"><i>📕</i> <span>Data Buku</span></a></li>
            <li><a href="anggota/data.php"><i>👥</i> <span>Data Anggota</span></a></li>
            <li><a href="peminjaman/data.php"><i>📖</i> <span>Peminjaman</span></a></li>
            <li><a href="laporan/buku.php"><i>📊</i> <span>Laporan</span></a></li>
            <li><a href="logout.php"><i>🚪</i> <span>Logout</span></a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header">
            <h1>Selamat Datang, <?php echo $_SESSION['username']; ?>!</h1>
            <div class="user-info">
                <div class="user-avatar">
                    <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                </div>
                <span>Role: <?php echo $_SESSION['role']; ?></span>
                <a href="logout.php"><button class="btn-logout">Logout</button></a>
            </div>
        </div>

        <!-- Statistik -->
        <div class="stats">
            <div class="stat-card">
                <div class="stat-icon buku">📚</div>
                <div class="stat-info">
                    <h3>Total Buku</h3>
                    <div class="number"><?php echo $total_buku; ?></div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon anggota">👥</div>
                <div class="stat-info">
                    <h3>Total Anggota</h3>
                    <div class="number"><?php echo $total_anggota; ?></div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon pinjam">📖</div>
                <div class="stat-info">
                    <h3>Sedang Dipinjam</h3>
                    <div class="number"><?php echo $total_pinjam; ?></div>
                </div>
            </div>
        </div>

        <!-- Aktivitas Terbaru -->
        <div class="recent-activity">
            <h2>Peminjaman Terbaru</h2>
            <?php
            $query_recent = "SELECT p.*, a.nama as nama_anggota, b.judul 
                           FROM peminjaman p
                           JOIN anggota a ON p.id_anggota = a.id_anggota
                           JOIN buku b ON p.id_buku = b.id_buku
                           ORDER BY p.tanggal_pinjam DESC LIMIT 5";
            $result_recent = mysqli_query($koneksi, $query_recent);
            
            if (mysqli_num_rows($result_recent) > 0):
            ?>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Anggota</th>
                        <th>Buku</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; while($row = mysqli_fetch_assoc($result_recent)): ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $row['nama_anggota']; ?></td>
                        <td><?php echo $row['judul']; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($row['tanggal_pinjam'])); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($row['tanggal_kembali'])); ?></td>
                        <td>
                            <span class="status <?php echo $row['status']; ?>">
                                <?php echo ucfirst($row['status']); ?>
                            </span>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <?php else: ?>
            <p style="color: #777; text-align: center; padding: 20px;">Belum ada data peminjaman</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>