const puppeteer = require('puppeteer');

async function takeScreenshot() {
    const browser = await puppeteer.launch({
        headless: 'new',
        args: ['--no-sandbox', '--disable-setuid-sandbox']
    });
    
    const page = await browser.newPage();
    await page.setViewport({ width: 1280, height: 1024 });
    
    // Homepage locale
    console.log('Cattura homepage locale...');
    await page.goto('http://127.0.0.1:8000/it/tests/homepage', { waitUntil: 'networkidle2' });
    await page.screenshot({ path: '/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/docs/visual-comparison/homepage-local-full.png', fullPage: true });
    
    // Hero section
    const heroElement = await page.$('[data-element="hero"]');
    if (heroElement) {
        await heroElement.screenshot({ path: '/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/docs/visual-comparison/hero-local.png' });
    }
    
    // Card section
    const cardSection = await page.$('[data-element="news"]');
    if (cardSection) {
        await cardSection.screenshot({ path: '/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/docs/visual-comparison/cards-local.png' });
    }
    
    // Services section
    const servicesSection = await page.$('[data-element="servizi"]');
    if (servicesSection) {
        await servicesSection.screenshot({ path: '/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/docs/visual-comparison/services-local.png' });
    }
    
    console.log('Screenshot locali catturati!');
    await browser.close();
}

takeScreenshot().catch(console.error);