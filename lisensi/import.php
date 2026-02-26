<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../index.php");
    exit;
}

include "../layout/header.php";
?>

<div class="card">
  <div class="card-header">
    <h3 class="card-title">Import Lisensi (Excel)</h3>
  </div>

  <div class="card-body">
    <form action="import_process.php" method="post" enctype="multipart/form-data">
      <div class="form-group">
        <label>File Excel (.xlsx)</label>
        <input type="file" name="file_excel" class="form-control" required accept=".xlsx">
      </div>

      <button type="submit" class="btn btn-success">
        <i class="fas fa-upload"></i> Import
      </button>

      <a href="index.php" class="btn btn-secondary">Kembali</a>
    </form>
  </div>
</div>

<?php include "../layout/footer.php"; ?>
