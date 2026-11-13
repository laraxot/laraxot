/**
 * Production-readiness audit (2026-11-11), F-01: nelle FAQ i pulsanti `accordion-button collapsed` partivano con `aria-expanded="true"` e pannello
 * chiuso (12 su 12 misurati): lo stato letto dal lettore di schermo era invertito fino al secondo clic (WCAG 4.1.2). Qui il comportamento reale col JS
 * del tema: chiuso = `false`, un clic apre (`true` e pannello `.show`), un secondo clic richiude (`false`).
 *
 * Run: cd laravel/Themes/Sixteen && FIXCITY_BASE_URL=http://127.0.0.1:8081 \
 *        npx playwright test tests/browser/faq-accordion-aria-expanded.spec.mjs --reporter=list
 * Guardia statica corrispondente: tests/Unit/CollapsedButtonsStartCollapsedTest.php (nessun `collapsed` con `aria-expanded="true"` nelle viste).
 */
import { existsSync } from 'node:fs';
import { test, expect } from '@playwright/test';

const BASE = process.env.FIXCITY_BASE_URL || 'http://127.0.0.1:8000';
const exe = [process.env.PLAYWRIGHT_CHROMIUM_EXECUTABLE_PATH, ...[1248, 1247, 1243].map((n) => `${process.env.HOME}/.cache/ms-playwright/chromium-${n}/chrome-linux64/chrome`)].filter(Boolean).find(existsSync);
test.use({ launchOptions: { executablePath: exe, args: ['--no-sandbox', '--disable-dev-shm-usage'] } });

const BUTTONS = '.cmp-accordion.faq .accordion-button';

test('every closed FAQ button says aria-expanded false and toggles with the panel', async ({ page }) => {
  await page.goto(`${BASE}/it/domande-frequenti`, { waitUntil: 'load' });

  const buttons = page.locator(BUTTONS);
  const total = await buttons.count();
  expect(total, 'nessuna domanda letta: la guardia passerebbe a vuoto').toBeGreaterThan(2);

  const wrong = await page.evaluate((selector) => [...document.querySelectorAll(selector)]
    .filter((button) => !document.querySelector(button.dataset.bsTarget)?.classList.contains('show') && button.getAttribute('aria-expanded') !== 'false')
    .length, BUTTONS);
  expect(wrong, 'pulsanti chiusi che non dichiarano aria-expanded="false"').toBe(0);

  const first = buttons.first();
  const panel = page.locator(await first.getAttribute('data-bs-target'));
  await first.click();
  await expect(first).toHaveAttribute('aria-expanded', 'true');
  await expect(panel).toHaveClass(/\bshow\b/);

  await first.click();
  await expect(first).toHaveAttribute('aria-expanded', 'false');
  await expect(panel).not.toHaveClass(/\bshow\b/);
});

/**
 * F-12: il radio delle stelle e' 1x1 px, quindi l'indicatore di focus visibile e' il `box-shadow` della label. Quello di Bootstrap Italia e' un anello
 * arancione di 2px (#f90 su bianco = 2.14:1, minimo 3:1). Il tema lo sostituisce con bianco + #1a1a1a (`20-dc-components.css`).
 */
test('keyboard focus on a rating star draws a ring with enough contrast', async ({ page }) => {
  await page.goto(`${BASE}/it/domande-frequenti`, { waitUntil: 'load' });
  const radio = page.locator('.dc-rating-stars__item input').first();
  expect(await radio.count(), 'nessuna stella letta: la guardia passerebbe a vuoto').toBe(1);

  await page.keyboard.press('Tab');
  await radio.focus();
  const ring = await radio.evaluate((el) => getComputedStyle(el.nextElementSibling).boxShadow);
  expect(ring).toContain('rgb(26, 26, 26)');
  expect(ring).not.toContain('rgb(255, 153, 0)');
});
