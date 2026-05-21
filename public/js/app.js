let activeScannerPrefix = null;

// ── Activity logging ──────────────────────────────────────
function logActivity(action, description) {
  const token = document.querySelector('meta[name="csrf-token"]')?.content;
  if (!token) return;
  fetch('/inventory/public/activity/log', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: new URLSearchParams({ _csrf: token, action, description }),
  }).catch(function() {});
}

// ── Modal helpers ─────────────────────────────────────────
function openModal(id) {
  const el = document.getElementById(id);
  if (el) el.style.display = "flex";
}
function closeModal(id) {
  const el = document.getElementById(id);
  if (el) el.style.display = "none";
}
document.addEventListener("click", function (e) {
  if (e.target.classList.contains("modal-overlay")) {
    e.target.style.display = "none";
  }
});
document.addEventListener("keydown", function (e) {
  if (e.key === "Escape") {
    document.querySelectorAll(".modal-overlay").forEach((m) => {
      m.style.display = "none";
    });
  }
});

// ── Device modals ─────────────────────────────────────────
async function openEditDevice(device) {
  if (typeof refreshLocGroups === "function") {
    await refreshLocGroups();
    const cab = document.getElementById("edit-device-cabinet");
    if (cab) {
      cab.innerHTML = '<option value="">Select cabinet...</option>';
      Object.keys(_locGroups).forEach(function(c) {
        const opt = document.createElement("option");
        opt.value = opt.textContent = c;
        cab.appendChild(opt);
      });
    }
  }
  document.getElementById("edit-device-id").value = device.id;
  document.getElementById("edit-device-name").value = device.name;
  document.getElementById("edit-device-type").value = device.type;
  document.getElementById("edit-device-cabinet").value = device.cabinet || "";
  if (typeof updateShelfOptions === "function") updateShelfOptions("edit");
  document.getElementById("edit-device-shelf").value = device.shelf || "";
  document.getElementById("edit-device-status").value = device.status;
  document.getElementById("edit-device-notes").value = device.notes || "";
  openModal("modal-edit-device");
}
function openReconcile(id, name, currentStatus) {
  document.getElementById("recon-device-id").value = id;
  document.getElementById("recon-device-name").value = name;
  document.getElementById("recon-new-status").value = currentStatus;
  openModal("modal-reconcile");
}

// ── Employee modals ───────────────────────────────────────
function openEditEmployee(emp) {
  document.getElementById("edit-emp-id").value = emp.id;
  document.getElementById("edit-emp-name").value = emp.name;
  document.getElementById("edit-emp-dept").value = emp.department;
  document.getElementById("edit-emp-qr").value = emp.qr_code;
  document.getElementById("edit-emp-role").value = emp.role;
  openModal("modal-edit-employee");
}

// ── Device table filtering ────────────────────────────────
function filterDevices() {
  const search = (
    document.getElementById("searchInput")?.value || ""
  ).toLowerCase();
  const status = (
    document.getElementById("statusFilter")?.value || ""
  ).toLowerCase();
  const type = (
    document.getElementById("typeFilter")?.value || ""
  ).toLowerCase();
  const location = document.getElementById("locationFilter")?.value || "";
  const typeCounts = {};
  document.querySelectorAll("#devicesTable tbody tr").forEach((row) => {
    const matchSearch =
      !search || (row.dataset.search || "").toLowerCase().includes(search);
    const matchStatus =
      !status || (row.dataset.status || "").toLowerCase() === status;
    const matchType = !type || (row.dataset.type || "").toLowerCase() === type;
    const matchLocation = !location || (row.dataset.location || "") === location;
    const visible = matchSearch && matchStatus && matchType && matchLocation;
    row.style.display = visible ? "" : "none";
    if (visible) {
      const t = (row.dataset.type || "").toLowerCase();
      typeCounts[t] = (typeCounts[t] || 0) + 1;
    }
  });
  document.querySelectorAll("[data-type-count]").forEach((el) => {
    const t = el.dataset.typeCount;
    el.textContent = typeCounts[t] || 0;
  });
}

