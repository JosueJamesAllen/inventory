<div class="auth-wrap">
  <div class="auth-card">

    <div class="auth-logo">
      <img src="/inventory/public/img/Philippine_Statistics_Authority.svg.png"
           alt="PSA" class="auth-psa-logo">
      <div class="auth-logo-text">
        <span class="auth-logo-agency">Philippine Statistics Authority</span>
        <span class="auth-logo-office">Marinduque Provincial Statistics Office</span>
        <span class="auth-logo-system">IT Inventory System</span>
      </div>
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
