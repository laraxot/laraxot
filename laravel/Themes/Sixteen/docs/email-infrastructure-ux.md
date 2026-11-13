---
title: "Email: requisiti UX del tema Sixteen"
type: guide
theme: Sixteen
status: active
tags: [email, ux, auth, verification]
---

# Email: requisiti UX del tema Sixteen

Le pagine di autenticazione devono comunicare chiaramente lo stato dell’invio
email:

- il pulsante di reinvio mostra un indicatore di caricamento;
- il pulsante è disabilitato durante la richiesta per impedire doppi invii;
- il testo di stato è localizzato e accessibile con `role="status"`;
- l’esito positivo resta visibile tramite il messaggio Livewire/sessione;
- un errore del provider non deve essere presentato come invio riuscito.

Questi requisiti sono indipendenti dal provider SMTP/ Mailtrap. La configurazione
infrastrutturale e i record DNS sono documentati in
`Modules/Notify/docs/email-infrastructure.md`.

