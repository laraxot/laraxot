---
title: "config/services.php"
type: concept
tags: [telegram]
created: 2026-07-14
updated: 2026-07-14
qmd: "telegram config/services.php"
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

https://dev.to/millykhamroev/laravel-package-to-integrate-telegram-bot-api-3l6e

https://medium.com/modulr/send-telegram-notifications-with-laravel-9-342cc87b406


Add telegram service into config/service.php file.

# config/services.php

'telegram-bot-api' => [
    'token' => env('TELEGRAM_BOT_TOKEN', 'YOUR BOT TOKEN HERE')
],


--- TUTORIAL ---
https://abstractentropy.com/laravel-notifications-telegram-bot/
