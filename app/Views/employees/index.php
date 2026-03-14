<?php $isAdmin = $user['role'] === 'admin'; ?>

<div class="page-header">
  <div>
    <h1>Employees</h1>
    <p class="page-sub">Staff registry · <?= count($employees) ?> employees</p>
  </div>
  <?php if ($isAdmin): ?>
  <button class="btn btn-primary" onclick="openModal('modal-add-employee')">+ Add Employee</button>
  <?php endif; ?>
</div>

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
      <td><code><?= htmlspecialchars($e['qr_code']) ?></code></td>
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
