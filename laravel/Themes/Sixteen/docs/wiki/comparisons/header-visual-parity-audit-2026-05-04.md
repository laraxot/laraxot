# Header Visual Parity Audit — 2026-05-04

**Tool**: Puppeteer screenshot (1280×300px crop)  
**Local URL**: http://127.0.0.1:8000/it/tests/segnalazione-crea?step=form.data%3A%3Adata%3A%3Awizard-step  
**Reference**: https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-02-dati.html  
**Screenshot**: `/tmp/local-header.png` vs `/tmp/ref-header.png` (non versionati — rigenera con Puppeteer)

## Gap confermati

| ID | Componente | Stato locale | Stato reference | Priorità | Fix |
|----|-----------|-------------|----------------|---------|-----|
| P1 | Slim bg | `#00402b` ✅ corretto | Dark green `#00402b` | — | Nessuno |
| P2 | Language switcher toggle | `data-bs-toggle` ❌ | Alpine.js | HIGH | Migrare Alpine |
| P3 | User dropdown toggle | Nessun meccanismo ❌ | Alpine.js | HIGH | Aggiungere Alpine |
| P4 | Guest CTA colore | `style="#007A52"` inline ❌ | `btn btn-primary` standard | MEDIUM | Rimuovere inline style |
| P7 | Nav active indicator | Box blu `#0066cc` su "Servizi" ❌ | Underline verde Bootstrap Italia | MEDIUM | Fix CSS `app.css:3680` |
| P8 | Logo size | SVG `82×82` | Visivamente più grande nel reference | LOW | Aumentare a ~100px |
| P9 | Language switcher dropdown classi | Tailwind misto ❌ | Solo Bootstrap Italia | HIGH | Rimuovere classi Tailwind |

## Mappatura file→gap

| File | Gap |
|------|-----|
| `partials/language-switcher.blade.php` | P2, P9 |
| `partials/user-dropdown.blade.php` | P3 |
| `partials/personal-area-guest-cta.blade.php` | P4 |
| `resources/css/app.css` line ~3680 | P7 |
| `v1.blade.php` line 109 | P8 |

## Token CSS rilevanti

```
--dc-green-dark: #00402b  ← slim header bg (CORRETTO)
--dc-green:      #007a52  ← center header + nav active underline
--dc-blue:       #0066cc  ← NON usare per slim header
```

## Storia

Story attiva: `8-103-header-segnalazione-crea-step2-design-comuni-visual-parity`

## Backlink

- [Sixteen wiki index](../index.md)
- [header-slim-dropdown-behavior](../concepts/header-slim-dropdown-behavior.md)
