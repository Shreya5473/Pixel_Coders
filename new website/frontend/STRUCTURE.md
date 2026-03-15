# Frontend — Application Run Structure

This folder describes the **frontend** layout used to run the application on localhost. The actual frontend code lives at the **repository root**.

## Frontend Paths (at repo root)

| Path | Purpose |
|------|---------|
| `frontend/` | Vite entrypoints (`frontend/src/css/app.css`, `frontend/src/js/app.js`) |
| `resources/` | Views, JS/CSS bridges, themes |
| `public/` | Built assets (`public/build/`), images, themes |
| `package.json` | Node dependencies and scripts |
| `vite.config.js` | Vite build config |

## Build & Run (from repo root)

```bash
npm install
npm run build
# Or for dev with HMR: LARAVEL_BYPASS_ENV_CHECK=1 npm run dev
```

Assets are served from `public/build/` when using `npm run build`. The Laravel app serves the site (e.g. `php artisan serve`).
