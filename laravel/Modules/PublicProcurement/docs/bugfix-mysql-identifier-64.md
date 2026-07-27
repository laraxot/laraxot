---
title: "Bugfix — MySQL identifier ≤64 (PublicProcurement indexes)"
type: troubleshooting
tags: [publicprocurement, migration, mysql-1059, index, identifier-length]
created: 2026-07-27
updated: 2026-07-27
qmd: "public_procurement index name too long 1059 submission_deadline_at evaluated_by_user_id mysql 64"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./README.md"
  - "./import-status.md"
  - "../../../../docs/wiki/concepts/migration-foreign-id-for.md"
  - "../../../../docs/wiki/concepts/xotbase-migration-religion.md"
  - "../../TimberBilling/docs/bugfix-users-cross-db-fk.md"
---

# Bugfix — MySQL 1059 identifier troppo lungo

## Perché (business)

Tabelle `public_procurement_*` hanno nomi lunghi (dominio appalti). Laravel genera indici auto `{table}_{column}_index`. Su MySQL/MariaDB il limite identifier è **64 caratteri**.

## Sintomi

```
Identifier name 'public_procurement_tender_eligibility_checks_evaluated_by_user_id_index' is too long
Identifier name 'public_procurement_tender_opportunities_submission_deadline_at_index' is too long
```

CREATE tabella riesce; ALTER INDEX fallisce → tabella orfana senza indice (`tableCreate` al retry salta).

## Zen / politica

| Regola | Intent |
|--------|--------|
| Prefisso corto `pp_*` / `pub_proc_*` | Stesso pattern delle FK `indexName` |
| `->index([...], 'pub_proc_…_idx')` | Nome esplicito ≤64 |
| `foreignIdFor(...)->constrained(..., indexName: '…')` | FK auto può superare 64 |
| Retry | bump timestamp + `tableUpdate` + `hasIndex` |
| XotBase | `$model_class` + `updateTimestamps` in `tableUpdate` |

## File

- `2026_07_27_121102_create_public_procurement_tender_opportunities_table.php` — `pub_proc_tender_sub_deadline_idx`
- `2026_07_24_000104` … eligibility / contacts / deadlines / … (già `pp_*_idx`)

## Checklist

- [ ] `len(index_name) <= 64`
- [ ] Nessun `->index()` nudo su colonne con tabella `public_procurement_*` lunga
- [ ] Dopo 1059: bump timestamp owner, non `add_*`
