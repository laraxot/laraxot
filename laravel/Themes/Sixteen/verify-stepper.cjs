const { chromium } = require('./node_modules/playwright');
const path = require('path');
const fs = require('fs');

const BASE_URL = 'http://127.0.0.1:8000';
const OUTPUT_DIR = path.join(__dirname, 'docs/screenshots/stepper-verification');

const viewports = [
  { name: 'desktop', width: 1280, height: 800 },
  { name: 'tablet', width: 768, height: 1024 },
  { name: 'mobile', width: 375, height: 667 }
];

async function takeScreenshot(browser, url, viewport) {
  const page = await browser.newPage();
  await page.setViewportSize({ width: viewport.width, height: viewport.height });
  
  console.log(`📸 Taking screenshot for ${viewport.name} (${viewport.width}x${viewport.height})`);
  
  try {
    await page.goto(url, { waitUntil: 'networkidle', timeout: 30000 });
    // Wait for the stepper
    await page.waitForSelector('.steppers', { timeout: 10000 });
    
    const outputPath = path.join(OUTPUT_DIR, `stepper-${viewport.name}.png`);
    await page.screenshot({ path: outputPath, fullPage: false });
    console.log(`   ✅ Saved to ${outputPath}`);
  } catch (error) {
    console.error(`   ❌ Error: ${error.message}`);
  } finally {
    await page.close();
  }
}

(async () => {
  if (!fs.existsSync(OUTPUT_DIR)) {
    fs.mkdirSync(OUTPUT_DIR, { recursive: true });
  }
  
  const browser = await chromium.launch({ headless: true });
  const url = `${BASE_URL}/it/tests/segnalazione-02-dati`;
  
  for (const viewport of viewports) {
    await takeScreenshot(browser, url, viewport);
  }
  
  await browser.close();
  console.log('\n✅ Verification complete!');
})().catch(console.error);
