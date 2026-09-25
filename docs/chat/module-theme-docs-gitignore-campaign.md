---
title: "Module/theme docs and root gitignore campaign"
type: handoff
status: active
tags: [bmad, second-brain, modules, themes, gitignore, dashboard, audit]
created: 2026-09-25
updated: 2026-09-25
qmd: "module theme docs gitignore campaign dashboard XotBaseDashboard AuditCoverage"
related:
  - "../stories/2.4.module-theme-docs-hygiene.story.md"
  - "../../laravel/Modules/Xot/docs/wiki/concepts/tests-audit-coverage-forbidden.md"
  - "../../laravel/Modules/AI/docs/wiki/concepts/dashboard-page-contract.md"
  - "../../laravel/Themes/docs/README.md"
  - "../../laravel/Themes/docs/shared-components/module-dashboard-page-religion.md"
---

# Handoff — module/theme docs and root gitignore campaign

## Stato eseguito

- Il parent BMAD canonico è [Story 2.4](../stories/2.4.module-theme-docs-hygiene.story.md); non è stata creata una story duplicata.
- `Modules/AI/app/Filament/Pages/Dashboard.php` è verificato con parent diretto `XotBaseDashboard`; il sync concorrente ha corretto il parent senza rimuovere la view AI.
- Censimento statico: 38 `Dashboard.php`, 37 con parent `XotBaseDashboard`; `Blog` ha una regressione separata a `XotBasePage`. `PassportDashboard` è una pagina cluster legittima.
- Il runtime end-to-end del panel non è dichiarato verde: Xot contiene marker in `XotBasePanelProvider`, `XotBasePage`, `Dashboard`, `MainDashboard` e `GetModulesNavigationItems`; il owner deve fare il triage semantico.
- Il contratto e i backlink sono documentati in [dashboard-page-contract.md](../../laravel/Modules/AI/docs/wiki/concepts/dashboard-page-contract.md).
- Il root hub Themes è stato rifatto; il vecchio [DOCUMENTATION_INDEX.md](../../laravel/Themes/docs/DOCUMENTATION_INDEX.md) è marcato legacy/superseded.
- Il README e l’indice AI sono stati ripuliti: i link MCP, second-brain, Dashboard e wiki ora puntano a target esistenti; nessun placeholder è stato ricreato.
- Il batch randomizzato dei `.gitignore` copre tutti i **48 moduli reali**: `tests/AuditCoverage/`, `.agents/`, `.codex/`, `.cursor/`, `.opencode/`, `.qwen/`, `rector.php`, `phpinsights.php`, `.php-cs-fixer.php`, `.php-cs-fixer.dist.php`.
- Audit finale: **0** directory fisiche `tests/AuditCoverage/`; `TestModule` e `TimberBilling` sono placeholder senza `app/` + `composer.json` e restano esclusi.
- `Seo/.gitignore` era lockato ed è stato preservato; nessun lock è stato forzato.
- Decisione utente 2026-09-25: `audit-coverage/` root è ora un artefatto vietato; 48/48 `.gitignore` lo ignorano e la quiet window ha confermato zero directory `audit-coverage`/`AuditCoverage`. Xot/User hanno 452 cancellazioni tracciate, non staged.
- SSoT: [audit-coverage-root-artifact-forbidden](../wiki/concepts/audit-coverage-root-artifact-forbidden.md).

## Validazione

- Dashboard: `php -l` ✅, PHPStan canonico mirato ✅, PHPInsights ✅ con warning preesistente “normal classes forbidden”; il bootstrap completo non è reliable per i marker Xot.
- PHPMD: non eseguibile, PHAR/binario assenti.
- Pest AI completo: bloccato dal bootstrap duplicato `OpenAI`; non è una regressione del Dashboard.
- `.gitignore`: `git diff --check` ✅ su tutti i repository; nessun edit di `phpstan.neon`.
- BMAD formale: **NO-GO** finché `.bmad-core/core-config.yaml` non è presente; non creare configurazioni manuali.

## Lavoro differito (non toccare in blocco)

- 36 README moduli generici/legacy e link rotti; procedere per batch e owner.
- 201 filename theme con maiuscole e 20 con date: D9 richiede backlink e collision check prima di ogni rename.
- Indici theme frammentati (`Sixteen`, `Zero`, `TwentyOne`) e story non raggiungibili: aprire un batch separato.
- Marker di conflitto in `Tenant`, `Seo`, `UI`, `Activity`, `Lang`, `Employee` e Xot: triage semantico per modulo, mai resolver automatico.
- `Blog/app/Filament/Pages/Dashboard.php` richiede un audit separato: è un’altra regressione `XotBasePage`, non parte del fix AI.
- Xot/Notify/Activity/UI/User e altri repository con WIP: attendere il lock e lo status del submodule.

## Regole di continuazione

1. Controllare `git -C <module> rev-parse --show-toplevel`, lock e status prima di ogni edit.
2. Aggiornare prima la wiki/story owner, poi il codice o il docs locale.
3. Non fare `restore`, `reset`, `revert`, commit o push senza richiesta esplicita.
4. Per docs: YAML valido, nomi lowercase-kebab-case, link relativi verificati, backlink `README.md` ↔ `docs/README.md`.
5. Per `.gitignore`: preservare il contenuto WIP e aggiungere pattern mancanti; `audit-coverage/` ora è esplicitamente ignorato e la directory root non deve essere ricreata.

## riferimenti

- [Xot AuditCoverage rule](../../laravel/Modules/Xot/docs/wiki/concepts/tests-audit-coverage-forbidden.md)
- [Xot root hygiene](../../laravel/Modules/Xot/docs/wiki/concepts/module-theme-root-hygiene.md)
- [Themes hub](../../laravel/Themes/docs/README.md)
- [Dashboard panel contract](../../laravel/Themes/docs/shared-components/module-dashboard-page-religion.md)
