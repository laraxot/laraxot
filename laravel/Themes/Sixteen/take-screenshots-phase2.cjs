const { chromium } = require('./node_modules/playwright');
const path = require('path');
const fs = require('fs');

const BASE_URL = 'http://127.0.0.1:8000';
const REF_URL = 'https://italia.github.io/design-comuni-pagine-statiche/sito';
const OUTPUT_DIR = '/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/docs/html-compare/segnalazioni-elenco/screenshots';

const viewports = [
  { name: 'desktop', width: 1440, height: 900 },
  { name: 'tablet', width: 768, height: 1024 },
  { name: 'mobile', width: 375, height: 667 }
];

const targets = [
  { name: 'local', url: `${BASE_URL}/it/tests/segnalazioni-elenco` },
  { name: 'reference', url: `${REF_URL}/segnalazioni-elenco.html` }
];

async function capture(browser, target, viewport) {
  const page = await browser.newPage();
  await page.setViewportSize({ width: viewport.width, height: viewport.height });
  
  const filename = `${target.name}-${viewport.name}.png`;
  const fullPath = path.join(OUTPUT_DIR, filename);
  
  console.log(`📸 Capturing ${target.name} at ${viewport.name}...`);
  
  try {
    await page.goto(target.url, { waitUntil: 'networkidle', timeout: 60000 });
    // Aspetta un po' per eventuali animazioni o caricamento font
    await page.waitForTimeout(2000);
    
    await page.screenshot({ path: fullPath, fullPage: true });
    console.log(`   ✅ Saved ${filename}`);
  } catch (err) {
    console.error(`   ❌ Error capturing ${target.name}: ${err.message}`);
  } finally {
    await page.close();
  }
}

(async () => {
  if (!fs.existsSync(OUTPUT_DIR)) {
    fs.mkdirSync(OUTPUT_DIR, { recursive: true });
  }

  const browser = await chromium.launch({ headless: true });
  
  for (const viewport of viewports) {
    for (const target of targets) {
      await capture(browser, target, viewport);
    }
  }

  await browser.close();
  console.log('\n✨ All captures complete!');
})();
