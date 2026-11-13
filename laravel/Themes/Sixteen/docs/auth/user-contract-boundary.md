---
title: "Sixteen auth: UserContract boundary"
type: architecture
theme: Sixteen
status: active
updated: 2026-10-18
tags: [auth, usercontract, i18n, bmad]
qmd: "Sixteen tema autenticazione UserContract BaseUser contratto consumer"
---

# Autenticazione del tema e `UserContract`

Le pagine e i componenti Sixteen sono consumer dell'identità fornita dal modulo
User. Il tema non deve dipendere da `Modules\User\Models\BaseUser`, perché
`BaseUser` è una implementazione astratta e non il contratto dell'identità.

## Regola operativa

Per controllare l'utente autenticato usare `Modules\User\Contracts\UserContract`
(o l'alias contrattuale previsto dal modulo), mantenendo le capability
specifiche separate dal controllo dell'identità.

Questo mantiene compatibili i flussi `auth`, Folio, Livewire e i modelli utente
concreti eventualmente forniti da altri moduli.

La stessa regola vale per i componenti riutilizzabili: il tema consuma
contratti e chiavi pubbliche, non classi interne del modulo.

## Allineamento modulo User (2026-10-18)

I listener e l'action di audit login del modulo User applicano la stessa
filosofia: identità via `UserContract`, log via `HasAuthentications`.
Il tema non deve introdurre `instanceof BaseUser` in blade/Livewire/auth.
Decisione correlata: [D18](../decision-log/D18-user-contract-over-base-user.md).

## Collegamenti

- SSoT modulo User: [usercontract-boundary.md](../../../../Modules/User/docs/wiki/concepts/usercontract-boundary.md)
- Decisione Xot: [user-contract-boundary.md](../../../../Modules/Xot/docs/bmad/user-contract-boundary.md)
- Second brain User: [bmad-second-brain.md](../../../../Modules/User/docs/bmad-second-brain.md)
