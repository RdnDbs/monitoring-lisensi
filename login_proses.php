<?php
session_start();
include 'config/koneksi.php';

$username = $_POST['username'];
$password = $_POST['password'];

$q = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
$user = mysqli_fetch_assoc($q);

if (!$user) {
  header("Location: login.php?error=user");
  exit;
}

// JIKA PASSWORD HASH
if (!password_verify($password, $user['password'])) {
  header("Location: login.php?error=pass");
  exit;
}

$_SESSION['login'] = true;
$_SESSION['username'] = $user['username'];
$_SESSION['role'] = $user['role'];

header("Location: ../dashboard/index.php");
exit;
