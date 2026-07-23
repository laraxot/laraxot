# Segnalazione Disservizio - Documentation Index

Documentation for implementing the "Segnalazione Disservizio" (Report Issue) flow in Design Comuni pages.

## Overview

Implementation of the citizen report flow following Design Comuni Italia patterns.

## Reference

- **Design Comuni Reference**: https://italia.github.io/design-comuni-pagine-statiche/sito/ticket-list.html
- **Reference HTML**: [`reference_segnalazioni.html`](./reference_segnalazioni.html)
- **Local HTML**: [`local_segnalazioni.html`](./local_segnalazioni.html) (generated)

## Pages in Flow

| Step | Page | Slug | Blade |
|------|------|------|-------|
| 1 | Privacy | `/it/tests/segnalazione-01-privacy` | `[slug].blade.php` |
| 2 | Dati | `/it/tests/segnalazione-02-dati` | `[slug].blade.php` |
| 3 | Riepilogo | `/it/tests/segnalazione-03-riepilogo` | `[slug].blade.php` |
| 4 | Conferma | `/it/tests/segnalazione-04-conferma` | `[slug].blade.php` |
| Elenco | Elenco segnalazioni | `/it/tests/ticket-list` | `[slug].blade.php` |

## Architecture

- **Blade**: `laravel/Themes/Sixteen/resources/views/pages/tests/[slug].blade.php`
- **JSON Config**: `laravel/config/local/fixcity/database/content/pages/tests.*.json`
- **Component**: `laravel/Themes/Sixteen/resources/views/components/blocks/segnalazioni/layout.blade.php`
- **Translations**: `laravel/Modules/Fixcity/lang/{it,en}/segnalazione.php`
- **Translation Pattern**: `fixcity::segnalazione.{context}.{key}.{type}`

## Comparison Reports

- **Body Structure Comparison**: `laravel/Themes/Sixteen/docs/body-structure-comparison/ticket-list/`
  - [`report.md`](../body-structure-comparison/ticket-list/report.md)
  - [`summary.json`](../body-structure-comparison/ticket-list/summary.json)
  - [`diff_details.json`](../body-structure-comparison/ticket-list/diff_details.json)
  - [`reference-body.html`](../body-structure-comparison/ticket-list/reference-body.html)
  - [`local-body.html`](../body-structure-comparison/ticket-list/local-body.html)
  - [`ticket-list-html-parity-analysis.md`](./ticket-list-html-parity-analysis.md)

## CSS

- **Parity Styles**: `laravel/Themes/Sixteen/resources/css/segnalazione-parity.css`

## Related Documentation

- [Theme Docs Index](../README.md)
- [Body Structure Comparison Index](../body-structure-comparison/INDEX.md)
- [Root Bridge Config](../../../../../docs/html-structure-comparison.md)
