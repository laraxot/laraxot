---
title: "Sincronizzazione multi-organizzazione (laraxot + provtv)"
type: concept
tags: [git, sync, multi-org, laraxot, provtv, quality-gates]
created: "2026-07-21"
<<<<<<< HEAD
updated: "2026-07-29"
=======
<<<<<<< HEAD
updated: "2026-07-29"
<<<<<<< HEAD
<<<<<<< .merge_file_Luwm0C
=======
<<<<<<< .merge_file_dISztb
=======
=======
<<<<<<< HEAD
updated: "2026-07-29"
>>>>>>> .merge_file_1cu6UR
>>>>>>> .merge_file_GNwaP7
=======
>>>>>>> 804451c (Lint)
related:
  - "../../../bashscripts/tools/prompts/02-gitmodules-sync.md"
=======
<<<<<<< HEAD
<<<<<<< HEAD
=======
=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
<<<<<<< HEAD
updated: "2026-07-29"
related:
  - "../../../bashscripts/tools/prompts/02-gitmodules-sync.md"
=======
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
updated: "2026-07-23"
>>>>>>> laraxot/dev
<<<<<<< HEAD
<<<<<<< .merge_file_Luwm0C
=======
<<<<<<< .merge_file_dISztb
=======
>>>>>>> laraxot/dev
>>>>>>> .merge_file_1cu6UR
>>>>>>> .merge_file_GNwaP7
=======
>>>>>>> 804451c (Lint)
related:
  - "../../../bashscripts/tools/prompts/02-gitmodules-sync.md"
updated: "2026-07-23"
  - "./wiki/troubleshooting/git-push-lfs-missing-objects.md"
<<<<<<< HEAD
related:
  - "../../../bashscripts/tools/prompts/02-gitmodules-sync.md"
=======
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< .merge_file_Luwm0C
=======
<<<<<<< .merge_file_dISztb
=======
related:
  - "../../../bashscripts/tools/prompts/02-gitmodules-sync.md"
=======
<<<<<<< HEAD
>>>>>>> .merge_file_1cu6UR
>>>>>>> .merge_file_GNwaP7
=======
>>>>>>> 804451c (Lint)
=======
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
=======
updated: "2026-07-29"
related:
  - "../../../bashscripts/tools/prompts/02-gitmodules-sync.md"
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
<<<<<<< HEAD
<<<<<<< .merge_file_Luwm0C
=======
<<<<<<< .merge_file_dISztb
=======
>>>>>>> laraxot/dev
>>>>>>> .merge_file_1cu6UR
>>>>>>> .merge_file_GNwaP7
=======
>>>>>>> 804451c (Lint)
  - "./git-multi-org-sync-handoff.md"
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

<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< .merge_file_Luwm0C
=======
<<<<<<< .merge_file_dISztb
=======
- **Blocco LFS lato server (provtv)** (storico): in una sessione precedente si era
  riscritta la storia senza tracking LFS. **Non ripetere** rewrite se evitabile.
- **Blocco LFS lato server (provtv)** (storico): in una sessione precedente si era
  riscritta la storia senza tracking LFS. **Non ripetere** rewrite se evitabile.
=======
<<<<<<< HEAD
>>>>>>> .merge_file_1cu6UR
>>>>>>> .merge_file_GNwaP7
=======
>>>>>>> 804451c (Lint)
=======
<<<<<<< HEAD
<<<<<<< HEAD
=======
- **Blocco LFS lato server (provtv)** (storico): in una sessione precedente si era
  riscritta la storia senza tracking LFS. **Non ripetere** rewrite se evitabile.
<<<<<<< HEAD
- **Blocco LFS lato server (provtv)** (storico): in una sessione precedente si era
  riscritta la storia senza tracking LFS. **Non ripetere** rewrite se evitabile.
=======
=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
<<<<<<< HEAD
=======
- **Blocco LFS lato server (provtv)** (storico): in una sessione precedente si era
  riscritta la storia senza tracking LFS. **Non ripetere** rewrite se evitabile.
>>>>>>> laraxot/dev
=======
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
<<<<<<< HEAD
<<<<<<< .merge_file_Luwm0C
=======
<<<<<<< .merge_file_dISztb
=======
>>>>>>> laraxot/dev
>>>>>>> .merge_file_1cu6UR
>>>>>>> .merge_file_GNwaP7
=======
>>>>>>> 804451c (Lint)
- **Violazione di dipendenza Geo→UI**: `app/Livewire/Components/Map/InteractiveMap.php`
  importava `Modules\\Geo\\Services\\{Geocoding,Map}Service`, un modulo che non fa
  parte di questo progetto e che comunque UI non dovrebbe mai importare
  (regola: la dipendenza corretta è Geo → UI, mai il contrario). Il file era già
  stato archiviato in passato (vedi `docs/geo-dependency-violation-interactive-map.md`)
  ma reintrodotto da un merge upstream da `laraxot/dev`; rimosso di nuovo.

