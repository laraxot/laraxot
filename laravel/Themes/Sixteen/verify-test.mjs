import { chromium } from 'playwright';

const browser = await chromium.launch({ headless: true });
const context = await browser.newContext({ viewport: { width: 1280, height: 900 } });
const page = await context.newPage();

console.log('\n=== VERIFICATION TEST ===\n');

await page.goto('http://127.0.0.1:8000/it/tests/segnalazione-crea?step=form.privacy', { waitUntil: 'networkidle', timeout: 30000 });
await page.waitForTimeout(3000);

// Check form width
const formWidth = await page.evaluate(() => {
    const forms = document.querySelectorAll('form');
    return Array.from(forms).map(f => ({
        className: f.className,
        maxWidth: getComputedStyle(f).maxWidth,
        width: getComputedStyle(f).width,
    }));
});
console.log('Form widths:', JSON.stringify(formWidth, null, 2));

// Check step labels
const stepLabels = await page.evaluate(() => {
    const steps = document.querySelectorAll('.stepper-step');
    return Array.from(steps).map(s => s.innerText.trim());
});
console.log('Step labels:', stepLabels);

// Check active step
const activeStep = await page.evaluate(() => {
    const step = document.querySelector('.fi-sc-wizard-step:not([style*="display: none"])');
    return step ? step.className : 'ALL HIDDEN';
});
console.log('Active step:', activeStep);

// Check stepper colors
const stepperColors = await page.evaluate(() => {
    const active = document.querySelector('.stepper-step.active');
    const inactive = document.querySelector('.stepper-step:not(.active)');
    return {
        activeColor: active ? getComputedStyle(active).color : null,
        inactiveColor: inactive ? getComputedStyle(inactive).color : null,
        activeClass: active ? active.className : null,
        inactiveClass: inactive ? inactive.className : null,
    };
});
console.log('Stepper colors:', JSON.stringify(stepperColors, null, 2));

await browser.close();
console.log('\n=== TEST COMPLETE ===\n');
