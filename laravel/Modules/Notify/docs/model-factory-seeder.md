---
title: "Model/Factory/Seeder Audit"
type: concept
tags: [model, factory, seeder]
created: 2026-07-14
updated: 2026-07-14
qmd: "model-factory-seeder model/factory/seeder audit"
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

# Model/Factory/Seeder Audit

Generated: [DATE] 16:29

## Coverage
| Model | Factory | Seeded |
|---|---|---|
| Notification | yes | no |
| MailTemplateLog | yes | no |
| NotificationTemplateVersion | yes | no |
| Contact | yes | no |
| NotifyTheme | yes | no |
| MailTemplateVersion | yes | no |
| NotificationType | yes | no |
| NotificationTemplate | yes | no |
| MailTemplate | yes | yes |
| NotifyThemeable | yes | no |

Seeders:
- `database/seeders/DatabaseSeeder.php`
- `database/seeders/MailTemplateSeeder.php`
- `database/seeders/MailTemplatesSeeder.php`
- `database/seeders/NotifyDatabaseSeeder.php`

## Missing / Actions
- Add exemplar seeding for: NotificationTemplate, NotificationTemplateVersion, NotificationType, Contact, NotifyTheme.
- Keep `MailTemplate*` seeding as is; extend with relationships if needed.

## Likely non-business-critical
- None; all listed are domain entities but some may be configuration-only and safe to seed minimally.
