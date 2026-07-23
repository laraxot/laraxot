const { chromium } = require('./node_modules/playwright');
const path = require('path');

const BASE_URL = process.env.BASE_URL || 'http://127.0.0.1:8000';
const OUTPUT_DIR = process.env.OUTPUT_DIR || '/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/docs/screenshots/segnalazioni-elenco';

const pages = [
  {
    slug: 'segnalazioni-elenco',
    url: `${BASE_URL}/it/tests/segnalazioni-elenco`,
    name: 'segnalazioni-elenco'
  }
];

async function takeScreenshot(page, url, outputPath, name) {
  console.log(`📸 Taking screenshot: ${name}`);
  console.log(`   URL: ${url}`);
  
  try {
    await page.goto(url, { waitUntil: 'domcontentloaded', timeout: 30000 });
    await page.waitForTimeout(3000);
    
    await page.screenshot({ 
      path: path.join(outputPath, `${name}-full.png`),
      fullPage: true
    });
    
    await page.screenshot({ 
      path: path.join(outputPath, `${name}-viewport.png`),
      fullPage: false
    });
    
    console.log(`   ✅ Saved to ${outputPath}`);
  } catch (error) {
    console.error(`   ❌ Error: ${error.message}`);
  }
}

(async () => {
  console.log('🚀 Starting screenshot capture...\n');
  
  const fs = require('fs');
  if (!fs.existsSync(OUTPUT_DIR)) {
    fs.mkdirSync(OUTPUT_DIR, { recursive: true });
    console.log(`📁 Created output directory: ${OUTPUT_DIR}\n`);
  }
  
  const browser = await chromium.launch({ headless: true });
  const page = await browser.newPage();
  await page.setViewportSize({ width: 1440, height: 900 });
  
  for (const p of pages) {
    await takeScreenshot(page, p.url, OUTPUT_DIR, p.name);
  }
  
  console.log('\n✅ Done!');
  await browser.close();
})().catch(console.error);
