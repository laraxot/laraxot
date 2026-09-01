---
title: Roadmap perfezione temi
type: concept
tags: [themes, perfection, phpstan, folio, sixteen, quality]
created: 2026-08-31
updated: 2026-08-31
qmd: themes perfection roadmap phpstan folio bootstrap-italia fixcity
related:
  - ./stato-qualita-temi-2026-08-31.md
  - ./phpstan-non-analizza-i-temi.md
  - ./DOCUMENTATION_INDEX.md
  - ../Sixteen/docs/wiki/overviews/perfection-checklist.md
  - ../../Modules/Xot/docs/wiki/concepts/progetto-perfezione-criteri.md
  - ../../docs/gestionale-technical-debt.md
---

# Roadmap perfezione — temi Laraxot

**Il progetto non è perfetto.** I moduli passano PHPStan; i temi no (ancora fuori gate).
Questa pagina è l'hub per chiudere il gap **presentazione / FO**.

---

## Stato per tema

| Tema | PHPStan (stima) | Folio canonico | Test | Priorità |
|------|-----------------|----------------|------|----------|
| **Sixteen** | ~1146 errori reali | **Violato** (12 dir semantiche legacy) | 4 file skipped, 7 attivi | **P0** |
| **TwentyOne** | Non misurato in dettaglio | Dir semantiche non documentate | 1 contract test | P1 |
| **Zero** | Basso | **OK** (`folio-pages-structure.md`) | Nessuno | P2 |
| **Barthelemy / Meetup** | Stub | N/D | Nessuno | P3 |

Misurazione: [stato-qualita-temi-2026-08-31.md](./stato-qualita-temi-2026-08-31.md).

---

## Perfezione tema = 5 condizioni

1. **Folio** — solo `[container0]`, `auth/`, `tests/` sotto `pages/` ([Sixteen page-directory-structure](../Sixteen/docs/page-directory-structure.md)).
2. **Dominio fuori dal tema** — niente model Eloquent municipali in `Themes/*/app/Models` (spostare in modulo).
3. **PHPStan** — zero errori reali dopo exclude `Themes/*/config/*` per `env()`.
4. **Test FO** — senza `RefreshDatabase`; skip solo con motivo in `theme-tests-data-sacred.md`.
5. **Docs** — un indice wiki aggiornato; niente duplicati `INDEX`/`index` senza owner.

---

## Sixteen — debiti strutturali (P0)

| Debito | Evidenza | Doc owner |
|--------|----------|-----------|
| Directory semantiche `pages/tickets`, `services`, … | `NoSemanticFolioPageDirectoriesTest` skipped | [page-directory-structure.md](../Sixteen/docs/page-directory-structure.md) |
| View BI legacy `pub_theme::bootstrap-italia.*` | 21 test skipped | [theme-tests-data-sacred.md](../Sixteen/docs/wiki/concepts/theme-tests-data-sacred.md) |
| Modulo Fixcity assente | Comune tests skipped | stesso file |
| 7 model `Municipal/*` (~650 errori PHPStan) | stato-qualita-temi | Spostare in modulo dominio |
| ~1437 file `.md` con duplicati case | DOCUMENTATION_INDEX stale | Potatura D9 + dedup |

Checklist operativa: [perfection-checklist.md](../Sixteen/docs/wiki/overviews/perfection-checklist.md).

---

## TwentyOne — gap da documentare

- Aggiungere regola “no semantic dirs” analoga a Sixteen.
- Inventariare `pages/{articles,blog,dashboard,…}` e piano migrazione `[container0]`.
- Collegare [folio-container0-filament-way.md](../TwentyOne/docs/wiki/concepts/folio-container0-filament-way.md).

---

## Ordine consigliato (temi)

```text
1. Escludere Themes/*/config/* da larastan.noEnvCallsOutsideOfConfig
2. Sixteen: Municipal models → modulo (o disabilitare + skip PHPStan)
3. Sixteen: migrazione 12 directory semantiche → CMS + [container0]
4. Portare componenti BI mancanti o aggiornare test a sixteen::components/*
5. Inserire Themes/ in phpstan.neon paths (baseline graduale)
6. TwentyOne allineamento Folio
7. Dedup documentazione Sixteen (~400 file target)
```

---

## Collegamenti moduli

I temi non duplicano business logic. Per perfezione end-to-end:

- [gc-modules-runtime-matrix.md](./gc-modules-runtime-matrix.md)
- [gestionale-technical-debt.md](../../docs/gestionale-technical-debt.md) (D8 FO operator Folio, D9 docs uppercase)
- Criteri piattaforma: [progetto-perfezione-criteri.md](../../Modules/Xot/docs/wiki/concepts/progetto-perfezione-criteri.md)
