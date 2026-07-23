const { chromium } = require('playwright');

const PAGES = [
  'segnalazione-area-personale',
  'segnalazioni-elenco',
  'segnalazione-dettaglio',
  'segnalazione-01-privacy',
  'segnalazione-02-dati',
  'segnalazione-03-riepilogo',
  'segnalazione-04-conferma',
];

const OUTPUT = '/var/www/_bases/base_fixcity_fila5/bashscripts/compare-html/output';
const REF_BASE = 'https://italia.github.io/design-comuni-pagine-statiche/sito';
const LOC_BASE = 'http://127.0.0.1:8000/it/tests';

const SELECTORS = {
  'body': ['fontFamily', 'fontSize', 'fontWeight', 'color', 'backgroundColor'],
  'h1, .title-xxxlarge': ['fontSize', 'lineHeight', 'fontWeight', 'color'],
  'h2, .title-xxlarge': ['fontSize', 'lineHeight', 'fontWeight', 'color'],
  'button.btn-primary': ['fontSize', 'fontWeight', 'backgroundColor', 'color'],
  '.card': ['backgroundColor', 'borderRadius', 'boxShadow'],
  'a': ['color'],
};

(async () => {
  const browser = await chromium.launch({ headless: true });
  const context = await browser.newContext({ viewport: { width: 1440, height: 900 } });
  const page = await context.newPage();

  const results = [];

  for (const slug of PAGES) {
    console.log(`\n🔍 ${slug}...`);
    const refStyles = {};
    const locStyles = {};
    
    for (const [urlPrefix, styles] of [[REF_BASE, refStyles], [LOC_BASE, locStyles]]) {
      const url = `${urlPrefix}/${slug}.html`;
      const altUrl = `${urlPrefix}/${slug}`;
      
      try {
        await page.goto(url, { waitUntil: 'networkidle', timeout: 30000 });
      } catch {
        try {
          await page.goto(altUrl, { waitUntil: 'networkidle', timeout: 30000 });
        } catch(e) {
          console.log(`  ❌ ${urlPrefix} failed: ${e.message.substring(0, 60)}`);
          continue;
        }
      }
      await page.waitForTimeout(1500);

      for (const [selector, props] of Object.entries(SELECTORS)) {
        const el = await page.$(selector);
        if (el) {
          const cs = await el.evaluate((el, props) => {
            const s = getComputedStyle(el);
            const result = {};
            for (const p of props) {
              result[p] = s[p];
            }
            return result;
          }, props);
          styles[selector] = cs;
        }
      }
    }

    // Compare
    let totalProps = 0;
    let matchProps = 0;
    const diffs = [];

    for (const [selector, props] of Object.entries(SELECTORS)) {
      if (!refStyles[selector] || !locStyles[selector]) continue;
      for (const prop of props) {
        totalProps++;
        const ref = refStyles[selector]?.[prop] || '';
        const loc = locStyles[selector]?.[prop] || '';
        // Normalize font-family comparison
        const normRef = ref.replace(/\s+/g, ' ').trim();
        const normLoc = loc.replace(/\s+/g, ' ').trim();
        const isMatch = normRef === normLoc || 
          (prop === 'fontFamily' && normLoc.includes('Titillium'));
        if (isMatch) {
          matchProps++;
        } else {
          diffs.push(`${selector}.${prop}: "${ref}" → "${loc}"`);
        }
      }
    }

    const pct = totalProps > 0 ? Math.round(matchProps / totalProps * 100) : 0;
    results.push({ slug, pct, diffs: diffs.slice(0, 8), totalProps, matchProps });
    console.log(`  📊 ${pct}% (${matchProps}/${totalProps} match)`);
    if (diffs.length > 0 && diffs.length <= 8) {
      diffs.forEach(d => console.log(`    ⚠️ ${d}`));
    } else if (diffs.length > 8) {
      console.log(`    ⚠️ ... and ${diffs.length - 8} more`);
    }
  }

  // Summary table
  console.log('\n' + '='.repeat(80));
  console.log('CSS PARITY SUMMARY');
  console.log('='.repeat(80));
  console.log('Page                        | Match % | Status');
  console.log('-'.repeat(80));
  for (const r of results) {
    const status = r.pct >= 90 ? '✅ READY' : r.pct >= 70 ? '⚠️ Needs work' : '❌ Major gaps';
    console.log(`${r.slug.padEnd(28)}| ${String(r.pct + '%').padStart(7)} | ${status}`);
  }

  await browser.close();
})();
