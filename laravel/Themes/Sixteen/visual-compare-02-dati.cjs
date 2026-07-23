/**
 * Visual parity analysis for segnalazione-02-dati
 * Reference: https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-02-dati.html
 * Local: http://127.0.0.1:8000/it/tests/segnalazione-02-dati
 */

const { chromium } = require('playwright');
const fs = require('fs');
const path = require('path');

const OUTPUT_DIR = '/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/docs/visual-analysis/segnalazione-02-dati';

(async () => {
  console.log('🚀 Starting Visual Parity Analysis - segnalazione-02-dati\n');
  
  if (!fs.existsSync(OUTPUT_DIR)) {
    fs.mkdirSync(OUTPUT_DIR, { recursive: true });
  }
  
  const browser = await chromium.launch({ headless: true });
  const context = await browser.newContext({ viewport: { width: 1440, height: 900 } });
  const page = await context.newPage();
  
  // 1. Screenshots
  console.log('📸 Taking screenshots...');
  
  // Reference
  await page.goto('https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-02-dati.html', { waitUntil: 'domcontentloaded', timeout: 30000 });
  await page.waitForTimeout(2000);
  await page.screenshot({ path: path.join(OUTPUT_DIR, 'reference-full.png'), fullPage: true });
  await page.screenshot({ path: path.join(OUTPUT_DIR, 'reference-viewport.png'), fullPage: false });
  
  // Local
  await page.goto('http://127.0.0.1:8000/it/tests/segnalazione-02-dati', { waitUntil: 'domcontentloaded', timeout: 30000 });
  await page.waitForTimeout(2000);
  await page.screenshot({ path: path.join(OUTPUT_DIR, 'local-full.png'), fullPage: true });
  await page.screenshot({ path: path.join(OUTPUT_DIR, 'local-viewport.png'), fullPage: false });
  
  console.log('✅ Screenshots saved\n');
  
  // 2. Computed Styles
  console.log('📐 Analyzing computed styles...\n');
  
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
      
      const allProps = ['fontFamily', 'fontSize', 'fontWeight', 'lineHeight', 'color', 'backgroundColor', 'marginTop', 'marginBottom', 'paddingTop', 'paddingBottom', 'paddingLeft', 'paddingRight', 'borderRadius', 'boxShadow', 'border', 'maxWidth', 'width'];
      
      const result = {};
      ['body', 'h1', 'h2', 'h3', 'p', 'label', 'input', 'select', 'textarea', '.btn', '.btn-primary', '.card', '.card-body', '.form-group', '.form-check'].forEach(sel => {
        result[sel.replace(/[^a-z0-9]/gi, '-')] = extract(get(sel), allProps);
      });
      
      // Get all form inputs
      const inputs = document.querySelectorAll('input, select, textarea');
      result['all-inputs-count'] = inputs.length;
      
      // Get button details
      const btn = document.querySelector('.btn-primary');
      if (btn) {
        const s = window.getComputedStyle(btn);
        result['btn-primary'] = {
          fontFamily: s.fontFamily,
          fontSize: s.fontSize,
          fontWeight: s.fontWeight,
          lineHeight: s.lineHeight,
          backgroundColor: s.backgroundColor,
          color: s.color,
          padding: `${s.paddingTop} ${s.paddingRight} ${s.paddingBottom} ${s.paddingLeft}`,
          borderRadius: s.borderRadius,
          width: s.width
        };
      }
      
      return result;
    });
  }
  
  const refStyles = await getStyles('https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-02-dati.html', 'REF');
  const locStyles = await getStyles('http://127.0.0.1:8000/it/tests/segnalazione-02-dati', 'LOC');
  
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
      diffs.slice(0, 10).forEach(d => {
        console.log(`   ${d.prop}:`);
        console.log(`     REF → ${d.ref}`);
        console.log(`     LOC → ${d.loc}`);
      });
      if (diffs.length > 10) console.log(`   ... and ${diffs.length - 10} more`);
    } else {
      console.log(`\n✅ ${element}: PERFECT MATCH`);
    }
  }
  
  console.log('=== STYLE COMPARISON ===\n');
  
  Object.keys(refStyles).forEach(el => {
    if (el !== 'all-inputs-count') {
      compare(el, refStyles[el], locStyles[el]);
    }
  });
  
  // Save for reference
  fs.writeFileSync(path.join(OUTPUT_DIR, 'ref-styles.json'), JSON.stringify(refStyles, null, 2));
  fs.writeFileSync(path.join(OUTPUT_DIR, 'loc-styles.json'), JSON.stringify(locStyles, null, 2));
  
  // Count total diffs
  let totalDiffs = 0;
  let perfectMatches = 0;
  Object.keys(refStyles).forEach(el => {
    if (el === 'all-inputs-count') return;
    const ref = refStyles[el];
    const loc = locStyles[el];
    if (!ref || !loc) return;
    for (const prop in ref) {
      if (ref[prop] !== loc[prop]) totalDiffs++;
    }
    if (totalDiffs === 0) perfectMatches++;
  });
  
  console.log(`\n\n=== SUMMARY ===`);
  console.log(`Total style differences: ${totalDiffs}`);
  console.log(`Perfect matches: ${perfectMatches}`);
  
  await browser.close();
  console.log('\n✅ ANALYSIS COMPLETE');
  console.log(`Screenshots: ${OUTPUT_DIR}/`);
})();
