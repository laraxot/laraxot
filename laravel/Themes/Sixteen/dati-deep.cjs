const { chromium } = require('playwright');
const OUTPUT = '/var/www/_bases/base_fixcity_fila5/bashscripts/compare-html/output';

(async () => {
  const browser = await chromium.launch({ headless: true });
  const context = await browser.newContext({ viewport: { width: 1440, height: 900 } });
  const page = await context.newPage();

  console.log('🔍 DEEP DIVE: segnalazione-02-dati\n');

  // Capture full pages
  for (const [label, url] of [
    ['REF', 'https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-02-dati.html'],
    ['LOC', 'http://127.0.0.1:8000/it/tests/segnalazione-02-dati']
  ]) {
    console.log(`📸 ${label} full page...`);
    await page.goto(url, { waitUntil: 'networkidle', timeout: 30000 });
    await page.waitForTimeout(1500);
    await page.screenshot({ path: `${OUTPUT}/dati-${label}-full.png`, fullPage: true });
    
    // Capture key sections
    const sections = [
      { sel: '.cmp-breadcrumbs', name: 'breadcrumbs' },
      { sel: '.steppers', name: 'steppers' },
      { sel: '#report-place', name: 'section-place' },
      { sel: '#report-info', name: 'section-info' },
      { sel: '#report-author', name: 'section-author' },
      { sel: '.steppers-nav', name: 'nav-buttons' },
      { sel: '.bg-grey-card', name: 'contacts-card' },
      { sel: '.upload-wrapper, .btn-wrapper', name: 'upload-section' },
    ];
    
    for (const s of sections) {
      try {
        const el = await page.$(s.sel);
        if (el) {
          await el.screenshot({ path: `${OUTPUT}/dati-${label}-${s.name}.png` });
        }
      } catch(e) {}
    }
  }

  // Detailed computed styles comparison
  console.log('\n📊 DETAILED COMPUTED STYLES:\n');
  
  const allStyles = {};
  for (const [label, url] of [
    ['REF', 'https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-02-dati.html'],
    ['LOC', 'http://127.0.0.1:8000/it/tests/segnalazione-02-dati']
  ]) {
    await page.goto(url, { waitUntil: 'networkidle', timeout: 30000 });
    await page.waitForTimeout(1500);
    
    allStyles[label] = await page.evaluate(() => {
      const results = {};
      const selectors = [
        'body',
        'h1',
        'h2.title-xxlarge',
        '.steppers-header li.active',
        '.steppers-header ul li',
        '.steppers-index',
        '#report-place h2',
        '#report-info h2',
        '#report-author h2',
        '.card.has-bkg-grey',
        '.card-header',
        '.card-body',
        'select#inefficiency',
        'input#title',
        'textarea#details',
        '.btn-wrapper .btn-primary',
        '.btn-wrapper p',
        '.steppers-nav .steppers-btn-prev',
        '.steppers-nav .steppers-btn-save',
        '.steppers-nav .steppers-btn-confirm',
        '.cmp-contacts .card',
        '.cmp-contacts .card-body',
        '.cmp-contacts h2',
        '.contact-list .list-item',
      ];
      
      for (const sel of selectors) {
        const el = document.querySelector(sel);
        if (el) {
          const cs = getComputedStyle(el);
          results[sel] = {
            fontFamily: cs.fontFamily.substring(0, 50),
            fontSize: cs.fontSize,
            fontWeight: cs.fontWeight,
            lineHeight: cs.lineHeight,
            color: cs.color,
            bg: cs.backgroundColor,
            padding: cs.padding.substring(0, 30),
            marginBottom: cs.marginBottom,
            borderRadius: cs.borderRadius,
            boxShadow: cs.boxShadow.substring(0, 40),
          };
        }
      }
      return results;
    });
  }

  // Compare
  let total = 0, match = 0;
  const diffs = [];
  
  for (const sel of Object.keys(allStyles.REF)) {
    if (!allStyles.LOC[sel]) {
      diffs.push(`  ❌ ${sel}: MISSING in local`);
      continue;
    }
    for (const prop of ['fontFamily', 'fontSize', 'fontWeight', 'lineHeight', 'color', 'bg']) {
      total++;
      const ref = allStyles.REF[sel]?.[prop] || '';
      const loc = allStyles.LOC[sel]?.[prop] || '';
      const normRef = ref.replace(/\s+/g, ' ').trim();
      const normLoc = loc.replace(/\s+/g, ' ').trim();
      const isMatch = normRef === normLoc || 
        (prop === 'fontFamily' && (normLoc.includes('Titillium') || normLoc.includes('Geneva'))) ||
        (prop === 'color' && normRef === 'rgb(25, 25, 25)' && normLoc === 'rgb(26, 26, 26)') ||
        (prop === 'bg' && (normRef === 'rgba(0, 0, 0, 0)' || normRef === 'transparent') && 
                      (normLoc === 'rgba(0, 0, 0, 0)' || normLoc === 'transparent'));
      if (isMatch) {
        match++;
      } else {
        diffs.push(`  ⚠️ ${sel}.${prop}: "${normRef}" → "${normLoc}"`);
      }
    }
  }
  
  const pct = total > 0 ? Math.round(match/total*100) : 0;
  console.log(`Match: ${match}/${total} (${pct}%)\n`);
  if (diffs.length > 0) {
    console.log('Differences:');
    diffs.slice(0, 30).forEach(d => console.log(d));
    if (diffs.length > 30) console.log(`  ... and ${diffs.length - 30} more`);
  }

  await browser.close();
  console.log('\n✅ Done!');
})();
