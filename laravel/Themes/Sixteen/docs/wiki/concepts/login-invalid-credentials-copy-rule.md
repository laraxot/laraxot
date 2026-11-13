---
title: "login invalid credentials copy rule"
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-09-26
qmd: "login invalid credentials copy rule"
issues: []
discussions: []
status: draft
---

# Login invalid credentials copy rule

## Regola

Il tema Sixteen non deve mostrare il copy framework grezzo per gli errori di login.

Per il frontoffice italiano il messaggio deve arrivare dal modulo User tramite chiave strutturata:

- `user::login.actions.login.error`

## Motivo

Il tema e' owner della resa UX, ma il copy autenticazione resta owner del modulo User e delle sue traduzioni strutturate.

## Riferimento modulo

- `../../../Modules/User/docs/wiki/concepts/login-invalid-credentials-copy-rule.md`
