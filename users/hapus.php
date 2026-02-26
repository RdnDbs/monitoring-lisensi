<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: /");
    exit;
}

require_once "../config/database.php";

$id = (int)$_GET['id'];
mysqli_query($conn, "DELETE FROM users WHERE id=$id");

header("Location: index.php");
exit;
