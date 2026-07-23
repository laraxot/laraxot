/**
 * Detailed visual parity analysis for segnalazione-01-privacy
 * Takes screenshots at multiple viewport sizes + element-level comparison
 */

const { chromium } = require('playwright');
const fs = require('fs');
const path = require('path');

const OUTPUT_DIR = '/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/docs/visual-analysis/segnalazione-01-privacy';

const VIEWPORTS = [
  { name: 'desktop', width: 1440, height: 900 },
  { name: 'tablet', width: 768, height: 1024 },
  { name: 'mobile', width: 375, height: 812 }
];

async function captureFullPage(page, url, label, viewportName) {
  const pageDir = path.join(OUTPUT_DIR, viewportName);
  if (!fs.existsSync(pageDir)) fs.mkdirSync(pageDir, { recursive: true });
  
  console.log(`\n📸 [${viewportName}] ${label}`);
  console.log(`   URL: ${url}`);
  
  await page.goto(url, { waitUntil: 'domcontentloaded', timeout: 30000 });
  await page.waitForTimeout(2000);
  
  // Full page
  await page.screenshot({
    path: path.join(pageDir, `${label}-full.png`),
    fullPage: true
  });
  
  // Viewport only
  await page.screenshot({
    path: path.join(pageDir, `${label}-viewport.png`),
    fullPage: false
  });
  
  // Key elements
  const elements = ['#main-container', '.hero-title', 'h1', '.card', '.form-group', '.btn', '.footer'];
  for (const selector of elements) {
    try {
      const el = await page.$(selector);
      if (el) {
        await el.screenshot({
          path: path.join(pageDir, `${label}-${selector.replace(/[^a-z0-9]/gi, '-')}.png`)
        });
        console.log(`   ✅ Element: ${selector}`);
      }
    } catch (e) {
      // Element not found
    }
  }
  
  console.log(`   ✅ Screenshots saved to ${pageDir}`);
}

async function analyzeComputedStyles(page, url, label) {
  await page.goto(url, { waitUntil: 'domcontentloaded', timeout: 30000 });
  await page.waitForTimeout(1000);
  
  const styles = await page.evaluate(() => {
    const results = {};
    
    // Body
    const body = document.body;
    const bodyStyle = window.getComputedStyle(body);
    results.body = {
      fontFamily: bodyStyle.fontFamily,
      fontSize: bodyStyle.fontSize,
      lineHeight: bodyStyle.lineHeight,
      backgroundColor: bodyStyle.backgroundColor,
      color: bodyStyle.color,
      margin: bodyStyle.margin,
      padding: bodyStyle.padding
    };
    
    // h1
    const h1 = document.querySelector('h1');
    if (h1) {
      const s = window.getComputedStyle(h1);
      results.h1 = {
        fontFamily: s.fontFamily,
        fontSize: s.fontSize,
        fontWeight: s.fontWeight,
        lineHeight: s.lineHeight,
        color: s.color,
        marginTop: s.marginTop,
        marginBottom: s.marginBottom
      };
    }
    
    // First card
    const card = document.querySelector('.card');
    if (card) {
      const s = window.getComputedStyle(card);
      results.card = {
        backgroundColor: s.backgroundColor,
        borderRadius: s.borderRadius,
        boxShadow: s.boxShadow,
        padding: s.padding,
        marginBottom: s.marginBottom
      };
    }
    
    // First button
    const btn = document.querySelector('.btn');
    if (btn) {
      const s = window.getComputedStyle(btn);
      results.btn = {
        backgroundColor: s.backgroundColor,
        color: s.color,
        borderRadius: s.borderRadius,
        padding: s.padding,
        fontSize: s.fontSize,
        fontWeight: s.fontWeight
      };
    }
    
    // Container
    const container = document.querySelector('.container');
    if (container) {
      const s = window.getComputedStyle(container);
      results.container = {
        maxWidth: s.maxWidth,
        paddingLeft: s.paddingLeft,
        paddingRight: s.paddingRight
      };
    }
    
    return results;
  });
  
  console.log(`\n📐 Computed Styles [${label}]:`);
  console.log(JSON.stringify(styles, null, 2));
  
  return styles;
}

