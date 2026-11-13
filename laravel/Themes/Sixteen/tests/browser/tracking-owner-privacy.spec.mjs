import { test, expect, chromium } from '@playwright/test';

const BASE = process.env.FIXCITY_BASE_URL || 'http://127.0.0.1:8095';
const OWNER_EMAIL = process.env.FIXCITY_BROWSER_OWNER_EMAIL;
const OWNER_PASSWORD = process.env.FIXCITY_BROWSER_OWNER_PASSWORD;
const OTHER_EMAIL = process.env.FIXCITY_BROWSER_OTHER_EMAIL;
const OTHER_PASSWORD = process.env.FIXCITY_BROWSER_OTHER_PASSWORD;
const TICKET_ID = process.env.FIXCITY_BROWSER_TICKET_ID;
const TICKET_CODE = process.env.FIXCITY_BROWSER_TICKET_CODE;
const PRIVATE_TICKET_ID = process.env.FIXCITY_BROWSER_PRIVATE_TICKET_ID;

test('tracking keeps owner capability private and guest access code-based', async () => {
  test.skip(
    !OWNER_EMAIL || !OWNER_PASSWORD || !OTHER_EMAIL || !OTHER_PASSWORD || !TICKET_ID || !TICKET_CODE || !PRIVATE_TICKET_ID,
    'Requires an isolated seeded SQLite database, two synthetic accounts and a private ticket.',
  );

  const browser = await chromium.launch({ headless: true });
  const owner = await browser.newPage({ viewport: { width: 390, height: 844 } });
  const errors = [];
  owner.on('pageerror', (error) => errors.push(error.message));

  await owner.goto(`${BASE}/it/auth/login`, { waitUntil: 'domcontentloaded', timeout: 30000 });
  await owner.locator('input[type="email"]').first().fill(OWNER_EMAIL);
  await owner.locator('input[type="password"]').first().fill(OWNER_PASSWORD);
  await owner.getByRole('button', { name: /Accedi|Login/i }).click();
  await owner.locator('#header-user-toggle').waitFor({ state: 'visible', timeout: 30000 });

  const ownerResponse = await owner.goto(`${BASE}/it/tickets/track?ticket_id=${encodeURIComponent(TICKET_ID)}`, {
    waitUntil: 'domcontentloaded',
    timeout: 30000,
  });
  const ownerText = await owner.locator('body').innerText();
  expect(ownerResponse?.status()).toBe(200);
  expect(ownerText).toContain('Buca profonda in via Morandi');
  expect(ownerText).toContain(TICKET_CODE);
  expect(ownerText).toContain('In lavorazione');

  const privateOwnerResponse = await owner.goto(`${BASE}/it/tickets/track?ticket_id=${encodeURIComponent(PRIVATE_TICKET_ID)}`, {
    waitUntil: 'domcontentloaded',
    timeout: 30000,
  });
  expect(privateOwnerResponse?.status()).toBe(200);
  expect(await owner.locator('body').innerText()).toContain('DEMO-PRIVATE-001');

  const otherCitizen = await browser.newPage({ viewport: { width: 390, height: 844 } });
  otherCitizen.on('pageerror', (error) => errors.push(error.message));
  await otherCitizen.goto(`${BASE}/it/auth/login`, { waitUntil: 'domcontentloaded', timeout: 30000 });
  await otherCitizen.locator('input[type="email"]').first().fill(OTHER_EMAIL);
  await otherCitizen.locator('input[type="password"]').first().fill(OTHER_PASSWORD);
  await otherCitizen.getByRole('button', { name: /Accedi|Login/i }).click();
  await otherCitizen.locator('#header-user-toggle').waitFor({ state: 'visible', timeout: 30000 });
  const otherCitizenResponse = await otherCitizen.goto(`${BASE}/it/tickets/track?ticket_id=${encodeURIComponent(TICKET_ID)}`, {
    waitUntil: 'domcontentloaded',
    timeout: 30000,
  });
  const otherCitizenText = await otherCitizen.locator('body').innerText();
  expect(otherCitizenResponse?.status()).toBe(200);
  expect(otherCitizenText).toContain('Buca profonda in via Morandi');
  expect(otherCitizenText).toContain('In lavorazione');
  expect(otherCitizenText).not.toContain(TICKET_CODE);
  const privateOtherCitizenResponse = await otherCitizen.goto(`${BASE}/it/tickets/track?ticket_id=${encodeURIComponent(PRIVATE_TICKET_ID)}`, {
    waitUntil: 'domcontentloaded',
    timeout: 30000,
  });
  expect(privateOtherCitizenResponse?.status()).toBe(403);

  const guest = await browser.newPage({ viewport: { width: 320, height: 844 } });
  guest.on('pageerror', (error) => errors.push(error.message));
  const privateIdResponse = await guest.goto(`${BASE}/it/tickets/track?ticket_id=${encodeURIComponent(TICKET_ID)}`, {
    waitUntil: 'domcontentloaded',
    timeout: 30000,
  });
  expect(privateIdResponse?.status()).toBe(403);

  const publicCodeResponse = await guest.goto(`${BASE}/it/tickets/track?code=${encodeURIComponent(TICKET_CODE)}`, {
    waitUntil: 'domcontentloaded',
    timeout: 30000,
  });
  const guestText = await guest.locator('body').innerText();
  expect(publicCodeResponse?.status()).toBe(200);
  expect(guestText).toContain('Buca profonda in via Morandi');
  expect(guestText).toContain('In lavorazione');
  expect(guestText).not.toContain(TICKET_CODE);
  expect(await guest.evaluate(() => document.documentElement.scrollWidth <= document.documentElement.clientWidth)).toBe(true);
  expect(errors).toEqual([]);

  await browser.close();
});
