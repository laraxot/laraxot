const { chromium } = require('playwright');
const { AxeBuilder } = require('@axe-core/playwright');

async function runAccessibilityTest(url) {
  console.log(`\n🔍 Testing: ${url}\n`);
  
  const browser = await chromium.launch();
  const context = await browser.newContext();
  const page = await context.newPage();
  
  try {
    await page.goto(url, { waitUntil: 'networkidle', timeout: 30000 });
    
    const accessibilityScanResults = await new AxeBuilder({ page, context })
      .withTags(['wcag2a', 'wcag2aa', 'best-practice'])
      .analyze();
    
    const violations = accessibilityScanResults.violations;
    
    if (violations.length === 0) {
      console.log('✅ No accessibility violations found!');
    } else {
      console.log(`❌ Found ${violations.length} accessibility violations:\n`);
      
      violations.forEach((violation, i) => {
        console.log(`${i + 1}. [${violation.impact}] ${violation.id}`);
        console.log(`   Description: ${violation.description}`);
        console.log(`   Help: ${violation.helpUrl}`);
        console.log(`   Elements affected: ${violation.nodes.length}`);
        violation.nodes.forEach((node, j) => {
          if (j < 3) {
            console.log(`   - ${node.html.substring(0, 100)}`);
          }
        });
        console.log('');
      });
    }
    
    return violations.length;
    
  } catch (error) {
    console.error('Error testing page:', error.message);
    return -1;
  } finally {
    await context.close();
    await browser.close();
  }
}

async function main() {
  const pages = [
    'https://sottana.net/it',
    'https://sottana.net/it/contatti',
    'https://sottana.net/it/servizi',
    'https://sottana.net/it/about',
  ];
  
  let totalViolations = 0;
  
  for (const url of pages) {
    const violations = await runAccessibilityTest(url);
    if (violations >= 0) {
      totalViolations += violations;
    }
  }
  
  console.log(`\n📊 Total violations found: ${totalViolations}`);
}

main();
