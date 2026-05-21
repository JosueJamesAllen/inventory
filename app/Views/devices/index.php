<?php
$canEdit = in_array($user['role'], ['admin', 'it_staff']);

$typeCounts = [];
foreach ($devices as $d) {
    $t = $d['type'];
    $typeCounts[$t] = ($typeCounts[$t] ?? 0) + 1;
}
arsort($typeCounts);

$typeIcons = [
    'laptop'   => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
    'tablet'   => 'M12 18h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z',
    'desktop'  => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
    'printer'  => 'M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z',
    'default'  => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10',
];
$iconColors = ['si-blue', 'si-purple', 'si-green', 'si-amber', 'si-teal', 'si-red'];
?>

<div class="page-header">
  <div>
    <h1>Devices</h1>
    <p class="page-sub">All IT equipment · <?= count($devices) ?> total</p>
  </div>
  <div class="btn-group">
    <button class="btn btn-outline" onclick="printDeviceQrs()">Print QR Codes</button>
    <button class="btn btn-outline" onclick="downloadDeviceQrPdf()">Download PDF</button>
    <?php if ($canEdit): ?>
    <button class="btn btn-primary" onclick="openAddDeviceModal()">+ Add Device</button>
    <?php endif; ?>
  </div>
</div>

<!-- Device type stat cards -->
<div class="stats-grid">
<?php $i = 0; foreach ($typeCounts as $type => $count):
    $key  = strtolower($type);
    $icon = $typeIcons[$key] ?? $typeIcons['default'];
    $col  = $iconColors[$i % count($iconColors)];
    $i++;
?>
  <div class="stat-card" data-type-card="<?= htmlspecialchars(strtolower($type)) ?>">
    <div class="stat-icon <?= $col ?>">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="<?= $icon ?>"/>
      </svg>
    </div>
    <div>
      <div class="stat-value" data-type-count="<?= htmlspecialchars(strtolower($type)) ?>"><?= $count ?></div>
      <div class="stat-label"><?= htmlspecialchars($type) ?></div>
    </div>
  </div>
<?php endforeach; ?>
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
  <select id="locationFilter" onchange="filterDevices()">
    <option value="">All Locations</option>
    <?php foreach ($locationsByGroup as $lcab => $shelves): ?>
      <?php foreach ($shelves as $lshelf): ?>
    <option value="<?= htmlspecialchars($lcab . '|' . $lshelf) ?>"><?= htmlspecialchars($lcab) ?> — <?= htmlspecialchars($lshelf) ?></option>
      <?php endforeach; ?>
    <?php endforeach; ?>
  </select>
</div>

