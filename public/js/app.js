// ── Modal helpers ────────────────────────────────────────
function openModal(id) {
  const el = document.getElementById(id);
  if (el) el.style.display = "flex";
}

function closeModal(id) {
  const el = document.getElementById(id);
  if (el) el.style.display = "none";
}

// Close modal on backdrop click
document.addEventListener("click", function (e) {
  if (e.target.classList.contains("modal-overlay")) {
    e.target.style.display = "none";
  }
});

// Close modal on Escape
document.addEventListener("keydown", function (e) {
  if (e.key === "Escape") {
    document.querySelectorAll(".modal-overlay").forEach((m) => {
      m.style.display = "none";
    });
  }
});

// ── Device modals ────────────────────────────────────────
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
    const rowSearch = (row.dataset.search || "").toLowerCase();
    const rowStatus = (row.dataset.status || "").toLowerCase();
    const rowType = (row.dataset.type || "").toLowerCase();

    const matchSearch = !search || rowSearch.includes(search);
    const matchStatus = !status || rowStatus === status;
    const matchType = !type || rowType === type;

    row.style.display = matchSearch && matchStatus && matchType ? "" : "none";
  });
}

// ── Auto-dismiss flash after 5s ──────────────────────────
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

function updateIcon(theme) {
  if (themeIcon) themeIcon.textContent = theme === "dark" ? "☀️" : "🌙";
}

// Set icon to match current theme on load
updateIcon(localStorage.getItem("theme") || "light");

if (themeToggle) {
  themeToggle.addEventListener("click", function () {
    const current = document.documentElement.getAttribute("data-theme");
    const next = current === "dark" ? "light" : "dark";
    document.documentElement.setAttribute("data-theme", next);
    localStorage.setItem("theme", next);
    updateIcon(next);
  });
}
