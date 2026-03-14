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
<div class="table-card">
  <table>
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
    <tr>
      <td>
        <strong><?= htmlspecialchars($r['device_name']) ?></strong>
        <br><span class="text-muted"><?= htmlspecialchars($r['asset_tag']) ?></span>
      </td>
      <td><?= htmlspecialchars($r['device_type']) ?></td>
      <td><?= htmlspecialchars($r['borrower_name']) ?></td>
      <td><?= htmlspecialchars($r['department']) ?></td>
      <td><?= date('M d, Y H:i', strtotime($r['borrowed_at'])) ?></td>
      <td>
        <?= $r['returned_at']
          ? date('M d, Y H:i', strtotime($r['returned_at']))
          : '<span class="text-muted">—</span>' ?>
      </td>
      <td><?= $r['facilitated_by_name'] ? htmlspecialchars($r['facilitated_by_name']) : '<span class="text-muted">Self</span>' ?></td>
      <td>
        <?php if ($r['returned_at']): ?>
          <?= $r['returned_by_name'] ? htmlspecialchars($r['returned_by_name']) : '<span class="text-muted">Self</span>' ?>
        <?php else: ?>
          <span class="text-muted">—</span>
        <?php endif; ?>
      </td>
      <td>
        <?php if ($r['returned_at']): ?>
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
<div class="table-card">
  <table>
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
    <tr>
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
<div class="table-card">
  <table>
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
    <tr>
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
