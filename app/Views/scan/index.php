<div class="page-header">
  <div>
    <h1>Borrow / Return</h1>
    <p class="page-sub">Point the webcam at a QR code to scan</p>
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
      <form method="POST" action="/inventory/public/scan/borrow" id="borrow-form">
        <?= $csrf ?>

        <!-- Step 1 -->
        <div class="scan-step" id="borrow-step1">
          <div class="step-label">
            <span class="step-num active">1</span>
            <span>Scan Employee ID Card</span>
          </div>
          <div class="video-wrap">
            <video id="borrow-video-emp" autoplay playsinline muted></video>
            <div class="scan-line" id="borrow-line-emp"></div>
            <canvas id="borrow-canvas-emp" hidden></canvas>
          </div>
          <div class="scan-feedback" id="borrow-feedback-emp">
            <span class="feedback-icon">📷</span>
            <span class="feedback-text">Waiting for employee QR...</span>
          </div>
          <input type="hidden" name="emp_qr" id="borrow-emp-qr">
        </div>

        <!-- Step 2 -->
        <div class="scan-step scan-step-locked" id="borrow-step2">
          <div class="step-label">
            <span class="step-num" id="borrow-step2-num">2</span>
            <span>Scan Device QR Code</span>
          </div>
          <div class="video-wrap">
            <video id="borrow-video-dev" autoplay playsinline muted></video>
            <div class="scan-line" id="borrow-line-dev" style="display:none"></div>
            <canvas id="borrow-canvas-dev" hidden></canvas>
          </div>
          <div class="scan-feedback" id="borrow-feedback-dev">
            <span class="feedback-icon">🔒</span>
            <span class="feedback-text">Complete Step 1 first</span>
          </div>
          <input type="hidden" name="dev_qr" id="borrow-dev-qr">
        </div>

        <button type="button" class="btn btn-outline btn-block btn-sm" id="borrow-reset"
          onclick="resetScanner('borrow')" style="margin-top:.75rem;display:none">
          ↺ Reset
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
      <form method="POST" action="/inventory/public/scan/return" id="return-form">
        <?= $csrf ?>

        <!-- Step 1 -->
        <div class="scan-step" id="return-step1">
          <div class="step-label">
            <span class="step-num active">1</span>
            <span>Scan Employee ID Card</span>
          </div>
          <div class="video-wrap">
            <video id="return-video-emp" autoplay playsinline muted></video>
            <div class="scan-line" id="return-line-emp"></div>
            <canvas id="return-canvas-emp" hidden></canvas>
          </div>
          <div class="scan-feedback" id="return-feedback-emp">
            <span class="feedback-icon">📷</span>
            <span class="feedback-text">Waiting for employee QR...</span>
          </div>
          <input type="hidden" name="emp_qr" id="return-emp-qr">
        </div>

        <!-- Step 2 -->
        <div class="scan-step scan-step-locked" id="return-step2">
          <div class="step-label">
            <span class="step-num" id="return-step2-num">2</span>
            <span>Scan Device QR Code</span>
          </div>
          <div class="video-wrap">
            <video id="return-video-dev" autoplay playsinline muted></video>
            <div class="scan-line" id="return-line-dev" style="display:none"></div>
            <canvas id="return-canvas-dev" hidden></canvas>
          </div>
          <div class="scan-feedback" id="return-feedback-dev">
            <span class="feedback-icon">🔒</span>
            <span class="feedback-text">Complete Step 1 first</span>
          </div>
          <input type="hidden" name="dev_qr" id="return-dev-qr">
        </div>

        <button type="button" class="btn btn-outline btn-block btn-sm" id="return-reset"
          onclick="resetScanner('return')" style="margin-top:.75rem;display:none">
          ↺ Reset
        </button>

      </form>
    </div>
  </div>

</div>

<!-- Camera permission denied message -->
<div id="camera-error" class="flash flash-error" style="display:none;margin-top:1.5rem">
  ⚠️ Camera access denied. Please allow camera permission in your browser and refresh the page.
</div>