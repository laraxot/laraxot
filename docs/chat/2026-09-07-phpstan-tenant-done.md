---
title: "PHPStan Modules/Tenant → 0 errori (fix root-cause, WIP altrui completato)"
type: chat-handoff
tags: [phpstan, tenant, quality-gate, filament, xotbaseresource, gitignore]
created: 2026-09-07
status: completato
---

# PHPStan Tenant — 2026-09-07 (claude sonnet 5)

## Esito: completato

Modulo con WIP concorrente non committato all'avvio (rimozione dell'override inline morto
`DomainResource::getFormSchema()` + conversione `static` → istanza su `DomainForm`/
`DomainInfolist`, stesso pattern gia' chiuso repo-wide in
`Modules/Xot/docs/stories/18.19.xotbaseresource-final-getformschema-table-illegal-override-repo-wide-fix.story.md`),
ma **incompleto**: 3 chiamanti nei test non erano stati aggiornati, causando 3 errori
PHPStan reali (`method.staticCall`) che a runtime sarebbero stati `Error` fatali, non solo
tipizzazione. Completato il fix, verificato, committato e pushato.

## Pre-check (punto 1 del mandato)

- `git status` su `laravel/Modules/Tenant`: 3 file gia' modificati da un altro agente
  (`DomainResource.php`, `DomainResource/Schemas/DomainForm.php`,
  `DomainResource/Schemas/DomainInfolist.php`) — nessun claim "in-progress" recente su
  Tenant in `docs/chat/INDEX.md` o `grep -li tenant docs/chat/*.md` (l'unico riferimento
  attivo era lo swarm generico `migration-foreignidfor-swarm`, non specifico a questo file).
- Storia gia' esistente: `docs/stories/phpstan-tenant-fix.md` (status Done, 2026-09-06,
  0 errori confermati in quella sessione) + `01.Tenant-phpstan-fix.story.md` (pointer,
  superseded) + `tenant-mixed-type-reduction.story.md` (2026-09-04). Nessuna story duplicata
  creata: aggiornata `phpstan-tenant-fix.md` con una nuova sezione datata.
