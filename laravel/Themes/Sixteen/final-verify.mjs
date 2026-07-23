import { chromium } from 'playwright';

const browser = await chromium.launch({ headless: true });
const context = await browser.newContext({ viewport: { width: 1280, height: 900 } });
const page = await context.newPage();

await page.goto('http://127.0.0.1:8000/it/tests/segnalazione-crea?step=form.privacy', { waitUntil: 'networkidle', timeout: 30000 });
await page.waitForTimeout(3000);

// Final verification checklist
const results = await page.evaluate(() => {
    const results = {
        stepper: {},
        form: {},
        content: {}
    };
    
    // Stepper labels
    const stepLabels = Array.from(document.querySelectorAll('.stepper-step')).map(s => s.innerText.split('\n')[0].trim());
    results.stepper.labels = stepLabels;
    results.stepper.labelsCorrect = stepLabels.includes('Autorizzazioni e condizioni');
    
    // Active step color
    const activeStep = document.querySelector('.stepper-step.active');
    if (activeStep) {
        const color = getComputedStyle(activeStep).color;
        results.stepper.activeColor = color;
        results.stepper.activeColorCorrect = color === 'rgb(0, 122, 82)';
    }
    
    // Inactive step color
    const inactiveStep = document.querySelector('.stepper-step:not(.active)');
    if (inactiveStep) {
        results.stepper.inactiveColor = getComputedStyle(inactiveStep).color;
    }
    
    // Step width (the actual step content)
    const wizardSteps = document.querySelectorAll('.fi-sc-wizard-step');
    results.form.stepMaxWidth = Array.from(wizardSteps).map(s => getComputedStyle(s).maxWidth);
    
    // GDPR content
    const checkbox = document.querySelector('input[type="checkbox"]');
    results.content.gdprCheckboxExists = !!checkbox;
    if (checkbox) {
        const label = checkbox.closest('.fi-fo-field')?.querySelector('.fi-fo-field-label')?.innerText;
        results.content.gdprLabel = label;
    }
    
    // Visible step content
    const visibleForm = Array.from(wizardSteps).find(s => getComputedStyle(s).display !== 'none');
    results.form.visibleStep = visibleForm ? {
        maxWidth: getComputedStyle(visibleForm).maxWidth,
        hasContent: visibleForm.innerHTML.length > 100
    } : null;
    
    return results;
});

console.log('\n=== FINAL VERIFICATION RESULTS ===\n');
console.log(JSON.stringify(results, null, 2));

// Summary
console.log('\n=== SUMMARY ===');
console.log('Stepper Labels:', results.stepper.labelsCorrect ? '✓ PASS' : '✗ FAIL');
console.log('Active Step Color:', results.stepper.activeColorCorrect ? '✓ PASS (green)' : '✗ FAIL');
console.log('Step Content Width:', results.form.stepMaxWidth[0] === '720px' ? '✓ PASS (720px)' : '✗ FAIL');
console.log('GDPR Checkbox:', results.content.gdprCheckboxExists ? '✓ PASS' : '✗ FAIL');

await browser.close();
