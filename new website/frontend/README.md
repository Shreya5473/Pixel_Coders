# Frontend Workspace

This folder contains root-level frontend entrypoints and build configuration inputs.

## Source

- `src/js/app.js` - Vite JS entrypoint.
- `src/js/bootstrap.js` - Axios/bootstrap wiring for browser requests.
- `src/css/app.css` - Root stylesheet entrypoint.

## Build

- Root Vite config points to this folder as the source for default app assets.
- Build output is emitted to `public/build` through Laravel Vite integration.

## Related Runtime Paths

- Package/theme frontend assets remain under `packages/Webkul/*/src/Resources/assets`.
- Shop/admin theme builds remain under `public/themes/*`.
