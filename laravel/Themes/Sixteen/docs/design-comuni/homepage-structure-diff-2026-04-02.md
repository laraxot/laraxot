# Homepage Structure Diff 2026-04-02

Report visuale correlato: [screenshots/homepage-visual-pass-2026-04-02.md](./screenshots/homepage-visual-pass-2026-04-02.md)

Origine confrontata:
- Reference: `https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html`
- Locale: `http://127.0.0.1:8000/it/tests/homepage`
- Sorgenti locali rilevanti: `resources/views/pages/tests/[slug].blade.php` e `config/local/fixcity/database/content/pages/tests.homepage.json`

## Esito

La struttura del `body`, escludendo gli script, e' sostanzialmente allineata e supera la soglia pratica del 90%.

Ordine delle macro-sezioni confermato in entrambe le pagine:
1. `div.skiplink`
2. `header.it-header-wrapper`
3. `main`
4. `h1.visually-hidden`
5. `section#head-section`
6. `section#calendario`
7. `section.evidence-section`
8. `section.useful-links-section`
9. blocco rating `div.bg-primary`
10. blocco contatti/problemi
11. ricerca finale
12. `footer.it-footer`

## Differenze strutturali residue

1. Il locale usa `<body class="dc-homepage-parity">`, la reference usa `<body>` senza classi.
2. Il locale usa `<main id="main-container">`, mentre la reference ha `<main>` e mette `id="main-container"` sull`h1` nascosto.
3. Nel locale `#head-section` contiene ancora un blocco `.cmp-search` subito sotto la card hero. E' nascosto via CSS ma nel DOM esiste; nella reference non c'e'.
4. Il locale ha path asset assoluti del tema (`/themes/Sixteen/...`), la reference usa path relativi (`../assets/...`).
5. Le immagini placeholder `picsum.photos` sono runtime-random in entrambe le pagine, quindi il DOM e' equivalente ma la resa visiva cambia ad ogni request.

## Implicazione pratica

Il lavoro corretto per il pass successivo e' CSS/JS, non refactoring della Blade o del JSON.

L'unica differenza DOM da tenere d'occhio e' la `.cmp-search` dentro `#head-section`: oggi non rompe la vista perche' e' soppressa da CSS, ma non e' parity HTML perfetta.

## File toccati nel pass 2026-04-02

- `resources/css/app.css`
- `resources/js/app.js`

## Verifica eseguita

- Estrazione e confronto manuale del `body` senza script.
- Verifica screenshot desktop e full-page.
- Build theme con `npm run build`.
- Allineamento asset serviti dal sito locale copiando manifest e bundle finali in `public_html/themes/Sixteen`.

## Nota

Alcuni report storici in `docs/design-comuni/` dichiarano una parity visiva totale. Lo stato verificato oggi e' migliore ma non ancora identico alla reference: la parity reale e' buona sulla struttura, parziale sulla resa.
