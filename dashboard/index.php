<?php
session_start();
require_once "../config/database.php";
include "../layout/header.php";


// =========================
// LOGIC ASLI (JANGAN DISENTUH)
// =========================
$expired = 0;
$expiring = 0;
$active  = 0;
$layanan = [];
$today = new DateTime();

$q = mysqli_query($conn, "SELECT * FROM lisensi ORDER BY tanggal_berakhir ASC");
while ($row = mysqli_fetch_assoc($q)) {

    $end = new DateTime($row['tanggal_berakhir']);
    $sisa = ($today > $end) ? 0 : $today->diff($end)->days;

    if ($sisa <= 30) {
        $warna = '#dc3545';
        $expired++;
    } elseif ($sisa <= 90) {
        $warna = '#ffc107';
        $expiring++;
    } else {
        $warna = '#28a745';
        $active++;
    }

    $layanan[] = [
        'nama'  => $row['nama_layanan'],
        'hari'  => $sisa,
        'warna' => $warna
    ];

    if ($row['sisa_hari'] != $sisa) {
        $id = (int)$row['id'];
        mysqli_query($conn, "UPDATE lisensi SET sisa_hari=$sisa WHERE id=$id");
    }
}
?>

<style>
.alert-blink {
  animation: alertBlink 2s infinite;
}

@keyframes alertBlink {
  0%,100% {
    box-shadow: 0 0 0 rgba(220,53,69,0);
    opacity: 1;
  }
  50% {
    box-shadow: 0 0 18px rgba(220,53,69,.6);
    opacity: .85;
  }
}
/* ===== DARK MODE CLEAN ===== */

body.dark {
  background:#0b1220 !important;
  color:#e2e8f0 !important;
}

/* Card */
.dark .card {
  background:#111c2e !important;
  border:1px solid #1f2a40;
  color:#e2e8f0 !important;
}

