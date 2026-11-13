/**
 * Home, blocco «Novità»: le card stanno su una riga da 3 a 1440 px, da 2 a 800 px e da 1 a 375 px, senza scorrimento orizzontale.
 *
 * Perche': il CSS compilato del tema scopa le regole di Bootstrap con `body:has(.page-content[data-dc]) .row` (specificita' 0,3,1) e batte
 * quelle del blocco. Un `gap` messo sopra il gutter di Bootstrap (`g-4`) fa sommare due distanze fra le colonne: 3 x 440 + 2 x 24 = 1368 px
 * in una riga larga 1320, e la terza card va a capo da sola (misurato il 2026-11-11 con 3 notizie a 1440 px).
 *
 * Run: cd laravel/Themes/Sixteen && FIXCITY_BASE_URL=http://127.0.0.1:8081 npx playwright test tests/browser/home-news-grid.spec.mjs --reporter=list
 * Legge soltanto la home pubblica: nessuna scrittura, nessuna credenziale.
 */
import { existsSync } from 'node:fs';
import { test, expect } from '@playwright/test';

const BASE = process.env.FIXCITY_BASE_URL || 'http://127.0.0.1:8000';
const exe = [process.env.PLAYWRIGHT_CHROMIUM_EXECUTABLE_PATH, '/usr/bin/google-chrome-stable', ...[1248, 1247, 1243].map((n) => `${process.env.HOME}/.cache/ms-playwright/chromium-${n}/chrome-linux64/chrome`)].filter(Boolean).find(existsSync);
test.use({ launchOptions: { executablePath: exe, args: ['--no-sandbox', '--disable-dev-shm-usage'] } });

const CASES = [
    { width: 1440, perRow: 3 },
    { width: 800, perRow: 2 },
    { width: 375, perRow: 1 },
];

for (const { width, perRow } of CASES) {
    test(`home news cards: ${perRow} per row at ${width}px`, async ({ page }) => {
        await page.setViewportSize({ width, height: 900 });
        await page.goto(`${BASE}/it`, { waitUntil: 'load' });

        const cards = page.locator('.fc-home-news__grid > *');
        const count = await cards.count();
        expect(count, 'la home deve avere almeno 3 notizie per misurare la riga').toBeGreaterThanOrEqual(3);

        const tops = await cards.evaluateAll((els) => els.map((el) => Math.round(el.getBoundingClientRect().top + window.scrollY)));
        const onFirstRow = tops.filter((top) => top === tops[0]).length;
        expect(onFirstRow).toBe(Math.min(perRow, count));

        const overflow = await page.evaluate(() => document.documentElement.scrollWidth - window.innerWidth);
        expect(overflow).toBeLessThanOrEqual(1);
    });
}