(async () => {
  console.log('🚀 Starting Detailed Visual Parity Analysis\n');
  console.log(`Output: ${OUTPUT_DIR}\n`);
  
  if (!fs.existsSync(OUTPUT_DIR)) {
    fs.mkdirSync(OUTPUT_DIR, { recursive: true });
  }
  
  const browser = await chromium.launch({ headless: true });
  
  // Desktop viewport
  const desktopCtx = await browser.newContext({ viewport: VIEWPORTS[0] });
  const desktopPage = await desktopCtx.newPage();
  
  console.log('=== DESKTOP (1440x900) ===');
  await captureFullPage(desktopPage, 'https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-01-privacy.html', 'reference', 'desktop');
  await captureFullPage(desktopPage, 'http://127.0.0.1:8000/it/tests/segnalazione-01-privacy', 'local', 'desktop');
  
  // Tablet viewport
  const tabletCtx = await browser.newContext({ viewport: VIEWPORTS[1] });
  const tabletPage = await tabletCtx.newPage();
  
  console.log('\n=== TABLET (768x1024) ===');
  await captureFullPage(tabletPage, 'https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-01-privacy.html', 'reference', 'tablet');
  await captureFullPage(tabletPage, 'http://127.0.0.1:8000/it/tests/segnalazione-01-privacy', 'local', 'tablet');
  
  // Mobile viewport
  const mobileCtx = await browser.newContext({ viewport: VIEWPORTS[2] });
  const mobilePage = await mobileCtx.newPage();
  
  console.log('\n=== MOBILE (375x812) ===');
  await captureFullPage(mobilePage, 'https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-01-privacy.html', 'reference', 'mobile');
  await captureFullPage(mobilePage, 'http://127.0.0.1:8000/it/tests/segnalazione-01-privacy', 'local', 'mobile');
  
  // Computed styles comparison (desktop)
  console.log('\n\n=== COMPUTED STYLES COMPARISON ===');
  const refStyles = await analyzeComputedStyles(desktopPage, 'https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-01-privacy.html', 'REFERENCE');
  const locStyles = await analyzeComputedStyles(desktopPage, 'http://127.0.0.1:8000/it/tests/segnalazione-01-privacy', 'LOCAL');
  
  // Save styles to file
  fs.writeFileSync(
    path.join(OUTPUT_DIR, 'computed-styles.json'),
    JSON.stringify({ reference: refStyles, local: locStyles }, null, 2)
  );
  
  console.log('\n\n=== DIFF ANALYSIS ===');
  
  function diff(obj1, obj2, prefix = '') {
    const diffs = [];
    for (const key in obj1) {
      const path = prefix ? `${prefix}.${key}` : key;
      if (typeof obj1[key] === 'object' && obj1[key] !== null && !Array.isArray(obj1[key])) {
        diffs.push(...diff(obj1[key], obj2[key] || {}, path));
      } else if (obj1[key] !== obj2[key]) {
        diffs.push({
          path,
          ref: obj1[key],
          local: obj2[key]
        });
      }
    }
    return diffs;
  }
  
  const styleDiffs = diff(refStyles, locStyles);
  console.log(`\n📊 Found ${styleDiffs.length} style differences:`);
  styleDiffs.forEach((d, i) => {
    console.log(`  ${i + 1}. ${d.path}`);
    console.log(`     REF: ${d.ref}`);
    console.log(`     LOC: ${d.local}`);
  });
  
  // Save diff
  fs.writeFileSync(
    path.join(OUTPUT_DIR, 'style-differences.json'),
    JSON.stringify(styleDiffs, null, 2)
  );
  
  await browser.close();
  
  console.log('\n\n✅ ANALYSIS COMPLETE');
  console.log(`Screenshots: ${OUTPUT_DIR}/`);
  console.log(`Styles: ${OUTPUT_DIR}/computed-styles.json`);
  console.log(`Diffs: ${OUTPUT_DIR}/style-differences.json`);
  
  console.log('\n📋 Files created:');
  fs.readdirSync(OUTPUT_DIR, { recursive: true }).forEach(f => {
    console.log(`  ${f}`);
  });
})();
