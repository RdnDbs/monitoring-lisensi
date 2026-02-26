<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: /index.php");
    exit;
}

require_once "../config/database.php";
include "../layout/header.php";

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $nama     = mysqli_real_escape_string($conn, $_POST['nama']);
    $password = md5($_POST['password']);
    $role     = mysqli_real_escape_string($conn, $_POST['role']);
    $status   = mysqli_real_escape_string($conn, $_POST['status']);

    // Cek username sudah ada atau belum
    $cek = mysqli_query($conn, "SELECT id FROM users WHERE username='$username'");
    if (mysqli_num_rows($cek) > 0) {
        $error = "Username sudah digunakan!";
    } else {

        mysqli_query($conn, "
            INSERT INTO users (username, nama, password, role, status, created_at)
            VALUES ('$username','$nama','$password','$role','$status', NOW())
        ");

        header("Location: index.php");
        exit;
    }
}
?>

<div class="card p-4">
  <h3 class="mb-4">
    <i class="fas fa-user-plus text-success mr-2"></i>
    Tambah User
  </h3>

  <?php if ($error): ?>
    <div class="alert alert-danger"><?= $error ?></div>
  <?php endif; ?>

  <form method="POST">

    <div class="form-group">
      <label>Username</label>
      <input type="text" name="username" class="form-control" required>
    </div>

    <div class="form-group">
      <label>Nama</label>
      <input type="text" name="nama" class="form-control">
    </div>

    <div class="form-group">
      <label>Password</label>
      <input type="password" name="password" class="form-control" required>
    </div>

    <div class="form-group">
      <label>Role</label>
      <select name="role" class="form-control" required>
        <option value="user">User</option>
        <option value="admin">Admin</option>
      </select>
    </div>

    <div class="form-group">
      <label>Status</label>
      <select name="status" class="form-control" required>
        <option value="active">Active</option>
        <option value="inactive">Inactive</option>
      </select>
    </div>

    <div class="mt-3">
      <button class="btn btn-success">
        <i class="fas fa-save"></i> Simpan
      </button>
      <a href="index.php" class="btn btn-secondary">Kembali</a>
    </div>

  </form>
</div>

<?php include "../layout/footer.php"; ?>