# Project structure

This repo is organized into **three areas**: frontend (UI and assets), backend (Laravel/Bagisto app and API), and old/unused files.

```
(repo root)
├── frontend/          ← Frontend source & entrypoints (see below)
├── backend/           ← Backend docs & entrypoints (see below)
├── old/               ← Unused / legacy files (optional to move here)
│
├── app/               [BACKEND] Laravel application
├── config/            [BACKEND] Configuration
├── database/          [BACKEND] Migrations & seeders
├── lang/              [BACKEND] Translations
├── packages/Webkul/   [BACKEND + FRONTEND] Bagisto packages (see breakdown)
├── public/            [FRONTEND output + BACKEND] Built assets, themes, images
├── resources/         [FRONTEND + BACKEND] Views, JS/CSS sources, Vite wiring
├── routes/            [BACKEND] HTTP & console routes
├── storage/           [BACKEND] Logs, cache, uploads
├── tests/             [BACKEND] PHP tests
├── bootstrap/         [BACKEND] Laravel bootstrap
├── artisan            [BACKEND] CLI
├── composer.json      [BACKEND] PHP deps
├── package.json       [FRONTEND] Node/Vite deps
├── vite.config.js     [FRONTEND] Build config
└── .env, .env.example [BACKEND] Environment
```

---

## 1. Frontend (files used for storefront UI and build)

| Path | Purpose |
|------|--------|
| **`frontend/`** | Main frontend workspace: source entrypoints for Vite |
| `frontend/src/js/app.js` | JS entrypoint |
| `frontend/src/js/bootstrap.js` | Axios / bootstrap for browser |
| `frontend/src/css/app.css` | Root stylesheet entrypoint |
| **`resources/css/`** | Additional CSS (e.g. app.css bridge) |
| **`resources/js/`** | Additional JS (e.g. app.js, bootstrap.js bridge) |
| **`public/build/`** | Built JS/CSS (Vite output) |
| **`public/css/`** | Extra styles (e.g. glassmorphism.css) |
| **`public/images/`** | Static images (e.g. collections, acm-logo) |
| **`public/themes/shop/`** | Compiled shop theme assets |
| **`packages/Webkul/Shop/`** | Shop package: Blade views, layout, components (storefront UI) |
| **`vite.config.js`** | Vite config; build output goes to `public/build` |

**Build:** From repo root run `npm run build` or `npm run dev`. Assets are emitted to `public/build` and used by Laravel.

---

## 2. Backend (Laravel / Bagisto app and API)

| Path | Purpose |
|------|--------|
| **`app/`** | Application code: Http, Providers, Listeners, Mail |
| **`config/`** | Laravel & package config |
| **`database/`** | Migrations, seeders (e.g. CollectionCategoriesSeeder, CollectionProductsSeeder) |
| **`routes/`** | Web and API routes |
| **`bootstrap/`** | Laravel bootstrap and cache |
| **`lang/`** | Translation files |
| **`storage/`** | Logs, cache, sessions, uploads |
| **`tests/`** | PHPUnit / Pest tests |
| **`packages/Webkul/Admin/`** | Admin panel (backend UI + logic) |
| **`packages/Webkul/Core/`** | Core Bagisto logic |
| **`packages/Webkul/Checkout/`** | Cart & checkout logic |
| **`packages/Webkul/Product/`** | Product catalog logic |
| **`packages/Webkul/Customer/`** | Customer & auth |
| **`packages/Webkul/Sales/`** | Orders, invoices |
| **`packages/Webkul/Category/`** | Categories |
| **`packages/Webkul/Shipping/`** | Shipping |
| **`packages/Webkul/Payment/`** | Payment |
| **Other `packages/Webkul/*`** | CartRule, CatalogRule, CMS, Attribute, Notification, etc. |
| **`backend/`** | README and docs pointing to these backend areas |

**Run:** From repo root: `php artisan serve`, `composer install`, etc.

---

## 3. Old / unused files

| Path | Notes |
|------|--------|
| **`old/`** | Folder for deprecated or unused files. Move here anything you no longer use (old seeders, unused views, legacy config). |
| **`public/themes/installer/`** | Installer theme; often unused after initial install |
| **Unused `lang/` locales** | If you only use one locale, others can be treated as optional |

Keep the app working: only move files into `old/` when you are sure they are not required by routes, config, or packages.

---

## Quick reference

- **Frontend** = `frontend/` + `resources/` (views, js, css) + `public/build`, `public/css`, `public/images`, `public/themes/shop` + **Shop** package views.
- **Backend** = `app/`, `config/`, `database/`, `routes/`, `packages/Webkul/*` (except Shop storefront views), `storage/`, `tests/`, `bootstrap/`, `artisan`, `composer.json`, `.env`.
- **Old** = `old/` and any deprecated files you move there.

See **`frontend/README.md`** and **`backend/README.md`** for more detail on each area.
