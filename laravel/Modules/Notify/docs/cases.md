---
title: "Case-Insensitive File Conflicts"
type: concept
tags: [cases]
created: 2026-07-14
updated: 2026-07-14
qmd: "cases case-insensitive file conflicts"
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

# Case-Insensitive File Conflicts

Duplicati individuati nel modulo `Notify`:

- `Modules/Notify/_docs`: `Test_SMTP.txt`, `test_smtp.txt`
- `Modules/Notify/app/Filament/Auth`: `FilamentLogin.fila2`, `filamentlogin.fila2`
- `Modules/Notify/app/Http/Livewire/Auth`: `FilamentLogin.fila2`, `filamentlogin.fila2`
- `Modules/Notify/app/Http/Middleware`: `FilamentMiddleware.fila2`, `filamentmiddleware.fila2`
- `Modules/Notify/app/Providers`: `FilamentServiceProvider.fila2`, `filamentserviceprovider.fila2`
- `Modules/Notify/docs`: `Test_SMTP.txt`, `test_smtp.txt`
- `Modules/Notify/docs`: `INDEX.md`, `index.md`
- `Modules/Notify/docs/integrations`: `README.md`, `readme.md`
- `Modules/Notify/docs/notifications`: `README.md`, `readme.md`
- `Modules/Notify/docs/templates`: `README.md`, `readme.md`

Per ogni coppia mantenere un'unica versione coerente (es. PascalCase per classi, maiuscolo per file documentali globali) e rimuovere quella ridondante.
