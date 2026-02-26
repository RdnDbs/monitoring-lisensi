<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

require_once __DIR__ . '/../config/database.php';

// kalau sudah login, tendang ke dashboard
if (isset($_SESSION['login'])) {
  header("Location: /mylisensi/lisensi/index.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login Monitoring Lisensi</title>

<style>
  body {
    background: linear-gradient(135deg, #1e3c72, #2a5298);
    font-family: Arial, sans-serif;
  }
  .login-box {
    width: 360px;
    margin: 120px auto;
    background: #ffffff;
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
    font-size: 14px;
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

    <button type="submit" name="login">Login</button>
  </form>

<?php
if (isset($_POST['login'])) {

  $u = mysqli_real_escape_string($conn, $_POST['username']);
  $p = md5($_POST['password']);

  $q = mysqli_query($conn, "
    SELECT id, username, role 
    FROM users 
    WHERE username='$u' AND password='$p'
    LIMIT 1
  ");

  if (mysqli_num_rows($q) === 1) {

    $data = mysqli_fetch_assoc($q);

    // 🔥 SIMPAN KE SESSION
    $_SESSION['login']    = true;
    $_SESSION['user_id']  = $data['id'];
    $_SESSION['username'] = $data['username'];
    $_SESSION['role']     = $data['role']; // admin / user

    header("Location: /mylisensi/lisensi/index.php");
    exit;

  } else {
    echo "<div class='error'>Username atau Password salah</div>";
  }
}
?>

</div>

</body>
</html>
