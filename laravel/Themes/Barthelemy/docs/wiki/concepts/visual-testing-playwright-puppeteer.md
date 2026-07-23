---
title: Visual Testing con Playwright e Puppeteer — Tema Barthelemy
type: concept
sources:
  - https://playwright.dev/docs/test-snapshots
  - https://playwright-php.dev/
  - https://www.browserstack.com/guide/playwright-vs-puppeteer
confidence: high
created: 2026-05-04
updated: 2026-05-04
tags: [visual-testing, playwright, puppeteer, e2e, screenshot, laravel, theme, Barthelemy]
related:
  - playwright-testing-policy
---

# Visual Testing con Playwright e Puppeteer — Tema Barthelemy

## Panoramica

Il testing visivo permette di rilevare regressioni UI nel tema Barthelemy confrontando screenshot di riferimento con screenshot attuali.

Per la policy di collocazione: vedi [Playwright Testing Policy](../../../../docs/wiki/concepts/playwright-testing-policy.md).

## Collocazione Test

```
laravel/Themes/Barthelemy/tests/Playwright/
```

## Playwright vs Puppeteer

| Criterio | Playwright | Puppeteer |
|----------|-----------|-----------|
| **Browser** | Chromium, Firefox, WebKit | Chrome/Chromium |
| **Auto-wait** | Integrato | Manuale |
| **Uso consigliato** | Standard progetto | Task Chrome-only |

## Esempio Base

```javascript
// laravel/Themes/Barthelemy/tests/Playwright/visual-regression.spec.js
import { test, expect } from '@playwright/test';

test('Barthelemy theme renders correctly', async ({ page }) => {
    await page.goto('/it/');
    await page.waitForLoadState('networkidle');
    await expect(page).toHaveScreenshot('Barthelemy-homepage.png', {
        maxDiffPixelRatio: 0.02,
        animations: 'disabled'
    });
});
```

## Best Practices

1. **Atomici**: Screenshot di singoli componenti
2. **Stabile**: `maxDiffPixelRatio: 0.02` per tolleranza sub-pixel
3. **Mascherare dinamici**: Usa `mask` per contenuti variabili
4. **Animazioni**: Disabilita con `animations: 'disabled'`
5. **CI/CD**: Esegui in ambiente Docker consistente

## Risorse

- [Playwright Docs](https://playwright.dev/)
- [Visual Control Mastery](../../../../docs/wiki/concepts/visual-control-mastery.md)
- [Playwright Testing Policy](../../../../docs/wiki/concepts/playwright-testing-policy.md)
