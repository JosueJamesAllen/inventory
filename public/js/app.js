let activeScannerPrefix = null;

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
function openEditDevice(device) {
  document.getElementById("edit-device-id").value = device.id;
  document.getElementById("edit-device-name").value = device.name;
  document.getElementById("edit-device-type").value = device.type;
  document.getElementById("edit-device-cabinet").value = device.cabinet;
  document.getElementById("edit-device-shelf").value = device.shelf;
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
  document.querySelectorAll("#devicesTable tbody tr").forEach((row) => {
    const matchSearch =
      !search || (row.dataset.search || "").toLowerCase().includes(search);
    const matchStatus =
      !status || (row.dataset.status || "").toLowerCase() === status;
    const matchType = !type || (row.dataset.type || "").toLowerCase() === type;
    row.style.display = matchSearch && matchStatus && matchType ? "" : "none";
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

// ── Dark mode ─────────────────────────────────────────────
const themeToggle = document.getElementById("themeToggle");
const themeIcon = document.getElementById("themeIcon");

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
  if (themeIcon) themeIcon.textContent = theme === "dark" ? "☀️" : "🌙";
}

setTheme(getTheme());

if (themeToggle) {
  themeToggle.addEventListener("click", function () {
    setTheme(
      document.documentElement.getAttribute("data-theme") === "dark"
        ? "light"
        : "dark",
    );
  });
}

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
      setFeedback(
        `${prefix}-feedback-emp`,
        "⏳",
        "Submitting...",
        "feedback-submitting",
      );

      setTimeout(() => form.submit(), 600);
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

  // Restart step 1 camera
  initScanner(prefix);
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
