import { chromium } from 'playwright';

const browser = await chromium.launch({ headless: true });
const context = await browser.newContext({ viewport: { width: 1280, height: 900 } });
const page = await context.newPage();

await page.goto('http://127.0.0.1:8000/it/tests/segnalazione-crea?step=form.privacy', { waitUntil: 'networkidle', timeout: 30000 });
await page.waitForTimeout(3000);

// Full HTML of the wizard
const html = await page.evaluate(() => {
    const wizard = document.querySelector('.fi-sc-wizard');
    return wizard ? wizard.outerHTML.substring(0, 3000) : 'NOT FOUND';
});

console.log('\n=== WIZARD HTML (first 3000 chars) ===\n');
console.log(html);

await browser.close();
