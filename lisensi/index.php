<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: /index.php");
    exit;
}

require_once "../config/database.php";
include "../layout/header.php";

$status_filter = $_GET['status'] ?? 'all';
$isAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
?>

<!-- PRINT HEADER -->
<div class="print-header text-center mb-4">
  <h2><b>LAPORAN DATA LISENSI</b></h2>
  <p>Tanggal Cetak: <?= date('d F Y H:i') ?></p>
  <hr>
</div>

<div class="card">
  <div class="card-header">
    <div class="d-flex align-items-center">
      <h3 class="mb-0">
        <i class="fas fa-file-contract mr-2 text-primary"></i>
        Data Lisensi
      </h3>

      <div class="ml-auto d-flex align-items-center">

        <!-- EXPORT -->
        <a href="#" onclick="exportPDF()" class="btn btn-danger btn-sm mr-2">
          <i class="fas fa-file-pdf"></i> Export PDF
        </a>

        <!-- ADMIN ONLY -->
        <?php if ($isAdmin): ?>
        <a href="tambah.php" class="btn btn-success btn-sm mr-2">
          <i class="fas fa-plus"></i> Tambah
        </a>

        <a href="import.php" class="btn btn-primary btn-sm mr-2">
          <i class="fas fa-file-excel"></i> Import Excel
        </a>
        <?php endif; ?>

        <!-- FILTER (SEMUA USER) -->
        <form method="get" class="mb-0">
          <select id="filterStatus"
                  name="status"
                  class="form-control form-control-sm font-weight-bold text-white"
                  onchange="this.form.submit()">
            <option value="all" <?= $status_filter=='all'?'selected':'' ?>>All</option>
            <option value="active" <?= $status_filter=='active'?'selected':'' ?>>Active</option>
            <option value="expiring" <?= $status_filter=='expiring'?'selected':'' ?>>Expiring</option>
            <option value="expired" <?= $status_filter=='expired'?'selected':'' ?>>Expired</option>
          </select>
        </form>

      </div>
    </div>
  </div>

  <div class="card-body table-responsive">
    <table class="table table-bordered">
      <thead class="text-center">
        <tr>
          <th>Nama Layanan</th>
          <th>Nama PIC</th>
          <th>Kontak PIC</th>
          <th>Owned</th>
          <th>Mulai</th>
          <th>Berakhir</th>
          <th>Sisa</th>
          <th>Status</th>
          <?php if ($isAdmin): ?>
          <th width="110" class="no-print">Aksi</th>
          <?php endif; ?>
        </tr>
      </thead>
      <tbody>

<?php
$today = new DateTime();
$q = mysqli_query($conn, "SELECT * FROM lisensi ORDER BY tanggal_berakhir ASC");
$has_data = false;

while ($r = mysqli_fetch_assoc($q)) {

    $end  = new DateTime($r['tanggal_berakhir']);
    $sisa = ($today > $end) ? 0 : $today->diff($end)->days;

    if ($today > $end) {
        $status = 'expired';
        $badge  = "<span class='badge badge-danger'>Expired</span>";
    } elseif ($sisa <= 90) {
        $status = 'expiring';
        $badge  = "<span class='badge badge-warning'>Expiring</span>";
    } else {
        $status = 'active';
        $badge  = "<span class='badge badge-success'>Active</span>";
    }

    if ($status_filter !== 'all' && $status_filter !== $status) continue;

    $has_data = true;
?>
<tr>
  <td><?= htmlspecialchars($r['nama_layanan']) ?></td>
  <td><?= htmlspecialchars($r['nama_pic']) ?></td>
  <td><?= htmlspecialchars($r['kontak_pic']) ?></td>
  <td><?= htmlspecialchars($r['owned_pengguna']) ?></td>
  <td class="text-center"><?= $r['tanggal_mulai'] ?></td>
  <td class="text-center"><?= $r['tanggal_berakhir'] ?></td>
  <td class="text-center font-weight-bold">
  <?= $sisa ?> hari
</td>
  <td class="text-center"><?= $badge ?></td>

  <?php if ($isAdmin): ?>
  <td class="text-center no-print">
    <a href="edit.php?id=<?= $r['id'] ?>" class="btn btn-warning btn-sm">
      <i class="fas fa-pen"></i>
    </a>
    <a href="hapus.php?id=<?= $r['id'] ?>"
       class="btn btn-danger btn-sm"
       onclick="return confirm('Yakin hapus data ini?')">
      <i class="fas fa-trash"></i>
    </a>
  </td>
  <?php endif; ?>
</tr>
<?php } ?>

<?php if (!$has_data): ?>
<tr>
  <td colspan="<?= $isAdmin ? '9' : '8' ?>" class="text-center text-muted">
    Data kosong
  </td>
</tr>
<?php endif; ?>

      </tbody>
    </table>
  </div>
</div>

<!-- FILTER COLOR SCRIPT -->
<script>
const filter = document.getElementById('filterStatus');

function setFilterColor() {
  filter.classList.remove(
    'bg-secondary','bg-success','bg-warning','bg-danger',
    'text-white','text-dark'
  );

  switch (filter.value) {
    case 'active':
      filter.classList.add('bg-success','text-white');
      break;
    case 'expiring':
      filter.classList.add('bg-warning','text-dark');
      break;
    case 'expired':
      filter.classList.add('bg-danger','text-white');
      break;
    default:
      filter.classList.add('bg-secondary','text-white');
  }
}

setFilterColor();
</script>

<script>
function exportPDF() {
  window.print();
}
</script>

<style>
.print-header { display: none; }

#filterStatus {
  transition: all 0.3s ease;
}

@media print {
  .print-header { display: block !important; }
  .no-print,
  .btn,
  .main-sidebar,
  .navbar,
  .card-header form {
    display: none !important;
  }
}
</style>

<?php include "../layout/footer.php"; ?>