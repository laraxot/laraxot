const puppeteer = require('puppeteer');

async function getBodyStructure() {
    const browser = await puppeteer.launch({
        headless: 'new',
        args: ['--no-sandbox', '--disable-setuid-sandbox']
    });
    
    const page = await browser.newPage();
    await page.setViewport({ width: 1280, height: 1024 });
    
    // Reference
    console.log('=== REFERENCE HTML STRUCTURE ===');
    await page.goto('https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html', { waitUntil: 'networkidle2', timeout: 60000 });
    const refHtml = await page.evaluate(() => {
        const body = document.body.cloneNode(true);
        
        // Remove scripts
        body.querySelectorAll('script').forEach(s => s.remove());
        
        // Get structure as text with indentation
        function getStructure(node, indent = 0) {
            let result = '';
            const spaces = '  '.repeat(indent);
            
            if (node.nodeType === Node.ELEMENT_NODE) {
                let attrs = '';
                if (node.id) attrs += `#${node.id}`;
                if (node.className && typeof node.className === 'string') {
                    const classes = node.className.split(' ').filter(c => c && !c.startsWith('vite')).slice(0, 5);
                    if (classes.length) attrs += '.' + classes.join('.');
                }
                if (node.getAttribute('data-element')) attrs += `[data-element="${node.getAttribute('data-element')}"]`;
                if (node.getAttribute('data-bs-toggle')) attrs += `[data-bs-toggle="${node.getAttribute('data-bs-toggle')}"]`;
                
                const tag = node.tagName.toLowerCase();
                const hasChildren = node.children.length > 0;
                
                result += `${spaces}${tag}${attrs ? ' ' + attrs : ''}`;
                
                if (node.children.length === 0) {
                    const text = node.textContent?.trim().slice(0, 30);
                    if (text) result += ` "${text}"`;
                }
                result += '\n';
                
                for (const child of node.children) {
                    result += getStructure(child, indent + 1);
                }
            }
            return result;
        }
        
        return getStructure(body);
    });
    
    console.log(refHtml);
    
    // Local
    console.log('\n\n=== LOCAL HTML STRUCTURE ===');
    await page.goto('http://127.0.0.1:8000/it/tests/homepage', { waitUntil: 'networkidle2', timeout: 30000 });
    const localHtml = await page.evaluate(() => {
        const body = document.body.cloneNode(true);
        
        // Remove scripts
        body.querySelectorAll('script').forEach(s => s.remove());
        
        function getStructure(node, indent = 0) {
            let result = '';
            const spaces = '  '.repeat(indent);
            
            if (node.nodeType === Node.ELEMENT_NODE) {
                let attrs = '';
                if (node.id) attrs += `#${node.id}`;
                if (node.className && typeof node.className === 'string') {
                    const classes = node.className.split(' ').filter(c => c && !c.startsWith('vite')).slice(0, 5);
                    if (classes.length) attrs += '.' + classes.join('.');
                }
                if (node.getAttribute('data-element')) attrs += `[data-element="${node.getAttribute('data-element')}"]`;
                if (node.getAttribute('data-bs-toggle')) attrs += `[data-bs-toggle="${node.getAttribute('data-bs-toggle')}"]`;
                
                const tag = node.tagName.toLowerCase();
                result += `${spaces}${tag}${attrs ? ' ' + attrs : ''}`;
                
                if (node.children.length === 0) {
                    const text = node.textContent?.trim().slice(0, 30);
                    if (text) result += ` "${text}"`;
                }
                result += '\n';
                
                for (const child of node.children) {
                    result += getStructure(child, indent + 1);
                }
            }
            return result;
        }
        
        return getStructure(body);
    });
    
    console.log(localHtml);
    
    await browser.close();
}

getBodyStructure().catch(console.error);