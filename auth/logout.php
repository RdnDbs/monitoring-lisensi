<?php
session_start();

/*
|--------------------------------------------------------------------------
| LOAD DATABASE
|--------------------------------------------------------------------------
*/
include "../config/database.php";

/*
|--------------------------------------------------------------------------
| SIMPAN DATA SEBELUM SESSION DIHAPUS
|--------------------------------------------------------------------------
*/
$username  = $_SESSION['username'] ?? '-';
$ip        = $_SERVER['REMOTE_ADDR'] ?? '-';
$userAgent = isset($_SERVER['HTTP_USER_AGENT'])
                ? mysqli_real_escape_string($conn, $_SERVER['HTTP_USER_AGENT'])
                : '-';

/*
|--------------------------------------------------------------------------
| INSERT LOG LOGOUT
|--------------------------------------------------------------------------
*/
if (isset($_SESSION['login'])) {
    mysqli_query($conn, "
        INSERT INTO users_log (username, ip_address, user_agent, status, noted)
        VALUES ('$username', '$ip', '$userAgent', 'Success', 'Logout')
    ");
}

/*
|--------------------------------------------------------------------------
| HAPUS SEMUA DATA SESSION
|--------------------------------------------------------------------------
*/
$_SESSION = [];

/*
|--------------------------------------------------------------------------
| HANCURKAN SESSION
|--------------------------------------------------------------------------
*/
session_destroy();

/*
|--------------------------------------------------------------------------
| HAPUS COOKIE SESSION
|--------------------------------------------------------------------------
*/
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

/*
|--------------------------------------------------------------------------
| REDIRECT KE LOGIN
|--------------------------------------------------------------------------
*/
header("Location: /index.php");
exit;