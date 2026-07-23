---
title: "Notify Module Rules Index"
type: rule
tags: [rules, index]
created: 2026-07-14
updated: 2026-07-14
qmd: "rules-index notify module rules index"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index-1.md"
  - "./00-index-2.md"
  - "./00-index.md"
  - "./absolute-completion-100.md"
  - "./acronym-naming-conventions-1.md"
  - "./acronym-naming-conventions-2.md"
  - "./acronym-naming-conventions.md"
  - "./action-plan-immediate.md"
---

# Notify Module Rules Index

## Overview
This file documents the rules and standards specific to the Notify module.

## Module-Specific Rules

### Notification Delivery
- Use queueable notifications for async delivery
- Always implement fallback to database notification

### Mail Templates
- Store templates in `resources/views/vendor/notifications/`
- Use Blade components for reusable elements

### Channel Configuration
- Configure channels in `config/notifications.php`
- Support: mail, database, slack, twilio (optional)

## Best Practices

### Action Classes
- Create QueueableActions for notification sending
- Use Data Transfer Objects for notification data

### Testing
- Test each notification channel separately
- Mock external services in unit tests

## Architectural Violations — Do Not Repeat

### No HTTP controllers
Controllers are forbidden in all modules. Architecture = Folio + Volt + Filament only.
A `NotificationTrackingController` was found and removed. Email tracking must be implemented
as a Folio page + Action, not a controller.
See: [no-http-controllers.md](./no-http-controllers.md)

## Related Documentation
- [README](./readme.md)
- [phpstan](./phpstan.md)
- [No HTTP controllers](./no-http-controllers.md)
