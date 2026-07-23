const puppeteer = require('puppeteer');

async function takeReferenceScreenshots() {
    const browser = await puppeteer.launch({
        headless: 'new',
        args: ['--no-sandbox', '--disable-setuid-sandbox']
    });
    
    const page = await browser.newPage();
    await page.setViewport({ width: 1280, height: 1024 });
    
    // Reference homepage
    console.log('Cattura homepage reference...');
    await page.goto('https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html', { waitUntil: 'networkidle2', timeout: 60000 });
    await page.screenshot({ path: '/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/docs/visual-comparison/homepage-reference-full.png', fullPage: true });
    
    // Hero section
    const heroElement = await page.$('[data-element="hero"]');
    if (heroElement) {
        await heroElement.screenshot({ path: '/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/docs/visual-comparison/hero-reference.png' });
    }
    
    // Card section
    const cardSection = await page.$('[data-element="news"]');
    if (cardSection) {
        await cardSection.screenshot({ path: '/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/docs/visual-comparison/cards-reference.png' });
    }
    
    // Services section
    const servicesSection = await page.$('[data-element="servizi"]');
    if (servicesSection) {
        await servicesSection.screenshot({ path: '/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/docs/visual-comparison/services-reference.png' });
    }
    
    console.log('Screenshot reference catturati!');
    await browser.close();
}

takeReferenceScreenshots().catch(console.error);