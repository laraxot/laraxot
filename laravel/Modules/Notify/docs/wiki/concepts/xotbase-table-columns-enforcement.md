---
title: "XotBaseResourceTable Columns Enforcement — Notify Module"
type: concept
sources: []
confidence: high
created: 2026-05-07
updated: 2026-07-16
qmd: "xotbase filament mirror inheritance"
issues: ["https://github.com/laraxot/base_techplanner_fila5/issues/45"]
discussions: ["https://github.com/laraxot/base_techplanner_fila5/discussions/12"]
tags: [xotbase, filament, tables, enforcement]
related:
  - "./claude-audit-static.md"
  - "./code-redundancy-notify.md"
  - "./composer-root-minimal-nwidart.md"
  - "./context-overflow-prevention.md"
  - "./enum-standards.md"
  - "./llm-wiki-governance.md"
  - "./method-name-homonyms.md"
  - "./module-root-uppercase-folders-archive.md"
---

# Notify Module: XotBaseResourceTable Columns

5 Table files populated with columns from notification models.

Resources: Contact, MailTemplate, Notification, NotificationTemplate, NotifyTheme

Columns derived from Model `$fillable` and `$casts` properties. Includes standard `id`, `created_at`, `updated_at` columns plus notification-specific fields.


