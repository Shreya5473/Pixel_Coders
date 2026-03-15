#!/usr/bin/env node
/**
 * After Vite build, creates dist/ with index.html + build assets for Vercel static deploy.
 * Run: node scripts/vercel-static-build.mjs
 * Then set Vercel Output Directory = dist
 */
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const root = path.join(__dirname, '..');
const buildDir = path.join(root, 'public', 'build');
const manifestPath = path.join(buildDir, 'manifest.json');
const distDir = path.join(root, 'dist');

if (!fs.existsSync(manifestPath)) {
  console.error('Run npm run build first. public/build/manifest.json not found.');
  process.exit(1);
}

const manifest = JSON.parse(fs.readFileSync(manifestPath, 'utf8'));
const entries = Object.values(manifest);
const jsFiles = entries.filter(e => e.file && e.file.endsWith('.js')).map(e => e.file);
const cssFiles = entries.filter(e => e.file && e.file.endsWith('.css')).map(e => e.file);

if (!fs.existsSync(distDir)) fs.mkdirSync(distDir, { recursive: true });

const buildDest = path.join(distDir, 'build');
if (fs.existsSync(buildDest)) fs.rmSync(buildDest, { recursive: true });
fs.cpSync(buildDir, buildDest, { recursive: true });

const scriptTags = jsFiles.map(f => `<script type="module" crossorigin src="/build/${f}"></script>`).join('\n    ');
const linkTags = cssFiles.map(f => `<link rel="stylesheet" crossorigin href="/build/${f}">`).join('\n    ');

const html = `<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Bagisto</title>
  ${linkTags}
</head>
<body>
  <div id="app"></div>
  ${scriptTags}
</body>
</html>
`;

fs.writeFileSync(path.join(distDir, 'index.html'), html, 'utf8');
console.log('Vercel static export ready in dist/');
console.log('Set Vercel Output Directory to: dist');
