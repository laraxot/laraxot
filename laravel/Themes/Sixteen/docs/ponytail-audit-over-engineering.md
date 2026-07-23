# Ponytail audit — Sixteen

**Ultimo run:** 2026-06-30  
**Ruolo:** tema avanzato (Bootstrap Italia, tooling Filament).  
**Hub temi:** [../../../../docs/project/ponytail-audit-themes.md](../../../../docs/project/ponytail-audit-themes.md)  
**Hub repo:** [../../../../docs/audit/ponytail-audit.md](../../../../docs/audit/ponytail-audit.md)  
**Remediation:** [../../../../docs/project/ponytail-audit-remediation.md](../../../../docs/project/ponytail-audit-remediation.md)  
**GitHub monorepo:** [Issue #221](https://github.com/laraxot/base_predict_fila5/issues/221) · [Discussion #222](https://github.com/laraxot/base_predict_fila5/discussions/222) · [Discussion #228](https://github.com/laraxot/base_predict_fila5/discussions/228)

**Repo upstream:** [theme_sixteen_fila5](https://github.com/laraxot/theme_sixteen_fila5) · [Issue #91](https://github.com/laraxot/theme_sixteen_fila5/issues/91)

## Findings

| # | Tag | Cosa | Sostituzione |
|---|-----|------|--------------|
| T16-1 | `delete`→`.bak` | `Main_files/` (~43 file) | Link upstream / archivio |
| T16-2 | `delete`→`.bak` | `ruvector.db` (~1.6 MB) | Non parte pipeline `npm run build` / Folio |
| T16-3 | `yagni` | ~1084 file `.md` in `docs/` (duplicati `00-INDEX`/`00-index`, `COMPONENTS_UPDATE`/`components-update`) | un file canonico per argomento |

## Collegamenti

- [ponytail-audit-themes.md](../../../../docs/project/ponytail-audit-themes.md)