- Decisione: procedere completando il WIP altrui (root cause coerente e gia' nota), senza
  `git add -A` cieco — verificato `git status` prima di ogni add.

## Errori

- Iniziali: 3 — tutti `method.staticCall`:
  - `tests/Unit/TenantCoverageBoostTest.php:166` — `DomainForm::getFormSchema()`
  - `tests/Unit/TenantCoverageBoostTest.php:167` — `DomainInfolist::getInfolistSchema()`
  - `tests/Unit/TenantStatementCoverageTest.php:378` — `DomainResource::getFormSchema()`
    (ereditato da `XotBaseResource::getFormSchema()`, istanza)
- Finali: 0 (confermato con `clear-result-cache` + run pulito).

## Causa radice e fix

`XotBaseResourceForm::getFormSchema()` / `XotBaseResourceInfolist::getInfolistSchema()` sono
`abstract public function` (istanza, mai `static`). Chiamarli con `Class::metodo()` e' un
`Error` PHP 8 fatale a runtime — confermato isolando le 2 righe originali con `git stash`
mirato ai soli file di test, run isolato, poi `git stash pop`:

```
Error: Non-static method ...\Schemas\DomainForm::getFormSchema() cannot be called statically
Error: Non-static method Modules\Xot\Filament\Resources\XotBaseResource::getFormSchema() cannot be called statically
```

Fix (pattern gia' documentato in
`bashscripts/ai/wiki/memories/geo-addressform-schema-reuse-static-call-bug.md`):
`app(DomainForm::class)->getFormSchema()` / `app(DomainInfolist::class)->getInfolistSchema()`.
Il test su `TenantStatementCoverageTest.php` verificava lo schema sulla `DomainResource`
(bridge morto per costruzione, ritorna sempre `[]`): riscritto per verificarlo su
`DomainForm`, dove lo schema vive davvero. Rimossi gli `use` orfani
(`RichEditor`/`TextInput` su `DomainResource.php`, `DomainResource` su
`TenantStatementCoverageTest.php`). Nessun `@phpstan-ignore`, nessuna baseline, nessun tocco
a `phpstan.neon`, nessun `mixed` introdotto.

## File toccati

- `laravel/Modules/Tenant/app/Filament/Resources/DomainResource.php` (solo cleanup import;
  la rimozione dell'override era gia' WIP altrui)
- `laravel/Modules/Tenant/app/Filament/Resources/DomainResource/Schemas/DomainForm.php`
  (gia' WIP altrui, non ritoccato nel merito)
- `laravel/Modules/Tenant/app/Filament/Resources/DomainResource/Schemas/DomainInfolist.php`
  (idem)
- `laravel/Modules/Tenant/tests/Unit/TenantCoverageBoostTest.php`
- `laravel/Modules/Tenant/tests/Unit/TenantStatementCoverageTest.php`
- `laravel/Modules/Tenant/docs/coverage.md` (sezione 2026-09-07 aggiunta)
- `laravel/Modules/Tenant/docs/stories/phpstan-tenant-fix.md` (story esistente aggiornata,
  non duplicata — vedi anche nota gitignore sotto)
- `bashscripts/ai/wiki/memories/gitignore-phpstan-star-swallows-bmad-story-docs.md` (nuovo,
  finding generalizzabile, vedi sotto)

## Scoperta collaterale: `docs/stories/phpstan-tenant-fix.md` non era mai stato tracciato

Il file esisteva su disco dal 2026-09-06 con contenuto reale (sessione precedente), ma
`git ls-files`/`git log` non lo vedevano mai: era silenziosamente ignorato dal pattern
`.gitignore` `phpstan*` (riga nel blocco "Auto-generated forbidden paths — do not edit
manually", pensato per bloccare config tool alla root modulo ma, senza `/` iniziale, matcha
anche i doc sotto `docs/`). Verificato che lo stesso pattern esiste in almeno altri 4 moduli
(Lang, Media, Notify, UI) e che l'impatto reale include diverse BMAD story mai committate
(`docs/stories/phpstan-batch.story.md`, `phpstan-<modulo>-fix.md`, ecc.). Non ho toccato il
`.gitignore` (auto-generato, fix cross-modulo fuori scope per questo task): ho solo forzato
il tracking del file di mia competenza con `git add -f` e documentato il pattern in memoria
(`gitignore-phpstan-star-swallows-bmad-story-docs.md`) perche' e' un rischio repo-wide per
l'intera campagna "PHPStan zero" (story chiuse ma mai arrivate al remoto).

## Quality gate

- PHPStan `Modules/Tenant`: **0 errori** (cache pulita, confermato due volte).
- PHPMD (`./tools/phpmd.sh Modules/Tenant text phpmd.xml`): stesse 4 violazioni
  pre-esistenti gia' note (`module_dir`/`module_ns` su
  `RouteServiceProvider`/`TenantServiceProvider`, template XotBase), nessuna nuova.
- PHPInsights: fallisce con `ComposerNotFound` — bug tooling noto quando scoped a un
  singolo modulo, non causato da questa modifica.
- Pest (`Modules/Tenant/tests --no-coverage`, 515s su macchina condivisa con 8+ suite Pest
  di altri agenti in esecuzione parallela, load average ~40/24 core):
  **120 passed / 21 failed / 32 skipped (940 assertions)**. I 2 test isolati dal fix
  passano (prima: `Error` fatale). I 21 failed residui sono pre-esistenti e non toccati
  (`ApplicationPublicPathCoverageTest`, `SushiToJsonTraitTest`, `Traits/SushiToJsonTest`,
  vari "Unexpected mockery expectation type" in `TenantCoverageBoostTest`/
  `TenantGapsCoverageTest`/`TenantStatementCoverageTest`) — verificato per esclusione che
  nessuno referenzia `DomainForm`/`DomainInfolist`/`DomainResource`.
- Coverage (pass/fail Pest, non code coverage %): baseline nota 2026-09-04 =
  81 passed/64 failed/28 skipped; oggi = 120 passed/21 failed/32 skipped. Nessuna
  regressione, dettaglio in `docs/coverage.md`.

## Git

- Commit `163c9cd` su `Modules/Tenant` `dev` (7 file, incluso `docs/stories/phpstan-tenant-fix.md`
  aggiunto con `git add -f` per il motivo sopra).
- `git fetch laraxot dev` → nessuna divergenza → push diretto, nessun merge necessario.
- `git push -u laraxot dev` → **riuscito**, `9b6269e..163c9cd`.
- Commit `304826cf` su `bashscripts` `dev` (solo il file di memoria di mia competenza,
  altri file untracked di altri agenti nella stessa cartella lasciati intatti) → push
  riuscito, `1ba01234..304826cf`.

## Non toccato (di proposito)

- Contenuto nel merito di `DomainForm.php`/`DomainInfolist.php`/`DomainResource.php` oltre
  al cleanup import — WIP di un altro agente, root cause gia' corretta, non ritoccata.
- I 21 test Pest falliti pre-esistenti — fuori scope, non referenziano i file di questa
  story.
- `.gitignore` del modulo — auto-generato, fix cross-modulo richiede coordinamento owner.
