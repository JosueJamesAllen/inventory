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
    <p class="auth-subtitle">Scan your employee ID card or enter your code manually</p>

    <?php if ($flash): ?>
    <div class="flash flash-<?= htmlspecialchars($flash['type']) ?>"><?= $flash['message'] ?></div>
    <?php endif; ?>

    <form method="POST" action="/inventory/public/login" id="login-form">
      <?= $csrf ?>
      <input type="hidden" name="qr_code" id="login-qr-hidden">
    </form>

    <!-- Camera scanner -->
    <div id="login-scanner">
      <div class="video-wrap">
        <video id="login-video" autoplay playsinline muted webkit-playsinline></video>
        <div class="scan-line" id="login-scan-line"></div>
        <canvas id="login-canvas" hidden></canvas>
      </div>
      <div class="scan-feedback" id="login-feedback">
        <span class="feedback-icon">📷</span>
        <span class="feedback-text">Waiting for QR code...</span>
      </div>
    </div>

    <!-- Manual entry -->
    <div style="margin-top:1rem">
      <button type="button" class="btn btn-outline btn-block" id="toggle-manual-btn" onclick="loginToggleManual()">
        Enter code manually
      </button>
      <div id="login-manual" style="display:none;margin-top:.75rem">
        <input type="text" id="login-manual-input"
               placeholder="e.g. ISA1-JAMJ"
               autocomplete="off"
               style="width:100%;box-sizing:border-box;margin-bottom:.5rem"
               onkeydown="if(event.key==='Enter')loginSubmitManual()">
        <button type="button" class="btn btn-primary btn-block" onclick="loginSubmitManual()">
          Sign In
        </button>
      </div>
    </div>

  </div>
</div>

<script>
(function () {
  var video   = document.getElementById('login-video');
  var canvas  = document.getElementById('login-canvas');
  var hidden  = document.getElementById('login-qr-hidden');
  var form    = document.getElementById('login-form');
  var scanLine = document.getElementById('login-scan-line');

  startCamera(video, canvas, function (code) {
    hidden.value = code;
    beep();
    setFeedback('login-feedback', '⏳', 'Signing in…', 'feedback-submitting');
    stopCamera(video);
    if (scanLine) scanLine.style.display = 'none';
    setTimeout(function () { form.submit(); }, 400);
  });

  window.loginToggleManual = function () {
    var panel = document.getElementById('login-manual');
    var btn   = document.getElementById('toggle-manual-btn');
    var show  = panel.style.display === 'none';
    panel.style.display = show ? 'block' : 'none';
    btn.textContent     = show ? 'Use camera instead' : 'Enter code manually';
    if (show) document.getElementById('login-manual-input').focus();
  };

  window.loginSubmitManual = function () {
    var val = document.getElementById('login-manual-input').value.trim();
    if (!val) return;
    hidden.value = val;
    form.submit();
  };
})();
</script>
