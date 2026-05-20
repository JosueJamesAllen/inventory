<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($appName) ?></title>
  <script>
    const t = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', t);
  </script>
  <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600&family=Fira+Code:wght@400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= '//' . $_SERVER['HTTP_HOST'] . '/inventory/public/css/app.css' ?>">
</head>

<body>

  <!-- ── Mobile top bar ── -->
  <header class="mobile-topbar">
    <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Open menu">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="M4 6h16M4 12h16M4 18h16"/>
      </svg>
    </button>
    <span class="mobile-topbar-title">IT Inventory</span>
    <button class="btn-theme-icon" id="themeToggleMobile" title="Toggle dark mode">
      <span id="themeIconMobile">🌙</span>
    </button>
  </header>
  <div class="sidebar-overlay" id="sidebarOverlay"></div>

  <div class="app-layout">

    <!-- ── Sidebar ── -->
    <aside class="sidebar" id="sidebar">
      <div class="sidebar-logo">
        <svg width="30" height="30" viewBox="0 0 40 40" fill="none">
          <rect width="40" height="40" rx="10" fill="rgba(255,255,255,.15)" />
          <path d="M10 28V16l10-7 10 7v12H24v-7h-8v7z" fill="#fff" />
          <rect x="17" y="21" width="6" height="7" rx="1" fill="#38BDF8" />
        </svg>
        <span>IT Inventory</span>

        <button class="btn-theme-icon" id="themeToggle" title="Toggle dark mode">
          <span id="themeIcon">🌙</span>
        </button>
      </div>

      <nav class="nav">
        <?php
        $navItems = [
          '/dashboard' => ['Dashboard',    'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
          '/scan'      => ['Borrow / Return', 'M7 16V4m0 0L3 8m4-4l4 4M17 8v12m0 0l4-4m-4 4l-4-4'],
          '/devices'   => ['Devices',       'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
          '/employees' => ['Employees',     'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
          '/audit'       => ['Audit Logs',    'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01'],
          '/maintenance' => ['Maintenance',   'M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4'],
        ];

        // Hide employees and maintenance from borrowers
        if ($user['role'] === 'borrower') {
            unset($navItems['/employees']);
            unset($navItems['/maintenance']);
        }

        $currentPath = '/' . trim(strtok($_SERVER['REQUEST_URI'], '?'), '/');
        $base        = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
        $currentPath = str_replace($base, '', $currentPath) ?: '/dashboard';

        foreach ($navItems as $path => [$label, $svgPath]):
          $active = str_starts_with($currentPath, $path) ? 'active' : '';
        ?>
          <a href="/inventory/public<?= $path ?>" class="nav-item <?= $active ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="<?= $svgPath ?>" />
            </svg>
            <?= htmlspecialchars($label) ?>
          </a>
        <?php endforeach; ?>
      </nav>

      <div class="sidebar-footer">
        <div class="user-card">
          <div class="user-avatar"><?= strtoupper(substr($user['name'], 0, 1)) ?></div>
          <div class="user-meta">
            <div class="user-name"><?= htmlspecialchars(explode(' ', $user['name'])[0]) ?></div>
            <div class="user-role"><?= htmlspecialchars(str_replace('_', ' ', $user['role'])) ?></div>
          </div>
        </div>
        <form method="POST" action="/inventory/public/logout">
          <?= $csrf ?>
          <button type="submit" class="btn btn-logout">Sign Out</button>
        </form>
      </div>
    </aside>

    <!-- ── Main ── -->
    <main class="main-content">
      <div class="agency-brand">
        <img src="/inventory/public/img/Philippine_Statistics_Authority.svg.png"
             alt="Philippine Statistics Authority"
             class="agency-logo">
        <div class="agency-text">
          <span class="agency-name">Philippine Statistics Authority</span>
          <span class="agency-unit">Marinduque Provincial Statistics Office</span>
        </div>
      </div>

      <?php if ($flash): ?>
        <div class="flash flash-<?= htmlspecialchars($flash['type']) ?>">
          <?= $flash['message'] ?>
          <button class="flash-close" onclick="this.parentElement.remove()">✕</button>
        </div>
      <?php endif; ?>

      <?= $content ?>
    </main>

  </div>

  <script src="/inventory/public/js/app.js"></script>

  <div id="konami-overlay" style="display:none">
    <div class="konami-inner">
      <div class="konami-name">axellexious</div>
      <div class="konami-name">drrnie<span class="konami-cursor"></span></div>
      <div class="konami-body">
        built an inventory system<br>
        for the government.<br>
        <br>
        it knows where the laptops are.
      </div>
      <div class="konami-org">
        Philippine Statistics Authority<br>
        Marinduque Provincial Statistics Office<br>
        2026
      </div>
      <div class="konami-dismiss">click anywhere · esc to close</div>
    </div>
  </div>

</body>

</html>