// ── Auto-dismiss flash ────────────────────────────────────
document.addEventListener("DOMContentLoaded", function () {
  const flash = document.querySelector(".flash");
  if (flash) {
    setTimeout(() => {
      flash.style.transition = "opacity .4s";
      flash.style.opacity = "0";
      setTimeout(() => flash.remove(), 400);
    }, 5000);
  }
});

// ── Mobile sidebar ────────────────────────────────────────
const sidebar = document.getElementById("sidebar");
const sidebarOverlay = document.getElementById("sidebarOverlay");
const mobileMenuBtn = document.getElementById("mobileMenuBtn");

function openSidebar() {
  sidebar.classList.add("sidebar-open");
  sidebarOverlay.classList.add("active");
  document.body.style.overflow = "hidden";
}
function closeSidebar() {
  sidebar.classList.remove("sidebar-open");
  sidebarOverlay.classList.remove("active");
  document.body.style.overflow = "";
}

if (mobileMenuBtn) mobileMenuBtn.addEventListener("click", openSidebar);
if (sidebarOverlay) sidebarOverlay.addEventListener("click", closeSidebar);
document.querySelectorAll(".nav-item").forEach((item) => {
  item.addEventListener("click", closeSidebar);
});

// ── Dark / Pastel mode ────────────────────────────────────
const themeToggle = document.getElementById("themeToggle");
const themeToggleMobile = document.getElementById("themeToggleMobile");
const themeIconMobile = document.getElementById("themeIconMobile");
let themeClickCount = 0;

function getTheme() {
  try {
    return localStorage.getItem("theme") || "light";
  } catch (e) {
    return "light";
  }
}
function setTheme(theme) {
  document.documentElement.setAttribute("data-theme", theme);
  try {
    localStorage.setItem("theme", theme);
  } catch (e) {}
  const icon = theme === "dark" ? "☀️" : theme === "pastel" ? "✨" : "🌙";
  if (themeIconMobile) themeIconMobile.textContent = icon;
  const authThemeIcon = document.getElementById("authThemeIcon");
  if (authThemeIcon) authThemeIcon.textContent = icon;
}

setTheme(getTheme());

function toggleTheme() {
  const current = document.documentElement.getAttribute("data-theme");
  if (current === "pastel") {
    setTheme("light");
    themeClickCount = 0;
    return;
  }
  themeClickCount++;
  if (themeClickCount >= 5) {
    setTheme("pastel");
    themeClickCount = 0;
    return;
  }
  setTheme(current === "dark" ? "light" : "dark");
}
if (themeToggle) themeToggle.addEventListener("click", toggleTheme);
if (themeToggleMobile) themeToggleMobile.addEventListener("click", toggleTheme);
document.addEventListener("DOMContentLoaded", function () {
  const authThemeToggle = document.getElementById("authThemeToggle");
  if (authThemeToggle) authThemeToggle.addEventListener("click", toggleTheme);
});

// ── QR Scanner ────────────────────────────────────────────
function beep() {
  try {
    const ctx = new (window.AudioContext || window.webkitAudioContext)();
    const osc = ctx.createOscillator();
    const gain = ctx.createGain();
    osc.connect(gain);
    gain.connect(ctx.destination);
    osc.type = "sine";
    osc.frequency.value = 1046;
    gain.gain.setValueAtTime(0.3, ctx.currentTime);
    gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.15);
    osc.start(ctx.currentTime);
    osc.stop(ctx.currentTime + 0.15);
  } catch (e) {}
}

