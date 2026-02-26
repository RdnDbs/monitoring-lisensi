<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isLogin = isset($_SESSION['login']);
$isAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <a href="/index.php" class="brand-link text-center">
    <span class="brand-text font-weight-bold">MONITORING</span>
  </a>

  <style>
    .main-sidebar {
      background: linear-gradient(180deg, #0f4c81, #0b3a66);
    }

    .brand-link {
      background-color: #0b3a66 !important;
      border-bottom: 1px solid rgba(255,255,255,.1);
    }

    .nav-sidebar .nav-link p,
    .nav-sidebar .nav-link i {
      color: #eaf2ff;
    }

    .nav-sidebar .nav-link:hover {
      background-color: rgba(255,255,255,.12);
    }

    .nav-sidebar .nav-link.active {
      background-color: #1e88e5;
      color: #fff;
    }
  </style>

  <div class="sidebar">
    <nav>
      <ul class="nav nav-pills nav-sidebar flex-column">

        <?php if ($isLogin): ?>

          <!-- Dashboard -->
          <li class="nav-item">
            <a href="../dashboard/index.php" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>Dashboard</p>
            </a>
          </li>

          <!-- Data Lisensi -->
          <li class="nav-item">
            <a href="../lisensi/index.php" class="nav-link">
              <i class="nav-icon fas fa-file-contract"></i>
              <p>Data Lisensi</p>
            </a>
          </li>

          <?php if ($isAdmin): ?>

          <!-- Manajemen User -->
          <li class="nav-item">
            <a href="../users/index.php" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>Manajemen User</p>
            </a>
          </li>

          <!-- Log Login -->
          <li class="nav-item">
            <a href="../users_log/index.php" class="nav-link">
              <i class="nav-icon fas fa-history"></i>
              <p>Log Login</p>
            </a>
          </li>

          <?php endif; ?>

          <!-- Logout -->
          <li class="nav-item">
            <a href="../dashboard/index.php" class="nav-link text-danger">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>Logout</p>
            </a>
          </li>

        <?php else: ?>

          <!-- Login -->
          <li class="nav-item mt-3">
            <a href="../auth/login.php" class="nav-link bg-success">
              <i class="nav-icon fas fa-sign-in-alt"></i>
              <p>Login</p>
            </a>
          </li>

        <?php endif; ?>

      </ul>
    </nav>
  </div>
</aside>