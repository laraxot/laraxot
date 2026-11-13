---
title: "Indagine homepage DesignComuni — Sixteen Theme"
type: investigation
project: "Fixcity — Tema Sixteen"
priority: high
tags: [homepage, design-comuni, a11y, bmad, design-system]
links:
  github_issue: https://github.com/italia/design-comuni-pagine-statiche/issues
  design_ref: https://italia.github.io/design-comuni-pagine-statiche/index.html
  homepage_ref: https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html
  segnalazione_ref: https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-dettaglio.html
  privacy_ref: https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-01-privacy.html
  dati_ref: https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-02-dati.html
  riepilogo_ref: https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-03-riepilogo.html
  conferma_ref: https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-04-conferma.html
status: draft
updated: 2026-10-11
qmd: "investigazione   homepage designcomuni sixteen"
---

# Investigazione — Homepage DesignComuni

## Fonte ufficiale
Modello DesignComuni v2.4.0 (Italia Developers): `https://github.com/italia/design-comuni-pagine-statiche`

## Pattern richiesti dalla homepage (template `homepage.html`)

### Struttura globale
- Skip link: `[Vai ai contenuti](#main-container) [Vai al footer](#footer)`
- Header: `it-header-wrapper` → `it-header-slim-wrapper` (logo EU + `it-brand-wrapper` con `it-pa` icon)
- Navbar: `it-nav-wrapper` → `it-header-center-wrapper` → `it-header-center-content-wrapper` → `it-brand-text` → `it-socials` → `it-search-wrapper` → `it-search`
- Footer: `it-footer` → `it-footer-main` → logo UE + `it-brand-text` + 4 colonne `it-footer-list`

### Sezione Segnalazione Disservizio (schema dal template `segnalazione-dettaglio.html`)
```
Segnalazione disservizio
- Servizio attivo
- Descrizione: "Un servizio aperto a tutti i cittadini per segnalare disservizi..."
- Azioni: "Segnala disservizio" / "Tutte le segnalazioni"
- Condividi: Facebook / Twitter / LinkedIn / WhatsApp
```

### Flusso di segnalazione in 3 step
| Step | Pagina | Descrizione |
|---|---|---|
| 1/3 | `segnalazione-01-privacy` | Informativa privacy + consenso GDPR art.13 |
| 2/3 | `segnalazione-02-dati` | Dati di segnalazione: luogo (autocomplete), dettagli disservizio, allegati, dati autore |
| 3/3 | `segnalazione-03-riepilogo` | Riepilogo informazioni (dichiarazione sostitutiva) |
| → | `segnalazione-04-conferma` | Conferma invio con tracking ID (es. AN4059281) |

## Componenti chiave da applicare

### Header DesignComuni
```blade
<header class="it-header-wrapper">
  <div class="it-header-slim-wrapper">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="it-header-slim-wrapper-content">
            <a class="d-lg-block navbar-brand" href="/">
              <svg class="icon"><use href="/themes/Sixteen/design-comuni/assets/images/logo-comune.svg"></use></svg>
              <div class="it-brand-text">
                <div class="it-brand-title">Nome del Comune</div>
                <div class="it-brand-tagline d-none d-md-block">...</div>
              </div>
            </a>
            <div class="it-header-slim-right-zone">
              <a class="btn btn-primary btn-icon" href="#">
                <svg class="icon icon-primary"><use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-user"></use></svg>
                <span data-element="personal-area-login">Area Personale</span>
              </a>
              <button class="search-link rounded-icon" aria-label="Cerca nel sito">
                <svg class="icon"><use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-search"></use></svg>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  ...
</header>
```

### Footer DesignComuni
```blade
<footer class="it-footer" id="footer">
  <div class="it-footer-main">
    <div class="container">
      <div class="row">
        <div class="col-12 footer-items-wrapper logo-wrapper">
          <img class="ue-logo" src="/themes/Sixteen/design-comuni/assets/images/logo-eu-inverted.svg" alt="logo Unione Europea">
          <div class="it-brand-wrapper"><a href="#"><svg class="icon"><use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-pa"></use></svg><div class="it-brand-text"><h2>...</h2></div></a></div>
        </div>
        <div class="col-md-3 footer-items-wrapper"><h4 class="footer-heading-title">...</h4><ul class="it-footer-list"><li><a class="list-item" href="#"></a></li></ul></div>
        <div class="col-md-6 footer-items-wrapper"><h4 class="footer-heading-title">...</h4>...</div>
        <div class="col-md-3 footer-items-wrapper mt-md-4"><h4 class="footer-heading-title">...</h4><ul class="list-inline social"><li><a class="p-1 text-white" href="#"><svg class="icon icon-sm icon-white align-top"><use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-twitter"></use></svg></a></li></ul></div>
      </div>
      <div class="row"><div class="col-12 footer-items-wrapper"><div class="footer-bottom">...</div></div></div>
    </div>
  </div>
</footer>
```

## Mappa del gap (current → target)
| Sezione | Current | Target |
|---|---|---|
| Header | `x-pub_theme` custom header | `it-header-wrapper` |
| Footer | `x-pub_theme` custom footer | `it-footer` |
| Hero | `bg-primary py-5`, `card border-0 shadow-sm` | Template `homepage.html` pattern |
| Mappa | `<map-lit>` inside card | Integrare pattern card DesignComuni |
| Come funziona | 3 card con numerazione inline | `card card-bg` con step numerato |
| Quick actions | `btn btn-primary btn-lg` | Pattern `btn btn-primary` DesignComuni |

## Notes
- `index.blade.php.old` già usato come base per prototipazione header DesignComuni
- Skip link `Vai ai contenuti` / `Vai al footer` obbligatori
- Sprite SVG: `/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-{icon}`