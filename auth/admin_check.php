<?php
session_start();

if (!isset($_SESSION['login'])) {
  header("Location: ../login.php");
  exit;
}

if ($_SESSION['role'] !== 'admin') {
  echo "<h3 style='text-align:center;margin-top:50px;color:red'>
        Akses ditolak! Khusus Admin
        </h3>";
  exit;
}
