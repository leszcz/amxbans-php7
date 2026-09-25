// Copies the Alpine.js builds used by the templates into assets/dist. Run: npm run build:js
import fs from 'node:fs';
const files = {
  'node_modules/@alpinejs/csp/dist/cdn.min.js': 'assets/dist/alpine-csp.min.js',
  'node_modules/@alpinejs/focus/dist/cdn.min.js': 'assets/dist/alpine-focus.min.js',
};
for (const [from, to] of Object.entries(files)) {
  fs.copyFileSync(from, to);
  console.log(`${from} -> ${to}`);
}
