---
title: "Email delivery and mail UI boundaries"
type: guide
theme: Sixteen
status: active
tags: [email, ux, filament, notify]
---

# Email delivery and UI boundaries

Sixteen owns presentation only. It renders the verification notice and loading
feedback; it does not own SMTP, IMAP, mailbox storage, DNS or provider secrets.

## Existing surfaces

- Authentication pages use the shared Sixteen auth shell.
- Notify owns database mail templates and the Filament administration/testing
  surface.
- Mail layouts live in `resources/mail-layouts` and are consumed by Notify's
  `SpatieEmail`.

## Operational rule

When delivery fails, fix the Notify/application transport or the host/provider
configuration. Do not add SMTP credentials, mailbox logic or delivery retries
to Blade views or theme components.

Transactional verification/password messages should remain concise, expected and
non-promotional. Marketing unsubscribe controls belong to the Notify mail
template/transport layer, not to a reusable theme view. The theme must preserve
plain-text and accessible HTML alternatives supplied by Notify.

The authoritative host audit and installation sequence is in the Notify module:
`Modules/Notify/docs/bmad/email-infrastructure-audit-and-runbook.md`.

## Host di produzione e anti-spam (2026-11-12)

Il tema non cambia: la consegna dipende da Postfix/DKIM/DNS, documentati nel modulo Notify
([story](../../../Modules/Notify/docs/stories/mail-server-send-receive.story.md)) e in `bashscripts/docs/mail/mail-server.md`.
Cosa resta al tema per non finire in spam: layout in `resources/mail-layouts/` con immagini servite da `fixcity.eu`
(mai da IP o domini terzi), niente link accorciati, testo leggibile anche senza immagini, rapporto testo/immagini
equilibrato. Il pulsante "invia di nuovo" della verifica (`components/pages/auth/verify.blade.php`) usa lo stato di
caricamento definito in `components/auth/assets.blade.php`: un conflitto git del 2026-11-12 l'aveva perso, ripristinato.
