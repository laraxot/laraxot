# Ponytail audit — Two

**Ultimo run:** 2026-06-30  
**Ruolo:** variante tema / legacy.  
**Hub temi:** [../../../../docs/project/ponytail-audit-themes.md](../../../../docs/project/ponytail-audit-themes.md)  
**Hub repo:** [../../../../docs/audit/ponytail-audit.md](../../../../docs/audit/ponytail-audit.md)  
**Remediation:** [../../../../docs/project/ponytail-audit-remediation.md](../../../../docs/project/ponytail-audit-remediation.md)  
**GitHub monorepo:** [Issue #221](https://github.com/laraxot/base_predict_fila5/issues/221) · [Discussion #222](https://github.com/laraxot/base_predict_fila5/discussions/222) · [Discussion #228](https://github.com/laraxot/base_predict_fila5/discussions/228)

**Repo upstream:** [theme_two_fila5](https://github.com/laraxot/theme_two_fila5) · [Issue #1](https://github.com/laraxot/theme_two_fila5/issues/1)

## Findings

| # | Tag | Cosa | Sostituzione |
|---|-----|------|--------------|
| T2-1 | `delete`→`.bak` | `Main_files/` (~320 file, ~102k righe, include `filament-peek-demo`) | `Main_files.bak/` | ✅ 2026-06-30 |

## Impatto

Zero su runtime se il build non referenzia `Main_files/`.

## Collegamenti

- [TwentyOne audit](../TwentyOne/docs/ponytail-audit-over-engineering.md)
