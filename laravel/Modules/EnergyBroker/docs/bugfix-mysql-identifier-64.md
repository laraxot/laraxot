---
title: "Bugfix — MySQL identifier ≤64 (EnergyBroker indexes)"
type: troubleshooting
tags: [energybroker, migration, mysql-1059, index]
created: 2026-07-27
updated: 2026-07-27
qmd: "energy_broker_supply_histories index too long 1059 mysql 64"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./README.md"
  - "../../PublicProcurement/docs/bugfix-mysql-identifier-64.md"
  - "../../../../docs/wiki/concepts/migration-foreign-id-for.md"
---

# Bugfix — EnergyBroker index MySQL 1059

Stesso pattern di [PublicProcurement](../../PublicProcurement/docs/bugfix-mysql-identifier-64.md): nomi tabella lunghi → auto-index Laravel >64 char.

**Fail:** `energy_broker_supply_histories_energy_broker_supply_point_id_status_index`

**Fix:** `->index([...], 'eb_supply_histories_point_status_idx')` + `tableUpdate` idempotente.
File: `2026_07_24_000205_create_energy_broker_supply_histories_table.php`.
