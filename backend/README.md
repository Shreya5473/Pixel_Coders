# Backend Workspace

This folder documents backend ownership zones for easier navigation in this monorepo-style layout.

## Core Backend Areas

- `app/` - Laravel application layer (controllers, listeners, providers, mail).
- `config/` - runtime configuration.
- `database/` - migrations/seeders.
- `routes/` - HTTP/console routes.
- `packages/Webkul/*` - Bagisto domain modules (Admin, Shop, Catalog, Checkout, etc).
- `tests/` - backend test suites.

## Notes

- These backend directories stay at root because Laravel and Bagisto autoloading/config conventions depend on them.
- Frontend entrypoints are now organized in `frontend/src/*` and wired through root `vite.config.js`.
