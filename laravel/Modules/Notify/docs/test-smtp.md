---
title: "Come far funzionare la pagina SendEmail"
type: concept
tags: [test, smtp]
created: 2026-07-14
updated: 2026-07-14
qmd: "test-smtp come far funzionare la pagina sendemail"
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

//----------------------------------------------------------------------------
Test Laravel SMTP Mail via Tinker
https://medium.com/@azishapidin/test-laravel-smtp-mail-via-tinker-cec59999214
//----------------------------------------------------------------------------

# Come far funzionare la pagina SendEmail

## Problema
La pagina `SendEmail` non funziona se la configurazione SMTP globale di Laravel (file `.env`) è errata, mancante o il server SMTP non è raggiungibile. Al contrario, `TestSmtpPage` funziona sempre perché permette di specificare i parametri SMTP a runtime.

## Soluzione

### 1. Verifica la configurazione SMTP in `.env`
Assicurati che le seguenti variabili siano corrette:
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.tuoserver.com
MAIL_PORT=587
MAIL_USERNAME=la-tua-username
MAIL_PASSWORD=la-tua-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=la-tua-email@dominio.com
MAIL_FROM_NAME="Nome mittente"
```
Dopo ogni modifica, esegui:
```
php artisan config:clear
php artisan cache:clear
```

### 2. Testa la configurazione
Usa Tinker o la pagina `TestSmtpPage` per verificare che l'invio funzioni:
```php
Mail::raw('Test SMTP', function($m){ $m->to('tuo@email.com')->subject('Test SMTP'); });
```

### 3. Miglioramenti consigliati per SendEmail
- Aggiungi gestione errori (try/catch) e mostra notifiche di errore.
- (Opzionale) Permetti l'override dei parametri SMTP da form, come in `TestSmtpPage`.

## Approfondimenti
- [TestSmtpPage vs SendEmail: differenze architetturali](./test-smtp-2.md)
- [Best practice per la configurazione SMTP](./email-best-practices-1.md)
- [Documentazione Laravel Mail](https://laravel.com/docs/mail)
