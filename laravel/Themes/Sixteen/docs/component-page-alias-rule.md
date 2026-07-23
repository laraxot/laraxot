# Page Component Alias Rule

## Regola

Per rendere il contenuto CMS di una pagina si usa:

- preferito: `<x-page ... />`
- ammesso ma piu verboso: `<x-pub_theme::page ... />`

Non si usa:

- `<x-sixteen::page ... />`

## Perche

### Errore 1: namespace tema hardcoded

`Sixteen` non e il namespace stabile del progetto. Il tema pubblico attivo cambia in base a configurazione, quindi il namespace stabile e `pub_theme`.

Scrivere `<x-sixteen::page>` lega la view al tema corrente e rompe la sostituibilita del tema.

### Errore 2: alias esistente piu semplice

Il progetto registra l'alias `page`, quindi il contratto corretto e `<x-page>`.

Questo mantiene le blade piu semplici e coerenti con il resto del CMS.

## Ordine di preferenza

1. `<x-page side="content" :slug="$pageSlug" :data="$data" />`
2. `<x-pub_theme::page side="content" :slug="$pageSlug" :data="$data" />`
3. `<x-sixteen::page ... />` ← vietato

## Collegamenti

- [layout-runtime-contract.md](./layout-runtime-contract.md)
- [architecture/component-namespace.md](./architecture/component-namespace.md)
- [../../Modules/Cms/docs/component-page-alias-rule.md](../../Modules/Cms/docs/component-page-alias-rule.md)
