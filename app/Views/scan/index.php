<div class="page-header">
  <div>
    <h1>Borrow / Return</h1>
    <p class="page-sub">Simulate QR code scanning — select employee and device</p>
  </div>
</div>

<div class="scan-grid">

  <!-- ── Borrow ── -->
  <div class="card">
    <div class="card-head card-head-borrow">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12l7 7 7-7"/></svg>
      Borrow Device
    </div>
    <div class="card-body">
      <form method="POST" action="/inventory/public/scan/borrow">
        <?= $csrf ?>

        <div class="form-group">
          <label>Step 1 — Employee QR Code</label>
          <select name="emp_qr" required>
            <option value="">Select employee...</option>
            <?php foreach ($employees as $e): ?>
            <option value="<?= htmlspecialchars($e['qr_code']) ?>"
              <?= ($user['qr_code'] === $e['qr_code']) ? 'selected' : '' ?>>
              <?= htmlspecialchars($e['name']) ?> · <?= htmlspecialchars($e['qr_code']) ?>
              <?= $e['active_borrows'] > 0 ? ' [' . $e['active_borrows'] . ' active]' : '' ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label>Step 2 — Device QR Code</label>
          <select name="dev_qr" required <?= empty($availDevices) ? 'disabled' : '' ?>>
            <option value="">Select available device...</option>
            <?php foreach ($availDevices as $d): ?>
            <option value="<?= htmlspecialchars($d['qr_code']) ?>">
              <?= htmlspecialchars($d['name']) ?> — <?= htmlspecialchars($d['asset_tag']) ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div>

        <button type="submit" class="btn btn-primary btn-block"
          <?= empty($availDevices) ? 'disabled' : '' ?>>
          <?= empty($availDevices) ? 'No Devices Available' : 'Confirm Borrow' ?>
        </button>
      </form>
    </div>
  </div>

  <!-- ── Return ── -->
  <div class="card">
    <div class="card-head card-head-return">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
      Return Device
    </div>
    <div class="card-body">
      <form method="POST" action="/inventory/public/scan/return">
        <?= $csrf ?>

        <div class="form-group">
          <label>Step 1 — Employee QR Code</label>
          <select name="emp_qr" required>
            <option value="">Select employee...</option>
            <?php foreach ($employees as $e): ?>
            <option value="<?= htmlspecialchars($e['qr_code']) ?>"
              <?= ($user['qr_code'] === $e['qr_code']) ? 'selected' : '' ?>>
              <?= htmlspecialchars($e['name']) ?> · <?= htmlspecialchars($e['qr_code']) ?>
            </option>
            <?php endforeach; ?>
          </select>
          <small>Can be the original borrower or any staff member (proxy return).</small>
        </div>

        <div class="form-group">
          <label>Step 2 — Device QR Code</label>
          <select name="dev_qr" required <?= empty($borrowedDevices) ? 'disabled' : '' ?>>
            <option value="">Select borrowed device...</option>
            <?php foreach ($borrowedDevices as $d): ?>
            <option value="<?= htmlspecialchars($d['qr_code']) ?>">
              <?= htmlspecialchars($d['name']) ?> — <?= htmlspecialchars($d['asset_tag']) ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div>

        <button type="submit" class="btn btn-success btn-block"
          <?= empty($borrowedDevices) ? 'disabled' : '' ?>>
          <?= empty($borrowedDevices) ? 'No Devices Borrowed' : 'Confirm Return' ?>
        </button>
      </form>
    </div>
  </div>

</div>

<!-- Currently Borrowed -->
<?php if (!empty($borrowedDevices)): ?>
<div class="section-head" style="margin-top:2rem">
  <h2>Currently Borrowed <span class="badge badge-amber"><?= count($borrowedDevices) ?></span></h2>
</div>
<div class="table-card">
  <table>
    <thead>
      <tr><th>Device</th><th>Asset Tag</th><th>Type</th><th>Cabinet</th><th>Shelf</th></tr>
    </thead>
    <tbody>
    <?php foreach ($borrowedDevices as $d): ?>
    <tr>
      <td><strong><?= htmlspecialchars($d['name']) ?></strong></td>
      <td><code><?= htmlspecialchars($d['asset_tag']) ?></code></td>
      <td><?= htmlspecialchars($d['type']) ?></td>
      <td><?= htmlspecialchars($d['cabinet']) ?></td>
      <td><?= htmlspecialchars($d['shelf']) ?></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php endif; ?>
