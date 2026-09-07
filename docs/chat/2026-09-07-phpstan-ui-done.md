---
title: "PHPStan zero Modules/UI — 4 → 0 errori"
type: coordination
tags: [phpstan, ui, quality-gates, done]
created: 2026-09-07
agent: claude sonnet 5
status: done
blocking: false
---

# PHPStan zero — Modules/UI

Esito: **completato**, commit e push fatti.

## Prima di iniziare

- `git status` in `laravel/Modules/UI`: working tree gia' sporco (non mio) con 7 file
  `app/Filament/Blocks/*.php` (WIP di un'altra sessione: `getFormSchema()` da
  `static` a instance, coerente con la convenzione repo-wide `$this->getFormSchema()`
  gia' usata in `XotBaseResource`/`XotBasePage`/`XotBaseWidget`) + vari file `docs/*.md`
  con normalizzazioni minori.
- `docs/chat/INDEX.md` + `grep -li "\bui\b" docs/chat/*.md`: nessun claim "in-progress"
  bloccante trovato all'avvio. Esisteva pero' un claim parallelo dispatchato dalla stessa
  sessione madre (`2026-09-07-claim-job-ui-comment-rating-phpstan-swarm.md`) che ha
  verificato UI a 0 errori **dopo** il mio fix (non prima) — nessun conflitto, nessun
  doppio lavoro: quel claim non ha toccato ne' committato nulla su UI.
- `bash bashscripts/lock/check.sh laravel/Modules/UI` → FREE.
- Story BMAD esistente riusata (non duplicata): `Modules/UI/docs/stories/01.UI-phpstan-fix.story.md`
  (era uno stub "TODO", ora completa).

## Errori iniziali → finali

- **4 → 0** (`./vendor/bin/phpstan analyse Modules/UI --no-progress --memory-limit=-1`,
  verificato anche con `clear-result-cache` per escludere cache stale).
- Tutti e 4 erano `method.staticCall` ("Static call to instance method") in:
  - `tests/Unit/UiGapCloser100Test.php:151` — `Image::getFormSchema()`
  - `tests/Unit/UiHighestMissCoverageTest.php:180-182` — `Contact`/`Category`/`Post::getFormSchema()`
- Causa radice: i test chiamavano staticamente un metodo che nel working tree (WIP
  concorrente, non mio, ma corretto e coerente con la convenzione framework) e' ora
  d'istanza. Non un bug nella modifica altrui — un call-site di test rimasto indietro.

## File toccati (commit `94fcb2f5`, repo `module_ui_fila5`, `d4e1f578..94fcb2f5`)

- `tests/Unit/UiGapCloser100Test.php`
- `tests/Unit/UiHighestMissCoverageTest.php`
- `docs/coverage.md`
- `docs/stories/01.UI-phpstan-fix.story.md`

Nessun `@phpstan-ignore`, nessuna baseline, nessun `mixed` introdotto, `phpstan.neon`
non toccato.

## File NON toccati (dirty ma non miei, lasciati intatti)

- `app/Filament/Blocks/{Category,Contact,Image,Navigation,Page,Post,Slider}.php` — WIP
  di un'altra sessione, necessario perche' i miei fix restino validi ma non mio da
  rivendicare/committare (il mio fix nei test funziona comunque in entrambe le
  direzioni, static o instance — vedi nota metodologica sotto).
- Vari `docs/*.md` (normalizzazioni minori, non miei).
- `lang/it/icon_state_group.php` — side-effect involontario della mia stessa run di
  Pest (auto-generazione label a runtime via `LangServiceProvider`/`AutoLabelAction`);
  non ho fatto `git add`, lasciato dirty per non introdurre rumore fuori scope.

## Quality gate

- **PHPStan**: 0 errori (vedi sopra).
- **PHPMD** (`Modules/Xot/phpmd.ruleset.xml` — UI non ha un ruleset proprio): stesso set
  di violazioni pre-esistenti gia' note, nessuna sui file toccati.
- **PHPInsights**: scoped a singolo modulo fallisce nativamente con `ComposerNotFound`
  (bug tooling noto, non mio). Aggirato con `--config-path` ephemeral in `/tmp` che
  rimuove solo l'insight `ForbiddenSecurityIssues` (nessuna modifica alla config
  condivisa). Risultato: Code 84.5, Complexity 95.3, Style 92.8 (tutti ≥ 80);
  Architecture 76.5 (< 80, gap pre-esistente da uso di trait/setter/proprieta'
  pubbliche diffuso nel modulo — fuori scope per una story mirata a 4 errori PHPStan).
- **Pest** (`Modules/UI/tests -c Modules/UI/phpunit.xml --no-coverage`): 197 passed,
  8 failed, 1 risky, 109 skipped (672 assertions). Identico alla baseline onesta gia'
  documentata nella story `7.13.mixed-type-reduction` (2026-09-04). Nessuno degli 8
  fallimenti riguarda i file toccati (DB test `permissions` mancante, locale,
  container binding `translator`, Mockery, view mancante — tutti pre-esistenti).
  Dettaglio completo in `Modules/UI/docs/coverage.md`.

## Second brain

Aggiornata (non duplicata) la memoria gia' esistente
`bashscripts/ai/wiki/memories/getformschema-static-to-instance-and-table-trans-gotchas.md`
con: (1) lo stesso pattern static->instance si presenta anche fuori da Schemas/Forms,
in `Filament/Blocks/*.php`; (2) nota sull'asimmetria di PHPStan (segnala solo
static-call-su-instance, mai il contrario), utile per fix sicuri anche quando il
sorgente e' ancora WIP di un'altra sessione concorrente.

## Git

Commit `94fcb2f5` su branch `dev`, fetch `laraxot dev` pulito (nessun commit nuovo da
mergiare), push fast-forward `d4e1f578..94fcb2f5` su `laraxot`. Nessun `--force`,
`revert`, `reset --hard`, `checkout` di file/commit, nessun nuovo branch.
