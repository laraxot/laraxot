import { test, expect } from '@playwright/test';

const BASE = process.env.FIXCITY_BASE_URL || 'http://localhost:8001';
const LOCALES = [
    { code: 'it', faq: 'Domande frequenti', sitemap: 'Mappa del sito', service: 'Servizio di segnalazione' },
    { code: 'en', faq: 'Frequently asked questions', sitemap: 'Site map', service: 'Reporting service' },
    { code: 'de', faq: 'Häufige Fragen', sitemap: 'Sitemap', service: 'Meldedienst' },
    { code: 'es', faq: 'Preguntas frecuentes', sitemap: 'Mapa del sitio', service: 'Servicio de avisos' },
];
const WIDTHS = [320, 390, 768, 1440];

test('FAQ and site map are real, localized and usable at all supported widths', async ({ page }) => {
    test.setTimeout(120_000);
    for (const locale of LOCALES) {
        for (const width of WIDTHS) {
            await page.setViewportSize({ width, height: 900 });
            const pageErrors = [];
            const onPageError = (error) => pageErrors.push(error.message);
            page.on('pageerror', onPageError);

            const faqResponse = await page.goto(`${BASE}/${locale.code}/domande-frequenti`, { waitUntil: 'domcontentloaded' });
            expect(faqResponse?.status()).toBe(200);
            await expect(page.getByRole('heading', { level: 1, name: locale.faq })).toBeVisible();
            const faqItems = page.locator('#faq-content .accordion-item, #accordion-faq .accordion-item');
            await expect(faqItems).not.toHaveCount(0);
            await expect(page.locator('#faq-content, #accordion-faq').first()).not.toContainText(/Lorem Ipsum|pub_theme::|fixcity::/i);
            const firstFaq = page.locator('#faq-content .accordion-button, #accordion-faq .accordion-button').first();
            await firstFaq.focus();
            await page.keyboard.press('Enter');
            await expect(faqItems.first().locator('p')).toBeVisible();
            await expect(page.locator(`a[href$="/${locale.code}/tickets/create"]`).last()).toBeVisible();
            await expect(page.locator(`footer a[href$="/${locale.code}/mappa-sito"]`).first()).toBeVisible();
            expect(await page.evaluate(() => document.documentElement.scrollWidth)).toBe(width);
            expect(pageErrors).toEqual([]);

            const sitemapResponse = await page.goto(`${BASE}/${locale.code}/mappa-sito`, { waitUntil: 'domcontentloaded' });
            expect(sitemapResponse?.status()).toBe(200);
            await expect(page.getByRole('heading', { level: 1, name: locale.sitemap })).toBeVisible();
            await expect(page.locator('#sitemap-content section')).toHaveCount(4, { timeout: 15_000 });
            await expect(page.locator('#sitemap-content')).not.toContainText(/pub_theme::|fixcity::/i);
            await expect(page.locator(`#sitemap-content a[href$="/${locale.code}/tickets"]`)).toBeVisible();
            await expect(page.locator(`#sitemap-content a[href$="/${locale.code}/area-personale/pratiche"]`)).toBeVisible();
            await expect(page.locator(`footer a[href$="/${locale.code}/domande-frequenti"]`).first()).toBeVisible();
            expect(await page.evaluate(() => document.documentElement.scrollWidth)).toBe(width);
            expect(pageErrors).toEqual([]);

            page.off('pageerror', onPageError);
        }
    }
});

test('site map keeps the guest personal-area redirect local to the selected language', async ({ page }) => {
    for (const locale of LOCALES) {
        await page.goto(`${BASE}/${locale.code}/mappa-sito`, { waitUntil: 'domcontentloaded' });
        await page.locator(`#sitemap-content a[href$="/${locale.code}/area-personale/pratiche"]`).click();
        await expect(page).toHaveURL(new RegExp(`/${locale.code}/auth/login$`));
    }
});
