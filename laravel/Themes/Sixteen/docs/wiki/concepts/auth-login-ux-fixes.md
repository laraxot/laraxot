# Correzioni UX pagina login FO

## URL

`/it/auth/login` — Folio `pages/auth/login.blade.php` + `Auth\LoginWidget`.

## Problemi risolti

| Problema | Fix |
|----------|-----|
| Bottone submit marrone + testo scuro (WCAG fail) | `PaDesignColors` + `<x-filament::button color="primary">` (no hex in `14-auth-login.css`) |
| Label form `email` / `password` grezzi | `User/lang/*/login_widget.php` → struttura `fields.*.label` |
| Doppio titolo (H1 pagina + H2 widget) | Rimosso blocco titolo dal widget; un solo H1 `#auth-login-heading` |
| Errore login mostrava testo supporto password | Alert mostra `user::login.actions.login.error` + messaggio reale |
| Meta description = `Laravel` | Slot `metaDescription` → `auth.login.page.description.label` |
| Stili form incoerenti | `.fo-filament-form-shell` + token; layout in `14-auth-login.css` |

## Pattern

- **Pagina** = layout, titolo, supporto laterale (vestito)
- **Widget** = solo form Filament (corpo)
- Traduzioni widget: `user::login_widget.fields.*`

## Collegamenti

- [fo-pa-tokens-uniformity](fo-pa-tokens-uniformity.md)
- [auth-login-ux-design-wcag](../design/auth-login-ux-design-wcag.md)
- [login-page-design-comuni](../../../Modules/User/docs/wiki/concepts/login-page-design-comuni.md)
- `resources/views/pages/auth/login.blade.php`
- `resources/views/filament/widgets/auth/login.blade.php`
