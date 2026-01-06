<?php
session_start();

if (isset($_SESSION['login'])) {
    header("Location: menu.php");
    exit();
}

include 'koneksi.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input_username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $input_password = $_POST['password'];
    
    $query = "SELECT * FROM user WHERE username = '$input_username'";
    $result = mysqli_query($koneksi, $query);
    
    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        $db_password = $user['password'];
        
        if ($input_password == $db_password || md5($input_password) == $db_password) {
            $_SESSION['login'] = true;
            $_SESSION['user_id'] = $user['id_user'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            
            header("Location: menu.php");
            exit();
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username/Email tidak ditemukan!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>LOGIN PERPUSTAKAAN</title>
    <style>
        body { font-family: Arial; background: #4A90E2; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-box { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 20px rgba(0,0,0,0.2); width: 350px; }
        h2 { text-align: center; color: #333; margin-bottom: 30px; }
        .input-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; color: #555; }
        input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px; }
        button { width: 100%; padding: 12px; background: #4A90E2; color: white; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; }
        button:hover { background: #357AE8; }
        .error { background: #FFEBEE; color: #C62828; padding: 10px; border-radius: 5px; margin-bottom: 20px; text-align: center; }
        .info { background: #E3F2FD; padding: 10px; border-radius: 5px; margin-top: 20px; font-size: 13px; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>📚 LOGIN PERPUSTAKAAN</h2>
        
        <?php if($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="input-group">
                <!-- INI YANG DIGANTI -->
                <label>Username atau Email</label>
                <input type="text" name="username" placeholder="admin123@gmail.com / petugas1" required>
            </div>
            
            <div class="input-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="admin123 / petugas123" required>
            </div>
            
            <button type="submit">MASUK</button>
        </form>
        
        <div class="info">
            <strong>Demo Account:</strong><br>
            • admin123@gmail.com / admin123<br>
            • petugas1 / petugas123
        </div>
    </div>
</body>
</html>