const { chromium } = require('./node_modules/playwright');

async function run() {
  const browser = await chromium.launch({ headless: true });
  const page = await browser.newPage({ viewport: { width: 1440, height: 900 } });
  await page.goto('http://192.168.1.40:8000/it', { waitUntil: 'networkidle', timeout: 30000 }).catch(() => {});
  await page.waitForTimeout(3000);
  await page.screenshot({ path: '/tmp/homepage-fixed.png', fullPage: false });
  console.log('Screenshot saved to /tmp/homepage-fixed.png');
  await browser.close();
}

run().catch((e) => { console.error(e.message); process.exit(1); });
