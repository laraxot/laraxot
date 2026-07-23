# Validazione PageSpeed Insights - Frontoffice

## Strumento

- **Web**: [PageSpeed Insights](https://pagespeed.web.dev/)
- Inserire l’URL della pagina e avviare l’analisi (Mobile e Desktop).

## Elenco pagine frontoffice da validare

Verificare **ogni** URL su [https://pagespeed.web.dev/](https://pagespeed.web.dev/):

| # | Pagina        | URL |
|---|----------------|-----|
| 1 | Home           | https://sottana.net/it |
| 2 | Contatti       | https://sottana.net/it/contatti |
| 3 | Servizi        | https://sottana.net/it/servizi |
| 4 | Chi siamo      | https://sottana.net/it/chi-siamo |
| 5 | Blog           | https://sottana.net/it/blog |
| 6 | FAQ            | https://sottana.net/it/faq |
| 7 | Privacy        | https://sottana.net/it/privacy |
| 8 | Termini        | https://sottana.net/it/termini |
| 9 | Cookie         | https://sottana.net/it/cookie |

## Risultati Ultima Validazione (20 Feb 2026)

### Home - https://sottana.net/it
| Metrica | Mobile | Desktop |
|---------|--------|---------|
| Performance | 96% | **100%** |
| Accessibility | 90% ⚠️ | 90% ⚠️ |
| Best Practices | 96% | 96% |
| SEO | 100% | 100% |

I problemi sotto persistono sul live finché non si esegue il deploy del tema + asset immagini.

### Problemi identificati (fix in repo, richiesto deploy)
- **Contrasto**: override in `app.css` per `#main-content .text-brand-orange` con `--color-brand-orange-dark` (!important).
- **Link name**: social con `aria-label` che inizia con nome piattaforma (LinkedIn:, Facebook:, Instagram:) + `<span class="sr-only">`.
- **Heading**: componente checklist usa `h3` e `text-orange-700` per "Perché è fondamentale?".

### Errori JS Console (fix in repo / da deploy)
- `$isActive is not defined`: menu mobile header v1 usa solo classi server-side, nessun `:class` Alpine; dopo deploy scompare.
- `$dispatch is not defined`: cookie-consent (vendor), non modificabile dal tema.
- 404 immagini: path aggiornati a `/themes/Two/images/`; **copiare** i file sotto in `public/themes/Two/images/` in deploy.

## Checklist deploy per PageSpeed (sottana.net)

Per far scomparire 404, $isActive e migliorare accessibilità dopo il deploy:

1. **Deploy del tema Two** (view, CSS, config già corretti in repo).
2. **Creare la cartella e copiare le immagini** sul server:
   - `public/themes/Two/images/` deve esistere
   - Copiare al suo interno: `hero-bg.jpg`, `medical-equipment.jpg`, `veterinary-radiology.jpg`  
     (origine tipica: `Themes/Two/Main_files/images/` o `Themes/Two/resources/images/`).
3. **Cache**: dopo il deploy eseguire `php artisan view:clear` e, se usate, svuotare cache CDN/proxy.
4. **Rieseguire PageSpeed** su https://sottana.net/it (Mobile + Desktop) e verificare che 404 e $isActive siano spariti e che il contrasto/heading risultino corretti.

## Come validare

1. Aprire [https://pagespeed.web.dev/](https://pagespeed.web.dev/).
2. Incollare un URL dalla tabella (es. `https://sottana.net/it/contatti`).
3. Cliccare **Analyze**.
4. Controllare i punteggi (Performance, Accessibility, Best Practices, SEO) per **Mobile** e **Desktop**.
5. Ripetere per tutte le pagine dell’elenco.

## Automazione (opzionale)

### Lighthouse CLI

Da terminale (una pagina per volta):

```bash
# Esempio: homepage
lighthouse https://sottana.net/it --view --output=html --output-path=./reports/psi-it.html

# Contatti
lighthouse https://sottana.net/it/contatti --view --output=html --output-path=./reports/psi-contatti.html
```

Installazione: `npm install -g lighthouse`

### Script batch (elenco URL)

Il file `laravel/docs/pagespeed-frontoffice-urls.txt` contiene un URL per riga. Si può usare per script batch con Lighthouse / axe / W3C.

## Categorie e obiettivi

- **Performance**: obiettivo ≥ 90 (verde).
- **Accessibility**: obiettivo 100; riferirsi anche al [piano WCAG](./wcag-compliance-plan.md).
- **Best Practices**: obiettivo ≥ 90.
- **SEO**: obiettivo ≥ 90.

## Validazione a fondo

Per markup (W3C), incolla HTML, script automatico, axe-core e uso MCP: [Validazione frontoffice a fondo](validation-in-depth.md).

## Collegamenti

- [Validazione a fondo (W3C, axe, MCP)](validation-in-depth.md)
- [Deployment e validazione](./deployment-and-validation.md)
- [Validazione batch (Lighthouse, axe, W3C)](frontoffice-validation-batch.md)
- [WCAG compliance plan](./wcag-compliance-plan.md)
- [Validazione MAUVE](./mauve-accessibility-validation.md)
- [PageSpeed Insights](https://pagespeed.web.dev/)
