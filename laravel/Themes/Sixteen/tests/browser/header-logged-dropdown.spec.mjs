/**
 * STORY-147 — header logged dropdown parity (headless Playwright)
 * Run: cd laravel && npx playwright test Themes/Sixteen/tests/browser/header-logged-dropdown.spec.mjs --headed=false
 */
import { test, expect, chromium } from '@playwright/test';

const BASE = process.env.FIXCITY_BASE_URL || 'http://127.0.0.1:8000';
const EMAIL = process.env.FIXCITY_TEST_EMAIL || 'puppeteer-1780589647418@example.test';
const PASSWORD = process.env.FIXCITY_TEST_PASSWORD || 'password123';

async function login(page) {
  await page.goto(`${BASE}/it/auth/login`, { waitUntil: 'domcontentloaded', timeout: 60000 });
  await page.locator('button:has-text("Ho capito")').first().click({ timeout: 2000 }).catch(() => {});
  await page.locator('input[type="email"]').first().fill(EMAIL);
  await page.locator('input[type="password"]').first().fill(PASSWORD);
  await page.getByRole('button', { name: /Accedi|Login/i }).click();
  await page.waitForSelector('#header-user-toggle', { timeout: 60000 });
}

test.describe('STORY-147 header logged dropdown', () => {
  test('desktop: nome e chevron visibili, markup btn-primary', async () => {
    const browser = await chromium.launch({ headless: true });
    const page = await browser.newPage({ viewport: { width: 1280, height: 800 } });
    await login(page);
    await page.goto(`${BASE}/it`, { waitUntil: 'domcontentloaded' });

    const toggle = page.locator('#header-user-toggle');
    await expect(toggle).toBeVisible({ timeout: 15000 });
    await expect(toggle).toHaveClass(/btn-primary/);
    await expect(toggle).toHaveClass(/btn-icon/);

    const nameSpan = toggle.locator('span:not(.rounded-icon)').first();
    await expect(nameSpan).toBeVisible();

    const chevron = toggle.locator('svg.d-none.d-lg-block');
    await expect(chevron).toBeVisible();

    await toggle.click();
    const menu = page.locator('#header-user-menu');
    await expect(toggle).toHaveAttribute('aria-expanded', 'true', { timeout: 10000 });
    await expect(menu).toBeVisible();
    await expect(menu.locator('.link-list')).toBeVisible();
    const practicesLink = menu.locator('a[href*="/area-personale/pratiche"]').first();
    await expect(practicesLink).toBeVisible();
    const practicesHref = await practicesLink.getAttribute('href');
    expect(practicesHref).toMatch(/^https?:\/\/[^/]+\/it\/area-personale\/pratiche$|^\/it\/area-personale\/pratiche$/);

    await page.screenshot({ path: '/tmp/fixcity-header-desktop-dropdown.png', fullPage: false });
    await browser.close();
  });

  test('mobile: solo avatar, nome e chevron nascosti', async () => {
    const browser = await chromium.launch({ headless: true });
    const page = await browser.newPage({ viewport: { width: 375, height: 812 } });
    await login(page);
    await page.goto(`${BASE}/it`, { waitUntil: 'domcontentloaded' });

    const toggle = page.locator('#header-user-toggle');
    await expect(toggle).toBeVisible({ timeout: 15000 });

    const nameSpan = toggle.locator('span:not(.rounded-icon)').first();
    const nameBox = await nameSpan.boundingBox();
    expect(nameBox?.width ?? 0, 'il nome resta disponibile agli screen reader senza occupare spazio visibile').toBeLessThanOrEqual(1);
    await expect(toggle).toHaveAccessibleName(new RegExp((await nameSpan.textContent()).trim()));

    const chevron = toggle.locator('svg.d-none.d-lg-block');
    await expect(chevron).toBeHidden();

    await expect(toggle.locator('.rounded-icon')).toBeVisible();

    await toggle.click();
    const menu = page.locator('#header-user-menu');
    await expect(toggle).toHaveAttribute('aria-expanded', 'true', { timeout: 10000 });
    await expect(menu).toBeVisible();

    await page.screenshot({ path: '/tmp/fixcity-header-mobile-dropdown.png', fullPage: false });
    await browser.close();
  });
});

/*
 * DCP-55: a 375 e 768 px il menu utente (230 px) e quello lingua (160 px) uscivano a destra dal viewport e restavano tagliati
 * (`placeDropdownMenu` in resources/js/app.js non guardava il bordo destro). Il test sopra controllava solo `toBeVisible`,
 * che passa anche con il menu tagliato: qui si misura il rettangolo.
 */
test.describe('STORY-DCP-55 menu dentro il viewport', () => {
  for (const width of [375, 768, 1024]) {
    for (const toggleSelector of ['#header-user-toggle', 'button[aria-controls="languages"]']) {
      test(`${width}px: ${toggleSelector} resta nel viewport`, async () => {
        const browser = await chromium.launch({ headless: true });
        const page = await browser.newPage({ viewport: { width, height: 900 } });
        await login(page);
        await page.goto(`${BASE}/it/tickets`, { waitUntil: 'networkidle' });

        const toggle = page.locator(toggleSelector).first();
        await expect(toggle).toBeVisible({ timeout: 15000 });
        await toggle.click();
        const menu = toggle.locator('xpath=ancestor::*[contains(@class,"dropdown")][1]').locator('.dropdown-menu');
        await expect(menu).toBeVisible();

        const box = await menu.boundingBox();
        const viewportWidth = await page.evaluate(() => document.documentElement.clientWidth);
        expect(box.x, 'bordo sinistro fuori dal viewport').toBeGreaterThanOrEqual(0);
        expect(box.x + box.width, 'bordo destro fuori dal viewport').toBeLessThanOrEqual(viewportWidth);
        await browser.close();
      });
    }
  }
});
