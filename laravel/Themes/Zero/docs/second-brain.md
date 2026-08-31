---
title: second brain — puntatore modulo
type: reference
qmd: second brain modulo wiki locale laravel
updated: 2026-07-29
updated: 2026-05-21
---

# Second brain (modulo)

Stub **puntatore**: disciplina e link esterni curati stanno nella wiki di progetto.

| Strato | Dove in questo package |
|--------|-------------------------|
| Input / note grezze | questa cartella `docs/` (escluso `docs/wiki/`) |
| Wiki compilata | `docs/wiki/` del modulo |
| Regole globali | wiki root del monorepo |

## Link operativi (relativi al repo)

- Modello: [../../../../docs/wiki/concepts/second-brain-operating-model.md](../../../../docs/wiki/concepts/second-brain-operating-model.md)
- Guida wiki modulo: [../../../../docs/wiki/how-to/module-wiki-documentation.md](../../../../docs/wiki/how-to/module-wiki-documentation.md)
- Benchmark lettura esterna (Karpathy, Obsidian, PARA, …): [../../../../docs/wiki/sources/second-brain-external-benchmarks.md](../../../../docs/wiki/sources/second-brain-external-benchmarks.md)
- **Filament (stack attuale): v5** — non v4. Policy: [../../../../docs/wiki/memories/filament-version-policy.md](../../../../docs/wiki/memories/filament-version-policy.md) · Xot: [../../../laravel/Modules/Xot/docs/filament-5-laraxot-rules.md](../../../laravel/Modules/Xot/docs/filament-5-laraxot-rules.md)

## Nota operativa 2026-07-29

Per sync multi-org del tema:

1. leggere sempre `git remote -v` dentro `laravel/Themes/Zero`;
2. fare `git fetch --all --prune`;
3. verificare `git rev-list --left-right --count HEAD...<remote>/dev`;
4. fare push solo quando il secondo numero e' `0`;
5. se QMD non trova la collection locale, usare wiki file-based e annotare il degrado nel report.
- Modello: [../../../../../docs/wiki/concepts/second-brain-operating-model.md](../../../../../docs/wiki/concepts/second-brain-operating-model.md)
- Guida wiki modulo: [../../../../../docs/wiki/how-to/module-wiki-documentation.md](../../../../../docs/wiki/how-to/module-wiki-documentation.md)
- Benchmark lettura esterna (Karpathy, Obsidian, PARA, …): [../../../../../docs/wiki/sources/second-brain-external-benchmarks.md](../../../../../docs/wiki/sources/second-brain-external-benchmarks.md)
- **Filament (stack attuale): v5** — non v4. Policy: [../../../../docs/wiki/memories/filament-version-policy.md](../../../../docs/wiki/memories/filament-version-policy.md) · Xot: [../../Modules/Xot/docs/filament-5-laraxot-rules.md](../../Modules/Xot/docs/filament-5-laraxot-rules.md)
