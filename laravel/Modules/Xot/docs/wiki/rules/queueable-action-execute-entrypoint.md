---
title: "QueueableAction execute entrypoint"
type: rule
module: Xot
confidence: high
created: 2026-07-12
updated: 2026-07-12
tags: [xot, actions, queueable-action, execute]
related:
  - ../../../../../docs/wiki/rules/queueable-action-execute-entrypoint.md
---

# QueueableAction execute entrypoint

Platform convention: a class using `QueueableAction` has one public business entrypoint named `execute(...)`.

Facade/helper classes with multiple operations may delegate to actions, but should not use `QueueableAction`.
