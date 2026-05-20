<div class="page-header">
  <div>
    <h1>Audit Logs</h1>
    <p class="page-sub">Complete transaction history &amp; reconciliation records</p>
  </div>
  <div class="btn-group">
    <a href="/inventory/public/audit/export?type=equipment" class="btn btn-outline btn-sm">↓ Equipment CSV</a>
    <a href="/inventory/public/audit/export?type=employee"  class="btn btn-outline btn-sm">↓ Employee CSV</a>
  </div>
</div>

<div class="tabs">
  <a href="/inventory/public/audit?tab=equipment"  class="tab <?= $tab === 'equipment'  ? 'tab-active' : '' ?>">Equipment Audit</a>
  <a href="/inventory/public/audit?tab=employee"   class="tab <?= $tab === 'employee'   ? 'tab-active' : '' ?>">Employee Audit</a>
  <a href="/inventory/public/audit?tab=reconcile"  class="tab <?= $tab === 'reconcile'  ? 'tab-active' : '' ?>">Reconciliations</a>
</div>

<!-- ── Equipment Audit ── -->
<?php if ($tab === 'equipment'): ?>
<div class="filter-bar">
  <input  type="text"   id="eq-search"      placeholder="Search device or borrower…" oninput="filterAudit('eq')">
  <select id="eq-status"                    onchange="filterAudit('eq')">
    <option value="">All Status</option>
    <option value="active">Active</option>
    <option value="returned">Returned</option>
  </select>
  <div class="filter-date-range">
    <span class="filter-date-label">Borrowed</span>
    <span class="filter-date-sep">From</span>
    <input type="date" id="eq-date-from" onchange="filterAudit('eq')">
    <span class="filter-date-sep">To</span>
    <input type="date" id="eq-date-to"   onchange="filterAudit('eq')">
  </div>
  <button class="btn btn-outline btn-sm" onclick="resetAuditFilter('eq')">Reset</button>
</div>
<div class="table-card">
  <table id="eq-table">
    <thead>
      <tr>
        <th>Device</th>
        <th>Type</th>
        <th>Borrower</th>
        <th>Department</th>
        <th>Borrowed At</th>
        <th>Returned At</th>
        <th>Facilitated By</th>
        <th>Returned By</th>
        <th>Status</th>
        <th>Notes</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($equipmentLog as $r): ?>
    <?php $isReturned = !empty($r['returned_at']); ?>
    <tr
      data-search="<?= htmlspecialchars(strtolower($r['device_name'] . ' ' . $r['asset_tag'] . ' ' . $r['borrower_name'] . ' ' . $r['department'])) ?>"
      data-status="<?= $isReturned ? 'returned' : 'active' ?>"
      data-date="<?= date('Y-m-d', strtotime($r['borrowed_at'])) ?>"
    >
      <td>
        <strong><?= htmlspecialchars($r['device_name']) ?></strong>
        <br><span class="text-muted"><?= htmlspecialchars($r['asset_tag']) ?></span>
      </td>
      <td><?= htmlspecialchars($r['device_type']) ?></td>
      <td><?= htmlspecialchars($r['borrower_name']) ?></td>
      <td><?= htmlspecialchars($r['department']) ?></td>
      <td><?= date('M d, Y H:i', strtotime($r['borrowed_at'])) ?></td>
      <td>
        <?= $isReturned
          ? date('M d, Y H:i', strtotime($r['returned_at']))
          : '<span class="text-muted">—</span>' ?>
      </td>
      <td><?= $r['facilitated_by_name'] ? htmlspecialchars($r['facilitated_by_name']) : '<span class="text-muted">Self</span>' ?></td>
      <td>
        <?php if ($isReturned): ?>
          <?= $r['returned_by_name'] ? htmlspecialchars($r['returned_by_name']) : '<span class="text-muted">Self</span>' ?>
        <?php else: ?>
          <span class="text-muted">—</span>
        <?php endif; ?>
      </td>
      <td>
        <?php if ($isReturned): ?>
          <span class="status-badge status-available">Returned</span>
        <?php else: ?>
          <span class="status-badge status-borrowed">Active</span>
        <?php endif; ?>
      </td>
      <td><span class="text-muted"><?= $r['notes'] ? htmlspecialchars($r['notes']) : '—' ?></span></td>
    </tr>
    <?php endforeach; ?>
    <?php if (empty($equipmentLog)): ?>
    <tr><td colspan="10" class="text-center text-muted" style="padding:2rem">No transactions recorded yet.</td></tr>
    <?php endif; ?>
    </tbody>
  </table>
</div>

<!-- ── Employee Audit ── -->
<?php elseif ($tab === 'employee'): ?>
<div class="filter-bar">
  <input  type="text"   id="emp-search"     placeholder="Search employee or device…" oninput="filterAudit('emp')">
  <input  type="text"   id="emp-dept"       placeholder="Department…"                oninput="filterAudit('emp')">
  <div class="filter-date-range">
    <span class="filter-date-label">Borrowed</span>
    <span class="filter-date-sep">From</span>
    <input type="date" id="emp-date-from" onchange="filterAudit('emp')">
    <span class="filter-date-sep">To</span>
    <input type="date" id="emp-date-to"   onchange="filterAudit('emp')">
  </div>
  <button class="btn btn-outline btn-sm" onclick="resetAuditFilter('emp')">Reset</button>
