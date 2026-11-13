/**
 * Production-readiness audit (2026-11-11), L-07: `#nav4` e' la barra di navigazione sempre visibile dal breakpoint lg e un drawer
 * sotto. Lo script le metteva `aria-hidden="true"` a prescindere dal viewport: su desktop 6 link focusabili finivano dentro un
 * nodo nascosto alle tecnologie assistive (axe `aria-hidden-focus`, WCAG 4.1.2). `aria-hidden` deve esistere solo sul drawer mobile
 * e seguire il viewport anche quando cambia a pagina aperta (rotazione, finestra ridimensionata).
 *
 * Run: cd laravel/Themes/Sixteen && FIXCITY_BASE_URL=http://127.0.0.1:8081 \
 *        npx playwright test tests/browser/header-nav-aria-hidden.spec.mjs --reporter=list
 * Serve il bundle ricostruito (`npm run build`): `public/assets/app-*.js` e' quello che la pagina carica.
 */
import { existsSync } from 'node:fs';
import { test, expect } from '@playwright/test';

const BASE = process.env.FIXCITY_BASE_URL || 'http://127.0.0.1:8000';
const exe = [process.env.PLAYWRIGHT_CHROMIUM_EXECUTABLE_PATH, ...[1248, 1247, 1243].map((n) => `${process.env.HOME}/.cache/ms-playwright/chromium-${n}/chrome-linux64/chrome`)].filter(Boolean).find(existsSync);
test.use({ launchOptions: { executablePath: exe, args: ['--no-sandbox', '--disable-dev-shm-usage'] } });

const PANEL = '#nav4.navbar-collapsable';

test('desktop navigation is not aria-hidden and keeps no focusable link under an aria-hidden ancestor', async ({ page }) => {
  await page.setViewportSize({ width: 1440, height: 900 });
  await page.goto(`${BASE}/it`, { waitUntil: 'load' });

  const panel = page.locator(PANEL).first();
  await expect(panel, 'pannello di navigazione assente: la guardia passerebbe a vuoto').toHaveCount(1);
  await expect(panel).not.toHaveAttribute('aria-hidden', /.*/);

  const hiddenFocusable = await page.evaluate((selector) => {
    const nodes = [...document.querySelectorAll(`${selector} a[href], ${selector} button`)];
    return nodes.filter((node) => node.closest('[aria-hidden="true"]') !== null).length;
  }, PANEL);
  expect(hiddenFocusable, 'link della navigazione dentro un antenato aria-hidden="true"').toBe(0);
});

test('mobile drawer is aria-hidden when closed and exposed when open', async ({ page }) => {
  await page.setViewportSize({ width: 390, height: 844 });
  await page.goto(`${BASE}/it`, { waitUntil: 'load' });

  const panel = page.locator(PANEL).first();
  await expect(panel).toHaveAttribute('aria-hidden', 'true');

  await page.locator('.custom-navbar-toggler[data-bs-target="#nav4"]').first().click();
  await expect(panel).toHaveAttribute('aria-hidden', 'false');

  await panel.locator('.close-menu').first().click();
  await expect(panel).toHaveAttribute('aria-hidden', 'true');
});

test('aria-hidden follows the viewport when it crosses the lg breakpoint', async ({ page }) => {
  await page.setViewportSize({ width: 390, height: 844 });
  await page.goto(`${BASE}/it`, { waitUntil: 'load' });
  const panel = page.locator(PANEL).first();
  await expect(panel).toHaveAttribute('aria-hidden', 'true');

  await page.setViewportSize({ width: 1440, height: 900 });
  await expect(panel, 'su desktop il drawer chiuso non deve restare aria-hidden').not.toHaveAttribute('aria-hidden', /.*/);

  await page.setViewportSize({ width: 390, height: 844 });
  await expect(panel, 'tornando a mobile il drawer richiuso torna aria-hidden').toHaveAttribute('aria-hidden', 'true');
});
