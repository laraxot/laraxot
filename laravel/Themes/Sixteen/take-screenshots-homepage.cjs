const { chromium } = require('./node_modules/playwright');
const path = require('path');
const fs = require('fs');

(async () => {
  const outputDir = '/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/docs/screenshots/homepage';
  fs.mkdirSync(outputDir, { recursive: true });
  
  const browser = await chromium.launch({ headless: true });
  
  const pages = [
    {
      url: 'https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html',
      name: 'reference'
    },
    {
      url: 'http://127.0.0.1:8000/it/tests/homepage',
      name: 'local'
    }
  ];
  
  for (const { url, name } of pages) {
    console.log(`Capturing ${name}: ${url}`);
    
    // Desktop full page
    const page = await browser.newPage();
    await page.setViewportSize({ width: 1440, height: 900 });
    try {
      await page.goto(url, { waitUntil: 'networkidle', timeout: 30000 });
    } catch(e) {
      await page.goto(url, { waitUntil: 'load', timeout: 30000 });
    }
    await page.waitForTimeout(2000);
    
    await page.screenshot({ 
      path: path.join(outputDir, `${name}-full-2026-04-07.png`),
      fullPage: true
    });
    console.log(`  Saved: ${name}-full-2026-04-07.png`);
    
    await page.screenshot({ 
      path: path.join(outputDir, `${name}-viewport-2026-04-07.png`),
      fullPage: false
    });
    console.log(`  Saved: ${name}-viewport-2026-04-07.png`);
    
    await page.close();
    
    // Mobile
    const mobilePage = await browser.newPage();
    await mobilePage.setViewportSize({ width: 375, height: 812 });
    try {
      await mobilePage.goto(url, { waitUntil: 'networkidle', timeout: 30000 });
    } catch(e) {
      await mobilePage.goto(url, { waitUntil: 'load', timeout: 30000 });
    }
    await mobilePage.waitForTimeout(1000);
    await mobilePage.screenshot({ 
      path: path.join(outputDir, `${name}-mobile-2026-04-07.png`),
      fullPage: false
    });
    console.log(`  Saved: ${name}-mobile-2026-04-07.png`);
    await mobilePage.close();
  }
  
  await browser.close();
  console.log('All screenshots captured!');
})().catch(err => {
  console.error('Error:', err.message);
  process.exit(1);
});
