import { chromium } from 'playwright';

const browser = await chromium.launch({ headless: true });
const context = await browser.newContext({ viewport: { width: 1280, height: 900 } });
const page = await context.newPage();

await page.goto('http://127.0.0.1:8000/it/tests/segnalazione-crea?step=form.privacy', { waitUntil: 'networkidle', timeout: 30000 });
await page.waitForTimeout(3000);

// Get full HTML to see the structure
const structure = await page.evaluate(() => {
    const wizard = document.querySelector('.fi-sc-wizard');
    if (!wizard) return 'NOT FOUND';
    
    return {
        wizardClass: wizard.className,
        forms: Array.from(wizard.querySelectorAll('form')).map(f => ({
            className: f.className,
            maxWidth: getComputedStyle(f).maxWidth,
            parentClass: f.parentElement?.className,
        })),
        steps: Array.from(wizard.querySelectorAll('.fi-sc-wizard-step')).map(s => ({
            className: s.className,
            display: getComputedStyle(s).display,
            style: s.getAttribute('style'),
        })),
    };
});

console.log('Wizard structure:', JSON.stringify(structure, null, 2));

// Check if GDPR content is in any step
const gdprContent = await page.evaluate(() => {
    const steps = document.querySelectorAll('.fi-sc-wizard-step');
    let gdprFound = false;
    let content = '';
    
    steps.forEach((step, i) => {
        const text = step.innerText;
        if (text.includes('GDPR') || text.includes('privacy') || text.includes('Regolamento')) {
            gdprFound = true;
            content = text.substring(0, 500);
        }
    });
    
    return { gdprFound, content };
});

console.log('\nGDPR content:', JSON.stringify(gdprContent, null, 2));

await browser.close();
