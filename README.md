<h1 align="center">Maytrix Education — Website + Admin Dashboard</h1>

<p align="center">
  <em>International online tutoring platform for</em><br>
  <strong>IB · IBMYP · Cambridge IGCSE · Cambridge AS &amp; A Level — Mathematics &amp; Physics</strong>
</p>

<p align="center">
  <img alt="PHP" src="https://img.shields.io/badge/PHP-8.1%2B-777BB4?logo=php&logoColor=white">
  <img alt="MySQL" src="https://img.shields.io/badge/MySQL-8.0-4479A1?logo=mysql&logoColor=white">
  <img alt="SQLite" src="https://img.shields.io/badge/SQLite-dev-003B57?logo=sqlite&logoColor=white">
  <img alt="No framework" src="https://img.shields.io/badge/framework-none%20(vanilla)-1E6F6B">
  <img alt="Hosting" src="https://img.shields.io/badge/deploy-Hostinger-673DE6">
</p>

---

A fully **dynamic, CMS-driven public website** and a **separate admin dashboard**, built for Maytrix Education. The public site is 100% editable from the dashboard — content, curricula, courses, batches, Zoom links, blog, pages and settings — with live seat counts, a 1-to-1 booking flow, business reports and one-click database backups.

Built with **vanilla PHP 8 + MySQL** (no framework, no build step) so it drops straight onto **Hostinger shared hosting**.

## ✨ Highlights

