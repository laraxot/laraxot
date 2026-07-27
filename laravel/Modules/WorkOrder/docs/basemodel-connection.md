---
title: "WorkOrder BaseModel — connessione work_order"
type: concept
tags: [workorder, basemodel, connection, eloquent]
created: 2026-07-27
updated: 2026-07-27
qmd: "WorkOrder BaseModel connection work_order TenantServiceProvider"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
related:
  - ./README.md
  - ../../Xot/docs/wiki/concepts/basemodel-connection-religion.md
  - ../../../../docs/wiki/concepts/basemodel-connection-religion.md
---

# WorkOrder — `protected $connection`

```php
// app/Models/BaseModel.php
protected $connection = 'work_order';
```

- `work_order` = `Module::find('WorkOrder')->getSnakeName()`
- `TenantServiceProvider` registra la connessione a runtime
- Test: `$connectionsToTransact` include `sqlite`, `xot` (vedi `tests/TestCase.php`)

Canon: [basemodel-connection-religion](../../Xot/docs/wiki/concepts/basemodel-connection-religion.md)
