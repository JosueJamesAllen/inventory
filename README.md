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
- [Login](#login)
- [Dark Mode](#dark-mode)
- [Troubleshooting](#troubleshooting)
- [Authors](#authors)

---

## Overview

This system replaces manual logging with a fast, accurate QR-based process. Any staff member who needs to borrow a laptop, tablet, or other IT device can do so in seconds by scanning two QR codes — their employee ID and the device sticker. IT staff always have a real-time view of every device's status and location.

---

## Features

| Feature                        | Description                                                                                      |
| ------------------------------ | ------------------------------------------------------------------------------------------------ |
| **QR Code Login**              | Sign in by scanning your employee ID card or typing your QR code manually                       |
| **Camera-Based QR Scanning**   | Built-in camera scanner on the Borrow / Return page — no USB scanner required                   |
| **Real-Time Dashboard**        | Live view of all device statuses, active borrows, overdue items, and today's transaction count   |
| **Dashboard Charts**           | Device status donut chart and 7-day transaction activity bar chart                               |
| **Borrow & Return**            | Three-step process — scan employee ID, scan device, then fill in borrow details                  |
| **Borrow Details**             | Purpose of borrowing and expected return date captured per transaction; indefinite borrows supported |
| **Overdue Flagging**           | Devices past their expected return date are highlighted in the dashboard and active borrows table |
| **Proxy Return**               | Any staff member can return equipment on behalf of the original borrower                         |
| **Designated Shelf Location**  | Every device has a permanent cabinet and shelf — displayed automatically on return               |
| **Manual Reconciliation**      | IT staff can override device status during physical inventory, with reason and timestamp logged  |
| **Equipment Audit Log**        | Full history per device — who borrowed it, for what purpose, who returned it, and when          |
| **Employee Audit Log**         | Full borrowing history per employee across all devices                                           |
| **CSV Export**                 | Export both audit logs to CSV for record-keeping and management review                           |
| **QR Code Printing**           | Print a compact sticker sheet of device QR codes directly from the Devices page                 |
| **Role-Based Access**          | Separate access levels for Admin, IT Staff, and Borrower                                         |
| **QR Code Privacy**            | Employee QR codes are hidden from IT Staff view — visible to Admins only                        |
| **Dark Mode**                  | Toggle between light and dark theme, preference saved across sessions                            |
| **Locally Hosted**             | Runs entirely on your office network — no internet required                                      |

---

## Requirements

- [XAMPP](https://www.apachefriends.org/) (Apache + MySQL + PHP 8.0+)
- A modern browser (Chrome, Firefox, Edge)
- Camera access (for QR scanning on the Borrow / Return and Login pages)

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

This creates all tables and loads the employee registry, device inventory, and any initial data included in the SQL file.

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

**5. Clear audit data before going live (optional)**

If the SQL file contains demo transaction or reconciliation records, clear them in phpMyAdmin before first use:

```sql
TRUNCATE TABLE reconciliations;
TRUNCATE TABLE transactions;
```

`reconciliations` must go first. This resets the audit log, active borrow counts, and dashboard figures to zero without touching employee or device records.

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
│   ├── app.php              ← App name, timezone, env, debug mode
│   └── database.php         ← DB credentials
│
└── storage/
    └── inventory.sql        ← Schema + seed data (import once)
```

> **Important:** Only the `public/` folder is served by Apache. All other folders — `app/`, `core/`, `config/`, `storage/` — are never directly accessible from the browser.

---

## User Roles

| Role         | Access                                                                                        |
| ------------ | --------------------------------------------------------------------------------------------- |
| **Admin**    | Full access — manages devices and employees, views all reports, exports data, sees QR codes   |
| **IT Staff** | Facilitates borrow/return transactions, manages devices, views employee list (QR codes hidden) |
| **Borrower** | Can scan their own ID to borrow or return equipment independently                             |

---

## How It Works

### Borrowing a Device

1. Go to **Borrow / Return** and select **Borrow**
2. The camera opens — hold up the **employee ID card** QR code
3. Once detected, hold up the **device sticker** QR code
4. Fill in the **purpose of borrowing** and **expected return date** (or mark as indefinite)
5. Press **Confirm Borrow** — device is marked as **Borrowed**

### Returning a Device

1. Go to **Borrow / Return** and select **Return**
2. Scan the **employee ID** (original borrower or any staff member)
3. Scan the **device** QR code
4. System confirms return and displays the device's **designated shelf location**

If the person returning is different from the original borrower, the system records both — this is a **proxy return** and is fully logged in the audit trail.

### Overdue Devices

Any device with an expected return date that has passed is automatically flagged. The dashboard shows an **Overdue** count, and affected rows are highlighted in the active borrows table. Devices marked as indefinite are never flagged.

### Manual Reconciliation

IT staff can override a device's status at any time during a physical inventory check. Every override requires a written reason and is permanently logged with the staff member's name and timestamp. Use the **Reconcile** button on the Devices page.

> **Note:** To mark a device as Borrowed, always use the Borrow / Return scan page. The Edit device form only allows switching between **Available** and **Out of Service** — this keeps transaction records accurate.

---

## Login

Staff sign in using their **employee QR code** (e.g. `ISA1-JAMJ`). There are two ways to sign in:

- **Camera scan** — the login page opens the camera automatically; hold up your employee ID card
- **Manual entry** — click **Enter code manually** and type your QR code, then press Enter

There are no shared passwords. Each employee's QR code is their credential. Admins manage employee records (including QR codes) from the Employees tab.

---

## Dark Mode

Click the **moon icon** (🌙) in the sidebar to toggle dark mode. Your preference is saved automatically and persists across all pages and sessions.

---

## Troubleshooting

**Page shows no styles**
The CSS path is not resolving. Confirm the `inventory` folder is placed directly inside `C:/xampp/htdocs/` and that XAMPP is running.

**404 on all pages except the homepage**
mod_rewrite is not enabled. See step 2 of [Installation](#installation).

**Database connection failed**
Make sure MySQL is running in the XAMPP Control Panel and that `inventory_db` exists in phpMyAdmin. Check credentials in `config/database.php`.

**Camera does not open on the login or scan page**
The browser requires HTTPS or `localhost` to allow camera access. Accessing the app via an IP address (e.g. `192.168.x.x`) on another device will block the camera — see the note below.

**Accessing from other devices on the network**
Camera access over a local IP requires HTTPS. The simplest option is to access the app only from the host machine (`localhost`), or set up a self-signed SSL certificate in XAMPP if network-wide access is needed.

**White screen after submitting a form**
Enable debug mode temporarily: open `config/app.php` and set `'debug' => true`. Reload the page to see the full error. Remember to set it back to `false` when done.

**OPcache serving stale pages after code changes**
Restart Apache from the XAMPP Control Panel to flush the bytecode cache. A hard browser refresh (Ctrl + Shift + R) alone is not enough when PHP files have changed.

**Dark mode doesn't persist**
Make sure the browser allows `localStorage`. Incognito/private mode may block it.

**CSS or JS not updating after changes**
Hard refresh with **Ctrl + Shift + R** to bypass the browser cache.

**Employee still shows active borrows after all devices returned**
This can happen if a device was manually set to Available before its transaction was closed. The active borrow count cross-checks against the device's actual status and corrects itself automatically on next page load.

---

## Authors

Prepared by **James Allen M. Josue** and **Frenz Darren J. Medallon**
Statistical Unit · May 2026

<!-- ↑↑↓↓←→←→ -->
