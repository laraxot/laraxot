---
title: "RESOLVED: PHPStan bootstrap fatal repo-wide — illegal getFormSchema()/getFormSchemaOld()/table() overrides on XotBaseResource"
type: coordination
tags: [phpstan, blocking, xot, resolved]
created: 2026-09-07T00:00:00Z
agent: claude (sonnet 5) — this session
status: resolved
blocking: false
---

# RESOLVED: il blocco repo-wide di PHPStan e' sbloccato

Segue `2026-09-07-URGENT-phpstan-bootstrap-fatal-ai-vs-xot-final-getformschema.md`.

Root cause reale (piu' ampia del singolo caso AI): `getFormSchema()`,
`getFormSchemaOld()` e `table(Table $table)` sono `final` in
`Modules\Xot\Filament\Resources\XotBaseResource`. Lo swarm
filament-canonical-structure aveva gia' creato i gemelli
`Schemas/{Model}Form.php` / `Tables/{Model}sTable.php` ovunque, ma non
aveva ancora rimosso ~130 override residui su ~30 moduli (incluse catene
indirette come `LangBaseResource` → `Cms\PageResource` etc.). Ogni file
cosi' rotto fatalizza il bootstrap di Filament per QUALSIASI scan
PHPStan, anche scoped a un modulo scorrelato.

Fix: rimossi gli override residui (verificato gemello esistente e
equivalente prima di cancellare — mai perso schema/colonne, il contenuto
vive gia' nel gemello). Dettaglio completo, incidente in corso d'opera e
metodologia:
`laravel/Modules/Xot/docs/stories/18.19.xotbaseresource-final-getformschema-table-illegal-override-repo-wide-fix.story.md`.

**Nota per chi sta ancora lavorando su Schemas/Tables extraction**: se il
tuo modulo aveva un override residuo, potrebbe essere gia' stato rimosso
(da questa sessione o da un'altra in parallelo — visti mtime identici su
piu' moduli, verosimilmente uno script bulk di qualcun altro ha chiuso
la maggior parte mentre questa story era in corso). Fai `git diff` sul tuo
modulo prima di ripetere il lavoro.

**Stato attuale verificato** (2026-09-07, questa sessione):
- `phpstan analyse Modules/Signature` → 0 errori (bootstrap sano).
- `phpstan analyse Modules` (full-tree) → completa senza fatal, **309 errori
  residui** (da 13417/43 moduli della baseline mattutina).

Prossimo: dispatch di sub-agenti paralleli sui moduli con errori residui
(vedi story successive / claim in arrivo) — verificare *questo* file e
`docs/chat/INDEX.md` prima di duplicare.
