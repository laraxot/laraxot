# Login `/it/auth/login` — fix UX/a11y (2026-06-04)

## Bottone submit + campo password (2026-06-04)

**Sintomi:** submit blu/ambra; password senza bordo (email sì).

**Cause:** asset Vite non ribuildati (`public_html/themes/Sixteen`); `.bg-primary` legacy = blu `#0066CC`; bordo Filament sul wrapper, password con suffix reveal.

**Fix:**

- `14-auth-login.css`: bordo unico su `.fi-input-wrp` (flex + `:has(.fi-revealable)`); input interni `border: none`
- Submit: `<x-filament::button color="primary">` + `PaDesignColors` (no hex in `14-auth-login.css`)
- `PaDesignColors` + `FilamentColor::register()` in `XotServiceProvider`
- `npm run build` in `Themes/Sixteen` (obbligatorio dopo edit CSS)

**Contrasto CTA:** ~4.5:1+ su verde PA + testo bianco.

**Validazione:** PageSpeed Insights + Mauve (STORY-137 MCP) su URL login.

## Problemi risolti

| Issue | Fix |
|-------|-----|
| Skip link `#main-container` senza target | `id="main-container"` + `tabindex="-1"` su `<main>` in `layouts/app.blade.php` |
| Errore login mostrava solo hint password | Alert mostra `$loginError` + hint separato |
| Doppio H1/H2 ridondante | Un solo `h1` pagina; titolo form → `p#auth-login-form-heading` |
| CTA registrazione duplicata | Rimossa banda pagina; link restano nel widget |
| Touch target / focus Filament | `app/14-auth-login.css` scoped `data-page="auth-login"` |
| Meta description | `:metaDescription` da traduzione pagina |

## Pattern

- Pagina: `pages/auth/login.blade.php` + `LoginWidget` (Filament-first)
- Stili: `fo-filament-form-shell.css` + `14-auth-login.css` (layout only)
- ADR: [fo-pa-tokens-uniformity.md](../architecture/fo-pa-tokens-uniformity.md)

## Anti-pattern rimosso (architettura)

**Vietato** il blocco CSS con `body[data-page='auth-login']` + `#007A52 !important` su submit/focus — non è il modello [design-comuni-pagine-statiche](https://github.com/italia/design-comuni-pagine-statiche). Vedi ADR root [fo-pa-tokens-no-per-page-hex.md](../../../../docs/wiki/decisions/fo-pa-tokens-no-per-page-hex.md).

## Verifica

```bash
curl -sL http://127.0.0.1:8000/it/auth/login | grep -c 'id="main-container"'
# atteso: >= 1
```

## Collegamenti

- [login-agid-fix-complete.md](./login-agid-fix-complete.md)
- [filament-first-frontoffice.md](../wiki/concepts/filament-first-frontoffice.md)
