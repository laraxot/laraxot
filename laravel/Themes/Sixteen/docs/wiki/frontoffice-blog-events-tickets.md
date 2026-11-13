---
title: "Frontoffice — blog / events / tickets — stato attuale"
type: note
status: active
tags: [sixteen, frontoffice, blog, events, tickets, ponytail]
created: 2026-10-26
updated: 2026-10-26
qmd: "frontoffice-blog-events-tickets"
---

# Stato attuale — pagine /it/blog, /it/events, /it/tickets

> **Contesto:** richiesta utente "popola" con BMAD + second brain + ponytail. Non inventare oltre il necessario (Ponytail: riusa, minimum, documenta, non duplicare).

## Contratto architettura (SSoT)

| Pagina | CMS JSON (SSoT) | Blocco tema (SSoT) | Modulo dominio |
|---|---|---|---|
| `/it/blog` → `Novità` | `config/local/fixcity/database/content/pages/blog/index.json` | `pub_theme::components.blocks.blog.index` (`laravel/Themes/Sixteen/resources/views/components/blocks/blog/index.blade.php`) | Blog (`Modules/Blog`) |
| `/it/events` → `Vivere il comune` | `config/local/fixcity/database/content/pages/events/index.json` + `.lock` (DCP-289, agent w3d, 2026-10-26) | `pub_theme::components.blocks.events.index` (esiste nel tema) | Event (`Modules/Event`) |
| `/it/tickets` → `Segnalazioni` | `config/local/fixcity/database/content/pages/tickets/index.json` | `pub_theme::components.blocks.ticket-list.design-comuni` | Fixcity (`Modules/Fixcity`) |

## Cosa è già popolato (Ponytail: riusa)

- **CMS JSON:** tutti e tre i file `index.json` esistono, hanno `content_blocks` con blocchi `blog.index`, `events.index`, `ticket_list`, layout `design-comuni`, traduzioni `it/en/de/es`. Non mancano dati strutturali.
- **Tema blocco blog:** `blog/index.blade.php` esiste (268 righe), usa `ThemeComposer` (`Modules\Blog\View\Composers\ThemeComposer`), `route('container0.index', ['container0' => 'blog'])`, `featuredBlogPosts()`, `paginatedBlogPosts()`.
- **Tema blocco events:** `events-calendar` esiste (`laravel/Themes/Sixteen/resources/views/components/blocks/events-calendar`).
- **Tema blocco ticket:** `ticket-layout`, `ticket-list-layout`, `segnalazioni-elenco/` esistono.

## Cosa NON serve inventare (Ponytail: no over-engineering)

- Non serve creare nuovi file Blade nel tema (`no-module-folio-page-directories.md`: mai `pages/blog.blade.php` o simili; il CMS JSON è la fonte).
- Non serve duplicare il contenuto CMS in docs (DRY: il JSON è SSoT).
- Non serve modificare `manifest.json` (è artifact build, non CMS).

## Lock aperto (evidenza)

- `events/index.json.lock` indica `LOCKED at 2026-10-26 17:18`, task `DCP-289`, agent `w3d`. Il lock non deve impedire la lettura/verifica, ma indica che un task BMAD è in corso sul modulo Event. Non rimuovere senza coordinamento.

## Prossimo (documentato, non fatto qui)

1. Verificare con `php artisan` se le rotte `/blog`, `/events`, `/tickets` rispondono (il blocco tema esiste, ma il server non era raggiungibile durante questa sessione).
2. Se il DB non ha `Article`/`Event` pubblicati, il contenuto CMS non verrà renderizzato (il blocco usa `ThemeComposer` che legge dal DB). Il CMS JSON non sostituisce i dati DB, ma fornisce il layout e i blocchi.
3. Aggiornare `docs/wiki/index.md` (radice) con link a questo documento e ai docs dei moduli.

## Riferimenti (second brain + BMAD)

- `docs/wiki/concepts/agent-bootstrap-compact.md` (bootstrap)
- `docs/wiki/rules/00-TRIGGER_MAP.md` (trigger frontoffice)
- `docs/wiki/rules/no-module-folio-page-directories.md` (regola pagine)
- `docs/wiki/concepts/folio-container0-index-filament-way.md`
- `docs/wiki/skills/fixcity-inbound-editorial.md`
- `laravel/Themes/Sixteen/docs/bmad/blog-public-index.md` (contratto blog)
- `laravel/Modules/Event/docs/00-INDEX.md` (modulo Event)
- `laravel/Modules/Blog/docs/00-INDEX.md` (modulo Blog)
