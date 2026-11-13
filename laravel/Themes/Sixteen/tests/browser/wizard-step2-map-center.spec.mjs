/**
 * DCP-146 - centro iniziale della mappa del passo 2 (tickets/create), con e senza posizione del browser.
 * Contratto: punto salvato > posizione del browser > centro approssimativo dal fuso orario > vista di default del territorio (mai il mondo). Mai un requisito HTTPS.
 * Run: cd laravel/Themes/Sixteen && FIXCITY_BASE_URL=http://127.0.0.1:8091 FIXCITY_TEST_EMAIL=... FIXCITY_TEST_PASSWORD=... \
 *      ./node_modules/.bin/playwright test tests/browser/wizard-step2-map-center.spec.mjs
 * Il caso "HTTP non sicuro" simula un'origine non locale (fixcity.test -> 127.0.0.1): su localhost il browser considera il contesto sicuro.
 */
import { test, expect, chromium } from '@playwright/test';

const BASE = process.env.FIXCITY_BASE_URL || 'http://127.0.0.1:8091';
const EMAIL = process.env.FIXCITY_TEST_EMAIL || '';
const PASSWORD = process.env.FIXCITY_TEST_PASSWORD || '';
const INSECURE_HOST = 'fixcity.test';
const STEP2 = '/it/tickets/create?step=form.dati-di-segnalazione%3A%3Adata%3A%3Awizard-step';

const insecureBase = () => BASE.replace(/\/\/[^:/]+/, `//${INSECURE_HOST}`);

async function openStep2({ base, timezoneId, permissions = [], geolocation, viewport = { width: 1280, height: 900 } }) {
  const host = new URL(base).hostname;
  const browser = await chromium.launch({ args: host === INSECURE_HOST ? [`--host-resolver-rules=MAP ${INSECURE_HOST} 127.0.0.1`] : [] });
  const touch = viewport.width < 768;
  const context = await browser.newContext({ viewport, timezoneId, permissions, geolocation, hasTouch: touch, isMobile: touch });
  // La posizione da IP (DCP-127) interroga un servizio esterno e, se risponde, sposta la mappa sulla citta' dell'IP: qui si prova il livello
  // fuso orario, quindi il servizio e' bloccato. Con la rete aperta il risultato dipenderebbe da dove gira il test.
  await context.route('**://get.geojs.io/**', (route) => route.abort());
  const page = await context.newPage();
  await page.goto(`${base}/it/auth/login`, { waitUntil: 'domcontentloaded' });
  await page.locator('input[type="email"]').first().fill(EMAIL);
  await page.locator('input[type="password"]').first().fill(PASSWORD);
  await page.getByRole('button', { name: /Accedi|Login/i }).click();
  await page.waitForURL((url) => !url.pathname.includes('/auth/login'), { timeout: 60000 });
  await page.goto(`${base}${STEP2}`, { waitUntil: 'networkidle' });
  await page.waitForSelector('coordinate-picker-lit .leaflet-container', { timeout: 30000 });
  await page.waitForTimeout(2500);
  const read = () => page.evaluate(() => {
    const el = document.querySelector('coordinate-picker-lit');
    const center = el._map.getCenter();
    return {
      secure: window.isSecureContext,
      lat: center.lat,
      lng: center.lng,
      zoom: el._map.getZoom(),
      markers: el.querySelectorAll('.leaflet-marker-icon').length,
      text: document.body.innerText,
    };
  });
  return { browser, page, read };
}

/** Contratto DCP-127/146: senza punto scelto, un clic a zoom < 10 avvicina soltanto; il clic a zoom >= 10 mette il punto. */
async function placePointByClicking(page) {
  const map = page.locator('coordinate-picker-lit .leaflet-container');
  const marker = page.locator('coordinate-picker-lit .leaflet-marker-icon');
  for (let attempt = 0; attempt < 4 && (await marker.count()) === 0; attempt += 1) {
    await map.click({ position: { x: 300, y: 200 } });
    await page.waitForTimeout(600);
  }
  await expect(marker).toHaveCount(1);
}

