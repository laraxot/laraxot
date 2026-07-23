# HTML Structure Comparison: segnalazione-02-dati

**Generated**: 2026-04-06T19:25:15.240Z
**Reference**: https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-02-dati.html
**Local**: http://127.0.0.1:8000/it/tests/segnalazione-02-dati

---

## Summary

| Metric | Reference | Local | Difference |
|--------|-----------|-------|------------|
| Total Elements | 561 | 385 | 176 |
| Unique Classes | 238 | 135 | 103 |
| HTML Lines | 941 | 521 | 420 |
| Data Elements | 16 | 12 | - |
| Semantic Tags | 11 | 7 | - |

**HTML Structure Similarity**: 81%

---

## Aggiornamento (story 7-3, 2026-04-09)

- **CSS §27**: selettori allineati al Blade (`steppers-btn-*`, `.cmp-card`, `.upload-wrapper`); gli stili scoped ora corrispondono al DOM.
- **Blade**: navigazione wizard e testi contatti da traduzioni `fixcity::segnalazione.*`; rimosso handler `confirmAndProceed()` inesistente.
- Le sezioni sotto generate automaticamente il **2026-04-06** possono risultare obsolete rispetto al markup attuale (es. `data-element` già presenti nel blade).

## Missing data-element Attributes (in local but not in reference)

- ❌ `breadcrumb`
- ❌ `page-index`
- ❌ `contacts`
- ❌ `appointment-booking`

## Extra data-element Attributes (in local but not in reference)

✅ No extra data-elements

---

## Missing CSS Classes (in reference but not in local)

Top 28 missing classes:
- `p-0`
- `d-flex`
- `mb-0`
- `active`
- `card`
- `card-body`
- `t-primary`
- `border-light`
- `card-header`
- `m-0`
- `form-group`
- `p-3`
- `btn-sm`
- `text-button-sm`
- `breadcrumb-item`
- `pb-lg-4`
- `it-page-section`
- `cmp-card`
- `has-bkg-grey`
- `shadow-sm`
- `p-lg-4`
- `border-0`
- `mb-lg-20`
- `title-xxlarge`
- `mt-3`
- `u-grey-dark`
- `px-lg-4`
- `w-100`

## Extra CSS Classes (in local but not in reference)

Top 28 extra classes:
- `col-md-3`
- `col-md-6`
- `col-md-4`
- `visually-hidden-focusable`
- `dropdown-item`
- `it-brand-wrapper`
- `it-brand-title`
- `it-socials`
- `navbar-nav`
- `max-w-4xl`
- `mx-auto`
- `px-4`
- `sm:px-6`
- `lg:px-8`
- `text-sm`
- `font-semibold`
- `text-blue-600`
- `font-bold`
- `text-gray-900`
- `mb-4`
- `variable-gutters`
- `mt-md-4`
- `skiplink`
- `it-header-wrapper`
- `it-header-slim-wrapper`
- `it-header-slim-wrapper-content`
- `navbar-brand`
- `it-header-slim-right-zone`

---

## Action Items

Based on the comparison:

1. **Add missing data-element attributes**: 4 elements
2. **Add missing CSS classes**: 28 classes
3. **Review extra classes**: 28 classes may be unnecessary
4. **Focus on CSS**: The HTML structure is 81% similar, focus on visual CSS fixes

---

**Related Docs**:
- [Visual Parity Report](../screenshots/VISUAL-PARITY-REPORT.md)
- [CSS/JS Plan](../design-comuni-html-match-css-js-plan.md)
- [Screenshot Tools](../../../../bashscripts/docs/SCREENSHOT-TOOLS.md)

## Aggiornamento header mobile overlay (story 7-10, 2026-04-20)

- Hamburger **≤991px**: pannello laterale Alpine + backdrop (non lista verticale infinita sotto header); dettaglio in [header-mobile-overlay.md](../../header-mobile-overlay.md).
