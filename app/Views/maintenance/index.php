<?php $canEdit = in_array($user['role'], ['admin', 'it_staff']); ?>

<div class="page-header">
  <div>
    <h1>Maintenance</h1>
    <p class="page-sub">Storage locations · <?= count($locations) ?> defined</p>
  </div>
  <?php if ($canEdit): ?>
  <button class="btn btn-primary" onclick="openModal('modal-add-location')">+ Add Location</button>
  <?php endif; ?>
</div>

<div class="table-card">
  <table id="locationsTable">
    <thead>
      <tr>
        <th>Cabinet</th>
        <th>Shelf / Section</th>
        <th>Devices Assigned</th>
        <?php if ($canEdit): ?><th>Actions</th><?php endif; ?>
      </tr>
    </thead>
    <tbody>
    <?php if (empty($locations)): ?>
    <tr>
      <td colspan="<?= $canEdit ? 4 : 3 ?>" class="text-muted" style="text-align:center;padding:2rem">
        No locations defined yet. Add a cabinet and shelf to get started.
      </td>
    </tr>
    <?php else: ?>
    <?php foreach ($locations as $loc): ?>
    <tr>
      <td><strong><?= htmlspecialchars($loc['cabinet']) ?></strong></td>
      <td><?= htmlspecialchars($loc['shelf']) ?></td>
      <td>
        <?php if ((int)$loc['device_count'] > 0): ?>
          <span class="chip"><?= (int)$loc['device_count'] ?></span>
        <?php else: ?>
          <span class="text-muted">—</span>
        <?php endif; ?>
      </td>
      <?php if ($canEdit): ?>
      <td class="actions-cell">
        <form method="POST" action="/inventory/public/maintenance/locations/delete" style="display:inline">
          <?= $csrf ?>
          <input type="hidden" name="location_id" value="<?= (int)$loc['id'] ?>">
          <button type="submit" class="btn btn-xs btn-warn"
            <?php if ((int)$loc['device_count'] > 0): ?>
              disabled
              title="<?= (int)$loc['device_count'] ?> device(s) still assigned — reassign them first"
            <?php else: ?>
              onclick="return confirm('Remove <?= htmlspecialchars(addslashes($loc['cabinet'] . ' — ' . $loc['shelf'])) ?>?')"
            <?php endif; ?>
          >Remove</button>
        </form>
      </td>
      <?php endif; ?>
    </tr>
    <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
  </table>
</div>

<?php if ($canEdit): ?>

<!-- ── Modal: Add Location ── -->
<div id="modal-add-location" class="modal-overlay" style="display:none">
  <div class="modal">
    <div class="modal-header">
      <h3>Add Location</h3>
      <button class="modal-close" onclick="closeModal('modal-add-location')">✕</button>
    </div>
    <form method="POST" action="/inventory/public/maintenance/locations">
      <?= $csrf ?>
      <div class="form-grid-2">
        <div class="form-group">
          <label>Cabinet *</label>
          <input type="text" name="cabinet" required placeholder="Cabinet A">
        </div>
        <div class="form-group">
          <label>Shelf / Section *</label>
          <input type="text" name="shelf" required placeholder="Shelf 1">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('modal-add-location')">Cancel</button>
        <button type="submit" class="btn btn-primary">Add Location</button>
      </div>
    </form>
  </div>
</div>

<?php endif; ?>
