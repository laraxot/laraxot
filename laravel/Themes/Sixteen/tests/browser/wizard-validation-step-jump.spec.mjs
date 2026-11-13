/**
 * DCP-146 - un errore di validazione in un passo precedente deve essere VISIBILE.
 * Aprendo il wizard direttamente a un passo avanzato (`?step=...`, consentito in locale/debug) il consenso privacy del passo 1 non e' stato dato:
 * "Invia" falliva in silenzio perche' l'errore stava nel passo 1, nascosto. Filament porta al campo in errore solo se questo ha
 * `data-validation-error`, e la vista personalizzata del consenso non lo aveva.
 * Run: cd laravel/Themes/Sixteen && FIXCITY_BASE_URL=http://127.0.0.1:8091 FIXCITY_TEST_EMAIL=... FIXCITY_TEST_PASSWORD=... \
 *      ./node_modules/.bin/playwright test tests/browser/wizard-validation-step-jump.spec.mjs
 */
import { test, expect, chromium } from '@playwright/test';

const BASE = process.env.FIXCITY_BASE_URL || 'http://127.0.0.1:8091';
const EMAIL = process.env.FIXCITY_TEST_EMAIL || '';
const PASSWORD = process.env.FIXCITY_TEST_PASSWORD || '';
const STEP3 = '/it/tickets/create?step=form.riepilogo%3A%3Adata%3A%3Awizard-step';

async function login(page) {
  await page.goto(`${BASE}/it/auth/login`, { waitUntil: 'domcontentloaded' });
  await page.locator('input[type="email"]').first().fill(EMAIL);
  await page.locator('input[type="password"]').first().fill(PASSWORD);
  await page.getByRole('button', { name: /Accedi|Login/i }).click();
  await page.waitForURL((url) => !url.pathname.includes('/auth/login'), { timeout: 60000 });
}

for (const [width, height] of [[1280, 900], [375, 812]]) {
  test(`${width}px: "Invia" senza consenso riporta al passo 1 con l'errore visibile`, async () => {
    const browser = await chromium.launch();
    const context = await browser.newContext({ viewport: { width, height }, hasTouch: width < 768, isMobile: width < 768 });
    await context.route('**://get.geojs.io/**', (route) => route.abort());
    const page = await context.newPage();
    await login(page);
    await page.goto(`${BASE}${STEP3}`, { waitUntil: 'networkidle' });

    const consentStep = page.locator('form[id^="form.autorizzazioni-e-condizioni"]');
    await expect(consentStep).toBeHidden();

    await page.getByRole('button', { name: /^Invia$/ }).click();

    // il passo del consenso diventa quello attivo e mostra il messaggio
    await expect(consentStep).toBeVisible({ timeout: 15000 });
    await expect(consentStep.locator('.form-feedback')).toBeVisible();
    await expect(consentStep.locator('.form-feedback')).toContainText(/accettat/i);
    await browser.close();
  });
}
