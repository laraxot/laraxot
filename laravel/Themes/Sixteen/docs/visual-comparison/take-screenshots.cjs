const puppeteer = require('puppeteer');

async function takeComparisonScreenshots() {
    const browser = await puppeteer.launch({
        headless: 'new',
        args: ['--no-sandbox', '--disable-setuid-sandbox']
    });
    
    const page = await browser.newPage();
    await page.setViewport({ width: 1280, height: 2400 });
    
    // Homepage locale
    console.log('Cattura homepage locale...');
    await page.goto('http://127.0.0.1:8000/it/tests/homepage', { waitUntil: 'networkidle2', timeout: 30000 });
    await page.screenshot({ path: '/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/docs/visual-comparison/local-full.png', fullPage: true });
    
    // Reference homepage
    console.log('Cattura homepage reference...');
    await page.goto('https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html', { waitUntil: 'networkidle2', timeout: 60000 });
    await page.screenshot({ path: '/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/docs/visual-comparison/reference-full.png', fullPage: true });
    
    console.log('Screenshot completati!');
    await browser.close();
}

takeComparisonScreenshots().catch(console.error);