<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: /index.php");
    exit;
}

require_once "../config/database.php";
include "../layout/header.php";

/*
|--------------------------------------------------------------------------
| FILTER
|--------------------------------------------------------------------------
*/
$filterDate   = $_GET['date'] ?? 'all';
$filterStatus = $_GET['status'] ?? 'all';

$where = [];

// Filter tanggal
if ($filterDate == 'today') {
    $where[] = "DATE(created_at) = CURDATE()";
} elseif ($filterDate == '7days') {
    $where[] = "created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
} elseif ($filterDate == '30days') {
    $where[] = "created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
}

// Filter status
if ($filterStatus == 'success') {
    $where[] = "status = 'Success'";
} elseif ($filterStatus == 'failed') {
    $where[] = "status = 'Failed'";
}

$whereSQL = "";
if (!empty($where)) {
    $whereSQL = "WHERE " . implode(" AND ", $where);
}

/*
|--------------------------------------------------------------------------
| STATISTIK
|--------------------------------------------------------------------------
*/
$totalLog = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) as total FROM users_log
"))['total'];

$totalSuccess = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) as total FROM users_log WHERE status='Success'
"))['total'];

$totalFailed = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) as total FROM users_log WHERE status='Failed'
"))['total'];
?>

<div class="container-fluid">

  <div class="d-flex align-items-center mb-3">
    <h3 class="mb-0">
      <i class="fas fa-history text-primary mr-2"></i>
      Log Login
    </h3>

    <div class="ml-auto d-flex">

      <form method="GET" class="mr-2">
        <select name="date" class="form-control form-control-sm" onchange="this.form.submit()">
          <option value="all" <?= $filterDate=='all'?'selected':'' ?>>Semua Tanggal</option>
          <option value="today" <?= $filterDate=='today'?'selected':'' ?>>Hari Ini</option>
          <option value="7days" <?= $filterDate=='7days'?'selected':'' ?>>7 Hari</option>
          <option value="30days" <?= $filterDate=='30days'?'selected':'' ?>>30 Hari</option>
        </select>
        <input type="hidden" name="status" value="<?= $filterStatus ?>">
      </form>

      <form method="GET">
        <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
          <option value="all" <?= $filterStatus=='all'?'selected':'' ?>>Semua Status</option>
          <option value="success" <?= $filterStatus=='success'?'selected':'' ?>>Success</option>
          <option value="failed" <?= $filterStatus=='failed'?'selected':'' ?>>Failed</option>
        </select>
        <input type="hidden" name="date" value="<?= $filterDate ?>">
      </form>

    </div>
  </div>

  <!-- Statistik -->
  <div class="row mb-3">
    <div class="col-md-4">
      <div class="card bg-info text-white">
        <div class="card-body">
          <h5>Total Log</h5>
          <h3><?= $totalLog ?></h3>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card bg-success text-white">
        <div class="card-body">
          <h5>Success</h5>
          <h3><?= $totalSuccess ?></h3>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card bg-danger text-white">
        <div class="card-body">
          <h5>Failed</h5>
          <h3><?= $totalFailed ?></h3>
        </div>
      </div>
    </div>
  </div>

  <!-- Table -->
  <div class="card shadow-sm">
    <div class="card-body table-responsive">

      <table class="table table-bordered table-hover">
        <thead class="thead-light text-center">
          <tr>
            <th>No</th>
            <th>Username</th>
            <th>IP</th>
            <th>User Agent</th>
            <th>Status</th>
            <th>Noted</th>
            <th>Created</th>
          </tr>
        </thead>
        <tbody>

        <?php
        $no = 1;
        $q = mysqli_query($conn, "
            SELECT * FROM users_log 
            $whereSQL 
            ORDER BY id DESC 
            LIMIT 300
        ");

        if (mysqli_num_rows($q) == 0) {
            echo "<tr><td colspan='7' class='text-center text-muted'>Tidak ada data</td></tr>";
        }

        while ($r = mysqli_fetch_assoc($q)) :

            $badge = ($r['status'] === 'Success')
                ? "<span class='badge badge-success'>Success</span>"
                : "<span class='badge badge-danger'>Failed</span>";
        ?>

        <tr>
          <td class="text-center"><?= $no++ ?></td>
          <td><?= htmlspecialchars($r['username']) ?></td>
          <td><?= $r['ip_address'] ?></td>
          <td style="max-width:250px;overflow:hidden;text-overflow:ellipsis;">
            <?= htmlspecialchars($r['user_agent']) ?>
          </td>
          <td class="text-center"><?= $badge ?></td>
          <td class="text-center"><?= $r['noted'] ?></td>
          <td class="text-center">
            <?= date('d M Y H:i', strtotime($r['created_at'])) ?>
          </td>
        </tr>

        <?php endwhile; ?>

        </tbody>
      </table>

    </div>
  </div>

</div>

<?php include "../layout/footer.php"; ?>