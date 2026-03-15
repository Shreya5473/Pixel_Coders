# Project Structure

## High-Level Layout

- **`new website/`** — Full application (backend + frontend). Run from here: `cd "new website"` then `composer install`, `npm install`, `php artisan serve`.
  - Backend: `app/`, `config/`, `database/`, `routes/`, `packages/`, `bootstrap/`, `artisan`, `composer.json`, Stripe in `config/`, etc.
  - Frontend: `frontend/`, `resources/`, `public/`, `package.json`, `vite.config.js`.
- **`old unused files/`** — Unused items: `dist/`, `scripts/`, `old/`, `docker-compose.yml`.
- **Root** — README, LICENSE, docs (CHANGELOG, CONTRIBUTING, etc.), `.github`, `.gitignore`, `.editorconfig`, `.gitattributes`.

## Run the Application

```bash
cd "new website"
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
npm install && npm run build
php artisan serve
```

## Verification Commands (from `new website/`)

- `php artisan about`
- `php artisan test --stop-on-failure`
- `npm run build`
