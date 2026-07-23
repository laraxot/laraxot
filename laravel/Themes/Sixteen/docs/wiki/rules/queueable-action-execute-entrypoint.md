---
title: "QueueableAction execute entrypoint"
type: rule
theme: Sixteen
confidence: high
created: 2026-07-12
updated: 2026-07-12
tags: [sixteen, actions, queueable-action, execute]
related:
  - ../../../../../docs/wiki/rules/queueable-action-execute-entrypoint.md
---

# QueueableAction execute entrypoint

Theme-side actions follow the same rule as modules: if a class uses Spatie `QueueableAction`, callers use `execute(...)`, optionally after `onQueue()`.

Do not introduce custom action entrypoints in themes.
