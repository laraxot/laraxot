/**
 * DCP-182: nel riepilogo del wizard `/tickets/create` le foto caricate al passo dei dati compaiono come anteprima.
 * Carica 2 PNG e 1 PDF; al Riepilogo si aspetta 2 miniature vere (decodificate, naturalWidth > 0, alt tradotto), nessuna per il PDF (resta solo
 * nel nome), e che tornare indietro, togliere una foto e riavanzare mostri l'insieme attuale (non quello del primo ingresso).
 *
 * Run: cd laravel/Themes/Sixteen && FIXCITY_BASE_URL=http://127.0.0.1:8091 FIXCITY_STORAGE_STATE=/tmp/scratch-auth-fixture-$USER/auth.json \
 *        npx playwright test tests/browser/wizard-summary-photo-previews.spec.mjs --reporter=list
 * Senza FIXCITY_STORAGE_STATE il test e' saltato (e dichiarato "skipped"). Nessuna credenziale qui: la sessione viene da scratch-auth-fixture.sh.
 */
import { existsSync } from 'node:fs';
import { deflateSync } from 'node:zlib';
import { test, expect } from '@playwright/test';
import AxeBuilder from '@axe-core/playwright';

const BASE = process.env.FIXCITY_BASE_URL || 'http://127.0.0.1:8000';
const STATE = process.env.FIXCITY_STORAGE_STATE || '';
const WIZARD2 = '/it/tickets/create?step=form.dati-di-segnalazione%3A%3Adata%3A%3Awizard-step';
const exe = [process.env.PLAYWRIGHT_CHROMIUM_EXECUTABLE_PATH, ...[1248, 1247, 1243].map((n) => `${process.env.HOME}/.cache/ms-playwright/chromium-${n}/chrome-linux64/chrome`)].filter(Boolean).find(existsSync);
test.use({ launchOptions: { executablePath: exe, args: ['--no-sandbox', '--disable-dev-shm-usage'] } });

/** PNG a tinta unita w x h, scritto a mano (nessuna dipendenza): firma, IHDR, IDAT (deflate), IEND. */
function png(w, h, [r, g, b]) {
  const crcTable = Array.from({ length: 256 }, (_, n) => { let c = n; for (let k = 0; k < 8; k += 1) c = c & 1 ? 0xedb88320 ^ (c >>> 1) : c >>> 1; return c >>> 0; });
  const crc = (buf) => { let c = 0xffffffff; for (const byte of buf) c = crcTable[(c ^ byte) & 0xff] ^ (c >>> 8); return (c ^ 0xffffffff) >>> 0; };
  const chunk = (type, data) => { const len = Buffer.alloc(4); len.writeUInt32BE(data.length); const td = Buffer.concat([Buffer.from(type), data]); const c = Buffer.alloc(4); c.writeUInt32BE(crc(td)); return Buffer.concat([len, td, c]); };
  const ihdr = Buffer.alloc(13); ihdr.writeUInt32BE(w, 0); ihdr.writeUInt32BE(h, 4); ihdr[8] = 8; ihdr[9] = 2;
  const row = Buffer.concat([Buffer.from([0]), Buffer.from(Array.from({ length: w }, () => [r, g, b]).flat())]);
  const raw = Buffer.concat(Array.from({ length: h }, () => row));
  return Buffer.concat([Buffer.from([0x89, 0x50, 0x4e, 0x47, 0x0d, 0x0a, 0x1a, 0x0a]), chunk('IHDR', ihdr), chunk('IDAT', deflateSync(raw)), chunk('IEND', Buffer.alloc(0))]);
}
const FILES = [
  { name: 'panchina-rotta.png', mimeType: 'image/png', buffer: png(64, 48, [200, 60, 40]) },
  { name: 'buca-in-strada.png', mimeType: 'image/png', buffer: png(64, 48, [40, 90, 200]) },
  { name: 'modulo-richiesta.pdf', mimeType: 'application/pdf', buffer: Buffer.from('%PDF-1.4\n1 0 obj<</Type/Catalog>>endobj\ntrailer<</Root 1 0 R>>\n%%EOF\n') },
];

async function fillDataStep(page) {
  await page.route(/nominatim\.openstreetmap\.org/, (route) => route.fulfill({ status: 200, contentType: 'application/json', headers: { 'access-control-allow-origin': '*' }, body: JSON.stringify({ display_name: 'Via Solferino, 50123 Firenze FI, Italia', address: { road: 'Via Solferino', postcode: '50123', city: 'Firenze', county: 'FI' }, lat: '43.7696', lon: '11.2558' }) }));
  await page.goto(BASE + WIZARD2, { waitUntil: 'load', timeout: 60000 });
  await page.waitForFunction(() => document.querySelector('coordinate-picker-lit:not([readonly])')?._map, null, { timeout: 30000 });
  await page.waitForTimeout(2500);
  const pane = await page.$('coordinate-picker-lit:not([readonly]) .map-picker-leaflet-pane');
  await pane.scrollIntoViewIfNeeded();
  const box = await pane.boundingBox();
  for (let i = 0; i < 5; i += 1) {
    await page.mouse.click(box.x + box.width * 0.45, box.y + box.height * 0.5);
    await page.waitForTimeout(700);
    if (await page.evaluate(() => document.querySelector('coordinate-picker-lit:not([readonly])')?._lat != null)) break;
  }
  await page.selectOption('select[id="form.type"]', { index: 1 });
  await page.locator('input[id*="name"], input[wire\\:model*="name"]').first().fill('Prova anteprima foto');
  await page.locator('textarea').first().fill('Descrizione di prova per verificare le anteprime delle foto nel riepilogo.');
}

