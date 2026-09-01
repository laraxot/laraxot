---
title: Quality roadmap — Xot
type: concept
tags: [xot, quality, perfection, phpstan, platform]
created: 2026-08-31
updated: 2026-08-31
qmd: xot quality roadmap perfection platform phpstan testing
related:
  - ./stato-qualita-progetto-2026-08-31.md
  - ./wiki/concepts/progetto-perfezione-criteri.md
  - ./wiki/concepts/artisan-migrate-no-force.md
  - ./testing/testing-setup.md
  - ../../../Themes/docs/progetto-perfezione-roadmap.md
---

# Quality roadmap — Xot (piattaforma)

Xot è il **owner** di gate, test infrastructure e policy dati sacri. La perfezione
moduli passa da qui.

## SSoT misurazione

- [stato-qualita-progetto-2026-08-31.md](./stato-qualita-progetto-2026-08-31.md) — numeri riproducibili
- [progetto-perfezione-criteri.md](./wiki/concepts/progetto-perfezione-criteri.md) — definizione “perfetto”

## Stato (2026-08-31)

| Area | Stato |
|------|-------|
| PHPStan Modules gate | **OK** |
| Artisan migrate no `--force` | **OK** (dati sacri) |
| `XotModuleSchema` / test DB | Documentato; D17 aperto |
| Test enforcement regole | **Mancante** (§4 stato-qualita) |
| Docs Xot | **2688 file** — potatura necessaria |

## Azioni piattaforma

1. Chiudere **D17** (`debt-phpunit-env-precedence.md`).
2. Pest scanner regole (`->label()`, `*Service`, case duplicates).
3. Documentare eccezione `xot:build-test-sqlite` + `--force` su file isolato.
4. Potatura doc: un owner per argomento PHPStan/testing.

Ogni modulo GC ha `docs/quality-roadmap.md` con gap locali.
