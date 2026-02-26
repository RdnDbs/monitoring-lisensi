<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: /index.php");
    exit;
}

require_once "../config/database.php";
include "../layout/header.php";

$total_user  = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM users"));
$total_admin = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM users WHERE role='admin'"));
$total_user_biasa = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM users WHERE role='user'"));
?>

<div class="container-fluid">

  <div class="d-flex align-items-center mb-3">
    <h3 class="mb-0">
      <i class="fas fa-users mr-2 text-primary"></i>
      Manajemen User
    </h3>

    <div class="ml-auto">
      <a href="tambah.php" class="btn btn-success btn-sm">
        <i class="fas fa-plus"></i> Tambah User
      </a>
    </div>
  </div>

  <!-- STATISTIK CARD -->
  <div class="row mb-4">

    <div class="col-md-4">
      <div class="card bg-info text-white shadow">
        <div class="card-body">
          <h5>Total User</h5>
          <h2><?= $total_user ?></h2>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card bg-success text-white shadow">
        <div class="card-body">
          <h5>Admin</h5>
          <h2><?= $total_admin ?></h2>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card bg-secondary text-white shadow">
        <div class="card-body">
          <h5>User Biasa</h5>
          <h2><?= $total_user_biasa ?></h2>
        </div>
      </div>
    </div>

  </div>

  <!-- TABEL -->
  <div class="card shadow-sm">
    <div class="card-body table-responsive">

      <table class="table table-bordered table-hover">
        <thead class="thead-light">
          <tr>
            <th>Username</th>
            <th>Nama</th>
            <th>Role</th>
            <th>Status</th>
            <th>Last Login</th>
            <th>Created</th>
            <th width="150" class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>

        <?php
        $q = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");

        if (mysqli_num_rows($q) == 0) {
            echo "<tr><td colspan='7' class='text-center text-muted'>Tidak ada data</td></tr>";
        }

        while ($r = mysqli_fetch_assoc($q)) :

          $roleBadge = ($r['role'] === 'admin')
            ? "<span class='badge badge-success'>Admin</span>"
            : "<span class='badge badge-secondary'>User</span>";

          $statusBadge = ($r['status'] === 'active')
            ? "<span class='badge badge-primary'>Active</span>"
            : "<span class='badge badge-danger'>Inactive</span>";
        ?>

          <tr>
            <td><?= htmlspecialchars($r['username']) ?></td>
            <td><?= htmlspecialchars($r['nama'] ?? '-') ?></td>
            <td><?= $roleBadge ?></td>
            <td><?= $statusBadge ?></td>
            <td><?= $r['last_login'] ? date('d M Y H:i', strtotime($r['last_login'])) : '-' ?></td>
            <td><?= $r['created_at'] ? date('d M Y', strtotime($r['created_at'])) : '-' ?></td>

            <td class="text-center">
              <a href="edit.php?id=<?= $r['id'] ?>" class="btn btn-warning btn-sm">
                <i class="fas fa-pen"></i>
              </a>

              <?php if ($r['username'] !== $_SESSION['username']): ?>
              <a href="hapus.php?id=<?= $r['id'] ?>"
                 class="btn btn-danger btn-sm"
                 onclick="return confirm('Yakin hapus user ini?')">
                <i class="fas fa-trash"></i>
              </a>
              <?php endif; ?>
            </td>
          </tr>

        <?php endwhile; ?>

        </tbody>
      </table>

    </div>
  </div>

</div>

<?php include "../layout/footer.php"; ?>