test('le foto del passo dati compaiono come anteprima nel Riepilogo e seguono le modifiche', async ({ browser }) => {
  test.skip(!STATE, 'serve FIXCITY_STORAGE_STATE (sessione di prova) per il wizard');
  test.setTimeout(180000);
  const context = await browser.newContext({ viewport: { width: 1440, height: 900 }, locale: 'it-IT', timezoneId: 'Europe/Rome', storageState: STATE });
  const page = await context.newPage();
  const failed = [];
  page.on('response', (r) => { if (r.status() >= 400 && !/nominatim|tile\.openstreetmap|favicon/.test(r.url())) failed.push(`${r.status()} ${r.url()}`); });

  await fillDataStep(page);
  await page.locator('input[type=file]').first().setInputFiles(FILES);
  // l'ultimo file e' un PDF: attendere che tutti e tre siano arrivati al server (la barra di avanzamento sparisce, compaiono 3 voci)
  await expect(page.locator('.upload-wrapper')).toHaveCount(3, { timeout: 30000 });
  await page.waitForTimeout(2500);
  await page.locator('button:has-text("Avanti"):visible').first().click();
  await page.waitForSelector('coordinate-picker-lit[readonly]', { timeout: 30000 });
  await page.waitForTimeout(1500);

  const thumbs = page.locator('[data-element="review-attachments"] img');
  await expect(thumbs).toHaveCount(2);
  for (let i = 0; i < 2; i += 1) {
    const decoded = await thumbs.nth(i).evaluate((img) => new Promise((resolve) => (img.complete ? resolve(img.naturalWidth) : img.addEventListener('load', () => resolve(img.naturalWidth)))));
    expect(decoded, `miniatura ${i + 1} decodificata`).toBeGreaterThan(0);
    expect(await thumbs.nth(i).getAttribute('src')).toContain('/preview-file/');
    expect(await thumbs.nth(i).getAttribute('alt')).toMatch(/^Anteprima della foto .+\.png$/);
  }
  const text = await page.locator('main').innerText();
  expect(text).toContain('panchina-rotta.png');
  expect(text).toContain('modulo-richiesta.pdf');
  expect(await page.locator('[data-element="review-attachments"]').getAttribute('aria-label')).toBe('Foto allegate');
  await page.screenshot({ path: process.env.PREVIEW_SHOT || 'test-results/riepilogo-anteprime.png', fullPage: true });
  const axe = await new AxeBuilder({ page }).withTags(['wcag2a', 'wcag2aa', 'wcag21a', 'wcag21aa']).include('[data-element="review-attachments"]').analyze();
  expect(axe.violations.map((v) => `${v.id}: ${v.help}`), 'axe sul gruppo delle anteprime').toEqual([]);
  // a 375 px le miniature stanno su due colonne e restano decodificate
  await page.setViewportSize({ width: 375, height: 800 });
  await page.waitForTimeout(600);
  const widths = await thumbs.evaluateAll((imgs) => imgs.map((img) => Math.round(img.getBoundingClientRect().width)));
  expect(Math.min(...widths), 'miniature visibili a 375 px').toBeGreaterThan(40);
  expect(await page.evaluate(() => document.documentElement.scrollWidth <= window.innerWidth + 1), 'nessuno scroll orizzontale a 375 px').toBe(true);
  await page.screenshot({ path: (process.env.PREVIEW_SHOT || 'test-results/riepilogo-anteprime.png').replace(/\.png$/, '-375.png'), fullPage: true });
  await page.setViewportSize({ width: 1440, height: 900 });

  // stadio 3: indietro, tolgo la prima foto, riavanzo -> una sola anteprima (la seconda), il PDF resta nel nome
  await page.locator('[data-element="review-attachments"]').scrollIntoViewIfNeeded();
  await page.locator('a:has-text("Modifica"), button:has-text("Modifica")').first().click();
  await page.waitForSelector('.upload-wrapper', { timeout: 20000 });
  // si toglie per NOME: l'ordine dei wrapper e' quello di arrivo degli upload (asincroni), non quello di selezione
  await page.locator('.upload-wrapper', { hasText: 'panchina-rotta.png' }).locator('button').last().click();
  await expect(page.locator('.upload-wrapper')).toHaveCount(2, { timeout: 15000 });
  await page.waitForTimeout(1500);
  await page.locator('button:has-text("Avanti"):visible').first().click();
  await page.waitForSelector('coordinate-picker-lit[readonly]', { timeout: 30000 });
  await page.waitForTimeout(1500);
  await expect(page.locator('[data-element="review-attachments"] img')).toHaveCount(1);
  expect(await page.locator('[data-element="review-attachments"] img').first().getAttribute('alt')).toContain('buca-in-strada.png');
  expect(await page.locator('main').innerText()).not.toContain('panchina-rotta.png');

  expect(failed, 'nessuna risposta 4xx/5xx').toEqual([]);
  await context.close();
});
