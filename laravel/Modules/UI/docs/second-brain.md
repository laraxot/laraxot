---
title: second brain — puntatore modulo UI
type: reference
qmd: second brain UI phpstan geo-boundary no map adapters Location Map
updated: 2026-07-22
issues:
  - https://github.com/provtv/module_ui_fila5/issues
discussions:
  - https://github.com/laraxot/base_fixcity_fila5/discussions/273
---

# Second brain (modulo UI)

Stub **puntatore**: disciplina globale nella wiki di progetto; qui solo lezioni operative del modulo.

## Link operativi (relativi al repo)

- Modello: [../../../../docs/wiki/concepts/second-brain-operating-model.md](../../../../docs/wiki/concepts/second-brain-operating-model.md)
- Confine UI≠Geo: [./geo-boundary.md](./geo-boundary.md)
- Git forward-only: [../../../../docs/wiki/rules/git-forward-only.md](../../../../docs/wiki/rules/git-forward-only.md)
- Board multi-agente: [../../../../docs/chat/multi-agent-standing-coordination.md](../../../../docs/chat/multi-agent-standing-coordination.md)
- Push dual-remote / LFS: [./wiki/troubleshooting/git-push-lfs-missing-objects.md](./wiki/troubleshooting/git-push-lfs-missing-objects.md)

## Lezioni operative

| Problema | Perché | Fix |
|----------|--------|-----|
| Bootstrap `unexpected <<` | Marker merge in PHP UI | Studiare `git show` e riscrivere (no restore) |
| Adapter Map/Location in UI | Dominio geografico, non design system | Eliminare; in `base_ptvx_fila5` Geo non c’è |
| `phpstan.path` su `Services/Map/Null*` | File rimossi; cache stale | Wipe cache swarm; non ricreare Map in UI |
| Push unpack / GH008 LFS | thin pack + OID LFS assenti su un org | `--no-thin`; `lfs fetch --all` da sibling sano |

**Map/Geo:** Nel modulo `UI` non devono esserci elementi legati a mappe, geolocalizzazione o dati geografici. Le cartelle `app/Adapters/Location` e `app/Adapters/Map` sono state rimosse. Il modulo `Geo` è un ambito separato e non fa parte di questo progetto; il modulo `UI` deve mantenere responsabilità esclusiva sui componenti UI generici.

Remotes tipici: `provtv` + `laraxot` → `module_ui_fila5` (`git remote -v`).
