---
title: Criteri di perfezione del progetto
type: concept
tags: [quality, perfection, phpstan, pest, bmad, laraxot]
created: 2026-08-31
updated: 2026-08-31
qmd: progetto perfezione criteri phpstan pest temi test dati sacri
related:
  - ../../stato-qualita-progetto-2026-08-31.md
  - ../../../Themes/docs/progetto-perfezione-roadmap.md
  - ../../../../docs/gestionale-technical-debt.md
  - ./artisan-migrate-no-force.md
  - ../../testing/testing-setup.md
---

# Criteri di perfezione — definizione operativa

**Perfezione** qui non significa “zero file da toccare”. Significa: ogni dimensione ha un
gate misurabile, verde o con skip documentato e piano di chiusura.

Misurazione numerica: [stato-qualita-progetto-2026-08-31.md](../../stato-qualita-progetto-2026-08-31.md).

---

## Le otto dimensioni

| # | Dimensione | Gate “perfetto” | Stato workorder (2026-08-31) |
|---|------------|-----------------|------------------------------|
| 1 | **PHPStan moduli** | L10, 0 errori su `Modules/` | **OK** |
| 2 | **PHPStan temi** | L10, 0 errori reali su `Themes/` | **NO** (~1146 debito; temi fuori gate) |
| 3 | **Boot runtime** | `artisan about`, `module:list`, panel senza crash | **OK** moduli enabled |
| 4 | **Test business** | Pest verde su logica dominio (unit + feature) | **Parziale** — D17 blocca feature con `roles` |
| 5 | **Dati sacri** | Mai `RefreshDatabase`, `migrate:fresh`, `--force` su prod | **OK** policy Xot; eccezione SQLite isolato da documentare |
| 6 | **Regole architetturali** | XotBase, Actions, traduzioni, no case-duplicates | **Debito** — vedi stato-qualita §2–3 |
| 7 | **Debito gestionale** | D1–D17 chiusi o playbook eseguito | **Aperto** — [gestionale-technical-debt.md](../../../../docs/gestionale-technical-debt.md) |
| 8 | **Documentazione** | Un owner canonico per argomento; `import-status` + `quality-roadmap` per modulo GC | **In corso** |

Un modulo è **perfetto** quando le righe 1, 4 (per il suo scope), 5 e 8 sono verdi e 6–7
non hanno P0 aperti nel modulo owner.

---

## Ordine di attacco (piattaforma)

```text
1. D17  — schema test `workorder_data_test` (roles, permission, tabelle GC)
2. D10  — collisione login FO
3. D1/D2 — media Document / VehicleDocument
4. Themes nel gate PHPStan (dopo exclude config env)
5. Test Pest enforcement regole (§4 stato-qualita)
6. Potatura docs + case-duplicates
```

---

## Dove documentare per modulo / tema

| Artefatto | Chi | Scopo |
|-----------|-----|--------|
| `import-status.md` | Modulo GC | Stato porting, PHPStan, migrazioni |
| `quality-roadmap.md` | Modulo GC | Gap verso perfezione + azioni ordinate |
| `tests-migration.md` | Modulo (se esiste) | Storia test portati da SRC |
| `stato-qualita-temi-*.md` | Themes/docs | Misurazione PHPStan temi |
| `perfection-checklist.md` | Tema owner (Sixteen) | Checklist FO |

Ogni nuovo file `.md` deve linkare almeno due pagine correlate (regola second brain).

---

## Cosa non chiamare “perfezione”

- Solo PHPStan verde su `Modules/` con `Themes/` esclusi.
- Docs con 5 roadmap PHPStan duplicate e nessun owner.
- Test skipped senza motivo in `quality-roadmap` o `import-status`.
- `ide-helper:models --write --reset` sui model (distrugge `@property` → migliaia di errori).
