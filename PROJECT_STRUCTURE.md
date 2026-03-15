# Project Structure

## High-Level Layout

- `frontend/` - root-level frontend source and build entrypoints.
- `backend/` - backend ownership map and navigation guide.
- `app/`, `config/`, `database/`, `routes/`, `packages/`, `tests/` - Laravel/Bagisto backend runtime code.
- `public/` - built assets and public files.

## Why Not Move All Backend Directories Under `backend/`

Bagisto relies on Laravel framework conventions and package paths (autoload, provider discovery, views/themes, assets). Moving those runtime directories would require broad path rewrites and can break package behavior.

## Frontend Organization Changes Applied

- Vite root app input moved from `resources/*` to `frontend/src/*`.
- Legacy `resources/css/app.css` and `resources/js/*.js` files now act as compatibility bridges.

## Verification Commands

- `php artisan about`
- `php artisan test --stop-on-failure`
- `npm run build`
