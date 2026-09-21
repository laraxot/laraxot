---
title: second brain — puntatore modulo UI
type: reference
qmd: second brain UI phpstan geo-boundary no map adapters Location Map
updated: 2026-07-22
issues:
  - https://github.com/provtv/module_ui_fila5/issues
discussions:
<<<<<<< HEAD
  - https://github.com/laraxot/<nome repitory>/discussions/273
  - https://github.com/laraxot/base_fixcity_fila5/discussions/273
  - https://github.com/laraxot/platform/discussions/273
=======
<<<<<<< HEAD
  - https://github.com/laraxot/<nome repitory>/discussions/273
  - https://github.com/laraxot/base_fixcity_fila5/discussions/273
  - https://github.com/laraxot/platform/discussions/273
=======
<<<<<<< HEAD
  - https://github.com/laraxot/base_fixcity_fila5/discussions/273
=======
<<<<<<< HEAD
<<<<<<< HEAD
=======
  - https://github.com/laraxot/<nome repitory>/discussions/273
=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
  - https://github.com/laraxot/platform/discussions/273
=======
  - https://github.com/laraxot/<nome repitory>/discussions/273
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
---

# Second brain (modulo UI)

Stub **puntatore**: disciplina globale nella wiki di progetto; qui solo lezioni operative del modulo.

## Link operativi (relativi al repo)

- Modello: [../../../../docs/wiki/concepts/second-brain-operating-model.md](../../../../docs/wiki/concepts/second-brain-operating-model.md)
- Confine UI≠Geo: [./geo-boundary.md](./geo-boundary.md)
- Git forward-only: [../../../../docs/wiki/rules/git-forward-only.md](../../../../docs/wiki/rules/git-forward-only.md)
- Board multi-agente: [../../../../docs/chat/multi-agent-standing-coordination.md](../../../../docs/chat/multi-agent-standing-coordination.md)
<<<<<<< HEAD
- Push dual-remote / LFS: [./wiki/troubleshooting/git-push-lfs-missing-objects.md](./wiki/troubleshooting/git-push-lfs-missing-objects.md)
- Push dual-remote / LFS: [./wiki/troubleshooting/git-push-lfs-missing-objects.md](./wiki/troubleshooting/git-push-lfs-missing-objects.md)
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
<<<<<<< HEAD
=======
- Push dual-remote / LFS: [./wiki/troubleshooting/git-push-lfs-missing-objects.md](./wiki/troubleshooting/git-push-lfs-missing-objects.md)
<<<<<<< HEAD
- Push dual-remote / LFS: [./wiki/troubleshooting/git-push-lfs-missing-objects.md](./wiki/troubleshooting/git-push-lfs-missing-objects.md)
=======
=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
<<<<<<< HEAD
=======
- Push dual-remote / LFS: [./wiki/troubleshooting/git-push-lfs-missing-objects.md](./wiki/troubleshooting/git-push-lfs-missing-objects.md)
>>>>>>> laraxot/dev
=======
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev

## Lezioni operative

| Problema | Perché | Fix |
|----------|--------|-----|
| Bootstrap `unexpected <<` | Marker merge in PHP UI | Studiare `git show` e riscrivere (no restore) |
<<<<<<< HEAD
| Adapter Map/Location in UI | Dominio geografico, non design system | Eliminare; in `base_ptvx_fila5` Geo non c’è |
| `phpstan.path` su `Services/Map/Null*` | File rimossi; cache stale | Wipe cache swarm; non ricreare Map in UI |
| Push unpack / GH008 LFS | thin pack + OID LFS assenti su un org | `--no-thin`; `lfs fetch --all` da sibling sano |
=======
<<<<<<< HEAD
| Adapter Map/Location in UI | Dominio geografico, non design system | Eliminare; in `base_ptvx_fila5` Geo non c’è |
| `phpstan.path` su `Services/Map/Null*` | File rimossi; cache stale | Wipe cache swarm; non ricreare Map in UI |
=======
| Adapter Map/Location in UI | Dominio geografico, non design system | Eliminare; in `<nome repository>` Geo non c’è |
| `phpstan.path` su `Services/Map/Null*` | File rimossi; cache stale | Wipe cache swarm; non ricreare Map in UI |
<<<<<<< HEAD
<<<<<<< HEAD
=======
| Push unpack / GH008 LFS | thin pack + OID LFS assenti su un org | `--no-thin`; `lfs fetch --all` da sibling sano |
<<<<<<< HEAD
>>>>>>> laraxot/dev
| Adapter Map/Location in UI | Dominio geografico, non design system | Eliminare; in `<nome repository>` Geo non c’è |
| `phpstan.path` su `Services/Map/Null*` | File rimossi; cache stale | Wipe cache swarm; non ricreare Map in UI |
| Push unpack / GH008 LFS | thin pack + OID LFS assenti su un org | `--no-thin`; `lfs fetch --all` da sibling sano |
| Push unpack / GH008 LFS | thin pack + OID LFS assenti su un org | `--no-thin`; `lfs fetch --all` da sibling sano |
<<<<<<< HEAD
=======
=======
=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
<<<<<<< HEAD
=======
| Push unpack / GH008 LFS | thin pack + OID LFS assenti su un org | `--no-thin`; `lfs fetch --all` da sibling sano |
>>>>>>> laraxot/dev
=======
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev

**Map/Geo:** Nel modulo `UI` non devono esserci elementi legati a mappe, geolocalizzazione o dati geografici. Le cartelle `app/Adapters/Location` e `app/Adapters/Map` sono state rimosse. Il modulo `Geo` è un ambito separato e non fa parte di questo progetto; il modulo `UI` deve mantenere responsabilità esclusiva sui componenti UI generici.

Remotes tipici: `provtv` + `laraxot` → `module_ui_fila5` (`git remote -v`).
