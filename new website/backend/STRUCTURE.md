# Backend — Application Run Structure

This folder describes the **backend** layout used to run the application on localhost. The actual backend code lives at the **repository root** (Laravel/Bagisto conventions).

## Backend Paths (at repo root)

| Path | Purpose |
|------|---------|
| `app/` | Laravel application (controllers, middleware, providers, mail) |
| `config/` | Runtime configuration (includes **Stripe** in `config/paymentmethods.php`) |
| `database/` | Migrations and seeders |
| `routes/` | HTTP and console routes |
| `packages/Webkul/` | Bagisto modules (Admin, Shop, Checkout, Catalog, etc.) |
| `bootstrap/` | Laravel bootstrap |
| `composer.json` | PHP dependencies |
| `artisan` | CLI entrypoint |
| `.env.example` | Environment template (set `STRIPE*`, `MEILISEARCH*`, etc.) |

## Stripe & Payments

- Stripe configuration: `config/paymentmethods.php`
- Payment-related code: `packages/Webkul/` (Checkout, Sales)

## Run (from repo root)

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan serve
```
