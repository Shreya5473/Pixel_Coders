# Frontend

This folder is the **frontend** area of the project. It holds the main Vite entrypoints; built assets are served from `public/`.

## Structure

- **`src/js/app.js`** – Vite JS entrypoint
- **`src/js/bootstrap.js`** – Axios / bootstrap for browser
- **`src/css/app.css`** – Root stylesheet entrypoint

## Build

From repo root:

- `npm run build` – production build → `public/build`
- `npm run dev` – dev server with HMR

Root `vite.config.js` uses this folder as the source; Laravel Vite integration serves from `public/build`.

## Related paths (frontend)

- `resources/css/`, `resources/js/` – bridge / extra sources
- `public/build/` – built JS/CSS
- `public/css/` – extra styles (e.g. glassmorphism)
- `public/images/` – static images
- `public/themes/shop/` – shop theme assets
- `packages/Webkul/Shop/` – storefront Blade views and components

See **[PROJECT_STRUCTURE.md](../PROJECT_STRUCTURE.md)** for the full Frontend / Backend / Old layout.