<div class="table-card">
  <table id="devicesTable">
    <thead>
      <tr>
        <?php if ($canEdit): ?><th style="width:2rem"><input type="checkbox" id="bulk-select-all" title="Select all" onchange="bulkToggleAll(this)"></th><?php endif; ?>
        <th>Device</th>
        <th>Type</th>
        <th>Asset Tag</th>
        <th>QR Code</th>
        <th>Location</th>
        <th>Status</th>
        <th>Current Borrower</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($devices as $d): ?>
    <tr data-status="<?= htmlspecialchars($d['status']) ?>"
        data-type="<?= htmlspecialchars(strtolower($d['type'])) ?>"
        data-location="<?= htmlspecialchars($d['cabinet'] . '|' . $d['shelf']) ?>"
        data-search="<?= htmlspecialchars(strtolower($d['name'] . ' ' . $d['asset_tag'] . ' ' . $d['type'])) ?>">
      <?php if ($canEdit): ?>
      <td><input type="checkbox" class="bulk-cb" value="<?= (int)$d['id'] ?>" onchange="bulkUpdateBar()"></td>
      <?php endif; ?>
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
      <td class="actions-cell">
        <button class="btn btn-xs btn-outline"
          onclick='openDeviceHistory(<?= (int)$d["id"] ?>)'>History</button>
        <?php if ($canEdit): ?>
        <button class="btn btn-xs btn-outline"
          onclick='openEditDevice(<?= json_encode($d) ?>)'>Edit</button>
        <button class="btn btn-xs btn-warn"
          onclick='openReconcile(<?= (int)$d["id"] ?>, <?= json_encode($d["name"]) ?>, <?= json_encode($d["status"]) ?>)'>Reconcile</button>
        <?php endif; ?>
      </td>
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
          <select name="cabinet" id="add-device-cabinet" required onchange="updateShelfOptions('add')">
            <option value="">Select cabinet...</option>
            <?php foreach (array_keys($locationsByGroup) as $cab): ?>
            <option value="<?= htmlspecialchars($cab) ?>"><?= htmlspecialchars($cab) ?></option>
            <?php endforeach; ?>
          </select>
          <?php if (empty($locationsByGroup)): ?>
          <small><a href="/inventory/public/maintenance">No locations defined — add them in Maintenance</a></small>
          <?php endif; ?>
        </div>
        <div class="form-group">
          <label>Shelf *</label>
          <select name="shelf" id="add-device-shelf" required>
            <option value="">Select cabinet first...</option>
          </select>
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
          <select name="cabinet" id="edit-device-cabinet" required onchange="updateShelfOptions('edit')">
            <option value="">Select cabinet...</option>
            <?php foreach (array_keys($locationsByGroup) as $cab): ?>
            <option value="<?= htmlspecialchars($cab) ?>"><?= htmlspecialchars($cab) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label>Shelf *</label>
          <select name="shelf" id="edit-device-shelf" required>
            <option value="">Select cabinet first...</option>
          </select>
        </div>
        <div class="form-group">
          <label>Status *</label>
          <select name="status" id="edit-device-status">
            <option value="available">Available</option>
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

<?php if ($canEdit): ?>
<!-- ── Bulk action bar ── -->
<form id="bulk-form" method="POST" action="/inventory/public/devices/bulk">
  <?= $csrf ?>
  <input type="hidden" name="bulk_status" id="bulk-status-input">
  <div id="bulk-bar" class="bulk-bar">
    <span id="bulk-count" class="bulk-bar-count">0 selected</span>
    <div class="bulk-bar-actions">
      <select id="bulk-status-select" class="bulk-bar-select">
        <option value="">Set status to…</option>
        <option value="available">Available</option>
        <option value="out_of_service">Out of Service</option>
      </select>
      <button type="button" class="btn btn-primary btn-sm" onclick="bulkSubmit()">Apply</button>
      <button type="button" class="btn btn-outline btn-sm" onclick="bulkClear()">Cancel</button>
    </div>
  </div>
</form>
<?php endif; ?>

<!-- ── Modal: Device History ── -->
<div id="modal-device-history" class="modal-overlay" style="display:none">
  <div class="modal modal-lg">
    <div class="modal-header">
      <div>
        <h3 id="history-device-name">Device History</h3>
        <p id="history-device-meta" class="modal-desc" style="margin:0"></p>
      </div>
      <button class="modal-close" onclick="closeModal('modal-device-history')">✕</button>
    </div>
    <div id="history-body" class="history-timeline">
      <div class="history-loading">Loading…</div>
    </div>
  </div>
</div>

<script>
let _locGroups = <?= json_encode($locationsByGroup) ?>;

async function refreshLocGroups() {
  try {
    const res = await fetch('/inventory/public/api/locations');
    _locGroups = await res.json();
  } catch(e) {}
}

async function openAddDeviceModal() {
  await refreshLocGroups();
  const cab = document.getElementById('add-device-cabinet');
  const prev = cab ? cab.value : '';
  cab.innerHTML = '<option value="">Select cabinet...</option>';
  Object.keys(_locGroups).forEach(function(c) {
    const opt = document.createElement('option');
    opt.value = opt.textContent = c;
    cab.appendChild(opt);
  });
  cab.value = prev;
  updateShelfOptions('add');
  openModal('modal-add-device');
}

