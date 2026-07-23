import { chromium } from 'playwright';

const browser = await chromium.launch({ headless: true });
const context = await browser.newContext({ viewport: { width: 1280, height: 900 } });
const page = await context.newPage();

await page.goto('http://127.0.0.1:8000/it/tests/segnalazione-crea?step=form.privacy', { waitUntil: 'networkidle', timeout: 30000 });
await page.waitForTimeout(3000);

// Get stepper step content
const stepperHtml = await page.$eval('.stepper-step.active', el => el.outerHTML).catch(() => 'NOT FOUND');
console.log('Active stepper step HTML:\n', stepperHtml);

// Get ALL forms and their content
const allContent = await page.evaluate(() => {
    const wizard = document.querySelector('.fi-sc-wizard');
    const forms = wizard.querySelectorAll('form');
    return Array.from(forms).map((f, i) => ({
        index: i,
        className: f.className,
        maxWidth: getComputedStyle(f).maxWidth,
        childCount: f.children.length,
        innerHTML: f.innerHTML.substring(0, 300),
    }));
});

console.log('\nAll forms in wizard:', JSON.stringify(allContent, null, 2));

// Find visible content
const visibleContent = await page.evaluate(() => {
    const allElements = document.querySelectorAll('.fi-sc-wizard *');
    let visibleElements = [];
    allElements.forEach(el => {
        const style = getComputedStyle(el);
        if (style.display !== 'none' && style.visibility !== 'hidden') {
            const text = el.innerText?.trim();
            if (text && text.length > 10 && text.length < 500) {
                visibleElements.push({
                    tag: el.tagName,
                    class: el.className,
                    text: text.substring(0, 100)
                });
            }
        }
    });
    return visibleElements.slice(0, 20);
});

console.log('\nVisible elements:', JSON.stringify(visibleContent, null, 2));

await browser.close();
