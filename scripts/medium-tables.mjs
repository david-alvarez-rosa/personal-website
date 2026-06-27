import { chromium } from '@playwright/test';
import { spawn } from 'node:child_process';
import { mkdirSync } from 'node:fs';
import { setTimeout as sleep } from 'node:timers/promises';

const slug = process.argv[2];
const outDir = `${process.env.HOME}/tmp`;
mkdirSync(outDir, { recursive: true });

const server = spawn('hugo', ['server', '--buildDrafts', '-p', '1313', '--disableFastRender'], { stdio: 'ignore' });
try {
  const browser = await chromium.launch();
  const page = await browser.newPage({ deviceScaleFactor: 2 });
  for (let i = 0; i < 50; i++) {
    try { await page.goto(`http://localhost:1313/posts/${slug}/`, { timeout: 1000 }); break; }
    catch { await sleep(300); }
  }
  const tables = await page.locator('table').all();
  let n = 0;
  for (const t of tables) {
    n++;
    await t.screenshot({ path: `${outDir}/${slug}-table-${n}.png` });
  }
  console.log(`${n} table(s) -> ${outDir}/${slug}-table-N.png`);
  await browser.close();
} finally {
  server.kill();
}
