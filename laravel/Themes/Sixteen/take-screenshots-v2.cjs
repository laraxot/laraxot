const { chromium } = require('./node_modules/playwright');
const path = require('path');

(async () => {
  const outputDir = '/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/docs/screenshots/homepage';
  const browser = await chromium.launch({ headless: true });
  
  const page = await browser.newPage();
  await page.setViewportSize({ width: 1440, height: 900 });
  await page.goto('http://127.0.0.1:8000/it/tests/homepage', { waitUntil: 'load', timeout: 30000 });
  await page.waitForTimeout(2000);
  
  await page.screenshot({ 
    path: path.join(outputDir, 'local-after-fix-full-2026-04-07.png'),
    fullPage: true
  });
  await page.screenshot({ 
    path: path.join(outputDir, 'local-after-fix-viewport-2026-04-07.png'),
    fullPage: false
  });
  console.log('Done!');
  await browser.close();
})().catch(console.error);
