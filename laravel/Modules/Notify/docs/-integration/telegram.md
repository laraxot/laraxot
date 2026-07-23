---
title: "telegram"
type: concept
tags: [telegram]
created: 2026-07-14
updated: 2026-07-14
qmd: "telegram telegram"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./firebase.md"
  - "./links.md"
  - "./login.md"
  - "./notifications.md"
  - "./repos.md"
  - "./test-smtp.md"
  - "./todo.md"
  - "./whatsapp.md"
---

# telegram

<!-- Contenuto migrato da _docs/telegram.txt -->

https://dev.to/millykhamroev/laravel-package-to-integrate-telegram-bot-api-3l6e

https://medium.com/modulr/send-telegram-notifications-with-laravel-9-342cc87b406


Add telegram service into config/service.php file.

# config/services.php

'telegram-bot-api' => [
    'token' => env('TELEGRAM_BOT_TOKEN', 'YOUR BOT TOKEN HERE')
],


--- TUTORIAL ---
https://abstractentropy.com/laravel-notifications-telegram-bot/
