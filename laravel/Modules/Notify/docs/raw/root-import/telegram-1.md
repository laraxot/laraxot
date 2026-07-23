---
title: "config/services.php"
type: concept
tags: [telegram]
created: 2026-07-14
updated: 2026-07-14
qmd: "telegram-1 config/services.php"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./agents.md"
  - "./changelog.md"
  - "./claude.md"
  - "./design-conversion-roadmap-1.md"
  - "./design-conversion-roadmap.md"
  - "./files-created-session-007-1.md"
  - "./files-created-session-007.md"
  - "./files-created-session-replikate.md"
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
