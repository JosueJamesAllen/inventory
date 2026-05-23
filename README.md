# IT Equipment Inventory & Borrowing System

A locally hosted web application for tracking the borrowing and return of IT equipment using QR code scanning. Built with PHP (MVC), MySQL, and vanilla JavaScript — no frameworks, no cloud dependency, no monthly fees.

---

## Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Database Setup](#database-setup)
- [Upgrading an Existing Install](#upgrading-an-existing-install)
- [Known Issues & Fixes](#known-issues--fixes)
- [Project Structure](#project-structure)
- [User Roles](#user-roles)
- [How It Works](#how-it-works)
- [Maintenance](#maintenance)
- [Activity Log](#activity-log)
- [My Borrows](#my-borrows)
- [Login](#login)
- [Themes](#themes)
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
| **Location Management**        | Add or remove cabinet and shelf combinations from the Maintenance page; device form uses dropdowns |
| **Activity Log**               | Admin-only feed of every action taken in the system — logins, role changes, device edits, borrows, QR prints, CSV exports, and more |
| **My Borrows**                 | Borrower-facing page showing currently checked-out equipment and full personal borrow history |
| **Manual Reconciliation**      | IT staff can override device status during physical inventory, with reason and timestamp logged  |
| **Equipment Audit Log**        | Full history per device — who borrowed it, for what purpose, who returned it, and when          |
| **Employee Audit Log**         | Full borrowing history per employee across all devices                                           |
| **CSV Export**                 | Export both audit logs to CSV for record-keeping and management review                           |
| **QR Code Printing**           | Print a compact sticker sheet of device QR codes directly from the Devices page                 |
| **Role-Based Access**          | Separate access levels for Admin, IT Staff, and Borrower                                         |
| **QR Code Privacy**            | Employee QR codes are hidden from IT Staff view — visible to Admins only                        |
| **Dark Mode**                  | Toggle between light and dark theme; dark mode adds neon glow effects to cards, modals, and nav items |
| **Pastel Mode**                | A hidden third theme. Some things are worth discovering on your own.                             |
| **Live Device Type Stats**     | Stat cards on the Devices tab show per-type counts that update in real time as filters are applied |
| **Locally Hosted**             | Runs entirely on your office network — no internet required                                      |

---

## Requirements

- [XAMPP](https://www.apachefriends.org/) v8.2 or later (Apache + MySQL + PHP 8.0+)
- A modern browser (Chrome 90+, Firefox 88+, Edge 90+)
- Camera access (for QR scanning on the Borrow / Return and Login pages)
- A device with a webcam or rear-facing camera

---

## Installation

Follow these steps in order. Each step includes a verification check so you can catch problems early.

---

### Step 1 — Install and start XAMPP

1. Download XAMPP from [https://www.apachefriends.org/](https://www.apachefriends.org/) and run the installer with default options.
2. Open the **XAMPP Control Panel**.
3. Click **Start** next to **Apache** and **MySQL**. Both status lights should turn green.
4. Verify Apache is running by visiting `http://localhost` in your browser — you should see the XAMPP welcome page.

> If port 80 is in use (common on Windows with IIS or Skype), open `C:/xampp/apache/conf/httpd.conf`, find `Listen 80`, and change it to an unused port such as `Listen 8080`. Update all URLs in this guide accordingly (e.g. `http://localhost:8080/inventory/public`).

---

### Step 2 — Place the project files

Clone the repository or extract the ZIP into your XAMPP web root so the path is exactly:

```
C:/xampp/htdocs/inventory/
```

Verify the folder contains `public/`, `app/`, `core/`, `config/`, and `storage/` at the root level. If you see those inside an extra subfolder (e.g. `inventory/inventory/`), move them up one level.

---

### Step 3 — Enable mod_rewrite in Apache

The app uses clean URLs routed through a single entry point. Without mod_rewrite, every page except the home page returns 404.

**3a. Enable the rewrite module**

Open `C:/xampp/apache/conf/httpd.conf` in a text editor (Notepad++ recommended). Search for:

```apache
#LoadModule rewrite_module modules/mod_rewrite.so
```

Remove the leading `#` so it reads:

```apache
LoadModule rewrite_module modules/mod_rewrite.so
```

**3b. Allow .htaccess overrides**

In the same file, find the `<Directory "C:/xampp/htdocs">` block. It will look like this:

```apache
<Directory "C:/xampp/htdocs">
    Options Indexes FollowSymLinks Includes ExecCGI
    AllowOverride None
    Require all granted
</Directory>
```

Change `AllowOverride None` to `AllowOverride All`:

```apache
<Directory "C:/xampp/htdocs">
    Options Indexes FollowSymLinks Includes ExecCGI
    AllowOverride All
    Require all granted
</Directory>
```

**3c. Restart Apache**

Save the file, then click **Stop** and **Start** on Apache in the XAMPP Control Panel. Do not skip the restart — the change has no effect until Apache reloads its config.

**Verify:** Visit `http://localhost/inventory/public` — you should see the login page, not a 404.

---

### Step 4 — Set up the database

See [Database Setup](#database-setup) below.

---

### Step 5 — Verify the full installation

After completing the database setup, run through this checklist:

- [ ] `http://localhost/inventory/public` loads the login page
- [ ] Camera activates automatically on the login page (you may need to allow camera permissions in the browser)
- [ ] Scanning a valid employee QR code logs you in
- [ ] The dashboard loads with correct device and borrow counts
- [ ] The Borrow / Return page can open the camera scanner

If any step fails, see [Troubleshooting](#troubleshooting).

---

## Database Setup

### 1. Open phpMyAdmin

```
http://localhost/phpmyadmin
```

### 2. Create the database

1. Click **New** in the left sidebar
2. Enter `inventory_db` as the database name
3. Set the collation to `utf8mb4_unicode_ci`
4. Click **Create**

### 3. Import the schema and seed data

1. Select `inventory_db` from the left sidebar
2. Click the **Import** tab at the top
3. Click **Choose File** and navigate to `storage/inventory.sql` inside the project folder
4. Leave all other options at their defaults
5. Click **Go**

This creates all tables and loads the employee registry, device inventory, locations, and any initial data included in the SQL file.

**Verify:** Click on `inventory_db` in the sidebar. You should see the following tables: `activity_log`, `devices`, `employees`, `locations`, `reconciliations`, `transactions`.

### 4. Confirm the database config

Open `config/database.php` and confirm the credentials match your XAMPP setup:

```php
return [
    'host'     => '127.0.0.1',
    'database' => 'inventory_db',
    'username' => 'root',
    'password' => '',        // XAMPP default is no password
];
```

If you set a MySQL root password during XAMPP installation, enter it in the `password` field.

### 5. Clear demo data before going live (optional)

If the SQL file contains demo transaction or reconciliation records, clear them before first use. In phpMyAdmin, select `inventory_db`, click the **SQL** tab, and run:

```sql
TRUNCATE TABLE reconciliations;
TRUNCATE TABLE transactions;
```

`reconciliations` must be truncated first due to the foreign key constraint. This resets the audit log, active borrow counts, and dashboard figures to zero without affecting employee or device records.

---

## Upgrading an Existing Install

If you are updating from an earlier version of this system (one that was already running with its own database), run the relevant migrations below rather than re-importing `inventory.sql`.

Each migration is safe to run on a database that already has the feature — it uses `IF NOT EXISTS` guards and will not duplicate data.

### Add purpose and expected return date to transactions

Adds the `purpose` and `expected_return_at` columns introduced when borrow details were added to the scan flow.

**File:** `storage/migration_add_purpose_expected_return.sql`

Or run directly in phpMyAdmin → `inventory_db` → **SQL** tab:

```sql
ALTER TABLE transactions
    ADD COLUMN IF NOT EXISTS purpose             TEXT        NULL AFTER notes,
    ADD COLUMN IF NOT EXISTS expected_return_at  DATETIME    NULL AFTER purpose;
```

### Add the locations table

Required for the Maintenance / cabinet-shelf location feature.

**File:** `storage/migration_add_locations.sql`

Or run directly:

```sql
CREATE TABLE IF NOT EXISTS locations (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    cabinet    VARCHAR(80) NOT NULL,
    shelf      VARCHAR(80) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_location (cabinet, shelf)
) ENGINE=InnoDB;

INSERT IGNORE INTO locations (cabinet, shelf) VALUES
('Cabinet A', 'Shelf 1'),
('Cabinet A', 'Shelf 2'),
('Cabinet A', 'Shelf 3'),
('Cabinet B', 'Shelf 1'),
('Cabinet B', 'Shelf 2'),
('Cabinet C', 'Shelf 1'),
('Cabinet C', 'Shelf 2'),
('Cabinet C', 'Shelf 3'),
('Cabinet 1', 'CBMS 1A'),
('Cabinet 1', 'CBMS 1B'),
('Cabinet 1', 'CBMS 1C'),
('Cabinet 1', 'CBMS 1D'),
('Cabinet 2', 'CBMS 2A'),
('Cabinet 2', 'CBMS 2B');
```

### Add the activity log table

Required for the Admin-only Activity Log page.

**File:** `storage/migration_add_activity_log.sql`

Or run directly:

```sql
CREATE TABLE IF NOT EXISTS `activity_log` (
    `id`          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id`     INT UNSIGNED        NULL,
    `user_name`   VARCHAR(120)    NOT NULL DEFAULT '',
    `user_role`   VARCHAR(20)     NOT NULL DEFAULT '',
    `action`      VARCHAR(60)     NOT NULL,
    `description` TEXT            NOT NULL,
    `created_at`  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_created` (`created_at`),
    KEY `idx_user`    (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

> **Fresh installs** using `storage/inventory.sql` get all three tables automatically. These migrations are only needed for databases created before each feature was added.

---

## Known Issues & Fixes

These are bugs identified during development and resolved in the current version. If you are running an older copy, apply the relevant fix.

---

**Borrow form missing purpose and return date fields**

Transactions created before the borrow-details feature was added have `NULL` in the `purpose` and `expected_return_at` columns. The UI handles these gracefully (showing "—" and never flagging them as overdue), but the columns must exist for the feature to work at all.

*Fix:* Run `storage/migration_add_purpose_expected_return.sql` on any database created before this feature was added. See [Upgrading an Existing Install](#upgrading-an-existing-install).

---

**Device dropdowns on Add / Edit form show no cabinet or shelf options**

If the `locations` table does not exist, the cabinet and shelf dropdowns on the device form render empty, and saving a device throws a database error.

*Fix:* Run `storage/migration_add_locations.sql`. See [Upgrading an Existing Install](#upgrading-an-existing-install).

---

**Activity Log page returns a 404 or blank screen**

The `activity_log` table did not exist in the initial schema. If the table is missing, any request to the Activity Log page fails with a database error (visible when `debug` is `true` in `config/app.php`).

*Fix:* Run `storage/migration_add_activity_log.sql`. See [Upgrading an Existing Install](#upgrading-an-existing-install).

---

**Employee still shows active borrows after all devices were returned**

This occurred when a device's status was manually changed to Available (via the Edit form or phpMyAdmin) without closing the associated transaction record. The active borrow counter cross-checks the device's live status and self-corrects on the next page load, but the underlying transaction record remains open.

*Fix:* In phpMyAdmin, find the orphaned row in the `transactions` table (where `returned_at IS NULL` for a device that is currently Available) and set `returned_at` to the correct return timestamp manually.

---

**Overdue flag appearing on indefinite borrows**

In an earlier version, borrows saved without an expected return date were stored as `expected_return_at = '0000-00-00 00:00:00'` instead of `NULL`, causing the overdue check to treat them as long-past-due.

*Fix:* Already resolved in the current schema. For any legacy rows, run the following in phpMyAdmin:

```sql
UPDATE transactions
SET expected_return_at = NULL
WHERE expected_return_at = '0000-00-00 00:00:00';
```

---

**Camera blocked when accessing over local network IP**

Browsers enforce a secure-context policy: camera access is only permitted on `localhost` or an HTTPS origin. Accessing the app via a LAN IP (e.g. `http://192.168.1.x/inventory/public`) from another device will silently block the camera.

*Fix:* Either access the app from the host machine only (`localhost`), or configure a self-signed SSL certificate in XAMPP for network-wide camera access.

To set up a self-signed certificate in XAMPP:
1. Open `C:/xampp/apache/conf/extra/httpd-ssl.conf`
2. Set `ServerName` to your machine's local IP
3. Point `SSLCertificateFile` and `SSLCertificateKeyFile` to certificate files generated with OpenSSL
4. Enable the SSL module in `httpd.conf` (`LoadModule ssl_module modules/mod_ssl.so`)
5. Restart Apache and access the app via `https://192.168.x.x/inventory/public`
6. Accept the browser's self-signed certificate warning

---

**White screen or PHP error after moving the project folder**

The app derives its base URL from `config/app.php`. If the project is not at `C:/xampp/htdocs/inventory/`, all asset paths and redirects will be wrong.

*Fix:* Update `base_url` in `config/app.php` to match the actual path:

```php
'base_url' => 'http://localhost/your-folder-name/public',
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
│   │   ├── AuditController.php
│   │   ├── MaintenanceController.php
│   │   ├── ActivityLogController.php
│   │   └── MyBorrowsController.php
│   │
│   ├── Models/              ← Database queries, one class per table
│   │   ├── BaseModel.php
│   │   ├── Employee.php
│   │   ├── Device.php
│   │   ├── Transaction.php
│   │   ├── Reconciliation.php
│   │   ├── Location.php
│   │   └── ActivityLog.php
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
│   │   ├── audit/
│   │   ├── maintenance/
│   │   ├── activity_log/
│   │   └── my_borrows/
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
    ├── inventory.sql                            ← Full schema + seed data (import once for fresh installs)
    ├── migration_add_purpose_expected_return.sql ← Adds purpose and expected_return_at to transactions
    ├── migration_add_locations.sql              ← Adds the locations table
    └── migration_add_activity_log.sql           ← Adds the activity_log table
```

> **Important:** Only the `public/` folder is served by Apache. All other folders — `app/`, `core/`, `config/`, `storage/` — are never directly accessible from the browser.

---

## User Roles

| Role         | Access                                                                                        |
| ------------ | --------------------------------------------------------------------------------------------- |
| **Admin**    | Full access — manages devices and employees, views all reports, exports data, sees QR codes, views Activity Log |
| **IT Staff** | Facilitates borrow/return transactions, manages devices and locations, views employee list (QR codes hidden) |
| **Borrower** | Can scan their own ID to borrow or return equipment independently; sees personal borrow history via My Borrows |

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

## Maintenance

The **Maintenance** page (accessible to Admin and IT Staff) manages the list of valid storage locations — cabinet and shelf combinations — that appear as dropdowns when adding or editing a device.

### Adding a Location

1. Go to **Maintenance** in the sidebar
2. Click **+ Add Location**
3. Enter the cabinet name (e.g. `Cabinet A`) and shelf or section name (e.g. `Shelf 1`)
4. Click **Add Location** — it appears in the table immediately and becomes available in the device form dropdowns

### Removing a Location

1. Go to **Maintenance**
2. Find the location you want to remove — the **Devices Assigned** column shows how many devices currently use it
3. If the count is zero, click **Remove** and confirm
4. If the count is non-zero, the Remove button is disabled — reassign those devices to a different location first (via **Devices → Edit**), then return to Maintenance to remove it

> **Note:** Removing a location does not affect device records — cabinet and shelf values are stored as plain text on each device. Removing a location only prevents it from appearing in the dropdown going forward.

### Changing a Location Name

There is no rename function. To rename a location (e.g. `Cabinet A` → `Storage Room 1`):

1. Edit each device assigned to the old location and select the new one
2. Once the old location's device count reaches zero, remove it from Maintenance
3. Add the new location name if it does not already exist

---

## Activity Log

The **Activity Log** page (accessible to Admin only) provides a unified, actor-centric record of every significant action taken in the system — separate from the device and employee audit logs, which are device/borrower-centric.

### What is recorded

| Category   | Actions logged |
| ---------- | -------------- |
| **auth**     | Sign in, sign out |
| **employee** | Employee added; employee updated; role changed (old → new role shown explicitly) |
| **device**   | Device added; device updated; bulk status change; manual reconciliation |
| **location** | Location added; location removed |
| **scan**     | Device borrowed (self-serve or facilitated); device returned (direct or proxy) |
| **audit**    | Equipment or employee CSV exported |
| **device**   | QR codes printed or downloaded as PDF |

### Filtering

The page supports filtering by user, category, and date range — all client-side, no page reload.

---

## My Borrows

The **My Borrows** page is visible to Borrowers only (not shown to Admin or IT Staff, who have the full Audit Log instead). It gives borrowers a self-service view of their account without needing to ask IT staff.

- **Currently Borrowing** — active checkouts with device name, type, shelf location, borrowed date, and due date. Overdue items are highlighted in red.
- **Borrow History** — all past transactions with return dates and who returned the device.

---

## Login

Staff sign in using their **employee QR code** (e.g. `ISA1-JAMJ`). There are two ways to sign in:

- **Camera scan** — the login page opens the camera automatically; hold up your employee ID card
- **Manual entry** — click **Enter code manually** and type your QR code, then press Enter

There are no shared passwords. Each employee's QR code is their credential. Admins manage employee records (including QR codes) from the Employees tab.

---

## Themes

The theme toggle button is in the top of the sidebar.

| Theme | How to activate | Icon shown |
| ----- | --------------- | ---------- |
| **Light** | Default | 🌙 (click to go dark) |
| **Dark** | Click the toggle once | ☀️ (click to go light) |
| **Pastel** | … | ✨ (click to return to light) |

Your preference is saved automatically and persists across all pages and sessions.

### Dark Mode Neon Effects

In dark mode, all cards, modals, stat cards, and the agency header have a subtle cyan neon edge glow. Nav items in the sidebar also glow and pulse on hover. These effects are dark mode only and do not appear in light or pastel mode.

---

## Troubleshooting

**Page shows no styles**
The CSS path is not resolving. Confirm the `inventory` folder is placed directly inside `C:/xampp/htdocs/` and that XAMPP is running. Also verify `base_url` in `config/app.php` matches the actual URL you are using.

**404 on all pages except the homepage**
mod_rewrite is not enabled or `.htaccess` overrides are blocked. Follow Step 3 of [Installation](#installation) carefully and restart Apache after saving changes.

**Database connection failed**
Make sure MySQL is running in the XAMPP Control Panel and that `inventory_db` exists in phpMyAdmin. Check credentials in `config/database.php`. If you never set a MySQL password, the `password` field should be an empty string.

**Missing tables (activity_log, locations, etc.)**
You may be running a database created before those features were added. See [Upgrading an Existing Install](#upgrading-an-existing-install) and run the relevant migration files.

**Camera does not open on the login or scan page**
The browser requires HTTPS or `localhost` to allow camera access. Accessing the app via an IP address (e.g. `192.168.x.x`) on another device will block the camera. See the "Camera blocked when accessing over local network IP" entry in [Known Issues & Fixes](#known-issues--fixes).

**White screen after submitting a form**
Enable debug mode temporarily: open `config/app.php` and set `'debug' => true`. Reload the page to see the full error. Remember to set it back to `false` in production.

**OPcache serving stale pages after code changes**
Restart Apache from the XAMPP Control Panel to flush the bytecode cache. A hard browser refresh (Ctrl + Shift + R) alone is not enough when PHP files have changed.

**Theme preference doesn't persist (dark mode, pastel mode)**
Make sure the browser allows `localStorage`. Incognito / private mode may block it depending on browser settings.

**CSS or JS not updating after changes**
Hard refresh with **Ctrl + Shift + R** to bypass the browser cache.

**Employee still shows active borrows after all devices returned**
See "Employee still shows active borrows after all devices returned" in [Known Issues & Fixes](#known-issues--fixes).

**Overdue items appearing for indefinite borrows**
See "Overdue flag appearing on indefinite borrows" in [Known Issues & Fixes](#known-issues--fixes).

**Borrow / Return form submits but no transaction is recorded**
The CSRF token may have expired (session timeout). Refresh the page to get a fresh token and try again.

---

## Authors

Prepared by **James Allen M. Josue** and **Frenz Darren J. Medallon**
Statistical Unit · May 2026

<!-- ↑↑↓↓←→←→ -->
