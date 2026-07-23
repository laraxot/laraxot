/**
 * Quick computed styles comparison
 * Focus on font-family, colors, spacing for segnalazione-01-privacy
 */

const { chromium } = require('playwright');
const fs = require('fs');

const OUTPUT_DIR = '/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/docs/visual-analysis/segnalazione-01-privacy';

(async () => {
  const browser = await chromium.launch({ headless: true });
  const context = await browser.newContext({ viewport: { width: 1440, height: 900 } });
  const page = await context.newPage();
  
  async function getStyles(url, label) {
    await page.goto(url, { waitUntil: 'domcontentloaded', timeout: 30000 });
    await page.waitForTimeout(1000);
    
    return await page.evaluate(() => {
      const get = (sel) => {
        const el = document.querySelector(sel);
        return el ? window.getComputedStyle(el) : null;
      };
      
      const extract = (style, props) => {
        if (!style) return null;
        const result = {};
        props.forEach(p => { result[p] = style[p]; });
        return result;
      };
      
      const fontProps = ['fontFamily', 'fontSize', 'fontWeight', 'lineHeight', 'letterSpacing'];
      const colorProps = ['color', 'backgroundColor', 'borderColor', 'borderTopColor'];
      const spacingProps = ['marginTop', 'marginBottom', 'paddingTop', 'paddingBottom', 'paddingLeft', 'paddingRight'];
      const boxProps = ['borderRadius', 'boxShadow', 'border', 'borderTop', 'borderBottom', 'maxWidth', 'width'];
      const allProps = [...fontProps, ...colorProps, ...spacingProps, ...boxProps];
      
      return {
        body: extract(get('body'), allProps),
        h1: extract(get('h1'), allProps),
        h2: extract(get('h2'), allProps),
        p: extract(get('p'), allProps),
        card: extract(get('.card'), allProps),
        btn: extract(get('.btn-primary'), allProps),
        container: extract(get('.container'), ['maxWidth', 'paddingLeft', 'paddingRight']),
        list: extract(get('.list-item'), allProps),
        link: extract(get('a'), allProps)
      };
    });
  }
  
  console.log('📐 Getting reference styles...\n');
  const refStyles = await getStyles('https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-01-privacy.html', 'REF');
  
  console.log('📐 Getting local styles...\n');
  const locStyles = await getStyles('http://127.0.0.1:8000/it/tests/segnalazione-01-privacy', 'LOC');
  
  // Compare
  function compare(element, ref, loc) {
    if (!ref || !loc) {
      console.log(`\n⚠️ ${element}: Missing data`);
      console.log(`   REF: ${ref ? 'OK' : 'NULL'}`);
      console.log(`   LOC: ${loc ? 'OK' : 'NULL'}`);
      return;
    }
    
    const diffs = [];
    for (const prop in ref) {
      if (ref[prop] !== loc[prop]) {
        diffs.push({ prop, ref: ref[prop], loc: loc[prop] });
      }
    }
    
    if (diffs.length > 0) {
      console.log(`\n❌ ${element}: ${diffs.length} differences`);
      diffs.forEach(d => {
        console.log(`   ${d.prop}:`);
        console.log(`     REF → ${d.ref}`);
        console.log(`     LOC → ${d.loc}`);
      });
    } else {
      console.log(`\n✅ ${element}: PERFECT MATCH`);
    }
  }
  
  console.log('=== STYLE COMPARISON ===\n');
  
  ['body', 'h1', 'h2', 'p', 'card', 'btn', 'container', 'list', 'link'].forEach(el => {
    compare(el, refStyles[el], locStyles[el]);
  });
  
  // Save for reference
  fs.writeFileSync(`${OUTPUT_DIR}/ref-styles.json`, JSON.stringify(refStyles, null, 2));
  fs.writeFileSync(`${OUTPUT_DIR}/loc-styles.json`, JSON.stringify(locStyles, null, 2));
  
  await browser.close();
})();
