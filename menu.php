<?php
session_start();
if(!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>MENU UTAMA</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        .header {
            background: linear-gradient(to right, #2c3e50, #4a6491);
            color: white;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
            padding: 40px;
            max-width: 1400px;
            margin: 0 auto;
        }
        .menu-card {
            background: white;
            padding: 30px 25px;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            text-align: center;
            text-decoration: none;
            color: #333;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255,255,255,0.5);
        }
        .menu-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
            border-color: #3498db;
        }
        .menu-icon {
            font-size: 60px;
            margin-bottom: 20px;
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: linear-gradient(135deg, #f0f7ff, #e3eeff);
        }
        .menu-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 10px;
            color: #2c3e50;
        }
        .menu-desc {
            color: #7f8c8d;
            font-size: 14px;
            line-height: 1.5;
        }
        .logout-btn {
            background: linear-gradient(to right, #e74c3c, #c0392b);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .logout-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
            color: #ecf0f1;
            font-size: 15px;
        }
        .user-badge {
            background: rgba(255,255,255,0.2);
            padding: 8px 15px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .welcome-text {
            font-size: 16px;
            margin-bottom: 5px;
        }
        .role-badge {
            background: #3498db;
            color: white;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }
        @media (max-width: 768px) {
            .container {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                padding: 20px;
                gap: 20px;
            }
            .header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
            .user-info {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <h1>📚 SISTEM PERPUSTAKAAN DIGITAL</h1>
            <div class="welcome-text">Selamat datang di sistem manajemen perpustakaan</div>
        </div>
        <div class="user-info">
            <div class="user-badge">
                <span>👤</span>
                <div>
                    <div style="font-weight:500;"><?php echo $_SESSION['username']; ?></div>
                    <div class="role-badge"><?php echo $_SESSION['role']; ?></div>
                </div>
            </div>
            <a href="logout.php">
                <button class="logout-btn">
                    <span>🚪</span>
                    Logout
                </button>
            </a>
        </div>
    </div>
    
    <div class="container">
        <!-- DASHBOARD -->
        <a href="main.php" class="menu-card">
            <div class="menu-icon" style="background:linear-gradient(135deg, #E3F2FD, #BBDEFB);">📊</div>
            <div class="menu-title">DASHBOARD</div>
            <div class="menu-desc">Statistik dan ringkasan sistem</div>
        </a>
        
        <!-- DATA BUKU -->
        <a href="form/buku/data.php" class="menu-card">
            <div class="menu-icon" style="background:linear-gradient(135deg, #E8F5E9, #C8E6C9);">📕</div>
            <div class="menu-title">DATA BUKU</div>
            <div class="menu-desc">Tambah, edit, hapus koleksi buku</div>
        </a>
        
        <!-- DATA ANGGOTA -->
        <a href="form/anggota/data.php" class="menu-card">
            <div class="menu-icon" style="background:linear-gradient(135deg, #F3E5F5, #E1BEE7);">👥</div>
            <div class="menu-title">DATA ANGGOTA</div>
            <div class="menu-desc">Kelola data anggota perpustakaan</div>
        </a>
        
        <!-- PEMINJAMAN -->
        <a href="form/peminjaman/data.php" class="menu-card">
            <div class="menu-icon" style="background:linear-gradient(135deg, #FFF3E0, #FFE0B2);">📖</div>
            <div class="menu-title">PEMINJAMAN</div>
            <div class="menu-desc">Proses peminjaman dan pengembalian buku</div>
        </a>
        
        <!-- LAPORAN BUKU -->
        <a href="form/laporan/lap_buku.php" class="menu-card">
            <div class="menu-icon" style="background:linear-gradient(135deg, #E8EAF6, #C5CAE9);">📋</div>
            <div class="menu-title">LAPORAN BUKU</div>
            <div class="menu-desc">Cetak laporan data koleksi buku</div>
        </a>
        
        <!-- LAPORAN PEMINJAMAN -->
        <a href="form/laporan/lap_peminjaman.php" class="menu-card">
            <div class="menu-icon" style="background:linear-gradient(135deg, #FCE4EC, #F8BBD0);">📄</div>
            <div class="menu-title">LAPORAN PINJAM</div>
            <div class="menu-desc">Cetak laporan transaksi peminjaman</div>
        </a>
        
        <!-- PENGATURAN -->
        <a href="#" class="menu-card">
            <div class="menu-icon" style="background:linear-gradient(135deg, #E0F2F1, #B2DFDB);">⚙️</div>
            <div class="menu-title">PENGATURAN</div>
            <div class="menu-desc">Pengaturan sistem dan preferensi</div>
        </a>
        
        <!-- BANTUAN -->
        <a href="#" class="menu-card">
            <div class="menu-icon" style="background:linear-gradient(135deg, #FFF8E1, #FFECB3);">❓</div>
            <div class="menu-title">BANTUAN</div>
            <div class="menu-desc">Panduan penggunaan sistem</div>
        </a>
    </div>
</body>
</html>