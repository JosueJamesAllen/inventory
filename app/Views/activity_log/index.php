<div class="page-header">
  <div>
    <h1>Activity Log</h1>
    <p class="page-sub">Every action taken by admins, IT staff, and borrowers</p>
  </div>
</div>

<div class="filter-bar">
  <input type="text" id="act-search" placeholder="Search description or user…" oninput="filterActivity()">
  <select id="act-user" onchange="filterActivity()">
    <option value="">All Users</option>
    <?php foreach ($users as $u): ?>
    <option value="<?= (int)$u['user_id'] ?>"><?= htmlspecialchars($u['user_name']) ?> (<?= htmlspecialchars(str_replace('_', ' ', $u['user_role'])) ?>)</option>
    <?php endforeach; ?>
  </select>
  <select id="act-category" onchange="filterActivity()">
    <option value="">All Categories</option>
    <option value="auth">Auth</option>
    <option value="employee">Employee</option>
    <option value="device">Device</option>
    <option value="location">Location</option>
    <option value="scan">Scan</option>
    <option value="audit">Audit</option>
  </select>
  <div class="filter-date-range">
    <span class="filter-date-label">Date</span>
    <span class="filter-date-sep">From</span>
    <input type="date" id="act-date-from" onchange="filterActivity()">
    <span class="filter-date-sep">To</span>
    <input type="date" id="act-date-to" onchange="filterActivity()">
  </div>
  <button class="btn btn-outline btn-sm" onclick="resetActivityFilter()">Reset</button>
</div>

<?php if (empty($entries)): ?>
<div class="empty-state">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
    <path stroke-linecap="round" stroke-linejoin="round"
      d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
  </svg>
  <p>No activity recorded yet. Actions will appear here once staff start using the system.</p>
</div>
<?php else: ?>
<div class="table-card">
  <table id="activity-table">
    <thead>
      <tr>
        <th style="width:13rem">When</th>
        <th style="width:14rem">User</th>
        <th style="width:7rem">Category</th>
        <th>Description</th>
      </tr>
    </thead>
    <tbody>
    <?php
    $categoryColors = [
        'auth'     => ['rgba(107,114,128,.12)', '#6b7280'],
        'employee' => ['rgba(16,185,129,.13)',  '#059669'],
        'device'   => ['rgba(59,130,246,.13)',  '#2563eb'],
        'location' => ['rgba(139,92,246,.13)',  '#7c3aed'],
        'scan'     => ['rgba(245,158,11,.13)',  '#d97706'],
        'audit'    => ['rgba(107,114,128,.12)', '#6b7280'],
    ];
    foreach ($entries as $e):
        $category = explode('.', $e['action'])[0];
        $dateOnly = substr($e['created_at'], 0, 10);
        [$chipBg, $chipColor] = $categoryColors[$category] ?? ['rgba(107,114,128,.12)', '#6b7280'];
    ?>
    <tr data-user="<?= (int)$e['user_id'] ?>"
        data-category="<?= htmlspecialchars($category) ?>"
        data-date="<?= htmlspecialchars($dateOnly) ?>"
        data-search="<?= htmlspecialchars(strtolower($e['user_name'] . ' ' . $e['description'] . ' ' . $e['action'])) ?>">
      <td style="font-size:.8rem;color:var(--text2);white-space:nowrap">
        <?= htmlspecialchars($e['created_at']) ?>
      </td>
      <td>
        <strong><?= htmlspecialchars($e['user_name']) ?></strong>
        <span class="chip" style="font-size:.68rem;margin-left:.3rem">
          <?= htmlspecialchars(str_replace('_', ' ', $e['user_role'])) ?>
        </span>
      </td>
      <td>
        <span style="display:inline-block;padding:.2rem .55rem;border-radius:.35rem;font-size:.72rem;font-weight:600;background:<?= $chipBg ?>;color:<?= $chipColor ?>">
          <?= htmlspecialchars($category) ?>
        </span>
      </td>
      <td><?= htmlspecialchars($e['description']) ?></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php endif; ?>

<script>
function filterActivity() {
  const search   = document.getElementById('act-search').value.toLowerCase();
  const userId   = document.getElementById('act-user').value;
  const category = document.getElementById('act-category').value;
  const dateFrom = document.getElementById('act-date-from').value;
  const dateTo   = document.getElementById('act-date-to').value;

  document.querySelectorAll('#activity-table tbody tr').forEach(function(row) {
    const ok =
      (!search   || row.dataset.search.includes(search)) &&
      (!userId   || row.dataset.user     === userId) &&
      (!category || row.dataset.category === category) &&
      (!dateFrom || row.dataset.date     >= dateFrom) &&
      (!dateTo   || row.dataset.date     <= dateTo);
    row.style.display = ok ? '' : 'none';
  });
}

function resetActivityFilter() {
  ['act-search','act-user','act-category','act-date-from','act-date-to']
    .forEach(function(id) { var el = document.getElementById(id); if (el) el.value = ''; });
  filterActivity();
}
</script>
