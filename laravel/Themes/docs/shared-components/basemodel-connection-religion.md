---
title: "BaseModel connection — guida temi/moduli"
type: rule
tags: [themes, basemodel, connection, shared-components]
created: 2026-07-27
updated: 2026-07-27
qmd: "themes BaseModel connection protected module snake"
related:
  - ../../../Modules/Xot/docs/wiki/concepts/basemodel-connection-religion.md
  - ../../../../docs/wiki/concepts/basemodel-connection-religion.md
---

# BaseModel — `$connection` (temi)

I temi non definiscono `BaseModel`; questa nota è per agenti che toccano `Modules/*/app/Models/BaseModel.php`.

Ogni `BaseModel` modulo: `protected $connection = '{getSnakeName()}'` — mai `null`.

Canon: [Xot — basemodel-connection-religion](../../../Modules/Xot/docs/wiki/concepts/basemodel-connection-religion.md)
