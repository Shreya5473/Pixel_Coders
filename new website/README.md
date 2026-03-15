# Application (run from this folder)

This folder contains the full application: backend (Laravel/Bagisto, Stripe, Meilisearch) and frontend (Vite, assets).

## Structure

| Folder / file   | Purpose |
|-----------------|--------|
| `app/`          | Laravel application layer |
| `config/`       | Configuration (Stripe in `config/paymentmethods.php`) |
| `database/`     | Migrations and seeders |
| `routes/`       | HTTP and console routes |
| `packages/`     | Bagisto modules (Shop, Admin, Checkout, etc.) |
| `frontend/`     | Frontend source (Vite entrypoints) |
| `resources/`    | Views, JS/CSS bridges |
| `public/`       | Web root, built assets |
| `bootstrap/`    | Laravel bootstrap |
| `composer.json` | PHP dependencies |
| `package.json`  | Node dependencies |
| `artisan`       | CLI entrypoint |

## Run locally

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

Open http://127.0.0.1:8000
