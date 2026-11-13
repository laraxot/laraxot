/**
 * DCP-152 - il marker della mappa nel dettaglio pubblico /{lang}/tickets/{id} e' quello del tipo di segnalazione:
 * cerchio col colore dello stato, glifo del tipo, nessuna iniziale "?" e nessun pin nero senza sfondo.
 * Run: cd laravel/Themes/Sixteen && FIXCITY_BASE_URL=http://127.0.0.1:8091 ./node_modules/.bin/playwright test tests/browser/ticket-detail-marker.spec.mjs
 * Serve almeno una segnalazione pubblica (fixture: scratch-auth-fixture.sh public-tickets).
 */
import { test, expect } from '@playwright/test';

const BASE = process.env.FIXCITY_BASE_URL || 'http://127.0.0.1:8091';

test('il marker del dettaglio mostra tipo e stato della segnalazione', async ({ page }) => {
  await page.goto(`${BASE}/it/tickets`, { waitUntil: 'domcontentloaded' });
  const href = await page.locator('a[href^="/it/tickets/"]').evaluateAll((links) => links.map((a) => a.getAttribute('href')).find((h) => /^\/it\/tickets\/\d+$/.test(h)));
  expect(href, 'nessuna segnalazione pubblica nell\'elenco').toBeTruthy();
  await page.goto(`${BASE}${href}`, { waitUntil: 'networkidle' });
  await page.waitForSelector('coordinate-picker-lit .leaflet-marker-icon', { timeout: 30000 });
  const marker = await page.evaluate(() => {
    const m = document.querySelector('coordinate-picker-lit .geo-map-marker-designcomuni');
    const circle = m.querySelector('.geo-map-marker-designcomuni__circle');
    return {
      circleBg: getComputedStyle(circle).backgroundColor,
      color: m.style.getPropertyValue('--marker-color'),
      glyph: m.querySelector('img.geo-map-marker-glyph')?.getAttribute('src') ?? '',
      initial: m.querySelector('.geo-map-marker-designcomuni__initial')?.textContent ?? '',
    };
  });
  expect(marker.circleBg).not.toBe('rgba(0, 0, 0, 0)');
  expect(marker.color).toMatch(/^#[0-9a-f]{6}$/i);
  expect(marker.glyph).toMatch(/\/svg\/[a-z-]+\.svg$/);
  expect(marker.initial).toBe('');
});
