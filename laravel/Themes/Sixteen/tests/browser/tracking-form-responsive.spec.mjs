import { test, expect, chromium } from '@playwright/test';

const BASE = process.env.FIXCITY_BASE_URL || 'http://localhost:8001';
const LOCALES = ['it', 'en', 'de', 'es'];
const WIDTHS = [320, 390, 575, 576, 768, 1440];
const CONTRAST_WIDTHS = [375, 1280];

function relativeLuminance(color) {
  const channels = color.match(/[\d.]+/g).slice(0, 3).map(Number).map((value) => value / 255);
  const linear = channels.map((value) => (value <= 0.04045 ? value / 12.92 : ((value + 0.055) / 1.055) ** 2.4));

  return (0.2126 * linear[0]) + (0.7152 * linear[1]) + (0.0722 * linear[2]);
}

function contrastRatio(foreground, background) {
  const first = relativeLuminance(foreground);
  const second = relativeLuminance(background);

  return (Math.max(first, second) + 0.05) / (Math.min(first, second) + 0.05);
}

/** Colori calcolati dal browser (supporta oklch/lab via normalizzazione su elemento temporaneo). */
async function effectiveButtonColors(page, selector) {
  return page.evaluate((sel) => {
    const button = document.querySelector(sel);
    if (!button) {
      return null;
    }

    const cssColorToRgb = (cssColor) => {
      const canvas = document.createElement('canvas');
      canvas.width = 1;
      canvas.height = 1;
      const ctx = canvas.getContext('2d');
      if (!ctx) {
        return null;
      }
      ctx.fillStyle = '#ffffff';
      ctx.fillRect(0, 0, 1, 1);
      ctx.fillStyle = cssColor;
      ctx.fillRect(0, 0, 1, 1);
      const [r, g, b, a] = ctx.getImageData(0, 0, 1, 1).data;
      if (a === 0) {
        return null;
      }

      return { r, g, b };
    };

    const buttonStyle = getComputedStyle(button);
    const fg = cssColorToRgb(buttonStyle.color);
    let bg = cssColorToRgb(buttonStyle.backgroundColor);

    if (!fg) {
      return null;
    }

    if (!bg) {
      let node = button.parentElement;
      while (node && node !== document.documentElement) {
        bg = cssColorToRgb(getComputedStyle(node).backgroundColor);
        if (bg) {
          break;
        }
        node = node.parentElement;
      }
    }

    if (!bg) {
      bg = { r: 255, g: 255, b: 255 };
    }

    return {
      foreground: `rgb(${fg.r}, ${fg.g}, ${fg.b})`,
      background: `rgb(${bg.r}, ${bg.g}, ${bg.b})`,
      rawBackground: buttonStyle.backgroundColor,
    };
  }, selector);
}

test('tracking search remains readable, responsive and keyboard-operable', async () => {
  const browser = await chromium.launch({ headless: true });
  const failures = [];

  for (const locale of LOCALES) {
    const page = await browser.newPage({ viewport: { width: WIDTHS[0], height: 900 } });
    const pageErrors = [];
    page.on('pageerror', (error) => pageErrors.push(error.message));

    try {
      const response = await page.goto(`${BASE}/${locale}/tickets/track`, {
        waitUntil: 'domcontentloaded',
        timeout: 30000,
      });
      expect(response?.status(), `${locale} response`).toBe(200);

      const input = page.locator('#ticket-track-code');
      const button = page.locator('.dc-ticket-track__form button[type="submit"]');
      await expect(input).toBeVisible();
      await expect(input).toHaveAttribute('required', '');
      await expect(input).toHaveAttribute('aria-describedby', /ticket-track-help/);
      await expect(page.locator('label[for="ticket-track-code"]')).toBeVisible();
      await expect(page.locator('#ticket-track-help')).toBeVisible();

      for (const width of WIDTHS) {
        await page.setViewportSize({ width, height: 900 });
        const geometry = await page.evaluate(() => {
          const inputRect = document.querySelector('#ticket-track-code').getBoundingClientRect();
          const buttonRect = document.querySelector('.dc-ticket-track__form button[type="submit"]').getBoundingClientRect();
          return {
            input: { x: inputRect.x, y: inputRect.y, width: inputRect.width, right: inputRect.right, bottom: inputRect.bottom },
            button: { x: buttonRect.x, y: buttonRect.y, width: buttonRect.width, right: buttonRect.right, bottom: buttonRect.bottom },
            scrollWidth: document.documentElement.scrollWidth,
            clientWidth: document.documentElement.clientWidth,
          };
        });

        expect(geometry.scrollWidth, `${locale} ${width}px page overflow`).toBeLessThanOrEqual(geometry.clientWidth);
        if (width <= 575) {
          expect(Math.abs(geometry.input.x - geometry.button.x), `${locale} ${width}px alignment`).toBeLessThanOrEqual(1);
          expect(Math.abs(geometry.input.width - geometry.button.width), `${locale} ${width}px full width`).toBeLessThanOrEqual(1);
          expect(geometry.button.y, `${locale} ${width}px stacked action`).toBeGreaterThanOrEqual(geometry.input.bottom + 7);
          expect(geometry.button.width, `${locale} ${width}px touch target`).toBeGreaterThanOrEqual(44);
        } else {
          expect(Math.abs(geometry.input.y - geometry.button.y), `${locale} ${width}px inline alignment`).toBeLessThanOrEqual(1);
          expect(geometry.button.x, `${locale} ${width}px inline order`).toBeGreaterThanOrEqual(geometry.input.right);
        }
      }

      await input.focus();
      await expect(input).toBeFocused();
      await page.keyboard.press('Tab');
      await expect(button).toBeFocused();
      expect(pageErrors, `${locale} page errors`).toEqual([]);
    } catch (error) {
      failures.push(`${locale}: ${error.message}`);
    } finally {
      await page.close();
    }
  }

  await browser.close();
  expect(failures).toEqual([]);
});

test('track submit button keeps opaque background and WCAG contrast at 375 and 1280 (DCP-195 B1)', async ({ page }) => {
  const buttonSelector = '.dc-ticket-track__form button[type="submit"]';

  for (const width of CONTRAST_WIDTHS) {
    await page.setViewportSize({ width, height: 900 });
    const response = await page.goto(`${BASE}/it/tickets/track`, {
      waitUntil: 'domcontentloaded',
      timeout: 30000,
    });
    expect(response?.status(), `${width}px response`).toBe(200);

    const button = page.locator(buttonSelector);
    await expect(button).toBeVisible();

    const colors = await effectiveButtonColors(page, buttonSelector);
    expect(colors, `${width}px computed colors`).not.toBeNull();
    expect(
      colors.rawBackground === 'rgba(0, 0, 0, 0)' || /\/\s*0\s*\)/.test(colors.rawBackground),
      `${width}px button background must not be transparent (${colors.rawBackground})`,
    ).toBe(false);
    expect(contrastRatio(colors.foreground, colors.background), `${width}px contrast ratio`).toBeGreaterThanOrEqual(4.5);
  }
});
