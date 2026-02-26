<?php
session_start();

/*
|--------------------------------------------------------------------------
| LOAD DATABASE
|--------------------------------------------------------------------------
*/
include "config/database.php";

if (!isset($conn)) {
    die("Koneksi database tidak ditemukan.");
}

/*
|--------------------------------------------------------------------------
| JIKA SUDAH LOGIN
|--------------------------------------------------------------------------
*/
if (isset($_SESSION['login'])) {
    header("Location: /dashboard/index.php");
    exit;
}

$error = "";

/*
|--------------------------------------------------------------------------
| PROSES LOGIN
|--------------------------------------------------------------------------
*/
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username  = mysqli_real_escape_string($conn, $_POST['username']);
    $password  = md5($_POST['password']);
    $ip        = $_SERVER['REMOTE_ADDR'];
    $userAgent = mysqli_real_escape_string($conn, $_SERVER['HTTP_USER_AGENT']);

    $sql = "SELECT * FROM users 
            WHERE username='$username' 
            AND password='$password' 
            LIMIT 1";

    $result = mysqli_query($conn, $sql);

    /*
    |--------------------------------------------------------------------------
    | LOGIN BERHASIL
    |--------------------------------------------------------------------------
    */
    if ($result && mysqli_num_rows($result) == 1) {

        $user = mysqli_fetch_assoc($result);

        // Cek status user
        if (isset($user['status']) && $user['status'] !== 'active') {

            $error = "Akun Anda tidak aktif.";

            // LOG INACTIVE LOGIN
            mysqli_query($conn, "
                INSERT INTO users_log (username, ip_address, user_agent, status, noted)
                VALUES ('$username', '$ip', '$userAgent', 'Failed', 'Login - Inactive')
            ");

        } else {

            // Set session
            $_SESSION['login']    = true;
            $_SESSION['username'] = $user['username'];
            $_SESSION['role']     = $user['role'] ?? 'user';

            // Update last_login
            mysqli_query($conn, "
                UPDATE users 
                SET last_login = NOW() 
                WHERE id=".$user['id']
            );

            // LOG SUCCESS LOGIN
            mysqli_query($conn, "
                INSERT INTO users_log (username, ip_address, user_agent, status, noted)
                VALUES ('{$user['username']}', '$ip', '$userAgent', 'Success', 'Login')
            ");

            header("Location: /dashboard/index.php");
            exit;
        }

    /*
    |--------------------------------------------------------------------------
    | LOGIN GAGAL
    |--------------------------------------------------------------------------
    */
    } else {

        $error = "Username atau Password salah";

        // LOG FAILED LOGIN
        mysqli_query($conn, "
            INSERT INTO users_log (username, ip_address, user_agent, status, noted)
            VALUES ('$username', '$ip', '$userAgent', 'Failed', 'Login')
        ");

        /*
        |--------------------------------------------------------------------------
        | AUTO BLOCK JIKA 5x GAGAL DALAM 5 MENIT
        |--------------------------------------------------------------------------
        */
        $check = mysqli_query($conn, "
            SELECT COUNT(*) as total 
            FROM users_log 
            WHERE username='$username'
            AND status='Failed'
            AND created_at >= DATE_SUB(NOW(), INTERVAL 5 MINUTE)
        ");

        $row = mysqli_fetch_assoc($check);

        if ($row['total'] >= 5) {

            mysqli_query($conn, "
                UPDATE users 
                SET status='inactive'
                WHERE username='$username'
            ");
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Login Monitoring Lisensi</title>
<style>
body {
  background: linear-gradient(135deg, #1e3c72, #2a5298);
  font-family: Arial, sans-serif;
}
.login-box {
  width: 360px;
  margin: 120px auto;
  background: #fff;
  padding: 30px;
  border-radius: 12px;
  box-shadow: 0 10px 30px rgba(0,0,0,.2);
  text-align: center;
}
.login-box h2 {
  margin-bottom: 25px;
  color: #2a5298;
}
.login-box input {
  width: 100%;
  padding: 12px;
  margin-bottom: 15px;
  border-radius: 8px;
  border: 1px solid #ccc;
}
.login-box button {
  width: 100%;
  padding: 12px;
  border: none;
  border-radius: 8px;
  background: #2a5298;
  color: #fff;
  font-size: 16px;
  cursor: pointer;
}
.login-box button:hover {
  background: #1e3c72;
}
.error {
  margin-top: 15px;
  color: #d9534f;
  font-weight: bold;
}
</style>
</head>

<body>

<div class="login-box">
  <form method="POST">
    <h2>Login Monitoring Lisensi</h2>
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
  </form>

  <?php if ($error != ""): ?>
    <div class="error"><?= $error ?></div>
  <?php endif; ?>
</div>

</body>
</html>