/* Stat Card tetap pakai gradient tapi lebih kalem */
.dark .bg-active {
  background:linear-gradient(135deg,#166534,#15803d);
}

.dark .bg-expiring {
  background:linear-gradient(135deg,#b45309,#d97706);
  color:#fff !important;
}

.dark .bg-expired {
  background:linear-gradient(135deg,#7f1d1d,#991b1b);
}

/* Card Header */
.dark .card-header {
  background:#0f172a !important;
  border-bottom:1px solid #1f2a40;
  color:#e2e8f0;
}

/* Alert */
.dark .alert-danger {
  background:#7f1d1d;
  border:none;
  color:#fff;
}

/* Table (kalau ada) */
.dark .table {
  background:#111c2e;
  color:#e2e8f0;
}

.dark .table thead {
  background:#1f2a40;
}

/* Chart text fix */
.dark canvas {
  filter: brightness(0.9);
}
.stat-card {
  border-radius:18px;
  padding:26px;
  position:relative;
  overflow:hidden;
}
.stat-card i {
  position:absolute;
  right:18px;
  bottom:10px;
  font-size:64px;
  opacity:.15;
}
.bg-active { background:linear-gradient(135deg,#22c55e,#4ade80); }
.bg-expiring { background:linear-gradient(135deg,#facc15,#fde047); color:#333; }
.bg-expired { background:linear-gradient(135deg,#ef4444,#f87171); }
.blink {
  animation: blink 1.2s infinite;
}
@keyframes blink {
  0%,100%{opacity:1}
  50%{opacity:.4}
}
</style>

<!-- ================= TOP CONTROL ================= -->
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">📊 License Monitoring Dashboard</h4>
  <div>
    <button onclick="toggleDark()" class="btn btn-sm btn-dark">🌙 Dark</button>
    <button onclick="goFull()" class="btn btn-sm btn-primary">📺 Fullscreen</button>
  </div>
</div>

<!-- ================= STAT ================= -->
<div class="row">
  <div class="col-lg-4">
    <div class="stat-card bg-active shadow">
      <h2 class="counter" data-count="<?= $active ?>">0</h2>
      <p>Active License</p>
      <i class="fas fa-check-circle"></i>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="stat-card bg-expiring shadow">
      <h2 class="counter" data-count="<?= $expiring ?>">0</h2>
      <p>Expiring Soon</p>
      <i class="fas fa-hourglass-half"></i>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="stat-card bg-expired shadow <?= $expired>0?'blink':'' ?>">
      <h2 class="counter" data-count="<?= $expired ?>">0</h2>
      <p>Expired</p>
      <i class="fas fa-times-circle"></i>
    </div>
  </div>
</div>

<?php if($expired>0): ?>
<div class="alert alert-danger mt-3 shadow alert-blink">
  🚨 <b>WARNING!</b> Ada lisensi yang sudah <b>EXPIRED</b>. Segera lakukan tindakan.
</div>
<?php endif; ?>


<!-- ================= CHART ================= -->
<div class="row mt-4">
  <div class="col-md-6">
    <div class="card shadow" style="height:340px;">
      <div class="card-header bg-transparent">
        <b>Status Lisensi</b>
      </div>
      <div class="card-body d-flex justify-content-center">
        <canvas id="pieChart" style="max-width:260px"></canvas>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card shadow" style="height:340px;">
      <div class="card-header bg-transparent">
        <b>Sisa Hari per Layanan</b>
      </div>
      <div class="card-body">
        <canvas id="barChart"></canvas>
      </div>
    </div>
  </div>
</div>

<!-- ================= SECURITY DASHBOARD ================= -->
<div class="card shadow-lg mt-4">
  <div class="card-header bg-dark text-white">
    🌐 Live Monitoring Dashboard
  </div>
  <div class="card-body p-0">
    <iframe
      src="https://livethreatmap.radware.com/"
      style="width:100%;height:480px;border:0;"
      loading="lazy"
      referrerpolicy="no-referrer">
    </iframe>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// COUNT UP
document.querySelectorAll('.counter').forEach(el=>{
  let to = el.dataset.count;
  let i = 0;
  let step = Math.max(1, to/30);
  let int = setInterval(()=>{
    i+=step;
    if(i>=to){ i=to; clearInterval(int); }
    el.innerText = Math.floor(i);
  },30);
});

// DARK MODE
function toggleDark(){
  document.body.classList.toggle('dark');
}

// FULLSCREEN
function goFull(){
  if(!document.fullscreenElement){
    document.documentElement.requestFullscreen();
  } else {
    document.exitFullscreen();
  }
}

// CHART
new Chart(pieChart,{
  type:'doughnut',
  data:{
    labels:['Active','Expiring','Expired'],
    datasets:[{
      data:[<?= $active ?>,<?= $expiring ?>,<?= $expired ?>],
      backgroundColor:['#22c55e','#facc15','#ef4444']
    }]
  },
  options:{cutout:'65%'}
});

const layanan = <?= json_encode($layanan); ?>;

const bar = new Chart(document.getElementById('barChart'), {
  type: 'bar',
  data: {
    labels: layanan.map(l => l.nama),
    datasets: [{
      data: layanan.map(l => l.hari),
      backgroundColor: layanan.map(l => l.warna),
      borderRadius: 6
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { display: false },
      tooltip: {
        callbacks: {
          label: ctx => ctx.raw
        }
      }
    },
    scales: {
      y: {
        beginAtZero: true,
        ticks: { precision: 0 }
      }
    },
    animation: {
      onComplete: function () {
        const ctx = this.ctx;
        ctx.font = 'bold 11px Arial';
        ctx.fillStyle = '#111';
        ctx.textAlign = 'center';

        this.data.datasets[0].data.forEach((value, i) => {
          const meta = this.getDatasetMeta(0).data[i];
          ctx.fillText(value, meta.x, meta.y - 5);
        });
      }
    }
  }
});
</script>

<?php include "../layout/footer.php"; ?>
