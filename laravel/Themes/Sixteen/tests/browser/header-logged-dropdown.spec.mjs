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

    const nameSpan = toggle.locator('span.d-none.d-lg-block').first();
    await expect(nameSpan).toBeVisible();

    const chevron = toggle.locator('svg.d-none.d-lg-block');
    await expect(chevron).toBeVisible();

    await toggle.click();
    const menu = page.locator('#header-user-menu');
    await expect(toggle).toHaveAttribute('aria-expanded', 'true', { timeout: 10000 });
    await expect(menu).toBeVisible();
    await expect(menu.locator('.link-list')).toBeVisible();
    await expect(menu.locator('text=I miei servizi').or(menu.locator('text=My services'))).toBeVisible();

    const servicesLink = menu.locator('a[href*="/servizi"]').first();
    await expect(servicesLink).toBeVisible();
    const servicesHref = await servicesLink.getAttribute('href');
    expect(servicesHref).toMatch(/^\/it\//);

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

    const nameSpan = toggle.locator('span.d-none.d-lg-block').first();
    await expect(nameSpan).toBeHidden();

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
