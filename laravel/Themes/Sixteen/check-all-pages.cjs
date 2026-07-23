/**
 * Check HTML parity and take screenshots for all 6 pages
 * Pages: segnalazione-area-personale, segnalazioni-elenco, segnalazione-dettaglio,
 *        segnalazione-01-privacy, segnalazione-02-dati, segnalazione-03-riepilogo, segnalazione-04-conferma
 */

const { chromium } = require('playwright');
const fs = require('fs');
const path = require('path');

const PAGES = [
  'segnalazione-area-personale',
  'segnalazioni-elenco',
  'segnalazione-dettaglio',
  'segnalazione-01-privacy',
  'segnalazione-02-dati',
  'segnalazione-03-riepilogo',
  'segnalazione-04-conferma'
];

const BASE_REF = 'https://italia.github.io/design-comuni-pagine-statiche/sito';
const BASE_LOCAL = 'http://127.0.0.1:8000';
const OUTPUT_DIR = '/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/docs/screenshots/segnalazione-pages';

async function analyzeFonts(page, url, label) {
  await page.goto(url, { waitUntil: 'domcontentloaded', timeout: 30000 });
  await page.waitForTimeout(1000);
  
  const fonts = await page.evaluate(() => {
    const elements = document.querySelectorAll('p, h1, h2, h3, h4, h5, h6, span, a, label, button, input, li');
    const fontMap = new Map();
    elements.forEach(el => {
      const style = window.getComputedStyle(el);
      const key = `${style.fontFamily}|${style.fontSize}|${style.fontWeight}|${style.lineHeight}`;
      fontMap.set(key, (fontMap.get(key) || 0) + 1);
    });
    return Array.from(fontMap.entries()).sort((a, b) => b[1] - a[1]);
  });
  
  return fonts;
}

async function checkHTMLParity(page, refUrl, localUrl) {
  // Get HTML structure from both pages
  await page.goto(refUrl, { waitUntil: 'domcontentloaded', timeout: 30000 });
  const refHTML = await page.evaluate(() => document.body.innerHTML);
  
  await page.goto(localUrl, { waitUntil: 'domcontentloaded', timeout: 30000 });
  const localHTML = await page.evaluate(() => document.body.innerHTML);
  
  // Simple tag-based comparison
  const extractTags = (html) => {
    const matches = html.match(/<[a-z][^>]*>/gi) || [];
    return matches.map(t => t.split(/[>\s]/)[0].replace('<', '')).filter(t => t && !t.startsWith('/'));
  };
  
  const refTags = extractTags(refHTML);
  const localTags = extractTags(localHTML);
  
  const common = refTags.filter(t => localTags.includes(t));
  const parity = refTags.length > 0 ? (common.length / refTags.length * 100).toFixed(1) : 0;
  
  return {
    refTagCount: refTags.length,
    localTagCount: localTags.length,
    commonCount: common.length,
    parity
  };
}

(async () => {
  console.log('🚀 Starting HTML Parity & Screenshot Analysis\n');
  
  if (!fs.existsSync(OUTPUT_DIR)) {
    fs.mkdirSync(OUTPUT_DIR, { recursive: true });
  }
  
  const browser = await chromium.launch({ headless: true });
  const page = await browser.newPage();
  await page.setViewportSize({ width: 1440, height: 900 });
  
  const results = [];
  
  for (const pageSlug of PAGES) {
    console.log(`\n${'='.repeat(60)}`);
    console.log(`📄 ${pageSlug}`);
    console.log('='.repeat(60));
    
    const refUrl = `${BASE_REF}/${pageSlug}.html`;
    const localUrl = `${BASE_LOCAL}/it/tests/${pageSlug}`;
    const pageOutputDir = path.join(OUTPUT_DIR, pageSlug);
    
    if (!fs.existsSync(pageOutputDir)) {
      fs.mkdirSync(pageOutputDir, { recursive: true });
    }
    
    try {
      // 1. HTML Parity
      console.log('\n🔍 Checking HTML parity...');
      const parity = await checkHTMLParity(page, refUrl, localUrl);
      console.log(`   Reference tags: ${parity.refTagCount}`);
      console.log(`   Local tags: ${parity.localTagCount}`);
      console.log(`   Common tags: ${parity.commonCount}`);
      console.log(`   ✅ HTML Parity: ${parity.parity}%`);
      
      // 2. Screenshots
      console.log('\n📸 Taking screenshots...');
      
      // Reference
      await page.goto(refUrl, { waitUntil: 'domcontentloaded', timeout: 30000 });
      await page.waitForTimeout(2000);
      await page.screenshot({ path: path.join(pageOutputDir, 'reference-full.png'), fullPage: true });
      await page.screenshot({ path: path.join(pageOutputDir, 'reference-viewport.png'), fullPage: false });
      console.log(`   ✅ Reference screenshots saved`);
      
      // Local
      await page.goto(localUrl, { waitUntil: 'domcontentloaded', timeout: 30000 });
      await page.waitForTimeout(2000);
      await page.screenshot({ path: path.join(pageOutputDir, 'local-full.png'), fullPage: true });
      await page.screenshot({ path: path.join(pageOutputDir, 'local-viewport.png'), fullPage: false });
      console.log(`   ✅ Local screenshots saved`);
      
      // 3. Font Analysis
      console.log('\n📝 Analyzing fonts...');
      const refFonts = await analyzeFonts(page, refUrl, 'REFERENCE');
      const locFonts = await analyzeFonts(page, localUrl, 'LOCAL');
      
      const refFontKeys = new Set(refFonts.map(f => f[0]));
      const locFontKeys = new Set(locFonts.map(f => f[0]));
      const commonFonts = [...refFontKeys].filter(k => locFontKeys.has(k));
      
      console.log(`   Reference fonts: ${refFonts.length}`);
      console.log(`   Local fonts: ${locFonts.length}`);
      console.log(`   ✅ Common fonts: ${commonFonts.length}`);
      
      // Top 5 fonts
      console.log('\n   Top 5 REFERENCE fonts:');
      refFonts.slice(0, 5).forEach(([font, count]) => {
        console.log(`     ${count}x → ${font.replace(/\|/g, ' | ')}`);
      });
      
      console.log('\n   Top 5 LOCAL fonts:');
      locFonts.slice(0, 5).forEach(([font, count]) => {
        console.log(`     ${count}x → ${font.replace(/\|/g, ' | ')}`);
      });
      
      results.push({
        page: pageSlug,
        htmlParity: parity.parity,
        fontMatch: commonFonts.length,
        status: parity.parity >= 80 ? '✅ PASS' : '⚠️ NEEDS WORK'
      });
      
    } catch (error) {
      console.error(`   ❌ Error: ${error.message}`);
      results.push({
        page: pageSlug,
        htmlParity: 'N/A',
        fontMatch: 'N/A',
        status: '❌ ERROR'
      });
    }
  }
  
  // Summary
  console.log(`\n\n${'='.repeat(80)}`);
  console.log('📊 SUMMARY TABLE');
  console.log('='.repeat(80));
  console.log(`Page                              | HTML Parity | Font Match | Status`);
  console.log('-'.repeat(80));
  
  results.forEach(r => {
    console.log(`${r.page.padEnd(35)} | ${String(r.htmlParity).padStart(9)}% | ${String(r.fontMatch).padStart(8)} | ${r.status}`);
  });
  
  const passCount = results.filter(r => r.status === '✅ PASS').length;
  console.log(`\n✅ ${passCount}/${results.length} pages with HTML Parity >= 80%`);
  
  await browser.close();
  console.log('\n✅ Done! Screenshots saved to:', OUTPUT_DIR);
})();
