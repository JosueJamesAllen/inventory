<?php $canEdit = in_array($user['role'], ['admin', 'it_staff']); ?>

<div class="page-header">
  <div>
    <h1>Devices</h1>
    <p class="page-sub">All IT equipment · <?= count($devices) ?> total</p>
  </div>
  <?php if ($canEdit): ?>
  <button class="btn btn-primary" onclick="openModal('modal-add-device')">+ Add Device</button>
  <?php endif; ?>
</div>

<!-- Filters -->
<div class="filter-bar">
  <input type="text" id="searchInput" placeholder="Search by name, asset tag, type..." oninput="filterDevices()">
  <select id="statusFilter" onchange="filterDevices()">
    <option value="">All Status</option>
    <option value="available">Available</option>
    <option value="borrowed">Borrowed</option>
    <option value="out_of_service">Out of Service</option>
  </select>
  <select id="typeFilter" onchange="filterDevices()">
    <option value="">All Types</option>
    <?php
    $types = array_unique(array_column($devices, 'type'));
    sort($types);
    foreach ($types as $t): ?>
    <option value="<?= htmlspecialchars($t) ?>"><?= htmlspecialchars($t) ?></option>
    <?php endforeach; ?>
  </select>
</div>

<div class="table-card">
  <table id="devicesTable">
    <thead>
      <tr>
        <th>Device</th>
        <th>Type</th>
        <th>Asset Tag</th>
        <th>QR Code</th>
        <th>Location</th>
        <th>Status</th>
        <th>Current Borrower</th>
        <?php if ($canEdit): ?><th>Actions</th><?php endif; ?>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($devices as $d): ?>
    <tr data-status="<?= htmlspecialchars($d['status']) ?>"
        data-type="<?= htmlspecialchars(strtolower($d['type'])) ?>"
        data-search="<?= htmlspecialchars(strtolower($d['name'] . ' ' . $d['asset_tag'] . ' ' . $d['type'])) ?>">
      <td>
        <strong><?= htmlspecialchars($d['name']) ?></strong>
        <?php if ($d['notes']): ?>
        <br><small class="text-muted"><?= htmlspecialchars($d['notes']) ?></small>
        <?php endif; ?>
      </td>
      <td><span class="chip"><?= htmlspecialchars($d['type']) ?></span></td>
      <td><code><?= htmlspecialchars($d['asset_tag']) ?></code></td>
      <td><code><?= htmlspecialchars($d['qr_code']) ?></code></td>
      <td><?= htmlspecialchars($d['cabinet']) ?>, <?= htmlspecialchars($d['shelf']) ?></td>
      <td><span class="status-badge status-<?= $d['status'] ?>"><?= str_replace('_', ' ', $d['status']) ?></span></td>
      <td>
        <?= $d['current_borrower']
            ? htmlspecialchars($d['current_borrower'])
            : '<span class="text-muted">—</span>' ?>
      </td>
      <?php if ($canEdit): ?>
      <td class="actions-cell">
        <button class="btn btn-xs btn-outline"
          onclick='openEditDevice(<?= json_encode($d) ?>)'>Edit</button>
        <button class="btn btn-xs btn-warn"
          onclick='openReconcile(<?= (int)$d["id"] ?>, <?= json_encode($d["name"]) ?>, <?= json_encode($d["status"]) ?>)'>Reconcile</button>
      </td>
      <?php endif; ?>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php if ($canEdit): ?>

