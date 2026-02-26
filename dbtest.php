<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$conn = mysqli_connect(
  "sql213.infinityfree.com",
  "if0_40993166",
  "LJXekNo8cINiJ",
  "if0_40993166_monitoring_lisensi"
);

if (!$conn) {
    die("❌ DB ERROR: " . mysqli_connect_error());
}

echo "✅ DB CONNECTED BRO 🔥";
