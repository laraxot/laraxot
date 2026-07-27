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

- **Storia scollegata risolta**: conflitti reali in `app/Contracts/UserContract.php`
  (docblock + generici PHPStan più precisi da laraxot) e in `helpers/Helper.php`
  (funzione `getRouteParameters()` usata da Progressioni, Performance, Ptv, Sigma,
  IndennitaCondizioniLavoro, IndennitaResponsabilita, Lang — ripristinata dopo il
  merge per non rompere quei moduli).

## Regola per il futuro

Prima di un merge/rebase su questo repo, controllare sempre `git remote -v` e
sincronizzare **tutti** i remote elencati, non solo `origin`/`provtv`. Mai forzare
push distruttivi su storie scollegate: preferire `--allow-unrelated-histories` e
revisione manuale dei conflitti reali.

### Playbook push dual-remote (2026-07-22, canon UI)

Se `unpack failed` / `did not receive expected object` → `git push --no-thin`.
Se `GH008` / LFS missing su un org e l’altro ha già accettato il tip →
`git lfs fetch <sibling> --all` poi `git lfs push <target> --all`, poi push.
Dettaglio (SSoT): [../UI/docs/wiki/troubleshooting/git-push-lfs-missing-objects.md](../UI/docs/wiki/troubleshooting/git-push-lfs-missing-objects.md).
Niente reset/squash/force per aggirare LFS.

### Caso User 2026-07-23 (unrelated)

`module_user_fila5`: `laraxot` tip `3ea7273a` (`0 0`); `provtv` **merge-base vuoto** → STOP (no merge/force).
Canon: [../User/docs/wiki/troubleshooting/git-push-dual-remote-unrelated.md](../User/docs/wiki/troubleshooting/git-push-dual-remote-unrelated.md).

### Caso Xot 2026-07-23 (laraxot allineato, provtv unrelated — di nuovo)

Working tree pulito, nessun merge/rebase in corso. `laraxot/dev`: **`0 0`**, nessuna
azione necessaria. `provtv/dev`: **behind 40 / ahead 12**, `git merge-base HEAD
provtv/dev` → **vuoto** (storie non correlate), nonostante la risoluzione
"unrelated" già documentata sopra (righe 30-34) — sembra essere ridivergiuto
(reset/force-push lato `provtv` dopo quella risoluzione, o quella sessione ha
risolto solo `laraxot`). **Non tentato `--allow-unrelated-histories`** per
istruzione esplicita di questo giro (rischio noto: centinaia di conflitti add/add,
visto su Modules/UI, parte di un loop automatico che disfa fix manuali). Lasciato a
decisione umana. Nessun push necessario (nulla da pushare su `laraxot`, `provtv`
non toccato).

