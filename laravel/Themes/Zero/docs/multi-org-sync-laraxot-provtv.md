---
title: "Sincronizzazione multi-organizzazione (laraxot + provtv)"
type: concept
tags: [git, sync, multi-org, laraxot, provtv, quality-gates]
created: "2026-07-21"
updated: "2026-07-23"
related:
  - "../../../bashscripts/tools/prompts/02-gitmodules-sync.md"
---

# Sincronizzazione multi-organizzazione (laraxot + provtv)

## Cosa è stato fatto

Questo repository è tracciato da due remote GitHub (`laraxot` = org upstream canonica,
`provtv` = org operativa del progetto ptvx). Il 2026-07-21 è stata eseguita una
sincronizzazione completa seguendo `bashscripts/tools/prompts/02-gitmodules-sync.md`:
fetch di tutti i remote, quality gates (PHPStan L10, PHPMD), risincronizzazione dopo ogni modifica.

## Problemi riscontrati e risolti

- **Clone shallow**: il repo era stato clonato con storia troncata, causando push
  respinti (`did not receive expected object`). Fix: `git fetch --unshallow` su tutti i remote.
- **Storie scollegate ("unrelated histories")**: alcuni repo avevano un branch `dev`
  remoto rigenerato senza antenato comune con la storia locale. Risolto con
  `git merge --allow-unrelated-histories`, verificando caso per caso i conflitti
  "add/add" (nella maggior parte dei casi contenuto identico, differenze reali
  risolte a mano confrontando i diff).

- **Storia scollegata risolta** con `laraxot/dev` (stesso pattern di Notify/Rating).

## Regola per il futuro

Prima di un merge/rebase su questo repo, controllare sempre `git remote -v` e
sincronizzare **tutti** i remote elencati, non solo `origin`/`provtv`. Mai forzare
push distruttivi su storie scollegate: preferire `--allow-unrelated-histories` e
revisione manuale dei conflitti reali.

### Playbook push dual-remote (2026-07-22, canon UI)

Se `unpack failed` / `did not receive expected object` → `git push --no-thin`.
Se `GH008` / LFS missing su un org e l’altro ha già accettato il tip →
`git lfs fetch <sibling> --all` poi `git lfs push <target> --all`, poi push.
Dettaglio (SSoT): [../../../Modules/UI/docs/wiki/troubleshooting/git-push-lfs-missing-objects.md](../../../Modules/UI/docs/wiki/troubleshooting/git-push-lfs-missing-objects.md).
Niente reset/squash/force per aggirare LFS.

## Aggiornamento 2026-07-22 — modulo UI (riferimento temi)

Stesso pattern dual-remote (`laraxot` + `provtv`) applicato a `laravel/Modules/UI`:

1. Shallow / unpack → deepen o `git push --no-thin`
3. Tip allineato `b874935` su entrambi i remote

Confine Geo: [geo-boundary.md](../../../Modules/UI/docs/geo-boundary.md)


2. LFS GH008 su un org → `git lfs fetch <sibling> --all` poi `git lfs push <target> --all`
3. Tip allineato `b874935` su entrambi i remote

Playbook UI: [git-push-lfs-missing-objects.md](../../../Modules/UI/docs/wiki/troubleshooting/git-push-lfs-missing-objects.md)  
Confine Geo: [geo-boundary.md](../../../Modules/UI/docs/geo-boundary.md)

I temi One/Zero/Three usano lo stesso playbook se il push fallisce per thin pack o LFS.

### Caso User 2026-07-23 (unrelated)

Se un remote ha `merge-base` vuoto vs HEAD → **non** merge/force automatico.
Esempio User: laraxot OK, provtv unrelated — [../../../Modules/User/docs/wiki/troubleshooting/git-push-dual-remote-unrelated.md](../../../Modules/User/docs/wiki/troubleshooting/git-push-dual-remote-unrelated.md).

