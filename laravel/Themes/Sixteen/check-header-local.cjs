const puppeteer = require('puppeteer');

(async () => {
  const browser = await puppeteer.launch();
  const page = await browser.newPage();
  await page.goto('http://127.0.0.1:8000/it/tests/segnalazione-crea');

  const headerSlim = await page.$('.it-header-slim-wrapper');
  if (headerSlim) {
    const boundingBox = await headerSlim.boundingBox();
    console.log('Header slim bounding box:', boundingBox);
    const style = await page.evaluate(el => {
      const cs = window.getComputedStyle(el);
      return {
        display: cs.display,
        position: cs.position,
        zIndex: cs.zIndex,
        backgroundColor: cs.backgroundColor,
        height: cs.height,
        width: cs.width,
        padding: cs.padding,
        margin: cs.margin
      };
    }, headerSlim);
    console.log('Header slim styles:', style);
  } else {
    console.log('Header slim not found');
  }

  const headerCenter = await page.$('.it-header-center-wrapper');
  if (headerCenter) {
    const boundingBox = await headerCenter.boundingBox();
    console.log('Header center bounding box:', boundingBox);
  }

  const headerNavbar = await page.$('.it-header-navbar-wrapper');
  if (headerNavbar) {
    const boundingBox = await headerNavbar.boundingBox();
    console.log('Header navbar bounding box:', boundingBox);
  }

  // Check for overlapping elements
  const elements = await page.$$('.it-header-wrapper > *');
  for (let el of elements) {
    const box = await el.boundingBox();
    console.log(`Element ${await page.evaluate(e => e.className, el)}:`, box);
  }

  await browser.close();
})();