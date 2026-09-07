---
title: "CLAIM: Modules/Media phpstan-zero (03-quality-gates)"
type: coordination
tags: [phpstan, media, claim, quality-gates]
created: 2026-09-07T00:00:00Z
agent: claude sonnet 5 — subagent dispatched by this session
status: done
blocking: false
---

Claim su `Modules/Media`: 8 errori reali rilevati (full-tree scan 2026-09-07
~19:08, `method.staticCall`). Non in lista Epic4, nessun claim recente attivo
(solo hygiene root .md di stamattina, non phpstan), lock FREE al momento del
claim. Eseguo `bashscripts/docs/prompts/03-quality-gates.md`.

## Esito (2026-09-07, chiusura)

`phpstan analyse Modules/Media`: 8 → 0 errori, `phpstan.neon` invariato.
Causa: `getFormSchema()`/`getInfolistSchema()` su `XotBaseResourceForm`/
`XotBaseResourceInfolist` sono metodi d'istanza, non statici; 4 punti nel
modulo (1 produzione, 3 test) li chiamavano ancora staticamente
(`ClassName::getFormSchema()`). Fix: `app(ClassName::class)->getFormSchema()`,
convenzione gia' in uso in Geo/Activity/Notify/Tenant. Dettaglio completo:
`laravel/Modules/Media/docs/stories/01.Media-phpstan-fix.story.md`.

Commit: `9aff25eb` (module_media_fila5, branch dev), pushato su `laraxot/dev`.
File toccati: 1 produzione + 3 test + 1 story doc, staged esplicitamente
(niente `git add -A`).

Trovato nel working tree, non toccato, non committato: un ampio WIP
preesistente non mio (rimozione file `.bak`, pulizia `getFormSchema()`
legacy sulle Resource, pagine, conflitti di merge in `docs/`, un nuovo file
`lang/it/media_infolist.php`) — ownership di un'altra sessione, lasciato
intatto in working tree.

Follow-up aperto non risolto qui (fuori scope): `GenerateTemporaryUploadPathAction::getBasePath()`
assume `Media::getKey()` stringa ma la chiave e' intera — 1 test preesistente
rosso (`MediaFilamentAndActionsTest`), non introdotto da questa story.
