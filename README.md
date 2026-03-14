# IT Equipment Inventory & Borrowing System

A locally hosted web application for tracking the borrowing and return of IT equipment using QR code scanning. Built with PHP (MVC), MySQL, and vanilla JavaScript — no frameworks, no cloud dependency, no monthly fees.

---

## Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Database Setup](#database-setup)
- [Project Structure](#project-structure)
- [User Roles](#user-roles)
- [How It Works](#how-it-works)
- [QR Scanner Setup](#qr-scanner-setup)
- [Dark Mode](#dark-mode)
- [Demo Accounts](#demo-accounts)
- [Troubleshooting](#troubleshooting)

---

## Overview

This system replaces manual logging with a fast, accurate QR-based process. Any staff member who needs to borrow a laptop, monitor, or other IT device can do so in seconds by scanning two QR codes — their employee ID and the device sticker. IT staff always have a real-time view of every device's status and location.

---

## Features

| Feature | Description |
|---|---|
| **QR Code Scanning** | Works with USB plug-in scanners or existing QR codes on employee IDs and equipment |
| **Real-Time Dashboard** | Live view of all device statuses, active borrows, and today's transaction count |
| **Borrow & Return** | Two-scan process — employee ID then device QR |
| **Proxy Return** | Any staff member can return equipment on behalf of the original borrower |
| **Designated Shelf Location** | Every device has a permanent cabinet and shelf — displayed automatically on return |
| **Manual Reconciliation** | IT staff can override device status during physical inventory, with reason and timestamp logged |
| **Equipment Audit Log** | Full history per device — who borrowed it, who returned it, and when |
| **Employee Audit Log** | Full borrowing history per employee across all devices |
| **CSV Export** | Export both audit logs to CSV for record-keeping and management review |
| **Role-Based Access** | Separate access levels for Admin, IT Staff, and Borrower |
| **Dark Mode** | Toggle between light and dark theme, preference saved across sessions |
| **Locally Hosted** | Runs entirely on your office network — no internet required |

---

## Requirements

- [XAMPP](https://www.apachefriends.org/) (Apache + MySQL + PHP 8.0+)
- A modern browser (Chrome, Firefox, Edge)
- A USB QR code scanner (optional — dropdowns available for demo/testing)

---

## Installation

**1. Clone or extract the project**

Place the `inventory` folder inside your XAMPP web root:

```
C:/xampp/htdocs/inventory/
```

**2. Enable mod_rewrite in Apache**

Open `C:/xampp/apache/conf/httpd.conf` and make these two changes:

Uncomment the rewrite module (remove the `#`):
```apache
LoadModule rewrite_module modules/mod_rewrite.so
```

Find the `<Directory "C:/xampp/htdocs">` block and change:
```apache
AllowOverride None
```
to:
```apache
AllowOverride All
```

Restart Apache in the XAMPP Control Panel after saving.

**3. Set up the database**

See [Database Setup](#database-setup) below.

**4. Open the app**

```
http://localhost/inventory/public
```

---

## Database Setup

**1. Open phpMyAdmin**
```
http://localhost/phpmyadmin
```

**2. Create the database**

Click **New** in the left sidebar, name it `inventory_db`, set collation to `utf8mb4_unicode_ci`, and click **Create**.

**3. Import the schema and seed data**

- Select `inventory_db` from the sidebar
- Click the **Import** tab
- Click **Choose File** and select `storage/inventory.sql`
- Click **Go**

This creates all tables and loads 8 employees, 18 devices, and sample transaction history.

**4. Verify the database config**

Open `config/database.php` and confirm the credentials match your XAMPP setup:

```php
return [
    'host'     => '127.0.0.1',
    'database' => 'inventory_db',
    'username' => 'root',
    'password' => '',        // XAMPP default is no password
];
```

---

## Project Structure

```
inventory/
├── public/                  ← Web root — only this folder is browser-accessible
│   ├── index.php            ← Single entry point for all requests
│   ├── .htaccess            ← Rewrites all routes through index.php
│   ├── css/
│   │   └── app.css
│   └── js/
│       └── app.js
│
├── app/
│   ├── Controllers/         ← Handle requests, call models, return views
│   │   ├── BaseController.php
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   ├── ScanController.php
│   │   ├── DeviceController.php
│   │   ├── EmployeeController.php
│   │   └── AuditController.php
│   │
│   ├── Models/              ← Database queries, one class per table
│   │   ├── BaseModel.php
│   │   ├── Employee.php
│   │   ├── Device.php
│   │   ├── Transaction.php
│   │   └── Reconciliation.php
│   │
│   ├── Views/               ← PHP templates, organised by page
│   │   ├── layouts/
│   │   │   ├── app.php      ← Authenticated layout (sidebar + nav)
│   │   │   └── auth.php     ← Login layout
│   │   ├── auth/
│   │   ├── dashboard/
│   │   ├── scan/
│   │   ├── devices/
│   │   ├── employees/
│   │   └── audit/
│   │
│   └── Middleware/
│       ├── AuthMiddleware.php
│       └── RoleMiddleware.php
│
├── core/                    ← Framework internals — Router, DB, Session, etc.
│   ├── Database.php
│   ├── Router.php
│   ├── Request.php
│   ├── Response.php
│   └── Session.php
│
├── config/
│   ├── app.php              ← App name, timezone, debug mode
│   └── database.php         ← DB credentials
│
└── storage/
    ├── inventory.sql        ← Schema + seed data (import once)
    └── exports/             ← Temporary CSV files before download
```

> **Important:** Only the `public/` folder is served by Apache. All other folders — `app/`, `core/`, `config/`, `storage/` — are never directly accessible from the browser.

---

## User Roles

| Role | Access |
|---|---|
| **Admin** | Full access — manages devices, employees, views all reports, exports data |
| **IT Staff** | Can facilitate borrow/return transactions, manage devices, view employees |
| **Borrower** | Can scan their own ID to borrow or return equipment independently |

---

## How It Works

### Borrowing a Device

1. Staff member scans their **employee ID card** QR code
2. Staff member scans the **device sticker** QR code
3. System confirms — device is marked as **Borrowed**

### Returning a Device

1. Scan the **employee ID** (original borrower or any staff member)
2. Scan the **device** QR code
3. System confirms return and displays the device's **designated shelf location**

If the person returning is different from the original borrower, the system records both — this is a **proxy return** and is fully logged in the audit trail.

### Manual Reconciliation

IT staff can override a device's status at any time during a physical inventory check. Every override requires a written reason and is permanently logged with the staff member's name and timestamp.

---

## QR Scanner Setup

### USB Plug-in Scanner (Recommended)

A USB QR scanner works like a keyboard — no drivers or extra code needed. Just plug it in.

1. Click into the **Employee QR** field on the Scan page
2. Scan the employee ID card — the code fills in and focus jumps automatically
3. Scan the device sticker — the form submits automatically

**Scanner settings to verify** (usually configured via the scanner's setup sheet):
- Suffix: **Enter** (auto-advances after each scan)
- Mode: **USB HID Keyboard**

### Camera-Based Scanning (Optional)

For camera-based scanning without a physical scanner, the [jsQR](https://github.com/cozmo/jsQR) library can be integrated. This reads the camera feed in real time and fills the form fields when a QR code is detected. See the code comments in `public/js/app.js` for the integration point.

---

## Dark Mode

Click the **moon icon** (🌙) in the top-right corner of the sidebar to toggle dark mode. Your preference is saved automatically and persists across all pages and sessions.

Works in Chrome, Firefox, and Edge. Firefox users on strict privacy mode are supported via a `localStorage` fallback.

---

## Demo Accounts

These accounts are created by the seed data in `inventory.sql`:

| QR Code | Name | Role |
|---|---|---|
| `EMP-001` | James Allen Josue | Admin |
| `EMP-002` | Frenz Darren Medallon | IT Staff |
| `EMP-003` | Maria Santos | Borrower |
| `EMP-004` | Carlos Reyes | Borrower |
| `EMP-005` | Ana Lim | Borrower |
| `EMP-006` | Rico Dela Cruz | Borrower |
| `EMP-007` | Patricia Gomez | Borrower |
| `EMP-008` | Ben Aquino | IT Staff |

On the login page, enter any QR code above or use the quick login buttons.

---

## Troubleshooting

**Page shows a giant purple shape, no styles**
The CSS path is not resolving. Open `app/Views/layouts/app.php` and `app/Views/layouts/auth.php` and make sure the stylesheet `href` uses `$_SERVER['HTTP_HOST']` to build the full URL dynamically.

**404 on all pages except the homepage**
mod_rewrite is not enabled. See step 2 of [Installation](#installation).

**Database connection failed**
Make sure MySQL is running in the XAMPP Control Panel and that `inventory_db` exists in phpMyAdmin. Check credentials in `config/database.php`.

**Dark mode doesn't work in Firefox**
Make sure the `localStorage` calls in `public/js/app.js` and the inline `<script>` in the layout are wrapped in `try/catch` blocks.

**`Declaration of ... must be compatible` error**
Rename the `count()` method in `app/Models/Employee.php` to `total()` and update the call in `DashboardController.php` to match.

**CSS or JS not updating after changes**
Hard refresh with **Ctrl + Shift + R** to bypass the browser cache.

---

## Authors

Prepared by **James Allen M. Josue** and **Frenz Darren J. Medallon**
IT Department · March 2026
