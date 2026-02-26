<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../index.php");
    exit;
}

require_once "../config/database.php";
include "../layout/header.php";
?>

<div class="card">
  <div class="card-header">
    <h3 class="card-title font-weight-bold">➕ Tambah Lisensi</h3>
  </div>

  <div class="card-body">
    <form method="post">
      <div class="row">
        <div class="col-md-6 mb-3">
          <label>Nama Layanan</label>
          <input type="text" name="nama_layanan" class="form-control" required>
        </div>

        <div class="col-md-6 mb-3">
          <label>Owned / Pengguna</label>
          <input type="text" name="owned_pengguna" class="form-control" required>
        </div>

        <div class="col-md-4 mb-3">
          <label>Nama PIC</label>
          <input type="text" name="nama_pic" class="form-control" required>
        </div>

        <div class="col-md-4 mb-3">
          <label>Kontak PIC</label>
          <input type="text" name="kontak_pic" class="form-control" required>
        </div>

        <div class="col-md-4 mb-3">
          <label>Email PIC</label>
          <input type="email" name="email_pic" class="form-control">
        </div>

        <div class="col-md-6 mb-3">
          <label>Tanggal Mulai</label>
          <input type="date" name="tanggal_mulai" class="form-control" required>
        </div>

        <div class="col-md-6 mb-3">
          <label>Tanggal Berakhir</label>
          <input type="date" name="tanggal_berakhir" class="form-control" required>
        </div>
      </div>

      <div class="mt-3">
        <button type="submit" name="simpan" class="btn btn-success">
          <i class="fas fa-save"></i> Simpan
        </button>
        <a href="index.php" class="btn btn-secondary">Kembali</a>
      </div>
    </form>
  </div>
</div>

<?php
if (isset($_POST['simpan'])) {
    $stmt = $conn->prepare("
      INSERT INTO lisensi 
      (nama_layanan, nama_pic, kontak_pic, email_pic, owned_pengguna, tanggal_mulai, tanggal_berakhir)
      VALUES (?,?,?,?,?,?,?)
    ");

    $stmt->bind_param(
        "sssssss",
        $_POST['nama_layanan'],
        $_POST['nama_pic'],
        $_POST['kontak_pic'],
        $_POST['email_pic'],
        $_POST['owned_pengguna'],
        $_POST['tanggal_mulai'],
        $_POST['tanggal_berakhir']
    );

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil ditambahkan');location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan data');</script>";
    }
}
include "../layout/footer.php";
?>
