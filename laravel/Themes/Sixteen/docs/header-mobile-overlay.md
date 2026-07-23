# Header mobile/tablet — overlay hamburger (story 7-10)

## Scopo

Su viewport **≤991px** il menu primario non deve espandersi in verticale sotto l’header: il pulsante hamburger apre un **pannello laterale** (slide-in da sinistra) con **backdrop** scuro, allineato a [Design Comuni — segnalazione-02-dati](https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-02-dati.html).

## Implementazione (single source of truth)

| Layer | File |
|--------|------|
| Markup + stato | `resources/views/components/bootstrap-italia/header.blade.php` — `x-data="headerMobileNav"`, backdrop `.navbar-overlay`, pannello `#nav4.navbar-collapsable`, `x-trap="mobileNavOpen"` |
| Logica Alpine | `resources/js/app.js` — `Alpine.data('headerMobileNav')`, plugin `@alpinejs/focus` per focus trap |
| Stili mobile | `resources/css/app.css` — `@media (max-width: 991.98px)` overlay/pannello fisso, `body.nav-open` scroll lock |
| Stili desktop | `resources/css/segnalazione-parity.css` — `@media (min-width: 992px)` pannello come riga orizzontale (`display: flex !important` su `.navbar-collapsable`) |

**Non** si affida a Bootstrap Italia JS per `navbarcollapsible` nel frontoffice: il toggle è Alpine.

## Comportamento

- **Toggle:** `toggle()` su hamburger; `close()` su X, backdrop (`@click.self`), `Escape`, passaggio a viewport **≥992px** (`matchMedia`).
- **Scroll:** classe `nav-open` su `<body>` quando il menu mobile è aperto.
- **Focus:** `@alpinejs/focus` con `x-trap="mobileNavOpen"`; focus iniziale sul primo link nel pannello.
- **Social nel drawer:** nascosti nel menu mobile (`.menu-wrapper .it-socials`), già presenti nel center wrapper.

## Verifica rapida

1. `/{locale}/tests/segnalazione-02-dati` a larghezza 375px: aprire menu → overlay + pannello; tab solo dentro il pannello; chiudi con X/backdrop/Escape.
2. Portare la finestra sopra 992px con menu aperto: si chiude e torna la navbar orizzontale.
3. `npm run build` nella cartella del tema Sixteen.

## Collegamenti

- [Playwright visual testing](./wiki/playwright-visual-testing.md)
- Story BMad: [7-10-header-mobile-tablet-overlay-parity.md](../../../../_bmad-output/implementation-artifacts/7-10-header-mobile-tablet-overlay-parity.md)
