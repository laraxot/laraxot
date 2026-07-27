# Ponytail audit — UI (over-engineering)

**Ultimo run:** 2026-06-30  
**Modulo:** design system, componenti Filament/Blade condivisi.  
**Hub:** [../../../../docs/audit/ponytail-audit.md](../../../../docs/audit/ponytail-audit.md)  
**Remediation:** [../../../../docs/project/ponytail-audit-remediation.md](../../../../docs/project/ponytail-audit-remediation.md)
**GitHub monorepo:** [Issue #221](https://github.com/laraxot/base_predict_fila5/issues/221) · [Discussion #222](https://github.com/laraxot/base_predict_fila5/discussions/222) · [Discussion #228](https://github.com/laraxot/base_predict_fila5/discussions/228)

## Findings

| # | Tag | Cosa | Sostituzione | Path |
|---|-----|------|--------------|------|
| UI1 | `delete`→`.bak` | `Config.bak/` (duplicato nested di `config/`) | Solo `config/` | `Config.bak/` |
| UI2 | `delete` | `docs/archive/` (~144 file duplicati sessione) | Solo `docs/wiki/` | `docs/archive/` |
| UI3 | `delete` | ~26 stub `.md` in root modulo (`api.md`, `blocks.md`, …) | `docs/` + indici | root `Modules/UI/*.md` |

## Collegamenti

- [wiki/concepts/ponytail-audit.md](./wiki/concepts/ponytail-audit.md)
- [00-INDEX.md](./00-INDEX.md)
