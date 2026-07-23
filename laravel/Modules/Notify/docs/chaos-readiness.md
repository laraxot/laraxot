---
title: "Notify Chaos Readiness - 2026-03-02"
type: concept
tags: [chaos, readiness]
created: 2026-07-14
updated: 2026-07-14
qmd: "chaos-readiness notify chaos readiness - 2026-03-02"
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

# Notify Chaos Readiness - 2026-03-02

## Scope
- Optional Firebase/FCM dependency hardening.

## Completed
- Refactored push notification pages to avoid hard dependency crashes.
- Refactored Firebase notification class with runtime guards.
- Added missing `MailTemplateVersionFactory` for static analysis consistency.
- Verified `Modules/Notify` passes PHPStan.

## Next Chaos Steps
- Disable Firebase packages and verify UI-level warning path.
- Inject invalid cloud message contracts and ensure controlled failures.
