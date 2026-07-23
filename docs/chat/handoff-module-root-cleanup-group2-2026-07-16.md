---
title: Handoff — Module root cleanup (group 2)
date: 2026-07-16
agent: cleanup-group2
modules: [Employee, Gdpr, Geo, Job, Lang]
---

# Module Root Cleanup — Group 2 Handoff

Rimozione di cartelle "scaffold" AI/tool/IDE e doc legacy dalle root dei 5 moduli
del gruppo 2, con documentazione del *perché*. Regola canonica:
[docs/wiki/rules/module-theme-root-cleanup.md](../wiki/rules/module-theme-root-cleanup.md).

Remote submodule: `laraxot` (non `origin`). Branch: `dev`.

## Contesto trovato

Una run precedente aveva già rimosso (working tree, **non committato**) gran parte
dello scaffold in Gdpr/Geo/Job/Lang e scritto i doc `no-ai-tool-scaffold-dirs.md`,
ma nulla era stato committato/pushato e il lavoro era incompleto (Geo `docs/scripts/`
e Job `.vscode/` ancora presenti). Ho completato, committato e pushato tutto.

## Per modulo

| Modulo | Trovato / Rimosso | Migrato | .gitignore | Push |
|---|---|---|---|---|
| **Employee** | root già pulita (nessuna forbidden dir) | — | +`.vscode/` (boy-scout) | `51acd63..206d529` |
| **Gdpr** | `docs/archive/` (dump legacy), `scripts/` | — (duplicati) | +sezione AI/TOOL SCAFFOLD | `8f661e2..86165b6` |
| **Geo** | `bashscripts/`, `scripts/`, `docs/workbench/`, `docs/scripts/` (55 file) | — nessun contenuto Geo-specifico: duplicati di `bashscripts/` root | +scaffold section | rebased su PHPStan fix remoto → `4151c0f..d9f2de6` |
| **Job** | `.vscode/` (config IDE), `bashscripts/`, `docs/archive/`, `docs/legacy/` | — (root ha già `.php-cs-fixer.php`) | +scaffold section | `6db8f5c..ddf90ba` |
| **Lang** | `bashscripts/`, `docs/archive/` (root dump) | — | +scaffold section | `b436d2b..f0cb1e9` |

### Contenuto nested TENUTO (non è il forbidden root `docs/archive/`)
- Gdpr: `docs/_integration/archive/cookie_consent.md` — contenuto GDPR reale.
- Lang: `docs/translations/archive/README.{it,es}.md`, `docs/_integration/archive/google_translate.md` — traduzioni/integrazioni reali.

Rimossa solo la `docs/archive/` a livello root (il dump legacy), non le sottocartelle
`archive/` annidate che contengono conoscenza ancora valida.

### Geo `docs/scripts/` — perché rimossa senza migrazione
55 file di tooling generico (git-conflict, docs-naming, phpstan, wiki, snapshot).
Verificato: ogni script ha già la copia canonica in `bashscripts/` alla root del
monorepo (es. `fix_git_conflicts.sh`, `check_before_phpstan.sh`, `sync_submodules.sh`).
Nessun riferimento a farmshop/geonames/geocod/province — solo la parola "comuni"
(=common). Duplicati puri → rimossi, non migrati.

## Il "why" / zen (scritto in ogni `docs/no-ai-tool-scaffold-dirs.md`)

Ogni modulo è anche un repo Git indipendente (submodule). I tool che girano nella
sua root non "sanno" di essere in un monorepo con convenzioni proprie e ci scrivono
il loro scaffold locale:
- **AI agent/skill** → `.kiro/`, `.claude-audit/`, `.ralph/`, `_bmad-output/`
- **CI template copiati modulo-per-modulo** → `.circleci/`, `test-results/`
- **IDE** → `.vscode/`, `.cursor/`, `.windsurf/`, `.devcontainer/`
- **Script one-off** → `scripts/`, `bashscripts/` (duplicano il tooling root)
- **Doc legacy** → `docs/archive|legacy|workbench/`

Lo zen: **una casa canonica per categoria** — conoscenza in `docs/wiki/`, tooling in
`bashscripts/` root, artefatti in `build/`, config IDE fuori dal tracking. Un secondo
posto per la stessa cosa è entropia. Il `.gitignore` aggiornato è preventivo: se il
tool rigenera la cartella, resta fuori dal versioning senza ripulire ogni sessione.

## Sanity
`phpstan analyse Modules/Geo Modules/Job` → nessun nuovo errore introdotto. L'unico
"errore" riportato è un pattern `@mixin` in `ignoreErrors` (neon) non matchato,
pre-esistente e non correlato (le dir rimosse non contengono PHP analizzato).

## Note per chi segue
- Remote è `laraxot`, non `origin`.
- I puntatori submodule nel repo-parent NON sono stati committati (fuori scope,
  altri agenti attivi in concorrenza).
