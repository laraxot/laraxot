---
title: "page directory structure"
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-10-22
qmd: "page directory structure"
issues: []
discussions: []
status: draft
---

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

In radice di `pages/` l'**unico file** ammesso e' `index.blade.php` (serve `/{lingua}` montando `home.json`).
`home.blade.php`, `homepage.blade.php` o qualunque altra pagina in radice sono vietati: seconda homepage, `name('home')`
duplicato, file morti che perdono contro `[container0]`. Perche', con le misure:
[why-home-blade-must-not-exist.md](bmad/knowledge/why-home-blade-must-not-exist.md).

## Directory ammesse

- `resources/views/pages/[container0]`
- `resources/views/pages/auth`
- `resources/views/pages/tests`

## Directory vietate

Qualunque directory il cui nome è una pagina (`domande-frequenti`, `blog`, `eventi`, `tickets`) è vietata, anche se non è in un elenco. Folio fa vincere quel nome su `[container0]`: la URL esce dallo slug CMS e da `<x-page>`.

Esempio: `pages/domande-frequenti/index.blade.php` è sbagliato. Folio prova quella cartella prima di `[container0]`, e `/it/domande-frequenti` esce dallo slug `domande-frequenti.index`. Il perché: [why-domande-frequenti-blade-php-must-not-exist.md](bmad/knowledge/why-domande-frequenti-blade-php-must-not-exist.md).

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

I JSON del tenant seguono la regola opposta: una cartella per contenitore (`pages/services/view.json`, `pages/area-personale/pratiche.json`). È il CMS a conoscere la tassonomia, non l'albero Folio. Vedi [json-content-slug-uniqueness.md](../../../Modules/Cms/docs/json-content-slug-uniqueness.md).

## Perché

- DRY: un solo router tematico dinamico invece di molti alberi duplicati.
- KISS: meno viste Folio special-case, meno collisioni, meno manutenzione.
- Portabilità: il tema pubblico cambia da `pub_theme`, quindi il routing del tema deve restare generico.
- Coerenza CMS: i contenuti si risolvono da slug e JSON, non da una directory dedicata per dominio editoriale.

## Pattern richiesti

### Tests

Il file [`[slug].blade.php`](../resources/views/pages/tests/[slug].blade.php) è l'unico entrypoint dinamico per tutte le pagine `tests/*`.

Non creare sotto-directory semantiche come `pages/tests/segnalazioni/`: il
segmento `segnalazioni-*` è parte dello slug CMS, non una directory Folio.

Il file [`index.blade.php`](../resources/views/pages/tests/index.blade.php) è l'entrypoint per `/tests`.

### Frontoffice dinamico

Il pattern generico resta:

- `resources/views/pages/[container0]/index.blade.php`
- `resources/views/pages/[container0]/[slug0]/index.blade.php`

### Pagine di dettaglio: chi serve cosa (misurato 2026-10-26, DCP-273)

Il dettaglio di un articolo, di un evento e di una segnalazione non ha una pagina propria: lo serve **la sola** pagina
`[container0]/[slug0]/index.blade.php` (route name `container0.view`). Per sapere chi serve un URL si risolve la rotta
(`php artisan route:list`, o una richiesta HTTP), non si leggono le cartelle `pages` dei moduli.

| URL | Chi decide | Quando risponde 404 |
|---|---|---|
| `/{lang}/blog/{slug}` | `Cms\ResolvePageAction` -> `Blog\ThemeComposer::findPublishedPostBySlug` (`content_type='post'`, pubblicato, `published_at` non nel futuro) | mai: ripiega sul template CMS `blog.view` (vedi sotto) |
| `/{lang}/events/{slug}` | `Cms\ResolvePageAction` -> `Blog\ThemeComposer::findPublishedEventBySlug` (`content_type='event'`, pubblicato) | mai: ripiega su `events.view` |
| `/{lang}/tickets/{id}` | `$specialPage = 'ticket-detail'` se `ctype_digit($slug0)`; dati da `Fixcity\BuildPublicTicketDetailAction` | `abort_if(null, 404)` se la segnalazione non e' visibile a un anonimo |

