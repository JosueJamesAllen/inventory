<div class="page-header">
  <div>
    <h1>Dashboard</h1>
    <p class="page-sub">Real-time equipment overview · <?= date('l, d F Y') ?></p>
  </div>
  <?php if (in_array($user['role'], ['admin','it_staff'])): ?>
  <a href="/inventory/public/scan" class="btn btn-primary">+ New Transaction</a>
  <?php endif; ?>
</div>

<!-- Stats -->
<div class="stats-grid">
  <?php
  $stats = [
    ['Total Devices',    $total,         'si-blue',   'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
    ['Available',        $available,     'si-green',  'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
    ['Borrowed',         $borrowed,      'si-amber',  'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
    ['Out of Service',   $oos,           'si-red',    'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
    ['Employees',        $employeeCount, 'si-purple', 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
    ['Transactions Today',$todayCount,   'si-teal',   'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
  ];
  foreach ($stats as [$label, $value, $iconClass, $path]):
  ?>
  <div class="stat-card">
    <div class="stat-icon <?= $iconClass ?>">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="<?= $path ?>"/>
      </svg>
    </div>
    <div>
      <div class="stat-value"><?= $value ?></div>
      <div class="stat-label"><?= $label ?></div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<!-- Charts -->
<div class="charts-grid">
  <div class="chart-card">
    <div class="chart-card-title">Device Status</div>
    <div class="chart-wrap">
      <canvas id="chart-status"></canvas>
    </div>
  </div>
  <div class="chart-card">
    <div class="chart-card-title">Daily Activity · Last 7 Days</div>
    <div class="chart-wrap">
      <canvas id="chart-activity"></canvas>
    </div>
  </div>
</div>

<!-- Active Borrows -->
<div class="section-head">
  <h2>Active Borrows <span class="badge badge-amber"><?= $borrowed ?></span></h2>
</div>

<?php if (empty($activeBorrows)): ?>
<div class="empty-state">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
  <p>All devices are accounted for — no active borrows.</p>
</div>
<?php else: ?>
<div class="table-card">
  <table>
    <thead>
      <tr>
        <th>Device</th>
        <th>Type</th>
        <th>Borrower</th>
        <th>Department</th>
        <th>Borrowed At</th>
        <th>Duration</th>
        <th>Facilitated By</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($activeBorrows as $b): ?>
    <tr>
      <td>
        <strong><?= htmlspecialchars($b['device_name']) ?></strong>
        <br><span class="text-muted"><?= htmlspecialchars($b['asset_tag']) ?></span>
      </td>
      <td><span class="chip"><?= htmlspecialchars($b['device_type']) ?></span></td>
      <td><?= $b['borrower_name'] ? htmlspecialchars($b['borrower_name']) : '<span class="text-muted">—</span>' ?></td>
      <td><?= $b['department'] ? htmlspecialchars($b['department']) : '<span class="text-muted">—</span>' ?></td>
      <td><?= $b['borrowed_at'] ? date('M d, H:i', strtotime($b['borrowed_at'])) : '<span class="text-muted">Manual</span>' ?></td>
      <td>
        <?php if ($b['hours_ago'] !== null): ?>
        <span class="duration <?= $b['hours_ago'] >= 8 ? 'duration-warn' : '' ?>">
          <?= $b['hours_ago'] ?>h
        </span>
        <?php else: ?>
        <span class="text-muted">—</span>
        <?php endif; ?>
      </td>
      <td><?= $b['facilitated_by_name'] ? htmlspecialchars($b['facilitated_by_name']) : '<span class="text-muted">—</span>' ?></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
(function () {
  const isDark    = document.documentElement.getAttribute('data-theme') === 'dark';
  const textColor = isDark ? 'rgba(148,163,184,0.85)' : 'rgba(100,116,139,0.9)';
  const gridColor = isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.06)';

  new Chart(document.getElementById('chart-status'), {
    type: 'doughnut',
    data: {
      labels: ['Available', 'Borrowed', 'Out of Service'],
      datasets: [{
        data: [<?= $available ?>, <?= $borrowed ?>, <?= $oos ?>],
        backgroundColor: ['#22c55e', '#f59e0b', '#ef4444'],
        borderColor: isDark ? '#1e293b' : '#ffffff',
        borderWidth: 3,
        hoverOffset: 6,
      }],
    },
    options: {
      responsive: true, maintainAspectRatio: false,
      cutout: '70%',
      plugins: {
        legend: {
          position: 'bottom',
          labels: { color: textColor, padding: 14, boxWidth: 12, font: { size: 12 } },
        },
        tooltip: { callbacks: { label: ctx => ` ${ctx.label}: ${ctx.parsed}` } },
      },
    },
  });

  <?php
  $weekMap = [];
  for ($i = 6; $i >= 0; $i--) {
      $weekMap[date('Y-m-d', strtotime("-$i days"))] = 0;
  }
  foreach ($weeklyActivity as $row) {
      if (isset($weekMap[$row['day']])) $weekMap[$row['day']] = (int)$row['count'];
  }
  $chartLabels = json_encode(array_map(fn($d) => date('D, M j', strtotime($d)), array_keys($weekMap)));
  $chartCounts = json_encode(array_values($weekMap));
  ?>

  new Chart(document.getElementById('chart-activity'), {
    type: 'bar',
    data: {
      labels: <?= $chartLabels ?>,
      datasets: [{
        label: 'Transactions',
        data: <?= $chartCounts ?>,
        backgroundColor: 'rgba(56,189,248,0.65)',
        borderColor: '#38bdf8',
        borderWidth: 1.5,
        borderRadius: 5,
      }],
    },
    options: {
      responsive: true, maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {
        x: { grid: { color: gridColor }, ticks: { color: textColor } },
        y: {
          beginAtZero: true,
          grid: { color: gridColor },
          ticks: { color: textColor, precision: 0 },
        },
      },
    },
  });
})();
</script>
