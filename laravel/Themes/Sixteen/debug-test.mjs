import { chromium } from 'playwright';

const browser = await chromium.launch({ headless: true });
const context = await browser.newContext({ viewport: { width: 1280, height: 900 } });
const page = await context.newPage();

console.log('\n=== DETAILED DOM ANALYSIS ===\n');

await page.goto('http://127.0.0.1:8000/it/tests/segnalazione-crea?step=form.privacy', { waitUntil: 'networkidle', timeout: 30000 });
await page.waitForTimeout(3000);

// Get all relevant DOM elements
const analysis = await page.evaluate(() => {
    const results = {};
    
    // Main container
    const mainContainer = document.querySelector('.cmp-wizard-widget');
    results.mainContainer = mainContainer ? {
        className: mainContainer.className,
        maxWidth: getComputedStyle(mainContainer).maxWidth,
    } : null;
    
    // Wizard widget
    const wizardWidget = document.querySelector('.fi-sc-wizard');
    results.wizard = wizardWidget ? {
        className: wizardWidget.className,
        tagName: wizardWidget.tagName,
    } : null;
    
    // Stepper
    const steppers = document.querySelector('.steppers');
    results.stepper = steppers ? {
        className: steppers.className,
        children: Array.from(steppers.children).map(c => ({
            className: c.className,
            tagName: c.tagName
        }))
    } : null;
    
    // Form elements
    const forms = document.querySelectorAll('form');
    results.forms = Array.from(forms).map(f => ({
        className: f.className,
        maxWidth: getComputedStyle(f).maxWidth,
        width: getComputedStyle(f).width,
    }));
    
    // Wizard step content
    const activeStep = document.querySelector('.fi-sc-wizard-step.fi-active');
    results.activeStep = activeStep ? {
        className: activeStep.className,
        innerHTML: activeStep.innerHTML.substring(0, 500),
    } : null;
    
    // All wizard steps
    const allSteps = document.querySelectorAll('.fi-sc-wizard-step');
    results.allSteps = Array.from(allSteps).map(s => ({
        className: s.className,
        display: getComputedStyle(s).display,
    }));
    
    return results;
});

console.log('DOM Analysis:', JSON.stringify(analysis, null, 2));

await browser.close();
