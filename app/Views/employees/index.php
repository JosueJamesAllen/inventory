<?php $isAdmin = $user['role'] === 'admin'; ?>

<div class="page-header">
  <div>
    <h1>Employees</h1>
    <p class="page-sub">Staff registry · <?= count($employees) ?> employees</p>
  </div>
  <div class="btn-group">
    <?php if ($isAdmin): ?>
    <button class="btn btn-outline" onclick="printEmployeeQrs()">Print QR Codes</button>
    <button class="btn btn-primary" onclick="openModal('modal-add-employee')">+ Add Employee</button>
    <?php endif; ?>
  </div>
</div>

<div class="tabs">
  <a href="/inventory/public/employees?tab=list"    class="tab <?= $tab === 'list'    ? 'tab-active' : '' ?>">All Employees</a>
  <a href="/inventory/public/employees?tab=summary" class="tab <?= $tab === 'summary' ? 'tab-active' : '' ?>">Borrowing Summary</a>
</div>

<?php if ($tab === 'summary'): ?>

<?php
// Group flat rows by employee
$grouped = [];
foreach ($summary as $row) {
    $grouped[$row['id']]['name']       = $row['name'];
    $grouped[$row['id']]['department'] = $row['department'];
    $grouped[$row['id']]['devices'][]  = $row;
}
?>

<?php if (empty($grouped)): ?>
<div class="empty-state">
  <div class="empty-icon">✅</div>
  <p>No devices are currently borrowed.</p>
</div>
<?php else: ?>
<div class="borrow-summary-grid">
  <?php foreach ($grouped as $empId => $emp): ?>
  <div class="borrow-summary-card">
    <div class="borrow-summary-header">
      <div class="user-avatar"><?= strtoupper(substr($emp['name'], 0, 1)) ?></div>
      <div>
        <div class="borrow-summary-name"><?= htmlspecialchars($emp['name']) ?></div>
        <div class="borrow-summary-dept"><?= htmlspecialchars($emp['department']) ?></div>
      </div>
      <span class="badge badge-amber"><?= count($emp['devices']) ?> borrowed</span>
    </div>
    <div class="borrow-summary-devices">
      <?php foreach ($emp['devices'] as $dev): ?>
      <?php
        $hours = (float)$dev['hours_borrowed'];
        if ($hours < 1)       $ago = 'Less than 1 hr ago';
        elseif ($hours < 24)  $ago = round($hours) . ' hr' . (round($hours) !== 1 ? 's' : '') . ' ago';
        else                  $ago = round($hours / 24, 1) . ' day' . (round($hours / 24, 1) !== 1.0 ? 's' : '') . ' ago';
      ?>
      <div class="borrow-device-row">
        <div class="borrow-device-info">
          <strong><?= htmlspecialchars($dev['device_name']) ?></strong>
          <span class="text-muted"><?= htmlspecialchars($dev['asset_tag']) ?></span>
        </div>
        <div class="borrow-device-meta">
          <span class="chip"><?= htmlspecialchars($dev['device_type']) ?></span>
          <span class="text-muted" style="font-size:.75rem"><?= $ago ?></span>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endforeach; ?>
</div>
<?php endif; ?>

<?php else: ?>

<div class="table-card">
  <table>
    <thead>
      <tr>
        <th>Name</th>
        <th>Department</th>
        <th>QR Code</th>
        <th>Role</th>
        <th>Total Borrows</th>
        <th>Active</th>
        <?php if ($isAdmin): ?><th>Actions</th><?php endif; ?>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($employees as $e): ?>
    <tr>
      <td><strong><?= htmlspecialchars($e['name']) ?></strong></td>
      <td><?= htmlspecialchars($e['department']) ?></td>
      <td><code><?= $isAdmin ? htmlspecialchars($e['qr_code']) : '••••••••' ?></code></td>
      <td><span class="role-badge role-<?= $e['role'] ?>"><?= str_replace('_', ' ', $e['role']) ?></span></td>
      <td><?= (int)$e['total_borrows'] ?></td>
      <td>
        <?php if ($e['active_borrows'] > 0): ?>
          <span class="badge badge-amber"><?= (int)$e['active_borrows'] ?> active</span>
        <?php else: ?>
          <span class="text-muted">—</span>
        <?php endif; ?>
      </td>
      <?php if ($isAdmin): ?>
      <td class="actions-cell">
        <button class="btn btn-xs btn-outline"
          onclick='openEditEmployee(<?= json_encode($e) ?>)'>Edit</button>
      </td>
      <?php endif; ?>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php if ($isAdmin): ?>

<!-- ── Modal: Add Employee ── -->
<div id="modal-add-employee" class="modal-overlay" style="display:none">
  <div class="modal">
    <div class="modal-header">
      <h3>Add Employee</h3>
      <button class="modal-close" onclick="closeModal('modal-add-employee')">✕</button>
    </div>
    <form method="POST" action="/inventory/public/employees">
      <?= $csrf ?>
      <div class="form-grid-2">
        <div class="form-group">
          <label>Full Name *</label>
          <input type="text" name="name" required placeholder="Juan Dela Cruz">
        </div>
        <div class="form-group">
          <label>Department *</label>
          <input type="text" name="department" required placeholder="Finance">
        </div>
        <div class="form-group">
          <label>QR Code / Employee ID *</label>
          <input type="text" name="qr_code" required placeholder="EMP-009">
          <small>Must be unique — this is scanned during borrow/return.</small>
        </div>
        <div class="form-group">
          <label>Role *</label>
          <select name="role" required>
            <option value="borrower">Borrower</option>
            <option value="it_staff">IT Staff</option>
            <option value="admin">Admin</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('modal-add-employee')">Cancel</button>
        <button type="submit" class="btn btn-primary">Add Employee</button>
      </div>
    </form>
  </div>
</div>

<!-- ── Modal: Edit Employee ── -->
<div id="modal-edit-employee" class="modal-overlay" style="display:none">
  <div class="modal">
    <div class="modal-header">
      <h3>Edit Employee</h3>
      <button class="modal-close" onclick="closeModal('modal-edit-employee')">✕</button>
    </div>
    <form method="POST" action="/inventory/public/employees/update">
      <?= $csrf ?>
      <input type="hidden" name="employee_id" id="edit-emp-id">
      <div class="form-grid-2">
        <div class="form-group">
          <label>Full Name *</label>
          <input type="text" name="name" id="edit-emp-name" required>
        </div>
        <div class="form-group">
          <label>Department *</label>
          <input type="text" name="department" id="edit-emp-dept" required>
        </div>
        <div class="form-group col-span-2">
          <label>QR Code <small>(cannot be changed)</small></label>
          <input type="text" id="edit-emp-qr" readonly class="input-readonly">
        </div>
        <div class="form-group">
          <label>Role *</label>
          <select name="role" id="edit-emp-role" required>
            <option value="borrower">Borrower</option>
            <option value="it_staff">IT Staff</option>
            <option value="admin">Admin</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('modal-edit-employee')">Cancel</button>
        <button type="submit" class="btn btn-primary">Save Changes</button>
      </div>
    </form>
  </div>
</div>

<?php endif; ?>

<?php if ($isAdmin): ?>
<script>
var employeeQrData = <?= json_encode(array_map(fn($e) => [
  'name' => $e['name'],
  'sub'  => $e['department'],
  'qr'   => $e['qr_code'],
], $employees)) ?>;

function printEmployeeQrs() {
  if (!employeeQrData.length) { alert('No employees to print.'); return; }
  openQrPrintWindow(employeeQrData, 'Employees');
}
</script>
<?php endif; ?>

<?php endif; // end tab === 'list' ?>
