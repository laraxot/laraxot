---
title: "Social Login — Folio + Volt + Filament Blueprint"
type: note
status: active
updated: 2026-10-27
tags: [docs, sixteen]
qmd: "social login   folio + volt + filament blueprint sixteen"
---
# Social Login — Folio + Volt + Filament Blueprint

## Architettura (SSoT rotte modulo User)

| Azione | URI | Owner | Route name |
|--------|-----|-------|------------|
| Redirect provider | `/{lang}/auth/login/{provider}` | `RedirectToProviderController` via `routes/socialite.php` | `oauth.redirect` |
| Callback provider | `/{lang}/auth/login/{provider}/callback` | Folio page Sixteen → `ProcessCallbackAction` | `oauth.callback` |

La callback è una pagina Folio+Volt del tema Sixteen, registrata con
`name('oauth.callback')`, e delega la policy a
`Modules\\User\\Actions\\Socialite\\ProcessCallbackAction`. Non esiste un
controller callback né una rotta callback classica: `web.php` resta immutato.

## Componenti

### 1. Login Page (`/it/auth/login`)
- **File**: `Themes/Sixteen/resources/views/pages/auth/login.blade.php`
- **Vestito**: `components/pages/auth-login.blade.php` + `LoginWidget`
- **Google Button**: `@include('components.utilities.google-button')` →
  `route('oauth.redirect', ['provider' => 'google', 'lang' => $locale])`

### 2. Redirect / Callback
- Dominio: Actions Socialite del modulo User; pagina callback Folio nel tema Sixteen
- `GOOGLE_REDIRECT_URI` deve essere esattamente
  `https://fixcity.eu/it/auth/login/google/callback` (o equivalente ambiente)

## Traduzioni (Convenzione 5 elementi)

- `user::auth.login.google.button.text`
- `user::auth.login.or_continue_with.text`

## .env

```env
GOOGLE_CLIENT_ID=...
GOOGLE_CLIENT_SECRET=...
GOOGLE_REDIRECT_URI=https://fixcity.eu/it/auth/login/google/callback
```

## Prevenzione errori

1. Mai `route('oauth.redirect', ['provider' => 'google'])` senza `lang`
2. Mai aprire OAuth in un nuovo tab
3. Mai `request()->segment(3)` per leggere il provider sotto URL localizzati
4. Piano: [bmad/plans/oauth-login-locale-fix.md](./bmad/plans/oauth-login-locale-fix.md)

## File chiave

```
Modules/User/routes/socialite.php
Modules/User/app/Filament/Widgets/Auth/SocialLoginWidget.php
Themes/Sixteen/resources/views/components/utilities/google-button.blade.php
Themes/Sixteen/resources/views/pages/auth/login/[provider]/callback.blade.php
Modules/User/app/Actions/Socialite/ProcessCallbackAction.php
```
