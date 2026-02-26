<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: /index.php");
    exit;
}

require_once "../config/database.php";
include "../layout/header.php";

$id = (int)$_GET['id'];
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id=$id"));

if (!$user) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $nama     = mysqli_real_escape_string($conn, $_POST['nama']);
    $role     = mysqli_real_escape_string($conn, $_POST['role']);
    $status   = mysqli_real_escape_string($conn, $_POST['status']);

    // Cegah admin menonaktifkan dirinya sendiri
    if ($user['username'] === $_SESSION['username'] && $status !== 'active') {
        echo "<script>alert('Anda tidak bisa menonaktifkan akun sendiri!');</script>";
    } else {

        if (!empty($_POST['password'])) {
            $password = md5($_POST['password']);
            mysqli_query($conn, "
                UPDATE users 
                SET username='$username',
                    nama='$nama',
                    role='$role',
                    status='$status',
                    password='$password'
                WHERE id=$id
            ");
        } else {
            mysqli_query($conn, "
                UPDATE users 
                SET username='$username',
                    nama='$nama',
                    role='$role',
                    status='$status'
                WHERE id=$id
            ");
        }

        header("Location: index.php");
        exit;
    }
}
?>

<div class="card p-4">
  <h3 class="mb-4">
    <i class="fas fa-user-edit text-primary mr-2"></i>
    Edit User
  </h3>

  <form method="POST">

    <div class="form-group">
      <label>Username</label>
      <input type="text" name="username" class="form-control"
             value="<?= htmlspecialchars($user['username']) ?>" required>
    </div>

    <div class="form-group">
      <label>Nama</label>
      <input type="text" name="nama" class="form-control"
             value="<?= htmlspecialchars($user['nama'] ?? '') ?>">
    </div>

    <div class="form-group">
      <label>Role</label>
      <select name="role" class="form-control" required>
        <option value="admin" <?= $user['role']=='admin'?'selected':'' ?>>Admin</option>
        <option value="user" <?= $user['role']=='user'?'selected':'' ?>>User</option>
      </select>
    </div>

    <div class="form-group">
      <label>Status</label>
      <select name="status" class="form-control" required>
        <option value="active" <?= $user['status']=='active'?'selected':'' ?>>Active</option>
        <option value="inactive" <?= $user['status']=='inactive'?'selected':'' ?>>Inactive</option>
      </select>
    </div>

    <div class="form-group">
      <label>Password (kosongkan jika tidak diganti)</label>
      <input type="password" name="password" class="form-control">
    </div>

    <div class="mt-3">
      <button class="btn btn-primary">
        <i class="fas fa-save"></i> Update
      </button>
      <a href="index.php" class="btn btn-secondary">Kembali</a>
    </div>

  </form>
</div>

<?php include "../layout/footer.php"; ?>