---
title: Email Module Analysis
type: concept
tags: [email, mailing, notifications, smtp]
created: 2026-09-05
updated: 2026-09-05
qmd: "email module-analysis scopo religione filosofia politica zen"
module: Email
---

# Email Module Analysis

## Scopo
Gestione centralizzata dell'invio email. Template email, code queue, tracking aperture/clic, gestione bounce/unsubscribe.

## Religione
- **Queue-first delivery**: tutte le email vanno in coda, mai invio sincrono in request
- **Template-driven**: le email sono blade templates versionati
- **MIME multipart**: HTML + plain text per massima compatibilità
- **Tracking pixel**: tracciamento apertura con 1x1 transparent GIF
- **Unsubscribe header**: RFC 8058 compliance per mailing list
- **DKIM/SPF/DMARC ready**: infrastruttura per email authentication

## Filosofia
- **Email as a service**: il modulo è il servizio email della piattaforma
- **Decoupled sender**: chi invia non sa come viene consegnato
- **Retry intelligent**: retry automatico con backoff esponenziale
- **Feedback loop**: bounce e complaint informano il sistema

## Politica
- Tutte le email passano per `EmailService` che decide routing
- I template sono blade con placeholder `{{ $slot }}` e componenti Laravel
- Le email transazionali hanno priorità alta, le newsletter bassa
- Il rate limiting è per sender domain
- Gli unsubscribe sono globali per tipo di comunicazione
- I log email contengono: to, subject, template, sent_at, opened_at, clicked_at

## Perché
Perché ogni modulo che deve inviare email non dovrebbe implementare la sua infrastruttura SMTP/queue/tracking.

## Zen
Inviata e dimenticata - la coda gestisce il resto.

## Cosa manca
- Drag-drop email builder (tipo Mailchimp)
- A/B testing per subject/body
- Email preview su multipli client email
- Warmup automatico per nuovi domini
- Dedicated IP management
- Inbox placement testing

## Cosa aggiungerei
- AI subject line optimization
- Send time optimization (invia quando il destinatario è più probabile apra)
- AMP email support (email interattive Gmail)
- SMS fallback per email non aperte
- Email verification (valida che la mailbox esiste prima di inviare)

## Divisione o Unione
- **Mantieni separato**: email è infrastruttura trasversale
- **Potenziale unione**: Notify ha già canale email, potrebbero convergere
- **Conflitto**: Notify gestisce template e canali multipli, Email gestisce delivery e tracking - sono complementari