function updateShelfOptions(prefix) {
  const cab      = document.getElementById(prefix + '-device-cabinet').value;
  const shelfSel = document.getElementById(prefix + '-device-shelf');
  const prev     = shelfSel.value;
  shelfSel.innerHTML = '<option value="">Select shelf...</option>';
  if (_locGroups[cab]) {
    _locGroups[cab].forEach(function(shelf) {
      const opt = document.createElement('option');
      opt.value = opt.textContent = shelf;
      shelfSel.appendChild(opt);
    });
    shelfSel.value = prev;
  }
}

function bulkUpdateBar() {
  const checked = document.querySelectorAll('.bulk-cb:checked');
  const bar     = document.getElementById('bulk-bar');
  const count   = document.getElementById('bulk-count');
  if (!bar) return;
  count.textContent = checked.length + ' device' + (checked.length !== 1 ? 's' : '') + ' selected';
  bar.classList.toggle('bulk-bar-visible', checked.length > 0);
  document.getElementById('bulk-select-all').indeterminate =
    checked.length > 0 && checked.length < document.querySelectorAll('.bulk-cb').length;
}

function bulkToggleAll(master) {
  document.querySelectorAll('.bulk-cb').forEach(cb => {
    const row = cb.closest('tr');
    if (!row || row.style.display === 'none') return;
    cb.checked = master.checked;
  });
  bulkUpdateBar();
}

function bulkClear() {
  document.querySelectorAll('.bulk-cb, #bulk-select-all').forEach(cb => cb.checked = false);
  const bar = document.getElementById('bulk-bar');
  if (bar) bar.classList.remove('bulk-bar-visible');
}

function bulkSubmit() {
  const status = document.getElementById('bulk-status-select').value;
  if (!status) { alert('Please choose a status to apply.'); return; }

  const ids    = [...document.querySelectorAll('.bulk-cb:checked')].map(cb => cb.value);
  if (!ids.length) return;

  const label  = status.replace('_', ' ');
  if (!confirm(`Set ${ids.length} device(s) to "${label}"?`)) return;

  document.getElementById('bulk-status-input').value = status;

  const form = document.getElementById('bulk-form');
  document.querySelectorAll('input[name="device_ids[]"]').forEach(el => el.remove());
  ids.forEach(id => {
    const inp = document.createElement('input');
    inp.type  = 'hidden';
    inp.name  = 'device_ids[]';
    inp.value = id;
    form.appendChild(inp);
  });
  form.submit();
}

function collectDeviceQrItems() {
  const rows  = document.querySelectorAll('#devicesTable tbody tr');
  const items = [];
  rows.forEach(row => {
    if (row.style.display === 'none') return;
    const cells  = row.querySelectorAll('td');
    const hasCb  = cells[0].querySelector('input[type="checkbox"]');
    const offset = hasCb ? 1 : 0;
    const name     = cells[offset].querySelector('strong')?.textContent.trim() || '';
    const assetTag = cells[offset + 2].textContent.trim();
    const qrCode   = cells[offset + 3].textContent.trim();
    if (qrCode) items.push({ name, sub: assetTag, qr: qrCode });
  });
  return items;
}

function printDeviceQrs() {
  const items = collectDeviceQrItems();
  if (!items.length) { alert('No devices to print.'); return; }
  openQrPrintWindow(items, 'Devices');
  logActivity('device.qr_printed', 'Printed QR codes for ' + items.length + ' device(s)');
}

function downloadDeviceQrPdf() {
  const items = collectDeviceQrItems();
  if (!items.length) { alert('No devices found.'); return; }
  downloadQrPdf(items, 'Devices');
  logActivity('device.qr_pdf_downloaded', 'Downloaded QR PDF for ' + items.length + ' device(s)');
}

