<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($appName) ?></title>
  <meta name="csrf-token" content="<?= Core\Session::csrfToken() ?>">
  <script>
    const t = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', t);
  </script>
  <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600&family=Fira+Code:wght@400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= '//' . $_SERVER['HTTP_HOST'] . '/inventory/public/css/app.css?v=4' ?>">
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
        <span>IT Inventory</span>
        <button class="sidebar-theme-btn" id="themeToggle" title="Toggle dark mode">
          <svg class="theme-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
          </svg>
          <svg class="theme-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
          </svg>
          <svg class="theme-sparkle" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
          </svg>
        </button>
      </div>

      <nav class="nav">
        <?php
        $navItems = [
          '/dashboard' => ['Dashboard',    'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
          '/scan'       => ['Borrow / Return', 'M7 16V4m0 0L3 8m4-4l4 4M17 8v12m0 0l4-4m-4 4l-4-4'],
          '/my-borrows' => ['My Borrows',     'M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4'],
          '/devices'    => ['Devices',        'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
          '/employees' => ['Employees',     'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
          '/audit'       => ['Audit Logs',    'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01'],
          '/maintenance' => ['Maintenance',   'M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4'],
          '/activity'    => ['Activity Log',  'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
        ];

        if ($user['role'] === 'borrower') {
            unset($navItems['/employees']);
            unset($navItems['/maintenance']);
            unset($navItems['/audit']);
            unset($navItems['/activity']);
        } else {
            unset($navItems['/my-borrows']);
            if ($user['role'] !== 'admin') {
                unset($navItems['/activity']);
            }
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
        <button class="about-trigger" id="aboutBtn" title="About this system">v1.0 &nbsp;·&nbsp; PSO Marinduque &nbsp;·&nbsp; 2026</button>
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
             class="agency-logo" id="psa-logo">
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

  <script src="/inventory/public/js/app.js?v=2"></script>

  <div id="about-overlay" style="display:none">
    <div class="about-card">
      <button class="about-close" id="aboutClose">✕</button>

      <div class="about-title">IT Equipment Inventory &amp; Borrowing System</div>
      <div class="about-version">v1.0 &nbsp;·&nbsp; Philippine Statistics Authority &nbsp;·&nbsp; Marinduque PSO &nbsp;·&nbsp; 2026</div>

      <div class="about-divider"></div>

      <p class="about-story">
        Before this system existed, every borrow and return was logged on a spreadsheet.
        A file that got out of date. A row that got missed.
        <br><br>
        This was built to fix that.
      </p>

      <div class="about-divider"></div>

      <div class="about-credits-label">Built by</div>
      <div class="about-credits">
        <div class="about-person">
          <span class="about-name">James Allen M. Josue</span>
          <span class="about-handle">axellexious</span>
        </div>
        <div class="about-person">
          <span class="about-name">Frenz Darren J. Medallon</span>
          <span class="about-handle">drrnie</span>
        </div>
        <div class="about-claude">With a lot of help from Claude.</div>
        <div class="about-unit">Statistical Unit &nbsp;·&nbsp; May 2026</div>
      </div>

      <div class="about-divider"></div>

      <div class="about-letter">
        <div class="about-letter-heading">To whoever maintains this next —</div>
        This system knows where the laptops are.<br>
        Keep it running. Update the employee list.<br>
        If something breaks, check the README.<br><br>
        It was built with care.<br>
        We hope it still shows.
      </div>

      <div class="about-divider"></div>

      <div class="about-footer">Philippine Statistics Authority &nbsp;·&nbsp; Marinduque Provincial Statistics Office</div>
    </div>
  </div>

  <div id="love-overlay" style="display:none">
    <div class="love-inner">
      <div class="love-heart">♥</div>
      <div class="love-to">for my love,</div>
      <div class="love-name">Krissia Flor Rodelas Rivadeniera</div>
      <div class="love-body">
        I hope to marry you someday.<br>
        I love you so, so much.
      </div>
      <div class="love-dismiss">click anywhere · esc to close</div>
    </div>
  </div>

  <div id="konami-overlay" style="display:none">
    <div class="konami-inner">
      <div class="konami-name">axellexious</div>
      <div class="konami-name">drrnie<span class="konami-cursor"></span></div>

      <div class="konami-rule"></div>

      <div class="konami-body">
        they replaced a spreadsheet<br>
        with something that actually works.
      </div>

      <div class="konami-knows">
        it knows where the laptops are.<br>
        it knows who borrowed them.<br>
        it knows when they're overdue.
      </div>

      <div class="konami-rule"></div>

      <div class="konami-meta">
        built · may 2026<br>
        <span class="konami-online"><span class="konami-dot"></span>still running</span>
      </div>

      <div class="konami-helper">with a lot of help from claude.</div>

      <div class="konami-org">
        Philippine Statistics Authority<br>
        Marinduque Provincial Statistics Office
      </div>
      <div class="konami-dismiss">click anywhere · esc to close</div>
    </div>
  </div>

</body>

</html>