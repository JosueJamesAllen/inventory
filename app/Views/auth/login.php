<div class="auth-wrap">
  <div class="auth-card">

    <div class="auth-logo">
      <svg width="42" height="42" viewBox="0 0 40 40" fill="none">
        <rect width="40" height="40" rx="10" fill="#0F4C81"/>
        <path d="M10 28V16l10-7 10 7v12H24v-7h-8v7z" fill="#fff"/>
        <rect x="17" y="21" width="6" height="7" rx="1" fill="#38BDF8"/>
      </svg>
      <span>IT Inventory System</span>
    </div>

    <h1>Sign In</h1>
    <p class="auth-subtitle">Enter your Employee QR Code to continue</p>

    <?php if ($flash): ?>
    <div class="flash flash-<?= htmlspecialchars($flash['type']) ?>"><?= $flash['message'] ?></div>
    <?php endif; ?>

    <form method="POST" action="/inventory/public/login">
      <?= $csrf ?>
      <div class="form-group">
        <label for="qr_code">QR Code / Employee ID</label>
        <input type="text" id="qr_code" name="qr_code" placeholder="e.g. EMP-001" autofocus required>
        <small>Scan your employee ID card or enter your code manually.</small>
      </div>
      <button type="submit" class="btn btn-primary btn-block">Sign In</button>
    </form>

    <div class="demo-section">
      <p class="demo-label">Quick login for demo:</p>
      <div class="demo-grid">
        <?php
        $demoAccounts = [
            ['EMP-001', 'James Josue', 'Admin',    'chip-admin'],
            ['EMP-002', 'Frenz Medallon', 'IT Staff', 'chip-it'],
            ['EMP-003', 'Maria Santos', 'Borrower', 'chip-user'],
            ['EMP-004', 'Carlos Reyes', 'Borrower', 'chip-user'],
        ];
        foreach ($demoAccounts as [$qr, $name, $role, $cls]):
        ?>
        <form method="POST" action="/inventory/public/login">
          <?= $csrf ?>
          <input type="hidden" name="qr_code" value="<?= $qr ?>">
          <button type="submit" class="demo-btn <?= $cls ?>" title="<?= $name ?>">
            <span class="demo-role"><?= $role ?></span>
            <span class="demo-name"><?= $name ?></span>
            <span class="demo-qr"><?= $qr ?></span>
          </button>
        </form>
        <?php endforeach; ?>
      </div>
    </div>

  </div>
</div>
