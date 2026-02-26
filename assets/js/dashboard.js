document.addEventListener("DOMContentLoaded", function () {

  const data = {
    active: <?= $active ?>,
    expiring: <?= $expiring ?>,
    expired: <?= $expired ?>
  };

  // PIE CHART
  new Chart(document.getElementById("pieChart"), {
    type: "pie",
    data: {
      labels: ["Active", "Expiring Soon", "Expired"],
      datasets: [{
        data: [data.active, data.expiring, data.expired],
        backgroundColor: ["#28a745", "#ffc107", "#dc3545"]
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: "bottom",
          labels: {
            boxWidth: 18,
            padding: 15
          }
        }
      }
    }
  });

  // BAR CHART
  new Chart(document.getElementById("barChart"), {
    type: "bar",
    data: {
      labels: ["Active", "Expiring Soon", "Expired"],
      datasets: [{
        label: "Jumlah Lisensi",
        data: [data.active, data.expiring, data.expired],
        backgroundColor: ["#28a745", "#ffc107", "#dc3545"]
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });

});
