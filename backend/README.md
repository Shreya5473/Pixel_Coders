# Backend

This folder is the **backend** entrypoint. The Laravel/Bagisto app lives at **repo root** (paths below are from root).

## Core backend paths

| Path | Purpose |
|------|--------|
| `app/` | Laravel application (Http, Providers, Listeners, Mail) |
| `config/` | Configuration |
| `database/` | Migrations, seeders (e.g. CollectionCategoriesSeeder, CollectionProductsSeeder) |
| `routes/` | Web and API routes |
| `packages/Webkul/Admin/` | Admin panel |
| `packages/Webkul/Core/` | Core Bagisto logic |
| `packages/Webkul/Checkout/` | Cart & checkout |
| `packages/Webkul/Product/` | Catalog |
| `packages/Webkul/Sales/` | Orders, invoices |
| Other `packages/Webkul/*` | Category, Shipping, Payment, CMS, etc. |
| `storage/` | Logs, cache, uploads |
| `tests/` | PHP tests |
| `bootstrap/` | Laravel bootstrap |

## Run

From repo root:

- `composer install`
- `php artisan serve`
- `php artisan migrate`

See **[PROJECT_STRUCTURE.md](../PROJECT_STRUCTURE.md)** for the full Frontend / Backend / Old layout.