- Le pagine `Modules/Blog/resources/views/pages/blog/[slug].blade.php` e `events/[slug].blade.php` (route name `blog.show`,
  `events.show`) sono oscurate da questo jolly. **Non si cancellano**: `route('events.show', ...)` e' usata da
  `pages/comune/homepage.blade.php` e da `templates/home.blade.php`.
- Ogni URL di dettaglio pubblico entra nella sitemap tramite `Seo\Contracts\SitemapPathsContract`, dichiarato dal modulo proprietario
  (vedi [DCP-273](../../../Modules/Seo/docs/stories/dcp-273-sitemap-con-le-pagine-di-dettaglio-di-blog-ed-eventi.story.md)).
  Un test confronta i link di `/it/blog` e `/it/events` con la sitemap.
- **Difetto noto (soft-404, non corretto):** un articolo che non esiste (`/it/blog/slug-che-non-esiste`) risponde 200 con il titolo
  «Novita'», perche' la pagina ripiega su `Page::findUniqueBySlug($container0.'.view')`. Per un motore di ricerca e' una pagina vuota con codice
  200. La correzione tocca `Cms\ResolvePageAction` e questa pagina (file condivisi): va decisa con una story, non con un workaround nel tema.

## Layout contract

Le pagine Folio non devono contenere header, footer o skiplink inline. Quegli elementi vivono nel layout:

- [`app.blade.php`](../resources/views/layouts/app.blade.php)
- [`main.blade.php`](../resources/views/layouts/main.blade.php)

## Enforcement (agenti + CI)

- Pest (l'unico controllo che gira, 0,2 s, senza DB): `cd laravel && vendor/bin/pest Themes/Sixteen/tests/Unit/NoSemanticFolioPageDirectoriesTest.php`.
  **Nessuna CI lo esegue** (misurato 2026-10-22: `ci.yml` costruisce solo il tema, `module-quality-check.yml` lancia solo i test di Notify):
  rilanciarlo prima di ogni commit che tocca `pages/`.
- Hook agenti: `bashscripts/ai/.agents/hooks/theme-pages-guard.sh` rifiuta la creazione di un file in una cartella nuova di `pages/`.
- Wiki: [sixteen-pages-folder-religion.md](wiki/rules/sixteen-pages-folder-religion.md)

## Un URL di modulo risponde 404: cosa fare (e cosa NO)

Il jolly `[container0]/[slug0]` oscura le pagine Folio dei moduli: `/it/profile/edit` da' 404 anche se
`Modules/User/resources/views/pages/profile/edit.blade.php` esiste (misurato 2026-10-22).

| Situazione | Azione |
|---|---|
| Nessuna pagina del FO linka quell'URL | lasciare il 404; correggere i link che lo emettono |
| Serve davvero una pagina | JSON CMS `config/local/fixcity/database/content/pages/<container>/<slug>.json` + widget nel **modulo owner** |
| Serve proprio la pagina del modulo | decisione di prodotto, non un workaround |
| **MAI** | `pages/<dominio>/...` nel tema, nemmeno come alias o redirect (caso reale: `profile/edit`, tre volte) — [why-profile-edit-blade-must-not-exist.md](./bmad/knowledge/why-profile-edit-blade-must-not-exist.md) (D34) |

## Collegamenti

- [why-profile-edit-blade-must-not-exist.md](./bmad/knowledge/why-profile-edit-blade-must-not-exist.md) — perché non ricreare `pages/profile/`
- [folio-no-semantic-pages-tickets.md](./wiki/concepts/folio-no-semantic-pages-tickets.md) — post-mortem STORY-285 (`sixteen::` / `pages/tickets`)
- [README.md](./README.md)
- [layout-runtime-contract.md](./layout-runtime-contract.md)
- [component-page-runtime.md](./component-page-runtime.md)
- [../../Modules/Cms/docs/page-directory-structure.md](../../../Modules/Cms/docs/page-directory-structure.md)
