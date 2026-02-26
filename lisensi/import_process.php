<?php
session_start();
require_once "../config/database.php";

/* ==============================
   CEK FILE
================================ */
if (!isset($_FILES['file_excel']) || $_FILES['file_excel']['error'] != 0) {
    die("File tidak ditemukan");
}

$file = $_FILES['file_excel']['tmp_name'];

/* ==============================
   LOAD EXCEL
================================ */
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Shared\Date;

$reader = new Xlsx();
$spreadsheet = $reader->load($file);
$sheet = $spreadsheet->getActiveSheet()->toArray();

$success = 0;

/* ==============================
   LOOP DATA
================================ */
foreach ($sheet as $i => $row) {

    if ($i == 0) continue; // skip header

    $nama = mysqli_real_escape_string($conn, trim($row[0] ?? ''));

    // HANDLE FORMAT TANGGAL EXCEL
    $mulai = is_numeric($row[1])
        ? Date::excelToDateTimeObject($row[1])->format('Y-m-d')
        : date('Y-m-d', strtotime($row[1]));

    $akhir = is_numeric($row[2])
        ? Date::excelToDateTimeObject($row[2])->format('Y-m-d')
        : date('Y-m-d', strtotime($row[2]));

    $ket = mysqli_real_escape_string($conn, trim($row[3] ?? ''));

    if ($nama == '' || !$mulai || !$akhir) continue;

    // HITUNG SISA HARI (INT)
    $sisa = floor((strtotime($akhir) - strtotime(date('Y-m-d'))) / 86400);
    if ($sisa < 0) $sisa = 0;

    mysqli_query($conn, "
        INSERT INTO lisensi 
        (nama_layanan, tanggal_mulai, tanggal_berakhir, keterangan, sisa_hari)
        VALUES
        ('$nama','$mulai','$akhir','$ket','$sisa')
    ");

    $success++;
}

/* ==============================
   REDIRECT
================================ */
header("Location: index.php?import=success&jumlah=$success");
exit;
