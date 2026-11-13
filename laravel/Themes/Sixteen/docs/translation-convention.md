---
title: "Convenzione 5 elementi aggiornata BMAD"
type: note
status: draft
updated: 2026-10-19
tags: [docs, sixteen]
qmd: "convenzione 5 elementi aggiornata bmad sixteen"
---
## Translation Convention (5 Elements) — BMAD + Second Brain

Conforme a `AGENTS.md` + `MEMORY.md`, tutte le chiavi di traduzione devono avere 5 elementi:

```
<namespace>::<contesto>.<collezione>.<chiave>.<tipo>
```

Esempi validi:
- `fixcity::auth.login.intro.text`
- `fixcity::auth.register.link.text`
- `user::auth.login.email.text`
- `user::auth.login.password.text`
- `user::auth.login.button.text`
- `user::auth.login.forgot_password.text`
- `pub_theme::accessibility.page.title.text`

Non valide (mancano elementi):
- `fixcity::auth.intro` → mancano `.login.` e `.text` → **FIXED**: `fixcity::auth.login.intro.text`
- `user::auth.password` → mancano `.login.` e `.text` → **FIXED**: `user::auth.login.password.text`
- `pub_theme::accessibility.title` → mancano `.page.` → **FIXED**: `pub_theme::accessibility.page.title.text`

Stack: Tailwind + Alpine + Lit + DaisyUI + Flowbite (NO Bootstrap Italia)
DB: mysql (MariaDB) — dati sacri, mai `migrate --force` / `database:refresh`
Initiative: KP-174d15a58ff94901b2b0df0d86b8907a

=== AGGIORNAMENTO ARCHITETTURA FOLIO (BMAD) ===
Pattern standard (da pages/index.blade.php -> applicato a pages/auth/login.blade.php):
- `name('login')` + `middleware(['guest'])` → route definita
- `new class extends Component` + `mount()` → componente Volt
- `@volt('login')` → HTML dentro componente, root tag presente
- `<x-layouts.auth>` → layout condiviso DesignComuni (container/row/col/card)
- JSON CMS: il componente legge `$this->data` ma per login usa dati statici (auth.form)
- `<form>` con `wire:submit` → credenziali + link registrazione/recupero
- Link OAuth: `route('oauth.redirect', ['provider' => 'google'])`
- DesignComuni: `agid-form-container`, `agid-form-header`, `agid-button`
- NO Bootstrap Italia / NO controllers / NO web.php/api.php

# Convenzione 5 elementi aggiornata BMAD
<namespace>::<contesto>.<collezione>.<chiave>.<tipo>
Esempio corretto: user::auth.login.intro.text