- 🏛️ **Two separate apps, two separate databases** — authentication is fully isolated from public content.
- 🧩 **Complete CMS** — every entity (curricula, subjects, courses, batches, students, enrolments, bookings, blog, pages, settings) is created/edited/deleted from the dashboard.
- 🪑 **Live seat counts** — `available = max_seats − confirmed enrolments`, computed in SQL.
- 💻 **Online & offline modes** — per-batch; the admin pastes a Zoom link that's auto-shared with confirmed students.
- 💳 **Payment-gateway hooks** — Razorpay + Stripe/PayPal key management in place.
- 📄 **Pagination on every admin list**, filter-aware (20/page).
- 📊 **Business Reports** — enrolment & booking pipelines, revenue by currency, demand by curriculum/country, batch fill rates.
- 💾 **One-click Backups** — download a full SQL dump (structure + data) of either database.
- 🔐 **Hardened security** — CSRF, brute-force lockout, session timeouts, strict CSP, RCE-proof uploads (see [Security](#-security)).
- 📱 **Responsive & SSL-ready**, SEO foundation (sitemap, robots, per-page meta), Analytics-ready.

## 🧱 Tech stack

| Layer | Choice |
|---|---|
| Language | PHP 8.1+ (no framework) |
| Database | MySQL 8 (production) · SQLite (local dev) — one schema, driver-aware |
| Front-end | Server-rendered PHP templates, hand-written CSS (no build step) |
| Hosting | Hostinger shared hosting (Apache + `.htaccess`) |
| Mail | SMTP (Hostinger mailbox) |

## 📂 Project structure

```text
Maytrix Education System Website/
├── app/
│   ├── bootstrap.php          # app boot (config, autoload, errors, DB)
│   ├── Core/                  # framework: Router, Model, View, Auth, Csrf, Paginator, Security…
│   ├── Models/                # one class per table
│   ├── Controllers/
│   │   ├── Site/              # public website controllers
│   │   └── Admin/             # dashboard controllers
│   ├── Support/               # helpers (countries, Backup)
│   └── Views/
│       ├── site/              # public templates
│       └── admin/             # dashboard templates
├── config/config.php          # reads .env → typed config
├── database/
│   ├── schema.php             # single source of truth for BOTH databases
│   ├── SchemaCompiler.php     # DSL → MySQL/SQLite DDL
│   ├── migrate.php            # CLI migrator (--fresh --seed --sql)
│   └── seed.php               # initial content + demo data
├── storage/                   # logs, cache, sqlite (kept out of the web root)
├── public_html/               # ← Apache document root (the ONLY served folder)
│   ├── index.php              # public website front controller
│   ├── admin/index.php        # admin dashboard front controller
│   ├── install.php            # one-time web installer (delete after use)
│   ├── .htaccess              # pretty URLs + security
│   └── assets/                # css / js / uploads
├── .env.example               # copy to .env and fill in
├── README.md
└── DEPLOYMENT.md              # Hostinger step-by-step
```

> Application code (`app/`, `config/`, `database/`, `storage/`) lives **outside** the web root. Only `public_html/` is served.

## 🚀 Local development

**Requirements:** PHP 8.1+ with `pdo_sqlite`, `pdo_mysql`, `mbstring`, `openssl`.

```bash
# 1. Configure — for local dev use SQLite
cp .env.example .env
#    set:  DB_DRIVER=sqlite   APP_ENV=local   APP_DEBUG=true

# 2. Create + seed the databases (SQLite files under storage/sqlite/)
php database/migrate.php --fresh --seed

# 3. Serve
php -S localhost:8000 -t public_html
```

- **Website:**  http://localhost:8000
- **Dashboard:** http://localhost:8000/admin
- **Seeded admin login:** `admin@maytrixeducation.com` / `Maytrix@2026` — **change immediately.**

> On some Windows PHP builds you must enable extensions and a session path via a custom `php.ini` (`php -c your.ini -S ...`). See notes in **DEPLOYMENT.md**.

### Useful commands

```bash
php database/migrate.php            # create tables (no drop)
php database/migrate.php --fresh    # drop & recreate all tables
php database/migrate.php --seed     # migrate + seed
php database/migrate.php --sql      # regenerate schema_web.sql / schema_admin.sql
```

## 🔁 How the core flows work

- **Seat counts** — a public enrolment creates a **pending** record (no seat consumed); the admin **confirms** it, which consumes a seat (atomic, race-safe) and emails the student the Zoom link (online) or venue (offline).
- **1-to-1 bookings** — the booking wizard creates a request; the admin opens it, sets a schedule + Zoom link, and emails the confirmation.
- **Content** — the 8 curriculum pages, blog posts, About page, homepage hero/stats and contact details are all edited in the dashboard.
- **Reports & Backups** — *Overview → Reports* for analytics; *Settings → Backups* to download a SQL dump of either database.

## 🔐 Security

Defence-in-depth, hardened against common web/malware vectors:

- **Injection** — prepared statements everywhere; output escaped via `e()`; mass-assignment limited by per-model `fillable`.
- **CSRF** — token verified on every POST.
- **Auth** — bcrypt hashing, session-fixation defence, **brute-force lockout** (5 fails → 15-min lock), **idle (30 min) + absolute (8 h) session timeouts**, generic login errors.
- **HTTP headers** — strict **CSP** (nonce'd inline analytics; admin CSP forbids external scripts), `frame-ancestors 'none'`, HSTS (on HTTPS), `X-Content-Type-Options`, `Referrer-Policy`, `Permissions-Policy`, `X-Powered-By` removed.
- **Malware / RCE** — the uploads folder disables script engines; `app/ config/ database/ storage/` carry deny-all guards; `.env`/`.sql`/`.log`/dotfiles blocked; directory listing off.
- **Isolation** — `.env` and app code sit outside the web root; the admin area is `noindex` and authenticates against a **separate database**.

## 🗄️ Database

- **Indexed** foreign-key and lookup columns for fast queries at scale — see `database/schema.php`.
- **Referential integrity** — unique constraints (slugs, codes, emails) + app-level "restrict on delete" guards that refuse to orphan pages/courses/batches/enrolments.
- `utf8mb4` throughout; money as `DECIMAL`; live seat counts computed in SQL.

## 📦 Deployment

See **[DEPLOYMENT.md](DEPLOYMENT.md)** for the full Hostinger walkthrough (hPanel, two databases, `.env`, the web installer, SSL/HSTS, and the post-launch checklist).

## ⚠️ Notes

- Never commit `.env`, the SQLite files, logs or uploads — they're excluded in `.gitignore`.
- `.env.example` documents every required setting; copy it to `.env` and fill in real values on the server.

---

<p align="center"><sub>Built to the EPIXS Media × Maytrix Education MOU · Phase 1.</sub></p>
