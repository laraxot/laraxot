/**
 * DCP-288 (FO-1-07): modale di ricerca dell'intestazione, uso da tastiera e impilamento.
 * Prima: all'apertura il focus restava sul pulsante, Tab proseguiva nella pagina dietro la modale (nessun focus trap), Esc chiudeva ma il
 * focus non tornava al pulsante (WCAG 2.4.3, 2.1.2) e i pulsanti della mappa (z-index 3002 fuori da ogni contesto di impilamento)
 * stavano SOPRA la modale (z-index 1055) e sopra il menu mobile.
 *
 * Run: cd laravel/Themes/Sixteen && FIXCITY_BASE_URL=http://127.0.0.1:8091 npx playwright test tests/browser/search-modal-keyboard.spec.mjs --reporter=list
 * Prima della build del tema (il JS e il CSS nuovi sono nei sorgenti ma non ancora in public/): FIXCITY_INJECT_SCRIPT=resources/js/theme/modal-focus.js
 * FIXCITY_INJECT_CSS=<file con le regole di resources/css/app/22-header-shell.css> li inietta nella pagina, come se fossero compilati.
 */
import { existsSync, readFileSync } from 'node:fs';
import { test, expect } from '@playwright/test';

const BASE = process.env.FIXCITY_BASE_URL || 'http://127.0.0.1:8000';
const INJECT_JS = process.env.FIXCITY_INJECT_SCRIPT || '';
const INJECT_CSS = process.env.FIXCITY_INJECT_CSS || '';
const exe = [process.env.PLAYWRIGHT_CHROMIUM_EXECUTABLE_PATH, ...[1248, 1247, 1243].map((n) => `${process.env.HOME}/.cache/ms-playwright/chromium-${n}/chrome-linux64/chrome`)].find((p) => p && existsSync(p));
test.use({ launchOptions: { executablePath: exe, args: ['--no-sandbox', '--disable-dev-shm-usage'] }, viewport: { width: 1280, height: 900 } });

async function openSearch(page, path = '/it/tickets') {
  await page.goto(`${BASE}${path}`, { waitUntil: 'networkidle' });
  if (INJECT_CSS) await page.addStyleTag({ content: readFileSync(INJECT_CSS, 'utf8') });
  if (INJECT_JS) {
    await page.addScriptTag({ content: readFileSync(INJECT_JS, 'utf8').replace(/^export /gm, '') });
    await page.evaluate(() => initModalFocus());
  }
  const trigger = page.locator('.it-search-wrapper button.search-link').first();
  await trigger.focus();
  await page.keyboard.press('Enter');
  await expect(page.locator('#search-modal')).toHaveClass(/\bshow\b/);

  return trigger;
}

const insideModal = (page) => page.evaluate(() => !!document.activeElement?.closest('#search-modal'));

test('all\'apertura il focus entra nel campo di ricerca', async ({ page }) => {
  await openSearch(page);
  await expect(page.locator('#search-modal input[type="search"]')).toBeFocused();
});

test('Tab e Maiusc+Tab restano dentro la modale', async ({ page }) => {
  await openSearch(page);
  for (let i = 0; i < 14; i++) {
    await page.keyboard.press('Tab');
    expect(await insideModal(page), `dopo ${i + 1} Tab il focus e' nella modale`).toBe(true);
  }
  for (let i = 0; i < 14; i++) {
    await page.keyboard.press('Shift+Tab');
    expect(await insideModal(page), `dopo ${i + 1} Maiusc+Tab il focus e' nella modale`).toBe(true);
  }
});

test('Esc chiude la modale e riporta il focus al pulsante che l\'ha aperta', async ({ page }) => {
  const trigger = await openSearch(page);
  expect(await insideModal(page), 'prima di Esc il focus e dentro la modale (senza, il test passerebbe per caso)').toBe(true);
  await page.keyboard.press('Escape');
  await expect(page.locator('#search-modal')).not.toHaveClass(/\bshow\b/);
  await expect(trigger).toBeFocused();
});

test('il pulsante di chiusura riporta il focus al pulsante che ha aperto la modale', async ({ page }) => {
  const trigger = await openSearch(page);
  await page.locator('#search-modal button.search-link.d-md-block').click();
  await expect(page.locator('#search-modal')).not.toHaveClass(/\bshow\b/);
  await expect(trigger).toBeFocused();
});

test('con la modale aperta nessun controllo della mappa sta sopra la modale', async ({ page }) => {
  await page.setViewportSize({ width: 375, height: 812 });
  await page.goto(`${BASE}/it/tickets`, { waitUntil: 'networkidle' });
  if (INJECT_CSS) await page.addStyleTag({ content: readFileSync(INJECT_CSS, 'utf8') });
  await page.locator('.ctrl-btn').first().scrollIntoViewIfNeeded();
  await page.locator('.it-search-wrapper button.search-link').first().evaluate((el) => el.click());
  await expect(page.locator('#search-modal')).toHaveClass(/\bshow\b/);
  const stack = await page.evaluate(() => {
    const box = document.querySelector('.ctrl-btn').getBoundingClientRect();
    return document.elementsFromPoint(box.left + box.width / 2, box.top + box.height / 2).map((el) => el.id || el.tagName);
  });
  const modalAt = stack.indexOf('search-modal');
  expect(modalAt, 'la modale e nello stack nel punto del controllo').toBeGreaterThanOrEqual(0);
  expect(stack.slice(0, modalAt).some((id) => id === 'BUTTON'), 'nessun pulsante della mappa sopra la modale').toBe(false);
});
