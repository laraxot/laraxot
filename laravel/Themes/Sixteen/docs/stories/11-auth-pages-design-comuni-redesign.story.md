---
id: "sixteen-fo-11-auth-pages-redesign"
type: "epic-story"
title: "Authentication pages redesign with Design Comuni"
description: "Riallineare le pagine di autenticazione (login, register, password reset, email verification) ai pattern di design-comuni-pagine-statiche (cmp-auth-form), usando componenti universali cmp-* e layout it-page-section."
frontmatter:
  github_issue: "#65"
  github_discussion: "#65"
links:
  github_issue: "#65"
  github_discussion: "#65"
  design_ref: "https://italia.github.io/design-comuni-pagine-statiche/sito/autenticazione.html"
  investigation_ref: "docs/investigation/redesign-front-office-pages-investigation.md"
---

# Authentication Pages DesignComuni — Epic Story

## Goal
Riallineare tutte le pagine di autenticazione ai pattern di `design-comuni-pagine-statiche` (`autenticazione.html`), usando componenti universali `cmp-*` per coerenza visiva e accessibilità WCAG 2.1 AA.

## Background
- Pagine Fixcity esistenti: `resources/views/pages_old/auth/*.blade.php` (login, register, password-reset, email-verification)
- Template DesignComuni: `autenticazione.html` — pattern card centrata con form accessibile
- Route: `/login`, `/register`, `/password/reset`, `/email/verify`, `/password/confirm`
- Multi-tenant: routing `container0` per separazione tenant

## Acceptance Criteria

### Layout Comune (tutte le pagine auth)
- [ ] **Wrapper**: `it-page-section` + `container` + `row justify-content-center` + `col-md-8 col-lg-6 col-xl-5`
- [ ] **Card**: `cmp-card` variant `elevated` + `card-bg` (background #f8f9fa)
- [ ] **Header card**: `cmp-brand` (logo + titolo "Area Personale") + descrizione contestuale
- [ ] **Footer card**: link "Hai bisogno di aiuto?" + "Contattaci" + link privacy/termini

### Login Page (/login)
- [ ] **Form** (`cmp-auth-form`):
  - [ ] Email: `cmp-form-field` type="email", `autocomplete="email"`, required, `aria-describedby` per errori
  - [ ] Password: `cmp-form-field` type="password", `autocomplete="current-password"`, required, toggle visibility (icon `it-eye`/`it-eye-slash`)
  - [ ] Remember me: `cmp-form-field` type="checkbox"
  - [ ] Link "Password dimenticata?" → `/password/reset`
  - [ ] Submit: `cmp-button` primary full-width "Accedi" + `aria-busy` durante submit
  - [ ] Divider: "o" + link "Registrati" → `/register`
  - [ ] Link "Accesso SPID/CIE" (se abilitato) — button outline con icona `it-spid`
- [ ] **Validazione**: client-side (Alpine.js) + server-side (FormRequest)
- [ ] **Errori**: `cmp-alert` danger sopra form + inline su campi (`aria-invalid`, `aria-describedby`)

### Register Page (/register)
- [ ] **Form** (`cmp-auth-form`):
  - [ ] Nome: `cmp-form-field` required
  - [ ] Cognome: `cmp-form-field` required
  - [ ] Email: `cmp-form-field` type="email", unique check, `autocomplete="email"`
  - [ ] Password: `cmp-form-field` type="password", strength meter (`cmp-progress`), `autocomplete="new-password"`
  - [ ] Conferma password: `cmp-form-field` type="password", match validation
  - [ ] Checkbox privacy: `cmp-form-field` required, link a privacy policy
  - [ ] Checkbox termini: `cmp-form-field` required, link a termini servizio
  - [ ] Submit: `cmp-button` primary full-width "Registrati"
  - [ ] Divider: "o" + link "Accedi" → `/login`
- [ ] **Post-registrazione**: redirect a `/email/verify` con messaggio "Controlla la tua email"

### Password Reset Request (/password/reset)
- [ ] **Form**: solo email + submit "Invia link reset"
- [ ] **Successo**: `cmp-alert` success "Se l'email esiste, riceverai un link"
- [ ] **Link back**: "Torna al login"

### Password Reset Form (/password/reset/{token})
- [ ] **Form**: password + conferma + submit "Reimposta password"
- [ ] **Strength meter**: `cmp-progress` real-time
- [ ] **Successo**: redirect `/login` con `cmp-toast` success "Password reimpostata"

### Email Verification (/email/verify)
- [ ] **Stato**: messaggio "Verifica la tua email" + `cmp-button` "Riinvia email"
- [ ] **Già verificata**: redirect `/dashboard` con toast

### Password Confirm (/password/confirm)
- [ ] **Form**: password corrente + submit "Conferma"
- [ ] **Timeout**: 3 ore default, configurabile

## Requisiti Tecnici
- [ ] **Alpine.js** per: toggle password visibility, strength meter, validazione client-side
- [ ] **CSP**: nonce su script inline, `form` action consentite
- [ ] **Rate limiting**: login 5/min, register 3/min, reset 2/min (Laravel throttle)
- [ ] **Accessibilità**: WCAG 2.1 AA, `autocomplete` corretti, `aria-label`, focus-visible, skip link
- [ ] **`prefers-reduced-motion`**: disabilita animazioni card entrance
- [ ] **Dark mode**: supporto completo (background #1a1a2e, card #16213e, testi #eaeaea)
- [ ] **Multi-lingua**: testi via `__('auth...')`, `__('fixcity::auth...')`
- [ ] **Redirect intelligente**: `intended()` URL o dashboard default

## Non-Scopi
- Backend auth logic — usa Laravel Fortify/Sanctum esistente
- SPID/CIE integration — modulo separato
- 2FA/MFA — story separata

## Dependency
- Componenti universali: `cmp-page-section`, `cmp-card`, `cmp-brand`, `cmp-auth-form`, `cmp-form-field`, `cmp-button`, `cmp-alert`, `cmp-toast`, `cmp-progress`, `cmp-link`
- Story correlate: `07-header-component-redesign` (link "Area Personale"), `08-footer-component-redesign`, `10-universal-components-cmp-library`