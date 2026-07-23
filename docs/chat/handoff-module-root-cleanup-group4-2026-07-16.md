---
title: "Handoff — Module Root Cleanup (Group 4: Tenant, UI, User, Xot)"
type: handoff
date: 2026-07-16
issue: https://github.com/laraxot/base_techplanner_fila5/issues/18
related:
  - "../wiki/rules/module-theme-root-cleanup.md"
---

# Handoff — Module Root Cleanup, Group 4

Rimozione delle cartelle scaffold AI/tool e delle archivi stantie da 4 moduli, con
hardening del `.gitignore` (pattern annidati `docs/**/archive/` ecc.) e documento "why"
per modulo. Regola canonica: [module-theme-root-cleanup.md](../wiki/rules/module-theme-root-cleanup.md).

Nota importante: il remote dei sottomoduli è `laraxot`, **non** `origin`. Push: `git push laraxot HEAD:dev`.

## Tenant — DONE, pushed (`86c513b`)
- Rimosse: `docs/{it,traits,en,_integration,it/config}/archive` (10 file, duplicati esatti di doc vivi).
- `.gitignore` riscritto (grouped/deduped + `docs/**/archive|legacy|scripts|bashscripts/`).
- Doc: `docs/module-root-cleanup.md`.
- Working tree pulito, push fast-forward.

## UI — DONE, pushed (`a78154c`)
- Adottato il lavoro non committato di un run precedente (cancellazioni già nel working tree)
  e completato: rimosse `_docs/`, `scripts/ci/`, `.claude-audit/`, `docs/archive/` (151 file)
  + le archivi annidate `docs/{filament,components,roadmap,_integration}/{archive,legacy}`.
- `.gitignore` riscritto; doc `docs/no-ai-tool-scaffold-dirs.md` arricchito.
- Rebase su `laraxot/dev` + push.
- **Da valutare a parte**: `resources/views/components/blocks/stats/archived/` (4 blocchi Blade
  non referenziati). NON rimossa — è codice applicativo, non sotto `docs/`, e i blocchi del
  page builder possono essere risolti dinamicamente per nome. Fuori dallo scope "forbidden folders".

## User — DONE (commit selettivo), pushed (`ba48d89f`)
- **Attenzione**: il modulo aveva ~1186 file di WIP non committato di un ALTRO agente
  (refactor Services/Support → Actions: modifiche app/, `GetPermissionModelAction.php`,
  `services-support-to-actions.md`, + ~1153 cancellazioni di doc non-forbidden). **Lasciato
  intatto e non committato.**
- Committati SOLO i file del mio scope: rimozione `bashscripts/`, `scripts/ci/`,
  `.claude-audit/`, `test-results/`, `docs/scripts/` e le archivi/legacy annidate sotto
  `docs/*`; `.gitignore` riscritto; doc `docs/no-ai-tool-scaffold-dirs.md` arricchito.
- Diverged (behind 1 / ahead 1): stash -u del WIP → rebase su `laraxot/dev` (commit remoto
  "Check & fix styling" su HasTeams.php, nessun overlap) → push → stash pop. WIP ripristinato.
- phpstan NON eseguito: il working tree contiene il refactor incompleto dell'altro agente
  che dominerebbe l'output; la mia modifica è solo docs/gitignore.

## Xot (modulo base) — DONE (commit selettivo), pushed (`0ee34657`)
- Stesso scenario: ~1819 file di WIP di un altro agente (refactor app/Actions + grandi
  modifiche doc) + un commit non pushato preesistente (`PestFunctionBridge`). Lasciato intatto;
  committato solo il mio scope.
- Rimosse: root `_docs/`, `scripts/`, `bashscripts/`, `.claude-audit/`; sotto docs:
  `docs/scripts/`, `docs/bashscripts/`, `docs/actions/archive/`, `docs/filament/archive/`,
  `docs/consolidated/archive/` (602 file), `docs/consolidated/phpstan/archive/`. Tutte
  git-tracked ⇒ recuperabili via history; 40/40 campionate in consolidated/archive avevano
  l'equivalente vivo.
- **Reperto**: `docs/scripts/cleanup-docs.sh` era la causa meccanica del ripresentarsi di
  `docs/archive/` — creava `docs/archive/{historical,duplicates,uppercase}/` e ci spostava
  dentro file datati/duplicati/UPPERCASE (con path hardcoded a `base_ptvx_fila5_mono`).
  Rimosso, non migrato: la deduplica corretta è `git mv`/`git rm`, non un cimitero `archive/`.
- `.gitignore` riscritto (fix anche della riga corrotta `*~headagentdb.rvf`); doc base
  extra-esteso `docs/no-ai-tool-scaffold-dirs.md` (tabella "una fonte di verità per categoria").
- push fast-forward. phpstan NON eseguito (WIP altrui + Xot già bloccato repo-wide da
  reference a TenantService rimosso, vedi memory).

## Riepilogo pattern .gitignore aggiunto (tutti i moduli)
Sezione `AI/TOOL SCAFFOLD` con, oltre ai path root, le varianti annidate:
`docs/**/archive/`, `docs/**/archived/`, `docs/**/legacy/`, `docs/**/workbench/`,
`docs/**/scripts/`, `docs/**/bashscripts/` — così anche `docs/it/archive/` ecc. restano
fuori dal tracking (i pattern piatti `docs/archive/` non le intercettavano).

## Note per chi riprende User/Xot
Il WIP non committato in User e Xot è di altri agenti (refactor Services/Support→Actions /
app/Actions). Va committato dai rispettivi owner. I miei commit sono disgiunti (solo
docs/gitignore + rimozione forbidden folders) e non toccano quei file.
