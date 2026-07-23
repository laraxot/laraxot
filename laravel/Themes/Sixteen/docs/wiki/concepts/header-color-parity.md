# Header color parity — Design Comuni (kit statico)

## Fonti ufficiali (ordine consigliato)

- Repository HTML/CSS di riferimento: [italia/design-comuni-pagine-statiche](https://github.com/italia/design-comuni-pagine-statiche) (pagine in `src/` / build su GitHub Pages).
- Anteprima pubblicata: [Design Comuni — pagine statiche](https://italia.github.io/design-comuni-pagine-statiche/) (es. flusso segnalazione: `sito/segnalazione-01-privacy.html`, `sito/segnalazione-02-dati.html`, …).

## Due contesti visivi (non mescolare)

### 1) Sito comunale “standard”

Bande blu istituzionali (slim + center + navbar coerenti con Bootstrap Italia / modello comuni). Token di riferimento nel tema: import in `resources/css/app.css` (`design-comuni-global.css`, ecc.).

### 2) Flusso “Segnalazione disservizio” (wizard)

Dal kit statico (es. [segnalazione-02-dati](https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-02-dati.html)):

- **Fascia slim** (regione / utilità): toni **verdi** del modello segnalazioni (es. `#00402b` / varianti da token), testo chiaro dove previsto dal prototipo.
- **Fascia menu principale** (`.it-header-navbar-wrapper`) su `segnalazione-crea`: **fondo verde `#007a52` come logo/slogan**, link bianchi, hover/focus verde piu scuro. Non usare sfondo blu e non usare fascia bianca.

## Implementazione nel tema Sixteen (DRY + KISS)

1. **Markup:** solo `resources/views/components/sections/header/v1.blade.php` (owner per `<x-section slug="header" />`).
2. **Bootstrap Italia 2.x:** sulla navbar del flusso test `tests/segnalazione-crea`, non aggiungere `theme-light-desk`: quella classe riporta verso la variante chiara e favorisce regressioni bianco/blu.
3. **CSS:** regole dedicate in **`resources/css/app.css`** sotto il modificatore **`.it-header-wrapper.is-segnalazione-crea`**, in fondo alla cascata. Devono coprire wrapper, `.navbar`, liste, `.nav-item`, `.nav-link` e `.nav-link span`.
4. **Moduli (wizard):** niente colori header inline nel widget Filament — parity solo lato tema.

## Anti-pattern (da non reintrodurre)

- `theme-light-desk` su `segnalazione-crea`: reintroduce la variante chiara e puo lasciare blu Bootstrap Italia come fallback.
- Cambiare solo un blocco generico `.it-header-navbar-wrapper` in `app.css`: esistono blocchi duplicati successivi; serve una regola finale scoped a `.it-header-wrapper.is-segnalazione-crea`.
- File CSS pubblicati a mano sotto `/themes/Sixteen/css/...` non presenti nel build: rischio **404**; preferire import Vite in `app.css`.

## Collegamenti

- [header design comuni code analysis](./header-design-comuni-code-analysis.md): analisi file/regole per parità header globale (story 7-107)
- [header section owner rule](../../../../../../docs/wiki/concepts/header-section-owner-rule.md)
- [header authenticated state](./header-authenticated-state.md)
- [header slim dropdown behavior](./header-slim-dropdown-behavior.md)
- [Wiki log tema](../log.md)
