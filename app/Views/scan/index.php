<div class="page-header">
  <div>
    <h1>Borrow / Return</h1>
    <p class="page-sub">Select a transaction type to begin</p>
  </div>
</div>

<!-- ── Transaction Type Prompt ── -->
<div id="type-prompt">
  <div class="tx-prompt">
    <p class="tx-prompt-label">What would you like to do?</p>
    <div class="tx-choices">

      <button class="tx-choice tx-choice-borrow" onclick="selectType('borrow')">
        <div class="tx-choice-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M12 5v14M5 12l7 7 7-7"/>
          </svg>
        </div>
        <div class="tx-choice-label">Borrow</div>
        <div class="tx-choice-sub">Check out a device</div>
      </button>

      <button class="tx-choice tx-choice-return" onclick="selectType('return')">
        <div class="tx-choice-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M12 19V5M5 12l7-7 7 7"/>
          </svg>
        </div>
        <div class="tx-choice-label">Return</div>
        <div class="tx-choice-sub">Return a borrowed device</div>
      </button>

    </div>
  </div>
</div>

<!-- ── Borrow Form ── -->
<div id="section-borrow" style="display:none">
  <div class="scan-section-header">
    <button class="btn-back" onclick="goBack()">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
      Back
    </button>
    <div>
      <h2>Borrow Device</h2>
      <p class="page-sub">Scan employee ID then device QR code</p>
    </div>
  </div>

  <div class="card scan-card">
    <div class="card-body">
      <form method="POST" action="/inventory/public/scan/borrow" id="borrow-form">
        <?= $csrf ?>

        <div class="scan-step" id="borrow-step1">
          <div class="step-label">
            <span class="step-num active">1</span>
            <span>Scan Employee ID Card</span>
          </div>
          <div class="video-wrap">
            <video id="borrow-video-emp" autoplay playsinline muted webkit-playsinline></video>
            <div class="scan-line" id="borrow-line-emp"></div>
            <canvas id="borrow-canvas-emp" hidden></canvas>
          </div>
          <div class="scan-feedback" id="borrow-feedback-emp">
            <span class="feedback-icon">📷</span>
            <span class="feedback-text">Waiting for employee QR...</span>
          </div>
          <input type="hidden" name="emp_qr" id="borrow-emp-qr">
        </div>

        <div class="scan-step scan-step-locked" id="borrow-step2">
          <div class="step-label">
            <span class="step-num" id="borrow-step2-num">2</span>
            <span>Scan Device QR Code</span>
          </div>
          <div class="video-wrap">
            <video id="borrow-video-dev" autoplay playsinline muted webkit-playsinline></video>
            <div class="scan-line" id="borrow-line-dev" style="display:none"></div>
            <canvas id="borrow-canvas-dev" hidden></canvas>
          </div>
          <div class="scan-feedback" id="borrow-feedback-dev">
            <span class="feedback-icon">🔒</span>
            <span class="feedback-text">Complete Step 1 first</span>
          </div>
          <input type="hidden" name="dev_qr" id="borrow-dev-qr">
        </div>

        <button type="button" class="btn btn-outline btn-block btn-sm"
          id="borrow-reset" onclick="resetScanner('borrow')"
          style="margin-top:.75rem;display:none">↺ Reset</button>

      </form>
    </div>
  </div>
</div>

<!-- ── Return Form ── -->
<div id="section-return" style="display:none">
  <div class="scan-section-header">
    <button class="btn-back" onclick="goBack()">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
      Back
    </button>
    <div>
      <h2>Return Device</h2>
      <p class="page-sub">Scan employee ID then device QR code</p>
    </div>
  </div>

  <div class="card scan-card">
    <div class="card-body">
      <form method="POST" action="/inventory/public/scan/return" id="return-form">
        <?= $csrf ?>

        <div class="scan-step" id="return-step1">
          <div class="step-label">
            <span class="step-num active">1</span>
            <span>Scan Employee ID Card</span>
          </div>
          <div class="video-wrap">
            <video id="return-video-emp" aautoplay playsinline muted webkit-playsinline></video>
            <div class="scan-line" id="return-line-emp"></div>
            <canvas id="return-canvas-emp" hidden></canvas>
          </div>
          <div class="scan-feedback" id="return-feedback-emp">
            <span class="feedback-icon">📷</span>
            <span class="feedback-text">Waiting for employee QR...</span>
          </div>
          <input type="hidden" name="emp_qr" id="return-emp-qr">
        </div>

        <div class="scan-step scan-step-locked" id="return-step2">
          <div class="step-label">
            <span class="step-num" id="return-step2-num">2</span>
            <span>Scan Device QR Code</span>
          </div>
          <div class="video-wrap">
            <video id="return-video-dev" autoplay playsinline muted webkit-playsinline></video>
            <div class="scan-line" id="return-line-dev" style="display:none"></div>
            <canvas id="return-canvas-dev" hidden></canvas>
          </div>
          <div class="scan-feedback" id="return-feedback-dev">
            <span class="feedback-icon">🔒</span>
            <span class="feedback-text">Complete Step 1 first</span>
          </div>
          <input type="hidden" name="dev_qr" id="return-dev-qr">
        </div>

        <button type="button" class="btn btn-outline btn-block btn-sm"
          id="return-reset" onclick="resetScanner('return')"
          style="margin-top:.75rem;display:none">↺ Reset</button>

      </form>
    </div>
  </div>
</div>

<!-- Camera error -->
<div id="camera-error" class="flash flash-error" style="display:none;margin-top:1.5rem">
  ⚠️ Camera access denied. Please allow camera permission and refresh.
</div>