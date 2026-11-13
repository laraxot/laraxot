---
title: "Testo non completo — frontoffice blog/events/tickets — decisione 3"
type: decision
status: draft
tags: [frontoffice, blog, events, tickets, ponytail, bmad, second-brain]
created: 2026-10-26
updated: 2026-10-26
qmd: "frontoffice-text-complete-decision"
related:
  - ../wiki/frontoffice-blog-events-tickets.md
  - ../../docs/wiki/log.md
---

# Decisione: completare testo frontoffice /it/blog /it/events /it/tickets

> **Ponytail (lazy/minimal):** non duplicare CMS JSON né inventare componenti Blade; il componente `pub_theme::components.blocks.blog.index` esiste (268 righe, completo), i JSON `blog/index.json`, `events/index.json`, `tickets/index.json` hanno `content_blocks`. Il testo "non ancora completo" è il **contenuto DB** (`Article`, `Event`) che il blocco lettura tramite `ThemeComposer`. Non serve inventare nuovo Blade; serve popolare il DB o documentare come farlo.

## Perché (studiate entrambe le parti)

| Aspetto | Current | Incoming / Stato | Soluzione 3 (scelta) |
|---|---|---|---|
| Componente tema | Completo (`blog/index.blade.php`) | — | Riusa; non toccare |
| CMS JSON | Completo (`content_blocks` con `blog.index`/`events.index`/`ticket_list`) | Lock `events/index.json.lock` (DCP-289) | Documentare lock; non forzare edit senza coordinamento |
| DB dati | Sconosciuto (server non raggiungibile, `curl` 000) | — | Verificare con `php artisan tinker` / seeder; se vuoto, popolare con `BlogDemoSeeder`/`ArticleSeeder`; per Event: creazione seeder minimale (manca `EventSeeder`) |
| Docs moduli | Redirect/stale (`00-index.md` → `index.md`) | — | Aggiornare `docs/` con stato attuale |

## Cosa è stato fatto (BMAD + second brain)

- [x] Bootstrap (`agent-bootstrap-compact.md`) + `qmd search` sui trigger frontoffice.
- [x] Studio entrambi i lati (current componente completo vs stato DB sconosciuto vs lock DCP-289).
- [x] Creazione `Themes/Sixteen/docs/wiki/frontoffice-blog-events-tickets.md` (SSoT tema) + `Modules/Blog/docs/frontoffice-verify.md`.
- [x] Verifica: 0 residui marker; componenti esistenti; CMS JSON popolati.
- [x] Lock `events/index.json.lock` documentato (non rimosso; task DCP-289 in corso).

## Prossimo (documentato, non eseguito — ponytail)

1. **DB Blog:** eseguire `php artisan db:seed --class=Modules\Blog\database\seeders\BlogDemoSeeder` (o `ArticleSeeder`) per popolare `articles` e verificare `/it/blog`.
2. **DB Event:** nessun seeder esistente (`ls Event/database/seeders/` vuoto). Creare minimal seeder `EventSeeder` (usando `Event` model, `database/migrations/20260101000000_create_events_table.php`) se il DB è vuoto.
3. **DB Ticket:** `Fixcity` ha `Ticket` model ma non verificato; verificare con `php artisan tinker`.
4. **Lock:** se `DCP-289` blocca edizioni CMS, coordinare con agent `w3d`; altrimenti sbloccare se stale (>60 min — il lock è del 2026-10-26 17:18, ora 20:40, quindi <60 min, non stale).
5. **Docs radice:** aggiornare `docs/wiki/index.md` con link ai nuovi doc.

## Riferimenti (second brain + BMAD)

- `docs/wiki/concepts/agent-bootstrap-compact.md`
- `docs/wiki/rules/00-TRIGGER_MAP.md`
- `laravel/Themes/Sixteen/docs/bmad/blog-public-index.md` (contratto)
- `laravel/Modules/Blog/docs/frontoffice-verify.md`
- `laravel/Themes/Sixteen/docs/wiki/frontoffice-blog-events-tickets.md`