</div>
<div class="table-card">
  <table id="emp-table">
    <thead>
      <tr>
        <th>Employee</th>
        <th>Department</th>
        <th>Device</th>
        <th>Asset Tag</th>
        <th>Type</th>
        <th>Borrowed At</th>
        <th>Returned At</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($employeeLog as $r): ?>
    <tr
      data-search="<?= htmlspecialchars(strtolower($r['employee_name'] . ' ' . $r['qr_code'] . ' ' . $r['device_name'] . ' ' . $r['asset_tag'])) ?>"
      data-dept="<?= htmlspecialchars(strtolower($r['department'])) ?>"
      data-date="<?= date('Y-m-d', strtotime($r['borrowed_at'])) ?>"
    >
      <td>
        <strong><?= htmlspecialchars($r['employee_name']) ?></strong>
        <br><span class="text-muted"><?= htmlspecialchars($r['qr_code']) ?></span>
      </td>
      <td><?= htmlspecialchars($r['department']) ?></td>
      <td><?= htmlspecialchars($r['device_name']) ?></td>
      <td><code><?= htmlspecialchars($r['asset_tag']) ?></code></td>
      <td><?= htmlspecialchars($r['device_type']) ?></td>
      <td><?= date('M d, Y H:i', strtotime($r['borrowed_at'])) ?></td>
      <td>
        <?= $r['returned_at']
          ? date('M d, Y H:i', strtotime($r['returned_at']))
          : '<span class="status-badge status-borrowed">Active</span>' ?>
      </td>
    </tr>
    <?php endforeach; ?>
    <?php if (empty($employeeLog)): ?>
    <tr><td colspan="7" class="text-center text-muted" style="padding:2rem">No transactions recorded yet.</td></tr>
    <?php endif; ?>
    </tbody>
  </table>
</div>

<!-- ── Reconciliations ── -->
<?php else: ?>
<div class="filter-bar">
  <input  type="text"   id="rec-search"     placeholder="Search device or staff…"    oninput="filterAudit('rec')">
  <select id="rec-status"                   onchange="filterAudit('rec')">
    <option value="">All New Status</option>
    <option value="available">Available</option>
    <option value="borrowed">Borrowed</option>
    <option value="out_of_service">Out of Service</option>
  </select>
  <div class="filter-date-range">
    <span class="filter-date-label">Logged</span>
    <span class="filter-date-sep">From</span>
    <input type="date" id="rec-date-from" onchange="filterAudit('rec')">
    <span class="filter-date-sep">To</span>
    <input type="date" id="rec-date-to"   onchange="filterAudit('rec')">
  </div>
  <button class="btn btn-outline btn-sm" onclick="resetAuditFilter('rec')">Reset</button>
</div>
<div class="table-card">
  <table id="rec-table">
    <thead>
      <tr>
        <th>Device</th>
        <th>Asset Tag</th>
        <th>Performed By</th>
        <th>Old Status</th>
        <th>New Status</th>
        <th>Reason</th>
        <th>Logged At</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($reconLog as $r): ?>
    <tr
      data-search="<?= htmlspecialchars(strtolower($r['device_name'] . ' ' . $r['asset_tag'] . ' ' . $r['staff_name'])) ?>"
      data-status="<?= htmlspecialchars($r['new_status']) ?>"
      data-date="<?= date('Y-m-d', strtotime($r['created_at'])) ?>"
    >
      <td><strong><?= htmlspecialchars($r['device_name']) ?></strong></td>
      <td><code><?= htmlspecialchars($r['asset_tag']) ?></code></td>
      <td><?= htmlspecialchars($r['staff_name']) ?></td>
      <td><span class="status-badge status-<?= htmlspecialchars($r['old_status']) ?>"><?= str_replace('_', ' ', $r['old_status']) ?></span></td>
      <td><span class="status-badge status-<?= htmlspecialchars($r['new_status']) ?>"><?= str_replace('_', ' ', $r['new_status']) ?></span></td>
      <td><?= htmlspecialchars($r['reason']) ?></td>
      <td><?= date('M d, Y H:i', strtotime($r['created_at'])) ?></td>
    </tr>
    <?php endforeach; ?>
    <?php if (empty($reconLog)): ?>
    <tr><td colspan="7" class="text-center text-muted" style="padding:2rem">No reconciliations logged yet.</td></tr>
    <?php endif; ?>
    </tbody>
  </table>
</div>
<?php endif; ?>

<script>
function filterAudit(prefix) {
  const search   = (document.getElementById(prefix + '-search')    ?.value || '').toLowerCase();
  const status   = (document.getElementById(prefix + '-status')    ?.value || '').toLowerCase();
  const dept     = (document.getElementById(prefix + '-dept')      ?.value || '').toLowerCase();
  const dateFrom = document.getElementById(prefix + '-date-from')  ?.value || '';
  const dateTo   = document.getElementById(prefix + '-date-to')    ?.value || '';

  const table = document.getElementById(prefix + '-table');
  if (!table) return;

  table.querySelectorAll('tbody tr').forEach(row => {
    const rowSearch = (row.dataset.search || '');
    const rowStatus = (row.dataset.status || '');
    const rowDept   = (row.dataset.dept   || '');
    const rowDate   = (row.dataset.date   || '');

    const ok =
      (!search   || rowSearch.includes(search)) &&
      (!status   || rowStatus === status) &&
      (!dept     || rowDept.includes(dept)) &&
      (!dateFrom || rowDate >= dateFrom) &&
      (!dateTo   || rowDate <= dateTo);

    row.style.display = ok ? '' : 'none';
  });
}

function resetAuditFilter(prefix) {
  ['search','status','dept','date-from','date-to'].forEach(id => {
    const el = document.getElementById(prefix + '-' + id);
    if (el) el.value = '';
  });
  filterAudit(prefix);
}
</script>
