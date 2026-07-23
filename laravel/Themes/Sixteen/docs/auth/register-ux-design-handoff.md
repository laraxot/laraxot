---
title: "Register FO — UX design handoff"
type: concept
created: 2026-06-04
updated: 2026-06-04
tags: [ux, wcag, auth, register, sixteen]
related:
  - register-ux-audit-2026-06-04.md
  - login-ux-fixes-2026-06-04.md
  - ../architecture/fo-pa-tokens-uniformity.md
---

# `/it/auth/register` — handoff UX (BMAD create-ux-design)

## Target

- Web desktop + mobile, WCAG 2.1 AA
- Stack PA: `PaDesignColors`, `fo-filament-form-shell`, token `civic-design-*`

## Layout form

| Campo | Disposizione |
|-------|----------------|
| Nome, Cognome, Email | Colonna singola (full width) |
| Password | Sotto email, **full width** |
| Conferma password | Sotto password, **full width** (mai affiancate) |
| CTA Registrati | Full width, min-height 44px |
| Link login | Sotto CTA, nav con `aria-label` |

## WCAG

| Criterio | Implementazione |
|----------|-----------------|
| 1.3.1 | Label visibili via `user::user_form.fields.*.label` |
| 3.3.2 | Helper text password in campo + sidebar `id` hint |
| 4.1.3 | Errori: `role="alert"` + `aria-live="assertive"` |
| 2.4.6 | H1 `#auth-register-heading`, form `aria-labelledby` |
| 2.4.7 | Focus ring su link login (`focus-visible:ring`) |

## File

- Schema: `Modules/User/.../UserForm::getRegisterFormSchema()`
- Vestito: `Themes/Sixteen/resources/views/filament/widgets/auth/register.blade.php`
- Pagina: `Themes/Sixteen/resources/views/pages/auth/register.blade.php`
- Traduzioni campi: `Modules/User/lang/it/user_form.php`

## Verifica

```bash
curl -sL http://127.0.0.1:8000/it/auth/register | grep -E 'Conferma password|cols-lg: repeat\\(2'
```

Atteso: label leggibili, **zero** griglia 2 colonne su password.