function startCamera(videoEl, canvasEl, onFrame) {
  navigator.mediaDevices
    .getUserMedia({
      video: {
        facingMode: "environment",
        width: { ideal: 1280 },
        height: { ideal: 720 },
      },
    })
    .then((stream) => {
      videoEl.srcObject = stream;
      videoEl.addEventListener("loadedmetadata", () => {
        videoEl.play().then(() => {
          requestAnimationFrame(() => tick(videoEl, canvasEl, onFrame));
        });
      });
    })
    .catch((err) => {
      console.error("Camera error:", err);
      const videoWrap = videoEl.closest(".video-wrap");
      if (videoWrap) {
        videoWrap.innerHTML = `
          <div style="display:flex;flex-direction:column;align-items:center;
                      justify-content:center;height:100%;color:#94A3B8;
                      gap:.5rem;padding:1rem;text-align:center">
            <span style="font-size:2rem">📷</span>
            <span style="font-size:.8rem">Camera access denied or unavailable.<br>
            Check browser permissions and refresh.</span>
          </div>`;
      }
      const errBanner = document.getElementById("camera-error");
      if (errBanner) errBanner.style.display = "flex";
    });
}

function stopCamera(videoEl) {
  if (videoEl && videoEl.srcObject) {
    videoEl.srcObject.getTracks().forEach((t) => t.stop());
    videoEl.srcObject = null;
  }
}

function tick(videoEl, canvasEl, onFrame) {
  if (!videoEl.srcObject) return;
  if (videoEl.readyState === videoEl.HAVE_ENOUGH_DATA) {
    canvasEl.width = videoEl.videoWidth;
    canvasEl.height = videoEl.videoHeight;
    const ctx = canvasEl.getContext("2d");
    ctx.drawImage(videoEl, 0, 0);
    const img = ctx.getImageData(0, 0, canvasEl.width, canvasEl.height);
    const code = jsQR(img.data, img.width, img.height, {
      inversionAttempts: "dontInvert",
    });
    if (code && code.data.trim()) {
      onFrame(code.data.trim());
      return;
    }
  }
  requestAnimationFrame(() => tick(videoEl, canvasEl, onFrame));
}

function setFeedback(id, icon, text, state) {
  const el = document.getElementById(id);
  if (!el) return;
  el.querySelector(".feedback-icon").textContent = icon;
  el.querySelector(".feedback-text").textContent = text;
  el.className = "scan-feedback" + (state ? " " + state : "");
}

function initScanner(prefix) {
  const videoEmp = document.getElementById(`${prefix}-video-emp`);
  const canvasEmp = document.getElementById(`${prefix}-canvas-emp`);
  const videoDev = document.getElementById(`${prefix}-video-dev`);
  const canvasDev = document.getElementById(`${prefix}-canvas-dev`);
  const step2El = document.getElementById(`${prefix}-step2`);
  const step2Num = document.getElementById(`${prefix}-step2-num`);
  const step1Num = document.querySelector(`#${prefix}-step1 .step-num`);
  const lineEmp = document.getElementById(`${prefix}-line-emp`);
  const lineDev = document.getElementById(`${prefix}-line-dev`);
  const resetBtn = document.getElementById(`${prefix}-reset`);
  const form = document.getElementById(`${prefix}-form`);
  const empInput = document.getElementById(`${prefix}-emp-qr`);
  const devInput = document.getElementById(`${prefix}-dev-qr`);

  if (!videoEmp) return;

  startCamera(videoEmp, canvasEmp, (code) => {
    // Step 1 — employee QR detected
    empInput.value = code;
    beep();
    stopCamera(videoEmp);
    lineEmp.style.display = "none";

    step1Num.classList.remove("active");
    step1Num.classList.add("done");
    step1Num.textContent = "✓";
    setFeedback(`${prefix}-feedback-emp`, "✅", code, "feedback-success");

    // Unlock step 2
    step2El.classList.add("unlocked");
    step2Num.classList.add("active");
    lineDev.style.display = "block";
    setFeedback(`${prefix}-feedback-dev`, "📷", "Waiting for device QR...", "");
    if (resetBtn) resetBtn.style.display = "block";

    startCamera(videoDev, canvasDev, (code) => {
      // Step 2 — device QR detected
      devInput.value = code;
      beep();
      stopCamera(videoDev);
      lineDev.style.display = "none";

      step2Num.classList.remove("active");
      step2Num.classList.add("done");
      step2Num.textContent = "✓";
      setFeedback(`${prefix}-feedback-dev`, "✅", code, "feedback-success");

      // Step 3 — show borrow details form (borrow only; return still auto-submits)
      if (prefix === "borrow") {
        const step3El  = document.getElementById("borrow-step3");
        const step3Num = document.getElementById("borrow-step3-num");
        const summary  = document.getElementById("borrow-confirm-summary");
        const empVal   = empInput.value;
        if (step3El) {
          step3El.style.display = "block";
          step3El.classList.remove("scan-step-locked");
          step3El.classList.add("unlocked");
          if (step3Num) { step3Num.classList.add("active"); }
          if (summary) {
            summary.innerHTML =
              `<span class="confirm-chip">👤 ${empVal}</span>` +
              `<span class="confirm-chip">💻 ${code}</span>`;
          }
          document.getElementById("borrow-purpose")?.focus();
        }
      } else {
        setFeedback(`${prefix}-feedback-emp`, "⏳", "Submitting...", "feedback-submitting");
        setTimeout(() => form.submit(), 600);
      }
    });
  });
}

