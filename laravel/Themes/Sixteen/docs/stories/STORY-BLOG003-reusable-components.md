# STORY-BLOG003 — Componenti Riutilizzabili (Reusability)

## Obiettivo
I componenti in `components/blocks/` devono essere riutilizzabili (reusable pieces). Non devono essere specifici a una singola pagina.

## Decisioni (EEC)
- Ogni componente `blocks/*.blade.php` deve usare `@props` per la configurazione.
- Deve accettare `data` come array e non dipendere da configurazioni hardcoded.
- La logica `ThemeComposer` è separata dalla logica di presentazione.

## Implementazione
- Blog: `components/blocks/blog/index.blade.php` — configurabile via `blog.index.json`
- Events: `components/blocks/events/index.blade.php` — configurabile via `events.index.json`
- Container0: `pages/[container0]/index.blade.php` — generico per CMS dinamico