<!-- ── Modal: Add Device ── -->
<div id="modal-add-device" class="modal-overlay" style="display:none">
  <div class="modal">
    <div class="modal-header">
      <h3>Add New Device</h3>
      <button class="modal-close" onclick="closeModal('modal-add-device')">✕</button>
    </div>
    <form method="POST" action="/inventory/public/devices">
      <?= $csrf ?>
      <div class="form-grid-2">
        <div class="form-group">
          <label>Device Name *</label>
          <input type="text" name="name" required placeholder="ThinkPad X1 Carbon">
        </div>
        <div class="form-group">
          <label>Type *</label>
          <select name="type" required>
            <option value="">Select type...</option>
            <?php foreach (['Laptop','Monitor','Keyboard','Mouse','Headset','Webcam','Hub','Cable','USB WiFi','Other'] as $t): ?>
            <option><?= $t ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label>Asset Tag *</label>
          <input type="text" name="asset_tag" required placeholder="DEV-L006">
          <small>QR code will be auto-generated as QR-{asset_tag}</small>
        </div>
        <div class="form-group">
          <label>Cabinet *</label>
          <input type="text" name="cabinet" required placeholder="Cabinet A">
        </div>
        <div class="form-group">
          <label>Shelf *</label>
          <input type="text" name="shelf" required placeholder="Shelf 1">
        </div>
        <div class="form-group">
          <label>Notes</label>
          <input type="text" name="notes" placeholder="Optional notes...">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('modal-add-device')">Cancel</button>
        <button type="submit" class="btn btn-primary">Add Device</button>
      </div>
    </form>
  </div>
</div>

<!-- ── Modal: Edit Device ── -->
<div id="modal-edit-device" class="modal-overlay" style="display:none">
  <div class="modal">
    <div class="modal-header">
      <h3>Edit Device</h3>
      <button class="modal-close" onclick="closeModal('modal-edit-device')">✕</button>
    </div>
    <form method="POST" action="/inventory/public/devices/update">
      <?= $csrf ?>
      <input type="hidden" name="device_id" id="edit-device-id">
      <div class="form-grid-2">
        <div class="form-group">
          <label>Device Name *</label>
          <input type="text" name="name" id="edit-device-name" required>
        </div>
        <div class="form-group">
          <label>Type *</label>
          <input type="text" name="type" id="edit-device-type" required>
        </div>
        <div class="form-group">
          <label>Cabinet *</label>
          <input type="text" name="cabinet" id="edit-device-cabinet" required>
        </div>
        <div class="form-group">
          <label>Shelf *</label>
          <input type="text" name="shelf" id="edit-device-shelf" required>
        </div>
        <div class="form-group">
          <label>Status *</label>
          <select name="status" id="edit-device-status">
            <option value="available">Available</option>
            <option value="borrowed">Borrowed</option>
            <option value="out_of_service">Out of Service</option>
          </select>
        </div>
        <div class="form-group">
          <label>Notes</label>
          <input type="text" name="notes" id="edit-device-notes">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('modal-edit-device')">Cancel</button>
        <button type="submit" class="btn btn-primary">Save Changes</button>
      </div>
    </form>
  </div>
</div>

<!-- ── Modal: Reconcile ── -->
<div id="modal-reconcile" class="modal-overlay" style="display:none">
  <div class="modal">
    <div class="modal-header">
      <h3>Manual Reconciliation</h3>
      <button class="modal-close" onclick="closeModal('modal-reconcile')">✕</button>
    </div>
    <p class="modal-desc">
      Override a device's status during physical inventory checks.
      Every change is permanently logged with your name, timestamp, and reason.
    </p>
    <form method="POST" action="/inventory/public/devices/reconcile">
      <?= $csrf ?>
      <input type="hidden" name="device_id" id="recon-device-id">
      <div class="form-group">
        <label>Device</label>
        <input type="text" id="recon-device-name" readonly class="input-readonly">
      </div>
      <div class="form-group">
        <label>Current Status → New Status *</label>
        <select name="new_status" id="recon-new-status" required>
          <option value="available">Available</option>
          <option value="borrowed">Borrowed</option>
          <option value="out_of_service">Out of Service</option>
        </select>
      </div>
      <div class="form-group">
        <label>Reason * <small>(required for audit trail)</small></label>
        <textarea name="reason" rows="3" required
          placeholder="e.g. Device found on shelf — borrower forgot to scan return."></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('modal-reconcile')">Cancel</button>
        <button type="submit" class="btn btn-warn">Log Reconciliation</button>
      </div>
    </form>
  </div>
</div>

<?php endif; ?>
