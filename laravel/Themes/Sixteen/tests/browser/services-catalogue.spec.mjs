import { test, expect } from '@playwright/test';

const BASE = process.env.FIXCITY_BASE_URL || 'http://localhost:8001';
const LOCALES = ['it', 'en', 'de', 'es'];
const VIEWPORTS = [320, 390, 768, 1440];

function relativeLuminance(color) {
    const channels = color.match(/[\d.]+/g).slice(0, 3).map(Number).map((value) => value / 255);
    const linear = channels.map((value) => value <= 0.04045 ? value / 12.92 : ((value + 0.055) / 1.055) ** 2.4);

    return (0.2126 * linear[0]) + (0.7152 * linear[1]) + (0.0722 * linear[2]);
}

function contrastRatio(foreground, background) {
    const first = relativeLuminance(foreground);
    const second = relativeLuminance(background);

    return (Math.max(first, second) + 0.05) / (Math.min(first, second) + 0.05);
}

test.describe('Design Comuni service catalogue scope', () => {
    test('keeps unpublished tenant sections out of navigation and rejects empty CMS pages', async ({ page }) => {
        test.setTimeout(90_000);
        for (const locale of LOCALES) {
            await page.setViewportSize({ width: 390, height: 844 });
            const response = await page.goto(`${BASE}/${locale}/services`, { waitUntil: 'networkidle' });
            expect(response?.status()).toBe(200);

            const publishedDestinations = [`/${locale}/tickets`, `/${locale}/blog`, `/${locale}/events`];
            for (const destination of publishedDestinations) {
                await expect(page.locator(`[data-element="main-navigation"] a[href="${BASE}${destination}"]`).first()).toHaveAttribute('href', `${BASE}${destination}`);
            }

            for (const dataElement of ['management', 'all-topics']) {
                await expect(page.locator(`[data-element="${dataElement}"]`)).toHaveCount(0);
            }

        }
    });

    test('shows only supported FixCity tasks and a working report category at every locale and width', async ({ page }) => {
        for (const locale of LOCALES) {
            for (const width of VIEWPORTS) {
                await page.setViewportSize({ width, height: 900 });
                const pageErrors = [];
                const onPageError = (error) => pageErrors.push(error.message);
                page.on('pageerror', onPageError);

                const response = await page.goto(`${BASE}/${locale}/services`, { waitUntil: 'networkidle' });

                expect(response?.status()).toBe(200);
                await expect(page.locator('[data-service-searchable]')).toHaveCount(3);
                const taskLinks = page.locator('#service-catalogue a[href$="/tickets/create"], #service-catalogue a[href$="/tickets"], #service-catalogue a[href$="/tickets/track"]');
                await expect(taskLinks).toHaveCount(3);
                const taskLinkLayout = await taskLinks.evaluateAll((links) => links.map((link) => {
                    const nav = link.parentElement;
                    const style = getComputedStyle(nav);

                    return {
                        width: link.getBoundingClientRect().width,
                        navAvailableWidth: nav.clientWidth - Number.parseFloat(style.paddingLeft) - Number.parseFloat(style.paddingRight),
                        clientWidth: link.clientWidth,
                        scrollWidth: link.scrollWidth,
                        minHeight: link.getBoundingClientRect().height,
                    };
                }));
                for (const link of taskLinkLayout) {
                    expect(link.scrollWidth).toBeLessThanOrEqual(link.clientWidth + 1);
                    expect(link.minHeight).toBeGreaterThanOrEqual(44);
                    if (width < 640) {
                        expect(Math.abs(link.width - link.navAvailableWidth)).toBeLessThanOrEqual(1);
                    }
                }
                await expect(page.getByText(/Registry and civil status|Anagrafe e stato civile|Meldewesen und Personenstand|Padrón y registro civil/)).toHaveCount(0);
                const heroColors = await page.locator('#fixcity-services-hero').evaluate((hero) => ({
                    background: getComputedStyle(hero).backgroundColor,
                    heading: getComputedStyle(hero.querySelector('h1')).color,
                    subtitle: getComputedStyle(hero.querySelector('p')).color,
                }));
                expect(contrastRatio(heroColors.heading, heroColors.background)).toBeGreaterThanOrEqual(4.5);
                expect(contrastRatio(heroColors.subtitle, heroColors.background)).toBeGreaterThanOrEqual(4.5);
                expect(await page.evaluate(() => document.documentElement.scrollWidth)).toBe(width);
                expect(pageErrors).toEqual([]);

                page.off('pageerror', onPageError);
            }
        }
    });

    test('service sheet preserves the locale and guides guests into sign-in', async ({ page }) => {
        for (const locale of LOCALES) {
            await page.context().clearCookies();
            await page.goto(`${BASE}/${locale}/services`, { waitUntil: 'networkidle' });
            await expect(page.locator('#service-catalogue a[href$="/tickets/create"]')).toHaveAttribute('href', new RegExp(`/${locale}/tickets/create$`));
            await page.locator('#service-catalogue a[href$="/tickets/create"]').click();
            await expect(page).toHaveURL(new RegExp(`/${locale}/auth/login$`));
        }
    });

    test('service catalogue exposes exactly the three supported FixCity tasks', async ({ page }) => {
        await page.goto(`${BASE}/it/services`, { waitUntil: 'networkidle' });
        await expect(page.locator('#service-catalogue [data-service-searchable]')).toHaveCount(3);
    });
});