function resetScanner(prefix) {
  stopCamera(document.getElementById(`${prefix}-video-emp`));
  stopCamera(document.getElementById(`${prefix}-video-dev`));

  document.getElementById(`${prefix}-emp-qr`).value = "";
  document.getElementById(`${prefix}-dev-qr`).value = "";

  const step2El = document.getElementById(`${prefix}-step2`);
  const step2Num = document.getElementById(`${prefix}-step2-num`);
  step2El.classList.remove("unlocked");
  step2Num.classList.remove("active", "done");
  step2Num.textContent = "2";
  document.getElementById(`${prefix}-line-dev`).style.display = "none";
  setFeedback(`${prefix}-feedback-dev`, "🔒", "Complete Step 1 first", "");

  const step1Num = document.querySelector(`#${prefix}-step1 .step-num`);
  step1Num.classList.remove("done");
  step1Num.classList.add("active");
  step1Num.textContent = "1";
  document.getElementById(`${prefix}-line-emp`).style.display = "block";
  setFeedback(`${prefix}-feedback-emp`, "📷", "Waiting for employee QR...", "");

  const resetBtn = document.getElementById(`${prefix}-reset`);
  if (resetBtn) resetBtn.style.display = "none";

  // Reset step 3 if borrow
  if (prefix === "borrow") {
    const step3El = document.getElementById("borrow-step3");
    const step3Num = document.getElementById("borrow-step3-num");
    if (step3El) { step3El.style.display = "none"; step3El.classList.remove("unlocked"); }
    if (step3Num) { step3Num.classList.remove("active", "done"); step3Num.textContent = "3"; }
    const purposeInput = document.getElementById("borrow-purpose");
    const dateInput    = document.getElementById("borrow-return-date");
    const cbInput      = document.getElementById("borrow-indefinite");
    if (purposeInput) purposeInput.value = "";
    if (dateInput)    { dateInput.value = ""; dateInput.disabled = false; }
    if (cbInput)      cbInput.checked = false;
  }

  // Restart step 1 camera
  initScanner(prefix);
}

// ── Script loader helper ──────────────────────────────────
function loadScript(src) {
  return new Promise((resolve, reject) => {
    if (document.querySelector(`script[src="${src}"]`)) { resolve(); return; }
    const s = document.createElement('script');
    s.src = src; s.onload = resolve; s.onerror = reject;
    document.head.appendChild(s);
  });
}

