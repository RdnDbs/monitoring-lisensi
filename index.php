<?php
/*
|--------------------------------------------------------------------------
| ROOT FILE
|--------------------------------------------------------------------------
| Semua akses ke domain utama langsung ke dashboard (PUBLIC)
| Tidak ada session, tidak ada database, tidak ada login process
*/

header("Location: ../dashboard/index.php");
exit;
