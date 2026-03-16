// ── Modal helpers ─────────────────────────────────────────
function openModal(id) {
  const el = document.getElementById(id);
  if (el) el.style.display = "flex";
}
function closeModal(id) {
  const el = document.getElementById(id);
  if (el) el.style.display = "none";
}
document.addEventListener("click", (e) => {
  if (e.target.classList.contains("modal-overlay"))
    e.target.style.display = "none";
});
document.addEventListener("keydown", (e) => {
  if (e.key === "Escape")
    document
      .querySelectorAll(".modal-overlay")
      .forEach((m) => (m.style.display = "none"));
});

// ── Device / Employee modal fillers ───────────────────────
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
    const ok =
      (!search || row.dataset.search.includes(search)) &&
      (!status || row.dataset.status === status) &&
      (!type || row.dataset.type === type);
    row.style.display = ok ? "" : "none";
  });
}

// ── Auto-dismiss flash ────────────────────────────────────
document.addEventListener("DOMContentLoaded", () => {
  const flash = document.querySelector(".flash");
  if (flash) {
    setTimeout(() => {
      flash.style.transition = "opacity .4s";
      flash.style.opacity = "0";
      setTimeout(() => flash.remove(), 400);
    }, 6000);
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
  themeToggle.addEventListener("click", () => {
    setTheme(
      document.documentElement.getAttribute("data-theme") === "dark"
        ? "light"
        : "dark",
    );
  });
}

// ── QR Scanner ────────────────────────────────────────────
const scannerState = {};

function beep() {
  try {
    const ctx = new (window.AudioContext || window.webkitAudioContext)();
    const osc = ctx.createOscillator();
    const gain = ctx.createGain();
    osc.connect(gain);
    gain.connect(ctx.destination);
    osc.type = "sine";
    osc.frequency.value = 1046; // high C
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
      videoEl.play();
      videoEl.addEventListener("loadedmetadata", () => {
        requestAnimationFrame(() => tick(videoEl, canvasEl, onFrame));
      });
    })
    .catch(() => {
      const err = document.getElementById("camera-error");
      if (err) err.style.display = "flex";
    });
}

function stopCamera(videoEl) {
  if (videoEl && videoEl.srcObject) {
    videoEl.srcObject.getTracks().forEach((t) => t.stop());
    videoEl.srcObject = null;
  }
}

function tick(videoEl, canvasEl, onFrame) {
  if (!videoEl.srcObject) return; // camera was stopped
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
      return; // stop ticking — camera will be stopped by caller
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
  // Elements
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

  if (!videoEmp) return; // not on the scan page

  // Start Step 1 camera
  startCamera(videoEmp, canvasEmp, (code) => {
    // Employee QR detected
    empInput.value = code;
    beep();
    stopCamera(videoEmp);
    lineEmp.style.display = "none";

    step1Num.classList.remove("active");
    step1Num.classList.add("done");
    step1Num.textContent = "✓";
    setFeedback(`${prefix}-feedback-emp`, "✅", code, "feedback-success");

    // Unlock Step 2
    step2El.classList.add("unlocked");
    step2Num.classList.add("active");
    lineDev.style.display = "block";
    setFeedback(`${prefix}-feedback-dev`, "📷", "Waiting for device QR...", "");
    if (resetBtn) resetBtn.style.display = "block";

    // Start Step 2 camera
    startCamera(videoDev, canvasDev, (code) => {
      // Device QR detected
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

      // Auto-submit after short delay so user can see the confirmation
      setTimeout(() => form.submit(), 600);
    });
  });
}

function resetScanner(prefix) {
  // Stop both cameras
  stopCamera(document.getElementById(`${prefix}-video-emp`));
  stopCamera(document.getElementById(`${prefix}-video-dev`));

  // Clear inputs
  document.getElementById(`${prefix}-emp-qr`).value = "";
  document.getElementById(`${prefix}-dev-qr`).value = "";

  // Reset Step 2
  const step2El = document.getElementById(`${prefix}-step2`);
  const step2Num = document.getElementById(`${prefix}-step2-num`);
  step2El.classList.remove("unlocked");
  step2Num.classList.remove("active", "done");
  step2Num.textContent = "2";
  document.getElementById(`${prefix}-line-dev`).style.display = "none";
  setFeedback(`${prefix}-feedback-dev`, "🔒", "Complete Step 1 first", "");

  // Reset Step 1
  const step1Num = document.querySelector(`#${prefix}-step1 .step-num`);
  step1Num.classList.remove("done");
  step1Num.classList.add("active");
  step1Num.textContent = "1";
  document.getElementById(`${prefix}-line-emp`).style.display = "block";
  setFeedback(`${prefix}-feedback-emp`, "📷", "Waiting for employee QR...", "");

  // Hide reset button
  document.getElementById(`${prefix}-reset`).style.display = "none";

  // Restart Step 1 camera
  const videoEmp = document.getElementById(`${prefix}-video-emp`);
  const canvasEmp = document.getElementById(`${prefix}-canvas-emp`);
  const videoDevEl = document.getElementById(`${prefix}-video-dev`);
  const canvasDevEl = document.getElementById(`${prefix}-canvas-dev`);
  const empInput = document.getElementById(`${prefix}-emp-qr`);
  const devInput = document.getElementById(`${prefix}-dev-qr`);
  const form = document.getElementById(`${prefix}-form`);
  const lineDev = document.getElementById(`${prefix}-line-dev`);
  const lineEmp = document.getElementById(`${prefix}-line-emp`);
  const step1NumEl = document.querySelector(`#${prefix}-step1 .step-num`);
  const resetBtn = document.getElementById(`${prefix}-reset`);

  initScanner(prefix);
}

// ── Init both scanners on page load ───────────────────────
document.addEventListener("DOMContentLoaded", () => {
  initScanner("borrow");
  initScanner("return");
});
