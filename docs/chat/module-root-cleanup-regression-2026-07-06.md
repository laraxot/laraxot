---
title: "Root moduli — regressione PascalCase + bonifica — 2026-07-06"
type: chat
created: 2026-07-06
updated: 2026-07-06
tags: [module-structure, cleanup, multi-agent]
related:
  - ../../laravel/Modules/Xot/docs/module-directory-structure-rule.md
---

# Root moduli — regressione e ri-bonifica — 2026-07-06 (notte)

L'utente ha segnalato che `Modules/Xot/{Datas,_docs,claude-code-bmad-skills,
Filament,Providers}` erano tornate — violazione della regola già "risolta"
il 2026-03-13 (vedi `Modules/Xot/docs/module-directory-structure-rule.md`).
Al momento della verifica un altro agente le aveva già rimosse in parallelo.

## Audit esteso a tutti i moduli

Eseguito il comando di verifica ufficiale su `Modules/*` (non solo Xot):
trovate e rimosse, dopo aver confermato che nessuna fosse l'unica copia
raggiungibile via autoload psr-4 (`Modules\X\` → `app/` in ogni
composer.json di modulo):

- `Modules/User/Listeners/AssignFreeCreditsListener.php` — non wired in
  `EventServiceProvider.php`, morto.
- `Modules/User/Application/UseCases/Owners/*` — duplicato quasi identico
  di `app/Application/UseCases/Owners/*` (solo differenze di formattazione
  phpdoc).
- `Modules/User/Events/UserRegistered.php` — versione stale; l'unico import
  reale (`Modules/Gdpr/app/Listeners/SaveGdprConsents.php`) risolve verso
  `app/Events/UserRegistered.php` via autoload, non verso la root.
- `Modules/User/Actions/CreateUserAction.php` — riferito solo in un file di
  docs, mai da codice.
- `Modules/User/Database/Migrations/` — **non** caricata da
  `XotBaseServiceProvider::boot()` (`loadMigrationsFrom` punta a
  `database/migrations` minuscolo, hardcoded). Mai eseguita nonostante
  contenuto diverso da `database/migrations/` — nessun rischio dati.
- `Modules/Notify/Models/{Theme,EmailTemplate}.php` — confermato dead code
  dal commento nel test stesso (`ThemeManagementBusinessLogicTest.php`):
  "these tests are skipped because the Theme model does not exist in the
  codebase". Non creato nulla, solo rimossa la root morta (il test resta
  skippato, correttamente).
- `Modules/Cms/Actions/ResolvePageContentAction.php` — solo un riferimento
  in docs, mai da codice.

**Eccezione**: `Modules/Xot/helpers/Helper.php` (lowercase) **non** toccato
— richiesto da `laravel/phpstan.neon` (`scanFiles`), di proprietà esclusiva
dell'utente; il proprietario stesso ha corretto la case del path in
questa sessione (`Helpers` → `helpers`).

## Altri file spazzatura trovati e rimossi

- `Modules/Xot/.gitattributes copy`, `Modules/Xot/_activity.code-workspace`
  (duplicato byte-identico di `_xot.code-workspace` — la regola ammette un
  solo `*.code-workspace`).
- `Modules/Activity/.md` — file vuoto (0 byte).
- `Modules/Cms/.docs-directory-violation-reminder.md` +
  `.docs_directory_violation_reminder.md` — due varianti quasi identiche,
  **contaminazione da un altro progetto** (`base_saluteora`, path
  `/var/www/html/_bases/base_saluteora/...`), non pertinenti a questo repo.

## Conflitto aperto (non risolto unilateralmente)

La checklist esistente in `module-directory-structure-rule.md` elenca
`CHANGELOG.md` tra i file ammessi alla root. L'istruzione dell'utente in
questa sessione dice invece "solo README.md". `CHANGELOG.md` esiste ancora
in `Activity`, `AI`, `Gdpr` — **non cancellato**, in attesa di conferma
esplicita (rischio di perdita di contenuto storico senza necessità).

## Verifica

`./vendor/bin/phpstan analyse Modules --no-progress` → `[OK] No errors`
dopo tutte le rimozioni.

— Claude (`claude-sonnet-5`)
