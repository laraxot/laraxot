const puppeteer = require('puppeteer');

async function takeSectionScreenshots() {
    const browser = await puppeteer.launch({
        headless: 'new',
        args: ['--no-sandbox', '--disable-setuid-sandbox']
    });
    
    const page = await browser.newPage();
    
    // 1. Header comparison
    await page.setViewport({ width: 1280, height: 300 });
    await page.goto('http://127.0.0.1:8000/it/tests/homepage', { waitUntil: 'networkidle2', timeout: 30000 });
    await page.screenshot({ path: '/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/docs/visual-comparison/sections/01-header-local.png' });
    
    await page.goto('https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html', { waitUntil: 'networkidle2', timeout: 60000 });
    await page.screenshot({ path: '/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/docs/visual-comparison/sections/01-header-reference.png' });
    
    // 2. Hero section
    await page.setViewport({ width: 1280, height: 500 });
    await page.goto('http://127.0.0.1:8000/it/tests/homepage', { waitUntil: 'networkidle2', timeout: 30000 });
    await page.screenshot({ path: '/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/docs/visual-comparison/sections/02-hero-local.png' });
    
    await page.goto('https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html', { waitUntil: 'networkidle2', timeout: 60000 });
    await page.screenshot({ path: '/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/docs/visual-comparison/sections/02-hero-reference.png' });
    
    // 3. Governance/Calendar section
    await page.setViewport({ width: 1280, height: 600 });
    await page.goto('http://127.0.0.1:8000/it/tests/homepage', { waitUntil: 'networkidle2', timeout: 30000 });
    await page.screenshot({ path: '/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/docs/visual-comparison/sections/03-governance-local.png' });
    
    await page.goto('https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html', { waitUntil: 'networkidle2', timeout: 60000 });
    await page.screenshot({ path: '/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/docs/visual-comparison/sections/03-governance-reference.png' });
    
    // 4. Evidence section
    await page.setViewport({ width: 1280, height: 800 });
    await page.goto('http://127.0.0.1:8000/it/tests/homepage', { waitUntil: 'networkidle2', timeout: 30000 });
    await page.screenshot({ path: '/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/docs/visual-comparison/sections/04-evidence-local.png' });
    
    await page.goto('https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html', { waitUntil: 'networkidle2', timeout: 60000 });
    await page.screenshot({ path: '/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/docs/visual-comparison/sections/04-evidence-reference.png' });
    
    // 5. Useful Links section
    await page.setViewport({ width: 1280, height: 400 });
    await page.goto('http://127.0.0.1:8000/it/tests/homepage', { waitUntil: 'networkidle2', timeout: 30000 });
    await page.screenshot({ path: '/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/docs/visual-comparison/sections/05-usefullinks-local.png' });
    
    await page.goto('https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html', { waitUntil: 'networkidle2', timeout: 60000 });
    await page.screenshot({ path: '/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/docs/visual-comparison/sections/05-usefullinks-reference.png' });
    
    // 6. Footer section
    await page.setViewport({ width: 1280, height: 600 });
    await page.goto('http://127.0.0.1:8000/it/tests/homepage', { waitUntil: 'networkidle2', timeout: 30000 });
    await page.screenshot({ path: '/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/docs/visual-comparison/sections/06-footer-local.png' });
    
    await page.goto('https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html', { waitUntil: 'networkidle2', timeout: 60000 });
    await page.screenshot({ path: '/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/docs/visual-comparison/sections/06-footer-reference.png' });
    
    console.log('Section screenshots completed!');
    await browser.close();
}

takeSectionScreenshots().catch(console.error);