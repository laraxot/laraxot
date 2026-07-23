# Body Class Rule - HTML Parity Enforcement

**Status**: Active  
**Created**: 2026-04-12  
**Last Updated**: 2026-04-12  
**Category**: HTML Parity / Design Comuni

## Rule Statement

**MAI** aggiungere classi custom al `<body>` tag per ottenere visual parity.

Il `<body>` tag deve essere ESATTAMENTE:
```html
<body>
```

**NON**:
```html
<!-- SBAGLIATO -->
<body class="page-tests-segnalazione-02-dati">
<body class="page-tests-segnalazione-01-privacy">
<body class="page-tests-ticket-list">
<body class="cmp-homepage-page">
```

## Rationale

Il reference Design Comuni (https://italia.github.io/design-comuni-pagine-statiche/) usa `<body>` senza classi custom. L'HTML parity richiede che la struttura HTML sia identica al reference.

Le classi custom sul body:
- Rompono la HTML parity
- Introducono marker non presenti nel reference
- Possono causare conflitti di specificità CSS
- Violano il principio di parity structure

## Approach Alternativo - CSS Scoping

Invece di usare body classes per scope CSS, usare solo hook parity-safe:

### Option 1: Hook strutturali gia presenti nel markup finale

```css
main #main-container .steppers-header,
main #main-container .cmp-nav-steps {
  /* stili specifici */
}
```

### Option 2: Data attributes applicativi gia stabili

```html
<div data-page="segnalazione-02-dati">
  <!-- contenuto -->
</div>
```

```css
/* CSS con data attribute */
[data-page="segnalazione-02-dati"] .header {
  /* stili specifici */
}
```

### Option 3: Classi componenti reali del reference o del blocco

```css
.steppers-header li.active,
.steppers-nav .steppers-btn-confirm {
  /* stili specifici */
}
```

## Pagine Affette

Le seguenti pagine attualmente hanno body classes custom che devono essere rimosse:

| Pagina | Body Class Attuale | Body Atteso |
|--------|-------------------|-------------|
| segnalazione-02-dati | `page-tests-segnalazione-02-dati` | `<body>` |
| segnalazione-01-privacy | `page-tests-segnalazione-01-privacy` | `<body>` |
| segnalazione-03-riepilogo | `page-tests-segnalazione-03-riepilogo` | `<body>` |
| segnalazione-04-conferma | `page-tests-segnalazione-04-conferfa` | `<body>` |
| segnalazione-area-personale | `page-tests-segnalazione-area-personale` | `<body>` |
| ticket-list | `page-tests-ticket-list` | `<body>` |
| homepage (design-comuni) | `cmp-homepage-page` / `dc-homepage-parity` | `<body>` |

## Root Cause

Il layout `bootstrap-italia.blade.php` usa:
```blade
<body class="@yield('body_class')">
```

La classe custom viene iniettata da:
1. Possibile generazione dinamica nel modulo Cms
2. Convenzione di naming basata sullo slug
3. Page model o middleware

## Fix Required

1. **Identificare** la source dell'iniezione della classe
2. **Rimuovere** l'iniezione per tutte le pagine tests
3. **Refactoring** CSS esistenti per usare hook strutturali reali o data-attr stabili invece del body selector
4. **Verificare** che la visual parity sia preservata senza body classes
5. **Documentare** la regola in coding standards

## CSS Refactoring Examples

### Before (WRONG - usa body class)
```css
body.page-tests-segnalazione-02-dati .it-header {
  display: flex;
  /* ... */
}
```

### After (CORRECT - usa wrapper)
```css
.segnalazione-02-dati-page .it-header,
[data-page="segnalazione-02-dati"] .it-header {
  display: flex;
  /* ... */
}
```

## Verification Checklist

- [ ] `<body>` tag senza attributo class in tutte le pagine tests
- [ ] HTML parity score mantenuto o migliorato
- [ ] Visual parity preservata con CSS scoped
- [ ] Nessun regresso in altre pagine
- [ ] Build Vite completata senza errori
- [ ] Screenshot verification post-fix

## Related Stories

- `7-27-segnalazione-02-dati-body-class-fix-and-visual-parity.md` - Story principale per il fix
- `7-24-segnalazione-02-dati-html-parity-final-residuals.md` - HTML parity residuals
- `7-25-segnalazione-02-dati-visual-parity-css-js-header-and-topbar.md` - Visual parity header
- `7-26-segnalazione-02-dati-visual-parity-last-mile.md` - Visual parity last mile

## References

- Reference HTML: https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-02-dati.html
- Theme layout: `laravel/Themes/Sixteen/resources/views/layouts/bootstrap-italia.blade.php`
- Body structure comparison: `laravel/Themes/Sixteen/docs/body-structure-comparison/`
- CSS scoping rule: [architecture/CSS-SCOPING-RULE.md](./architecture/CSS-SCOPING-RULE.md)
- Fixcity module rule: [../../../Modules/Fixcity/docs/html-body-parity-rule.md](../../../Modules/Fixcity/docs/html-body-parity-rule.md)

## Notes

Questa regola e fondamentale per mantenere HTML parity con il reference Design Comuni.
Tutte le future implementazioni devono rispettare questa convenzione.