// ── QR Code PDF download ──────────────────────────────────
async function downloadQrPdf(items, title) {
  try {
    await Promise.all([
      loadScript('https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js'),
      loadScript('https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js'),
    ]);
  } catch {
    alert('Could not load PDF library. Check your internet connection.');
    return;
  }

  // Generate QR data URLs via qrcodejs canvas
  const qrUrls = items.map(item => {
    const div = document.createElement('div');
    div.style.cssText = 'position:absolute;left:-9999px;';
    document.body.appendChild(div);
    new QRCode(div, { text: item.qr, width: 256, height: 256, correctLevel: QRCode.CorrectLevel.M });
    const canvas = div.querySelector('canvas');
    const url = canvas ? canvas.toDataURL('image/png') : null;
    document.body.removeChild(div);
    return url;
  });

  const { jsPDF } = window.jspdf;
  const doc = new jsPDF({ orientation: 'portrait', unit: 'mm', format: 'a4' });

  const cols   = 9;
  const mX     = 8, mY = 8, gX = 2, gY = 3;
  const cardW  = (210 - mX * 2 - gX * (cols - 1)) / cols;
  const qrSize = cardW - 2;
  const cardH  = qrSize + 9;
  const rows   = Math.floor((297 - mY * 2) / (cardH + gY));
  const perPg  = cols * rows;

  items.forEach((item, i) => {
    if (i > 0 && i % perPg === 0) doc.addPage();
    const idx = i % perPg;
    const x = mX + (idx % cols) * (cardW + gX);
    const y = mY + Math.floor(idx / cols) * (cardH + gY);

    doc.setDrawColor(210, 210, 210);
    doc.setLineWidth(0.2);
    doc.rect(x, y, cardW, cardH);

    if (qrUrls[i]) doc.addImage(qrUrls[i], 'PNG', x + 1, y + 1, qrSize, qrSize);

    doc.setFontSize(5.5);
    doc.setTextColor(30, 30, 30);
    doc.text(item.name, x + cardW / 2, y + qrSize + 4.5, { align: 'center', maxWidth: cardW - 2 });

    if (item.sub) {
      doc.setFontSize(4.5);
      doc.setTextColor(110, 110, 110);
      doc.text(item.sub, x + cardW / 2, y + qrSize + 7.5, { align: 'center', maxWidth: cardW - 2 });
    }
  });

  doc.save(`${title.replace(/\s+/g, '-')}-QR-Codes.pdf`);
}

// ── QR Code print window ─────────────────────────────────
function openQrPrintWindow(items, title) {
  const cards = items.map(item => `
    <div class="qr-card">
      <div class="qr-wrap" data-qr="${item.qr.replace(/"/g, '&quot;')}"></div>
      <div class="qr-name">${item.name.replace(/</g,'&lt;')}</div>
      ${item.sub ? `<div class="qr-sub">${item.sub.replace(/</g,'&lt;')}</div>` : ''}
    </div>`).join('');

  const html = `<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>${title} — QR Codes</title>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: "Montserrat", Arial, sans-serif; background: #fff; color: #111; }
    .toolbar { display: flex; align-items: center; gap: 1rem; padding: .75rem 1.25rem;
               border-bottom: 1px solid #e2e8f0; background: #f8fafc; }
    .toolbar h2 { font-size: 1rem; font-weight: 700; flex: 1; }
    .toolbar button { padding: .45rem 1rem; border-radius: 7px; border: 1px solid #cbd5e1;
                      font-size: .85rem; cursor: pointer; font-weight: 600; }
    .toolbar .btn-print { background: #0f4c81; color: #fff; border-color: #0f4c81; }
    .qr-grid { display: grid; grid-template-columns: repeat(6, 1fr);
               gap: .35rem; padding: .5rem; }
    .qr-card { border: 1px solid #e2e8f0; border-radius: 6px; padding: .3rem .25rem;
               text-align: center; break-inside: avoid; }
    .qr-wrap { display: flex; justify-content: center; margin-bottom: .2rem; }
    .qr-wrap canvas, .qr-wrap img { max-width: 100%; height: auto; }
    .qr-name { font-size: .65rem; font-weight: 700; line-height: 1.2; }
    .qr-sub  { font-size: .58rem; color: #64748b; margin-top: .1rem; }
    @media print {
      .toolbar { display: none; }
      .qr-grid { gap: .6rem; padding: .5rem; }
      body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    }
    @media (max-width: 600px) { .qr-grid { grid-template-columns: repeat(2, 1fr); } }
  </style>
</head>
<body>
  <div class="toolbar">
    <h2>${title} — QR Codes (${items.length})</h2>
    <button class="btn-print" onclick="window.print()">Print</button>
    <button onclick="window.close()">Close</button>
  </div>
  <div class="qr-grid">${cards}</div>
  <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"><\/script>
  <script>
    document.querySelectorAll('.qr-wrap[data-qr]').forEach(function(el) {
      new QRCode(el, { text: el.dataset.qr, width: 80, height: 80,
                       correctLevel: QRCode.CorrectLevel.M });
    });
  <\/script>
</body>
</html>`;

  const win = window.open('', '_blank', 'width=900,height=700');
  win.document.write(html);
  win.document.close();
}

