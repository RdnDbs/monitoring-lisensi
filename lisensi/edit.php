<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../index.php");
    exit;
}

require_once "../config/database.php";

// ================== AMBIL ID ==================
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $conn->prepare("SELECT * FROM lisensi WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) {
    echo "<script>alert('Data tidak ditemukan');location='index.php';</script>";
    exit;
}

// ================== PROSES UPDATE ==================
if (isset($_POST['update'])) {

    $nama_layanan     = $_POST['nama_layanan'];
    $nama_pic         = $_POST['nama_pic'];
    $kontak_pic       = $_POST['kontak_pic'];
    $owned_pengguna   = $_POST['owned_pengguna'];
    $tanggal_mulai    = $_POST['tanggal_mulai'];
    $tanggal_berakhir = $_POST['tanggal_berakhir'];

    $update = $conn->prepare("
        UPDATE lisensi SET
            nama_layanan = ?,
            nama_pic = ?,
            kontak_pic = ?,
            owned_pengguna = ?,
            tanggal_mulai = ?,
            tanggal_berakhir = ?
        WHERE id = ?
    ");

    // ✅ 6 string + 1 integer
    $update->bind_param(
        "ssssssi",
        $nama_layanan,
        $nama_pic,
        $kontak_pic,
        $owned_pengguna,
        $tanggal_mulai,
        $tanggal_berakhir,
        $id
    );

    if ($update->execute()) {
        header("Location: index.php?msg=updated");
        exit;
    } else {
        $error = "Gagal update data";
    }
}

include "../layout/header.php";
?>

<div class="card">
  <div class="card-header">
    <h3 class="card-title font-weight-bold">✏️ Edit Lisensi</h3>
  </div>

  <div class="card-body">
    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="post">
      <div class="row">
        <div class="col-md-6 mb-3">
          <label>Nama Layanan</label>
          <input type="text" name="nama_layanan" class="form-control"
                 value="<?= htmlspecialchars($data['nama_layanan']) ?>" required>
        </div>

        <div class="col-md-6 mb-3">
          <label>Owned / Pengguna</label>
          <input type="text" name="owned_pengguna" class="form-control"
                 value="<?= htmlspecialchars($data['owned_pengguna']) ?>" required>
        </div>

        <div class="col-md-4 mb-3">
          <label>Nama PIC</label>
          <input type="text" name="nama_pic" class="form-control"
                 value="<?= htmlspecialchars($data['nama_pic']) ?>" required>
        </div>

        <div class="col-md-4 mb-3">
          <label>Kontak PIC</label>
          <input type="text" name="kontak_pic" class="form-control"
                 value="<?= htmlspecialchars($data['kontak_pic']) ?>" required>
        </div>

        <div class="col-md-6 mb-3">
          <label>Tanggal Mulai</label>
          <input type="date" name="tanggal_mulai" class="form-control"
                 value="<?= $data['tanggal_mulai'] ?>" required>
        </div>

        <div class="col-md-6 mb-3">
          <label>Tanggal Berakhir</label>
          <input type="date" name="tanggal_berakhir" class="form-control"
                 value="<?= $data['tanggal_berakhir'] ?>" required>
        </div>
      </div>

      <div class="mt-3">
        <button type="submit" name="update" class="btn btn-warning">
          <i class="fas fa-save"></i> Update
        </button>
        <a href="index.php" class="btn btn-secondary">Kembali</a>
      </div>
    </form>
  </div>
</div>

<?php include "../layout/footer.php"; ?>
