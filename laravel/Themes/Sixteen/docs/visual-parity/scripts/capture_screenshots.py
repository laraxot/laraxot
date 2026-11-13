#!/usr/bin/env python3
"""
Screenshot Comparison Script per visual parity
Usa Playwright per catturare screenshot delle pagine reference e local
"""

import asyncio
import os
from playwright.async_api import async_playwright

OUTPUT_DIR = "/var/www/_bases/base_fixcity_fila5/laravel/Theme/Sixteen/docs/visual-parity/screenshots"

PAGES = [
    "lista-categorie",
    "amministrazione",
    "novita",
    "servizi",
    "eventi",
    "luoghi",
    "contatti",
]

async def capture_screenshot(page_name: str, url: str, output_file: str):
    """Cattura screenshot di una pagina"""
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        page = await browser.new_page(viewport={"width": 1920, "height": 1080})
        
        print(f"Catturando {page_name} da {url}...")
        try:
            await page.goto(url, wait_until="domcontentloaded", timeout=30000)
            await asyncio.sleep(2)  # Attendi caricamento completo
            
            await page.screenshot(full_page=True, path=output_file)
            print(f"  → Salvato: {output_file}")
        except Exception as e:
            print(f"  → ERRORE: {e}")
        
        await browser.close()

async def main():
    os.makedirs(OUTPUT_DIR, exist_ok=True)
    
    # Reference pages (Italia Design Comuni)
    for page in PAGES:
        url = f"https://italia.github.io/design-comuni-pagine-statiche/sito/{page}.html"
        output = f"{OUTPUT_DIR}/reference-{page}.png"
        await capture_screenshot(f"reference-{page}", url, output)
    
    # Local pages (FixCity)
    for page in PAGES:
        url = f"http://127.0.0.1:8000/it/tests/{page}"
        output = f"{OUTPUT_DIR}/local-{page}.png"
        await capture_screenshot(f"local-{page}", url, output)
    
    print("\nScreenshot completati!")

if __name__ == "__main__":
    asyncio.run(main())