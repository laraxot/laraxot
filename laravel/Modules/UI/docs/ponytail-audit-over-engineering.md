---
title: "Ponytail audit — UI (over-engineering)"
type: concept
tags: [ponytail, audit, over, engineering]
created: 2026-07-14
updated: 2026-07-14
qmd: "ponytail-audit-over-engineering ponytail audit — ui (over-engineering)"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index-1.md"
  - "./00-index.md"
  - "./04-datas.md"
  - "./advanced-form-components-1.md"
  - "./advanced-form-components.md"
  - "./agent-confidence-discipline.md"
  - "./agent-confidence-protocol.md"
  - "./agent-edit-discipline.md"
---

# Ponytail audit — UI (over-engineering)

**Ultimo run:** 2026-07-01  
**Modulo:** design system, componenti Filament/Blade condivisi.  
**Hub:** [../../../../docs/audit/ponytail-audit.md](../../../../docs/audit/ponytail-audit.md)  
**Remediation:** [../../../../docs/project/ponytail-audit-remediation.md](../../../../docs/project/ponytail-audit-remediation.md)
**GitHub monorepo:** [Issue #221](https://github.com/laraxot/base_predict_fila5/issues/221) · [Discussion #222](https://github.com/laraxot/base_predict_fila5/discussions/222) · [Discussion #228](https://github.com/laraxot/base_predict_fila5/discussions/228)

## Findings

| # | Tag | Cosa | Sostituzione | Path | Stato |
|---|-----|------|--------------|------|-------|
| UI0 | `delete` | Layer Map/Geocoding speculativo (contratto + `Null*` senza wiring) | Geo module quando serve | `app/Contracts/`, `app/Services/Map/` | ✅ 2026-07-01 |
| UI1 | `delete` | `Config/` maiuscolo + `Config.bak/` (duplicato di `config/`) | Solo `config/` | `Config/`, `Config.bak/` | ✅ 2026-07-01 |
| UI2 | `delete` | `docs/archive/` (~144 file duplicati sessione) | Solo `docs/wiki/` | `docs/archive/` | ✅ 2026-07-01 |
| UI3 | `delete` | ~26 stub `.md` / `.txt` in root + mirror `_docs/`, `docs/root-*` | `docs/wiki/` + indici | root `Modules/UI/*`, `_docs/` | ✅ 2026-07-01 |

## Collegamenti

- [wiki/concepts/ponytail-audit.md](./wiki/concepts/ponytail-audit.md)
- [00-index-1.md](./00-index-1.md)
