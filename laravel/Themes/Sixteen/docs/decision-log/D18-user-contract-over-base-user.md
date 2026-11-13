---
title: "D18 — UserContract over BaseUser in theme boundaries"
type: decision
status: active
updated: 2026-10-18
tags: [bmad, second-brain, user, theme, contracts]
qmd: "d18   usercontract over baseuser in theme boundaries sixteen"
---

# D18 — UserContract over BaseUser in theme boundaries

Sixteen tratta l’utente come contratto applicativo, non come classe concreta.
Le view, i componenti e gli eventi del tema devono usare
`Modules\Xot\Contracts\UserContract` (alias `Modules\User\Contracts\UserContract`)
nei guard e nei type hint.

`BaseUser` appartiene all’infrastruttura del modulo User: usarla nel tema crea
accoppiamento alla gerarchia interna e rompe la sostituibilità dei modelli
utente. Il tema deve richiedere solo le capacità che usa e lasciare al modulo
User la scelta dell’implementazione concreta.

**Verifica 2026-10-18:** nessun `instanceof BaseUser` / type-hint `BaseUser`
nelle views auth Sixteen; i consumer Auth del modulo User usano
`UserContract` (+ `HasAuthentications` per i log). Vedi
[user-contract-boundary](../auth/user-contract-boundary.md).