function openDeviceHistory(id) {
  document.getElementById('history-device-name').textContent = 'Device History';
  document.getElementById('history-device-meta').textContent = '';
  document.getElementById('history-body').innerHTML = '<div class="history-loading">Loading…</div>';
  openModal('modal-device-history');

  fetch('/inventory/public/devices/history?id=' + id)
    .then(r => r.json())
    .then(data => {
      if (data.error) {
        document.getElementById('history-body').innerHTML = '<p class="text-muted" style="padding:1rem">Error loading history.</p>';
        return;
      }

      const d = data.device;
      document.getElementById('history-device-name').textContent = d.name;
      document.getElementById('history-device-meta').textContent = d.type + ' · ' + d.asset_tag;

      const rows = data.history;
      if (!rows.length) {
        document.getElementById('history-body').innerHTML =
          '<p class="text-muted" style="padding:1rem;text-align:center">No transactions recorded for this device yet.</p>';
        return;
      }

      const html = rows.map(r => {
        const active  = !r.returned_at;
        const badge   = active
          ? '<span class="status-badge status-borrowed">Active</span>'
          : '<span class="status-badge status-available">Returned</span>';
        const borrowed  = formatDate(r.borrowed_at);
        const returned  = r.returned_at ? formatDate(r.returned_at) : '—';
        const via       = r.facilitated_by_name ? 'via ' + escHtml(r.facilitated_by_name) : 'Self-serve';
        const retBy     = r.returned_by_name ? 'Returned by ' + escHtml(r.returned_by_name) : (r.returned_at ? 'Self-serve' : '');
        const dueStr    = r.expected_return_at
          ? (() => { const d = new Date(r.expected_return_at); return d.toLocaleDateString('en-PH', {month:'short',day:'numeric',year:'numeric'}); })()
          : 'Indefinite';
        const isOverdue = !r.returned_at && r.expected_return_at && new Date(r.expected_return_at) < new Date();
        const dueBadge  = `<span style="font-size:.75rem;color:${isOverdue ? 'var(--error)' : 'var(--text2)'}">Due: ${dueStr}${isOverdue ? ' ⚠' : ''}</span>`;

        return `
          <div class="history-entry ${active ? 'history-active' : ''}">
            <div class="history-dot ${active ? 'history-dot-active' : ''}"></div>
            <div class="history-content">
              <div class="history-row-top">
                <strong>${escHtml(r.borrower_name)}</strong>
                <span class="text-muted" style="font-size:.78rem">${escHtml(r.department)}</span>
                ${badge}
              </div>
              ${r.purpose ? `<div style="font-size:.8rem;margin:.15rem 0 .35rem;color:var(--text)">Purpose: <em>${escHtml(r.purpose)}</em></div>` : ''}
              <div class="history-row-dates">
                <span>Borrowed: <b>${borrowed}</b></span>
                <span>Returned: <b>${returned}</b></span>
                ${dueBadge}
              </div>
              <div class="history-row-meta">
                <span class="text-muted">${via}</span>
                ${retBy ? '<span class="text-muted">' + retBy + '</span>' : ''}
                ${r.notes ? '<span class="text-muted">Note: ' + escHtml(r.notes) + '</span>' : ''}
              </div>
            </div>
          </div>`;
      }).join('');

      document.getElementById('history-body').innerHTML = html;
    })
    .catch(() => {
      document.getElementById('history-body').innerHTML =
        '<p class="text-muted" style="padding:1rem">Failed to load history.</p>';
    });
}

function formatDate(str) {
  if (!str) return '—';
  const d = new Date(str.replace(' ', 'T'));
  return d.toLocaleDateString('en-PH', { month: 'short', day: 'numeric', year: 'numeric' })
       + ' ' + d.toLocaleTimeString('en-PH', { hour: '2-digit', minute: '2-digit' });
}

function escHtml(str) {
  return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
</script>