// ── Scan page type selector ───────────────────────────────
function selectType(type) {
  document.getElementById("type-prompt").style.display = "none";
  document.getElementById("section-borrow").style.display = "none";
  document.getElementById("section-return").style.display = "none";

  document.getElementById("section-" + type).style.display = "block";
  activeScannerPrefix = type;

  setTimeout(() => initScanner(type), 100);
}

function toggleIndefinite(cb) {
  const dateInput = document.getElementById("borrow-return-date");
  if (!dateInput) return;
  dateInput.disabled = cb.checked;
  if (cb.checked) dateInput.value = "";
}

function goBack() {
  if (activeScannerPrefix) {
    stopCamera(document.getElementById(activeScannerPrefix + "-video-emp"));
    stopCamera(document.getElementById(activeScannerPrefix + "-video-dev"));
    activeScannerPrefix = null;
  }
  document.getElementById("section-borrow").style.display = "none";
  document.getElementById("section-return").style.display = "none";
  document.getElementById("type-prompt").style.display = "block";
}

// ── Konami code easter egg ─────────────────────────────────
(function () {
  const seq = ['ArrowUp','ArrowUp','ArrowDown','ArrowDown','ArrowLeft','ArrowRight','ArrowLeft','ArrowRight','b','a'];
  let pos = 0;
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      const el = document.getElementById('konami-overlay');
      if (el) el.style.display = 'none';
    }
    pos = (e.key === seq[pos]) ? pos + 1 : (e.key === seq[0] ? 1 : 0);
    if (pos === seq.length) {
      pos = 0;
      const el = document.getElementById('konami-overlay');
      if (el) {
        el.style.animation = 'none';
        el.offsetWidth;
        el.style.animation = '';
        el.style.display = 'flex';
      }
    }
  });
  document.getElementById('konami-overlay')?.addEventListener('click', function () {
    this.style.display = 'none';
  });
})();

// ── PSA logo love easter egg ───────────────────────────────
(function () {
  let psaClicks = 0;
  document.getElementById('psa-logo')?.addEventListener('click', function () {
    if (document.documentElement.getAttribute('data-theme') !== 'pastel') {
      psaClicks = 0;
      return;
    }
    psaClicks++;
    if (psaClicks >= 5) {
      psaClicks = 0;
      const el = document.getElementById('love-overlay');
      if (el) {
        el.style.animation = 'none';
        el.offsetWidth;
        el.style.animation = '';
        el.style.display = 'flex';
      }
    }
  });
  document.getElementById('love-overlay')?.addEventListener('click', function () {
    this.style.display = 'none';
  });
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      const el = document.getElementById('love-overlay');
      if (el) el.style.display = 'none';
    }
  });
})();
