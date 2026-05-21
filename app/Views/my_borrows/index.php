<div class="page-header">
  <div>
    <h1>My Borrows</h1>
    <p class="page-sub">Equipment checked out to you</p>
  </div>
</div>

<!-- ── Active borrows ── -->
<p style="font-size:.8rem;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:var(--text2);margin:0 0 .6rem">
  Currently Borrowing
</p>

<?php if (empty($active)): ?>
<div class="empty-state" style="margin-bottom:2rem">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
    <path stroke-linecap="round" stroke-linejoin="round"
      d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
  </svg>
  <p>You have no equipment checked out.</p>
</div>
<?php else: ?>
<div class="table-card" style="margin-bottom:2rem">
  <table>
    <thead>
      <tr>
        <th>Device</th>
        <th>Type</th>
        <th>Location</th>
        <th>Purpose</th>
        <th>Borrowed</th>
        <th>Due</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($active as $r):
      $isOverdue = $r['expected_return_at'] && strtotime($r['expected_return_at']) < time();
    ?>
    <tr class="<?= $isOverdue ? 'row-overdue' : '' ?>">
      <td>
        <strong><?= htmlspecialchars($r['device_name']) ?></strong><br>
        <small class="text-muted"><code><?= htmlspecialchars($r['asset_tag']) ?></code></small>
      </td>
      <td><span class="chip"><?= htmlspecialchars($r['device_type']) ?></span></td>
      <td><?= htmlspecialchars($r['cabinet']) ?>, <?= htmlspecialchars($r['shelf']) ?></td>
      <td><?= $r['purpose'] ? htmlspecialchars($r['purpose']) : '<span class="text-muted">—</span>' ?></td>
      <td><?= date('M j, Y', strtotime($r['borrowed_at'])) ?></td>
      <td>
        <?php if ($r['expected_return_at']): ?>
          <?= date('M j, Y', strtotime($r['expected_return_at'])) ?>
          <?php if ($isOverdue): ?>
            &nbsp;<span class="status-badge status-borrowed" style="font-size:.68rem;vertical-align:middle">Overdue</span>
          <?php endif; ?>
        <?php else: ?>
          <span class="text-muted">Indefinite</span>
        <?php endif; ?>
      </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php endif; ?>

<!-- ── Borrow history ── -->
<p style="font-size:.8rem;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:var(--text2);margin:0 0 .6rem">
  Borrow History
</p>

<?php if (empty($history)): ?>
<div class="empty-state">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
    <path stroke-linecap="round" stroke-linejoin="round"
      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
  </svg>
  <p>No past borrows recorded.</p>
</div>
<?php else: ?>
<div class="table-card">
  <table>
    <thead>
      <tr>
        <th>Device</th>
        <th>Type</th>
        <th>Purpose</th>
        <th>Borrowed</th>
        <th>Returned</th>
        <th>Returned By</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($history as $r): ?>
    <tr>
      <td>
        <strong><?= htmlspecialchars($r['device_name']) ?></strong><br>
        <small class="text-muted"><code><?= htmlspecialchars($r['asset_tag']) ?></code></small>
      </td>
      <td><span class="chip"><?= htmlspecialchars($r['device_type']) ?></span></td>
      <td><?= $r['purpose'] ? htmlspecialchars($r['purpose']) : '<span class="text-muted">—</span>' ?></td>
      <td><?= date('M j, Y', strtotime($r['borrowed_at'])) ?></td>
      <td><?= date('M j, Y', strtotime($r['returned_at'])) ?></td>
      <td>
        <?php if ($r['returned_by_name']): ?>
          <?= htmlspecialchars($r['returned_by_name']) ?>
        <?php else: ?>
          <span class="text-muted">—</span>
        <?php endif; ?>
      </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php endif; ?>
