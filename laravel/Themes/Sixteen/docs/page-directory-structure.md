# Sixteen Page Directory Structure

## Regola canonica

Nel tema `Sixteen` la cartella `resources/views/pages` deve restare minimale e agnostica.

Sono ammessi solo questi entrypoint:

```text
resources/views/pages/
├── [container0]/
│   └── [slug0]/
├── auth/
└── tests/
```

## Directory ammesse

- `resources/views/pages/[container0]`
- `resources/views/pages/auth`
- `resources/views/pages/tests`

## Directory vietate

Non devono esistere directory semantiche hardcoded come:

- `resources/views/pages/administration`
- `resources/views/pages/ambiente`
- `resources/views/pages/article`
- `resources/views/pages/articles`
- `resources/views/pages/categories`
- `resources/views/pages/cultura`
- `resources/views/pages/dashboard`
- `resources/views/pages/eventi`
- `resources/views/pages/famiglia`
- `resources/views/pages/genesis`
- `resources/views/pages/lavoro`
- `resources/views/pages/learn`
- `resources/views/pages/mobilita`
- `resources/views/pages/news`
- `resources/views/pages/pages`
- `resources/views/pages/profile`
- `resources/views/pages/salute`
- `resources/views/pages.tickets`
- `resources/views/pages/services`
- `resources/views/pages/sport`
- `resources/views/pages/tickets`
- `resources/views/pages/turismo`

## Perché

- DRY: un solo router tematico dinamico invece di molti alberi duplicati.
- KISS: meno viste Folio special-case, meno collisioni, meno manutenzione.
- Portabilità: il tema pubblico cambia da `pub_theme`, quindi il routing del tema deve restare generico.
- Coerenza CMS: i contenuti si risolvono da slug e JSON, non da una directory dedicata per dominio editoriale.

## Pattern richiesti

### Tests

Il file [`[slug].blade.php`](../resources/views/pages/tests/[slug].blade.php) è l'unico entrypoint dinamico per tutte le pagine `tests/*`.

Il file [`index.blade.php`](../resources/views/pages/tests/index.blade.php) è l'entrypoint per `/tests`.

### Frontoffice dinamico

Il pattern generico resta:

- `resources/views/pages/[container0]/index.blade.php`
- `resources/views/pages/[container0]/[slug0]/index.blade.php`

## Layout contract

Le pagine Folio non devono contenere header, footer o skiplink inline. Quegli elementi vivono nel layout:

- [`app.blade.php`](../resources/views/layouts/app.blade.php)
- [`main.blade.php`](../resources/views/layouts/main.blade.php)

## Enforcement (agenti + CI)

- Rule Cursor: `.cursor/rules/no-semantic-folio-page-dirs.mdc`
- Wiki: [no-semantic-folio-page-directories.md](../../../../docs/wiki/rules/no-semantic-folio-page-directories.md)
- Script: `bash bashscripts/tools/verify-no-semantic-folio-pages.sh`
- Pest: `NoSemanticFolioPageDirectoriesTest.php`

## Collegamenti

- [folio-no-semantic-pages-tickets.md](./wiki/concepts/folio-no-semantic-pages-tickets.md) — post-mortem STORY-285 (`sixteen::` / `pages/tickets`)
- [README.md](./README.md)
- [layout-runtime-contract.md](./layout-runtime-contract.md)
- [component-page-runtime.md](./component-page-runtime.md)
- [../../Modules/Cms/docs/page-directory-structure.md](../../Modules/Cms/docs/page-directory-structure.md)
