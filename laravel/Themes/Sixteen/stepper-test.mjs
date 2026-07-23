import { chromium } from 'playwright';

const browser = await chromium.launch({ headless: true });

console.log('\n=== TESTING: http://127.0.0.1:8000/it/tests/segnalazione-crea?step=form.privacy ===\n');

const context = await browser.newContext({ viewport: { width: 1280, height: 800 } });
const page = await context.newPage();

try {
  await page.goto('http://127.0.0.1:8000/it/tests/segnalazione-crea?step=form.privacy', { waitUntil: 'networkidle', timeout: 30000 });
  await page.waitForTimeout(2000);
  
  // Get stepper HTML
  const stepperHtml = await page.$eval('.steppers', el => el.outerHTML).catch(() => 'NOT FOUND');
  console.log('STEPPER HTML:\n', stepperHtml);
  
  // Get stepper computed styles
  const stepperStyles = await page.evaluate(() => {
    const steppers = document.querySelector('.steppers');
    const stepperStep = document.querySelector('.stepper-step');
    const steppersIndex = document.querySelector('.steppers-index');
    const header = document.querySelector('.steppers-header');
    const activeItem = document.querySelector('.stepper-step.active');
    
    return {
      steppers: steppers ? {
        backgroundColor: getComputedStyle(steppers).backgroundColor,
        padding: getComputedStyle(steppers).padding,
        borderBottom: getComputedStyle(steppers).borderBottom,
      } : null,
      stepperStep: stepperStep ? {
        color: getComputedStyle(stepperStep).color,
        fontWeight: getComputedStyle(stepperStep).fontWeight,
        fontSize: getComputedStyle(stepperStep).fontSize,
        className: stepperStep.className,
      } : null,
      activeItem: activeItem ? {
        color: getComputedStyle(activeItem).color,
        fontWeight: getComputedStyle(activeItem).fontWeight,
        className: activeItem.className,
      } : null,
      steppersIndex: steppersIndex ? {
        backgroundColor: getComputedStyle(steppersIndex).backgroundColor,
        color: getComputedStyle(steppersIndex).color,
        fontSize: getComputedStyle(steppersIndex).fontSize,
        borderRadius: getComputedStyle(steppersIndex).borderRadius,
        padding: getComputedStyle(steppersIndex).padding,
      } : null,
      header: header ? {
        display: getComputedStyle(header).display,
        justifyContent: getComputedStyle(header).justifyContent,
        maxWidth: getComputedStyle(header).maxWidth,
      } : null,
    };
  });
  
  console.log('\nSTEPPER COMPUTED STYLES:\n', JSON.stringify(stepperStyles, null, 2));
  
  // Check GDPR text
  const gdprText = await page.evaluate(() => {
    const el = document.querySelector('.fi-sc-wizard-step.fi-active');
    return el ? el.innerText.substring(0, 800) : 'NOT FOUND';
  });
  console.log('\nGDPR TEXT (first 800 chars):\n', gdprText);
  
  // Check form width
  const formStyles = await page.evaluate(() => {
    const form = document.querySelector('.fi-sc-wizard form');
    return form ? {
      maxWidth: getComputedStyle(form).maxWidth,
      width: getComputedStyle(form).width,
      margin: getComputedStyle(form).margin,
      padding: getComputedStyle(form).padding,
    } : null;
  });
  console.log('\nFORM STYLES:\n', JSON.stringify(formStyles, null, 2));
  
} catch (err) {
  console.log('ERROR:', err.message);
}

await browser.close();
console.log('\n=== TEST COMPLETE ===\n');
