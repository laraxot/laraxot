# Architettura FO — uniformità token PA (no hex per pagina)

**Riferimento istituzionale:** [design-comuni-pagine-statiche](https://github.com/italia/design-comuni-pagine-statiche)  
**Brand FixCity:** verde `#007A52` per CTA / Filament `primary` (scelta prodotto, documentata in token)

---

## Religione (regola permanente)

| Vietato | Obbligatorio |
|---------|--------------|
| Blocchi `body[data-page='…']` con `#007A52 !important` su CTA | Token `--fixcity-primary` in `civic-design-tokens.css` |
| Duplicare bordi campo per ogni pagina auth/wizard | Classe `.fo-filament-form-shell` (CSS condiviso) |
| `bg-primary-*` quando `.bg-primary` legacy era blu senza allineamento | `PaDesignColors` + `FilamentColor::register()` + `@theme` primary |
| Mix Amber Filament su FO | `XotServiceProvider::registerPaFilamentColors()` |

**Perché:** il repo [design-comuni-pagine-statiche](https://github.com/italia/design-comuni-pagine-statiche) espone HTML statico con **variabili e classi componente** (es. `.btn-primary`), non override per singola pagina. La demo usa **blu** `#0066CC` per CTA ufficiali; FixCity documenta **verde** `#007A52` come brand su Filament `primary`. L'uniformità = **una SSoT** colore + **un shell** campi (`.fo-filament-form-shell`), non ripetere hex sotto `body[data-page]`.

---

## Stack token (SSoT)

```text
PaDesignColors.php (PHP Filament palette)
        ↓ FilamentColor::register()
:root --primary-* (inline @filamentStyles)
        ↓
civic-design-tokens.css → --fixcity-primary, --fixcity-field-border
        ↓
@theme in app.css → --color-primary-* (Tailwind)
        ↓
Componenti: <x-filament::button color="primary"> | .fo-filament-form-shell
```

---

## Implementazione auth login/register

| Pezzo | File |
|-------|------|
| Token | `resources/css/components/civic-design-tokens.css` |
| Shell campi | `resources/css/components/fo-filament-form-shell.css` |
| Layout pagina | `resources/css/app/14-auth-login.css` (solo layout, no colori CTA) |
| Vista | `filament/widgets/auth/login.blade.php` — `fo-filament-form-shell` + `x-filament::button` |
| Link secondari | `text-italia-blue-*` (blu Design Comuni, non `text-primary-*`) |

---

## Collegamenti

- ADR root: [fo-pa-tokens-no-per-page-hex.md](../../../../docs/wiki/decisions/fo-pa-tokens-no-per-page-hex.md)
- [filament-pa-design-colors.md](../../../Modules/Xot/docs/wiki/concepts/filament-pa-design-colors.md)
- [auth-login-ux-design-wcag.md](../wiki/design/auth-login-ux-design-wcag.md)
- [civic-design-tokens.css](../../resources/css/components/civic-design-tokens.css)
- `.cursor/rules/fo-pa-tokens-uniformity.mdc`