/** Una mappa Leaflet senza il suo CSS ha comunque i tile nel DOM, ma in flusso normale (static) e fuori dal riquadro: va misurata la geometria. */
async function readMapGeometry(page) {
  return page.evaluate(() => {
    const root = document.querySelector('coordinate-picker-lit');
    const box = root.querySelector('.leaflet-container').getBoundingClientRect();
    const pane = getComputedStyle(root.querySelector('.leaflet-pane'));
    const tiles = [...root.querySelectorAll('img.leaflet-tile')].map((t) => t.getBoundingClientRect());
    // i tile coprono il riquadro e al massimo lo sfiorano di un tile (256 px): niente tile a centinaia di px di distanza
    const outside = tiles.filter((r) => r.bottom < box.top - 300 || r.top > box.bottom + 300 || r.right < box.left - 300 || r.left > box.right + 300).length;
    const covering = tiles.filter((r) => r.right > box.left && r.left < box.right && r.bottom > box.top && r.top < box.bottom).length;
    return { panePosition: pane.position, tiles: tiles.length, outside, covering, box: [box.width, box.height] };
  });
}

test.describe('DCP-146 centro iniziale della mappa del passo 2', () => {
  for (const [width, height] of [[1280, 900], [375, 812]]) {
    test(`${width}px: il riquadro dei controlli non copre la mappa (tocco, trascinamento, zoom)`, async () => {
      const { browser, page } = await openStep2({ base: BASE, timezoneId: 'Europe/Rome', viewport: { width, height } });
      await page.locator('coordinate-picker-lit .leaflet-container').scrollIntoViewIfNeeded();
      // Il riquadro .layer-controls-overlay e la riga di stato sono contenitori: solo i pulsanti e la ricerca devono ricevere i tocchi.
      // Un contenitore trasparente largo 240 px su una mappa di 269 lasciava il 13% della mappa utilizzabile su telefono.
      const hit = await page.evaluate(() => {
        const box = document.querySelector('coordinate-picker-lit .leaflet-container').getBoundingClientRect();
        let samples = 0; let dead = 0;
        for (let x = box.left + 4; x < box.right - 2; x += 8) {
          for (let y = box.top + 4; y < box.bottom - 2; y += 8) {
            if (y < 0 || y > innerHeight) continue;
            samples += 1;
            const el = document.elementFromPoint(x, y);
            const interactive = el && el.closest('button, input, a, select, [role="listbox"], .ctrl-btn, .search-box');
            if (el && !interactive && el.closest('.layer-controls-overlay, .map-control-status')) dead += 1;
          }
        }
        return { samples, dead };
      });
      expect(hit.samples).toBeGreaterThan(100);
      expect(hit.dead, `punti della mappa coperti dal solo contenitore dei controlli: ${hit.dead}/${hit.samples}`).toBe(0);
      await browser.close();
    });
  }

  test('la mappa e\' disegnata: pannelli assoluti e tile dentro il riquadro (CSS di Leaflet attivo nelle pagine Design Comuni)', async () => {
    const { browser, page } = await openStep2({ base: BASE, timezoneId: 'Europe/Rome' });
    const g = await readMapGeometry(page);
    expect(g.panePosition).toBe('absolute');
    expect(g.tiles).toBeGreaterThan(0);
    expect(g.outside).toBe(0);
    expect(g.covering).toBeGreaterThanOrEqual(2);
    await browser.close();
  });

  test('i pulsanti dei controlli hanno sfondo opaco, bordo e angoli arrotondati (il reset dei button delle pagine DC non li azzera)', async () => {
    const { browser, page } = await openStep2({ base: BASE, timezoneId: 'Europe/Rome' });
    const buttons = await page.evaluate(() => [...document.querySelectorAll('coordinate-picker-lit .ctrl-btn')].map((b) => {
      const c = getComputedStyle(b);
      return { label: b.getAttribute('aria-label'), bg: c.backgroundColor, border: c.borderTopWidth, radius: c.borderTopLeftRadius };
    }));
    expect(buttons.length).toBeGreaterThanOrEqual(5);
    for (const b of buttons) {
      expect(b.bg, `${b.label}: sfondo`).toBe('rgb(255, 255, 255)');
      expect(b.border, `${b.label}: bordo`).toBe('1px');
      expect(b.radius, `${b.label}: raggio`).toBe('8px');
    }
    await browser.close();
  });

  test('posizione del browser concessa: centra li e mette il punto', async () => {
    const { browser, read } = await openStep2({ base: BASE, timezoneId: 'Europe/Rome', permissions: ['geolocation'], geolocation: { latitude: 45.4642, longitude: 9.19 } });
    const s = await read();
    expect(s.secure).toBe(true);
    expect(s.lat).toBeCloseTo(45.4642, 2);
    expect(s.lng).toBeCloseTo(9.19, 2);
    expect(s.zoom).toBeGreaterThanOrEqual(13);
    expect(s.markers).toBe(1);
    await browser.close();
  });

  for (const [timezoneId, lat, lng] of [['Europe/Rome', 41.9, 12.48], ['America/New_York', 40.71, -74.01], ['Asia/Tokyo', 35.65, 139.74]]) {
    test(`HTTP non sicuro, fuso ${timezoneId}: centro approssimativo, mappa utilizzabile, nessun messaggio HTTPS`, async () => {
      const { browser, page, read } = await openStep2({ base: insecureBase(), timezoneId });
      const s = await read();
      expect(s.secure).toBe(false);
      expect(s.lat).toBeCloseTo(lat, 1);
      expect(s.lng).toBeCloseTo(lng, 1);
      expect(s.zoom).toBeGreaterThanOrEqual(5);
      expect(s.zoom).toBeLessThanOrEqual(7);
      expect(s.markers).toBe(0);
      expect(s.text).not.toMatch(/HTTPS/i);
      expect(s.text).toContain('Clicca sulla mappa per indicare la posizione');

      // la mappa resta usabile: i clic avvicinano e poi mettono il punto
      await placePointByClicking(page);
      await browser.close();
    });
  }

  test('HTTP non sicuro: "Usa la mia posizione" riporta alla zona approssimativa, senza messaggi su HTTPS', async () => {
    const { browser, page, read } = await openStep2({ base: insecureBase(), timezoneId: 'Europe/Rome' });
    await page.evaluate(() => document.querySelector('coordinate-picker-lit')._map.setView([-33.87, 151.21], 10, { animate: false }));
    await page.getByRole('button', { name: 'Usa la mia posizione' }).click();
    await page.waitForTimeout(1500);
    const s = await read();
    expect(s.lat).toBeCloseTo(41.9, 1);
    expect(s.lng).toBeCloseTo(12.48, 1);
    expect(s.markers).toBe(0);
    expect(s.text).not.toMatch(/HTTPS/i);
    await browser.close();
  });

  test('permesso negato in contesto sicuro: stesso centro approssimativo', async () => {
    const { browser, read } = await openStep2({ base: BASE, timezoneId: 'Asia/Tokyo', permissions: [] });
    const s = await read();
    expect(s.lat).toBeCloseTo(35.65, 1);
    expect(s.zoom).toBeLessThanOrEqual(7);
    expect(s.text).not.toMatch(/HTTPS/i);
    await browser.close();
  });

  test('fuso sconosciuto (UTC): vista di default del territorio (mai il mondo), mappa utilizzabile', async () => {
    const { browser, page, read } = await openStep2({ base: insecureBase(), timezoneId: 'UTC' });
    const s = await read();
    expect(s.zoom).toBeGreaterThanOrEqual(5); // non il mondo intero
    expect(s.lat).toBeCloseTo(42.5, 0);
    expect(s.lng).toBeCloseTo(12.5, 0);
    expect(s.markers).toBe(0);
    expect(s.text).not.toMatch(/HTTPS/i);
    await placePointByClicking(page);
    await browser.close();
  });
});