### Push dual-remote 2026-07-22 (tip `b874935`)

| Sintomo | Causa | Fix |
|---------|-------|-----|
| `unpack failed` / `did not receive expected object` | pack thin + storia merge laraxot↔provtv | `git push --no-thin` |
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< .merge_file_Luwm0C
=======
<<<<<<< .merge_file_dISztb
=======
| `GH008` / LFS missing su `provtv` | OID LFS non presenti su quel remote | `git lfs fetch laraxot --all` → `git lfs push provtv --all` → push |

Playbook completo: [wiki/troubleshooting/git-push-lfs-missing-objects.md](./wiki/troubleshooting/git-push-lfs-missing-objects.md).
| `GH008` / LFS missing su `provtv` | OID LFS non presenti su quel remote | `git lfs fetch laraxot --all` → `git lfs push provtv --all` → push |

Playbook completo: [wiki/troubleshooting/git-push-lfs-missing-objects.md](./wiki/troubleshooting/git-push-lfs-missing-objects.md).
=======
<<<<<<< HEAD
>>>>>>> .merge_file_1cu6UR
>>>>>>> .merge_file_GNwaP7
=======
>>>>>>> 804451c (Lint)

=======
<<<<<<< HEAD
<<<<<<< HEAD
=======
| `GH008` / LFS missing su `provtv` | OID LFS non presenti su quel remote | `git lfs fetch laraxot --all` → `git lfs push provtv --all` → push |

Playbook completo: [wiki/troubleshooting/git-push-lfs-missing-objects.md](./wiki/troubleshooting/git-push-lfs-missing-objects.md).
<<<<<<< HEAD
| `GH008` / LFS missing su `provtv` | OID LFS non presenti su quel remote | `git lfs fetch laraxot --all` → `git lfs push provtv --all` → push |

Playbook completo: [wiki/troubleshooting/git-push-lfs-missing-objects.md](./wiki/troubleshooting/git-push-lfs-missing-objects.md).
=======
=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
<<<<<<< HEAD

=======
| `GH008` / LFS missing su `provtv` | OID LFS non presenti su quel remote | `git lfs fetch laraxot --all` → `git lfs push provtv --all` → push |

Playbook completo: [wiki/troubleshooting/git-push-lfs-missing-objects.md](./wiki/troubleshooting/git-push-lfs-missing-objects.md).
>>>>>>> laraxot/dev
=======

>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
<<<<<<< HEAD
<<<<<<< .merge_file_Luwm0C
=======
<<<<<<< .merge_file_dISztb
=======
>>>>>>> laraxot/dev
>>>>>>> .merge_file_1cu6UR
>>>>>>> .merge_file_GNwaP7
=======
>>>>>>> 804451c (Lint)

## Regola per il futuro

Prima di un merge/rebase su questo repo, controllare sempre `git remote -v` e
sincronizzare **tutti** i remote elencati, non solo `origin`/`provtv`. Mai forzare
push distruttivi su storie scollegate: preferire `--allow-unrelated-histories` e
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< .merge_file_Luwm0C
=======
<<<<<<< .merge_file_dISztb
=======
revisione manuale dei conflitti reali. In push: **FF + `--no-thin`**; LFS da sibling sano, non squash/reset.
=======
<<<<<<< HEAD
>>>>>>> .merge_file_1cu6UR
>>>>>>> .merge_file_GNwaP7
=======
>>>>>>> 804451c (Lint)
=======
<<<<<<< HEAD
<<<<<<< HEAD
=======
revisione manuale dei conflitti reali. In push: **FF + `--no-thin`**; LFS da sibling sano, non squash/reset.
<<<<<<< HEAD
revisione manuale dei conflitti reali. In push: **FF + `--no-thin`**; LFS da sibling sano, non squash/reset.
=======
=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
<<<<<<< HEAD
=======
revisione manuale dei conflitti reali. In push: **FF + `--no-thin`**; LFS da sibling sano, non squash/reset.
>>>>>>> laraxot/dev
=======
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
<<<<<<< HEAD
<<<<<<< .merge_file_Luwm0C
=======
<<<<<<< .merge_file_dISztb
=======
>>>>>>> laraxot/dev
>>>>>>> .merge_file_1cu6UR
>>>>>>> .merge_file_GNwaP7
=======
>>>>>>> 804451c (Lint)

### Caso User 2026-07-23 (unrelated)

`module_user_fila5`: `laraxot` tip `3ea7273a` (`0 0`); `provtv` **merge-base vuoto** → STOP (no merge/force).
Canon: [../User/docs/wiki/troubleshooting/git-push-dual-remote-unrelated.md](../User/docs/wiki/troubleshooting/git-push-dual-remote-unrelated.md).

