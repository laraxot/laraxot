# Ponytail audit — Blog (over-engineering)

**Ultimo run:** 2026-06-30  
**Modulo:** contenuti editoriali, banner, menu.  
**Hub:** [../../../../docs/audit/ponytail-audit.md](../../../../docs/audit/ponytail-audit.md)  
**Remediation:** [../../../../docs/project/ponytail-audit-remediation.md](../../../../docs/project/ponytail-audit-remediation.md)  
**GitHub monorepo:** [Issue #221](https://github.com/laraxot/base_predict_fila5/issues/221) · [Discussion #222](https://github.com/laraxot/base_predict_fila5/discussions/222) · [Discussion #228](https://github.com/laraxot/base_predict_fila5/discussions/228)

## Scopo business

Post, categorie, banner e widget testuali per il front office. Il modulo non deve duplicare factory o PHPDoc copiati da modelli sibling.

## Findings ranked

| # | Tag | Cosa tagliare | Sostituzione | Path | Righe ~ |
|---|-----|---------------|--------------|------|---------|
| B1 | `shrink` | `@method static MenuFactory` su modello `Banner` | `BannerFactory` in PHPDoc e factory dedicata | `app/Models/Banner.php` | ~2 |
| B2 | `yagni` | verificare factory `MenuFactory` riusata su modelli diversi | una factory per modello | `database/factories/` | da grep |

## Azione proposta

Correggere PHPDoc/factory su `Banner`; grep `MenuFactory` vs `BannerFactory` prima di `.bak`.

## Collegamenti

- [00-INDEX.md](./00-INDEX.md)
- [Predict audit](../../Predict/docs/ponytail-audit-over-engineering.md)
