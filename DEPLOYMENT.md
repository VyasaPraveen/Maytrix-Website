# Deploying Maytrix Education to Hostinger

This guide targets **Hostinger shared hosting** (the "1 Year Hosting included" plan in the MOU) with **PHP 8.1+** and **MySQL**. No SSH is required — everything can be done from **hPanel** (File Manager + phpMyAdmin).

---

## Overview

You will:
1. Create **two MySQL databases** (content + admin).
2. Upload the project so that `public_html/` is the site root and the rest sits one level above it.
3. Configure `.env`.
4. Create the tables + seed data (web installer **or** phpMyAdmin import).
5. Point the domain, enable SSL, and secure the install.

---

## 1. Create the two databases

In **hPanel → Databases → MySQL Databases**, create **two** databases and a user for each (Hostinger usually prefixes names, e.g. `u123456789_`):

| Purpose | Example name | Example user |
|---|---|---|
| Website content | `u123456789_maytrix_web` | `u123456789_web` |
| Admin / auth | `u123456789_maytrix_admin` | `u123456789_admin` |

Note each database name, username and password — you'll need them for `.env`.

> The two-database split is a project requirement: authentication is isolated from public content.

---

## 2. Upload the files

Recommended layout (application code kept **out** of the web root):

```
/home/uXXXX/domains/yourdomain.com/
├── app/          ← upload
├── config/       ← upload
├── database/     ← upload
├── storage/      ← upload (must be writable)
└── public_html/  ← upload the CONTENTS of the repo's public_html here
```

**Steps:**
1. Zip the whole project locally.
2. hPanel → **File Manager** → open your domain folder.
3. Upload the zip and **Extract**.
4. Move `app/`, `config/`, `database/`, `storage/` so they are **siblings of** `public_html/` (one level above the web root), and put everything that was inside the repo's `public_html/` **into** the live `public_html/`.

> If your plan does not allow files above `public_html`, you may place `app/`, `config/`, `database/`, `storage/` inside `public_html/` instead — the included `.htaccess` blocks direct access to `.env` and code. Keeping them above the web root is preferred.

5. Ensure **`storage/` and its subfolders are writable** (File Manager → Permissions → 755/775). Logs and cache are written there.

---

## 3. Configure `.env`

Copy `.env.example` to `.env` (in the project root, next to `app/`) and fill in:

```ini
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
APP_KEY=<paste 40+ random characters>

DB_DRIVER=mysql

# Website content DB
DB_WEB_HOST=localhost
DB_WEB_NAME=u123456789_maytrix_web
DB_WEB_USER=u123456789_web
DB_WEB_PASS=********

# Admin / auth DB
DB_ADMIN_HOST=localhost
DB_ADMIN_NAME=u123456789_maytrix_admin
DB_ADMIN_USER=u123456789_admin
DB_ADMIN_PASS=********

# Email (Hostinger SMTP or a mailbox you create)
MAIL_ENABLED=true
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=465
MAIL_ENCRYPTION=ssl
MAIL_USERNAME=no-reply@yourdomain.com
MAIL_PASSWORD=********
MAIL_FROM_ADDRESS=no-reply@yourdomain.com
MAIL_ADMIN_NOTIFY=you@yourdomain.com

# Payments (secret keys here; public keys can also be set in the dashboard)
RAZORPAY_KEY_ID=
RAZORPAY_KEY_SECRET=
STRIPE_PUBLIC_KEY=
STRIPE_SECRET_KEY=
PAYPAL_CLIENT_ID=
PAYPAL_SECRET=
PAYMENT_CURRENCY=INR

GA_MEASUREMENT_ID=G-XXXXXXXXXX
```

> `DB_WEB_HOST`/`DB_ADMIN_HOST` is usually `localhost` on Hostinger. If they give you a specific host, use that.

---

## 4. Create tables + seed data

**Option A — Web installer (easiest):**
1. Browse to `https://yourdomain.com/install.php`.
2. It creates every table in both databases and seeds the content.
3. When it says *Installation complete*, **DELETE `public_html/install.php`** (File Manager → delete).

**Option B — phpMyAdmin import:**
1. hPanel → **phpMyAdmin** → select `..._maytrix_web` → **Import** → upload `database/schema_web.sql` → Go.
2. Select `..._maytrix_admin` → **Import** → upload `database/schema_admin.sql` → Go.
3. This creates the tables. To load starter content + the admin user, use the web installer once (it skips tables that already have rows), or insert an admin row manually (password must be a PHP `password_hash`).

**Default seeded login:** `admin@maytrixeducation.com` / `Maytrix@2026` — **change it immediately** after first sign-in (Dashboard → Admin Users).

---

## 5. Domain, SSL & security

1. **Domain** — point the domain to Hostinger (the client owns the domain/registrar per the MOU). In hPanel, attach the domain so its document root is this `public_html/`.
2. **SSL** — hPanel → **SSL** → install the free certificate. Once active, in `public_html/.htaccess` **uncomment** (a) the *Force HTTPS* `RewriteCond`/`RewriteRule` and (b) the `Strict-Transport-Security` (HSTS) header line. (The app also sends HSTS automatically once it detects HTTPS.)
3. **Delete `install.php`** if you haven't already.
4. **Verify** `.env` is not publicly reachable: `https://yourdomain.com/.env` should be **403/404** (the `.htaccess` blocks it; keeping `.env` above the web root also prevents access).
5. **Analytics / Search Console** — add your GA4 ID in Dashboard → Settings (or `.env`), then submit `https://yourdomain.com/sitemap.xml` in Google Search Console.

---

## 6. Post-launch checklist

- [ ] Signed in and **changed the admin password**
- [ ] Updated **Settings** (brand, contact email/WhatsApp, hero, stats)
- [ ] Reviewed the **8 curriculum pages** content + SEO fields
- [ ] Created real **Courses** and **Batches** (set mode + Zoom link)
- [ ] Sent a **test booking** and **test enrolment** from the public site and confirmed they appear in the dashboard + email arrives
- [ ] Added the live **payment gateway keys**
- [ ] `install.php` deleted, HTTPS forced, sitemap submitted

---

## Backups

- **From the dashboard:** *Settings → Backups* generates an on-demand SQL dump (structure + data) of either database — `maytrix_web` (content) or `maytrix_admin` (accounts/audit). Download and store these off-site; the admin dump contains password hashes, so keep it private. Restore by importing the `.sql` in **phpMyAdmin**.
- **From Hostinger:** also enable **hPanel → Files → Backups** for scheduled, automatic snapshots.

## Updating the site later

- **Content** (pages, batches, posts, settings): all in the dashboard, no code needed.
- **Code changes**: edit files locally, test with `php -S`, then re-upload the changed files via File Manager. Schema changes: add columns to `database/schema.php`, run `php database/migrate.php --sql`, and apply the new SQL in phpMyAdmin (the migrator only *creates* missing tables; column changes on existing tables should be applied manually to avoid data loss).

## Troubleshooting

| Symptom | Fix |
|---|---|
| 500 error, blank page | Set `APP_DEBUG=true` temporarily; check `storage/logs/php-error.log`. Usually a DB credential or a non-writable `storage/`. |
| "Database connection failed" | Recheck `.env` DB name/user/pass; confirm both databases exist and the user is assigned to them. |
| Pretty URLs 404 | Ensure `mod_rewrite` is on (default on Hostinger) and both `.htaccess` files uploaded. |
| Emails not sending | Confirm `MAIL_ENABLED=true` and SMTP creds; check `storage/logs/mail.log`. |
| Login always fails | Confirm the admin DB imported and has a row in `admins`; re-run the web installer. |
