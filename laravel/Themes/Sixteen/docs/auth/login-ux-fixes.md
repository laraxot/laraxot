---
title: "login ux fixes"
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-10-20
qmd: "login ux fixes"
issues: []
discussions: []
status: active
---

# Login `/it/auth/login` — fix UX/a11y (2026-06-04)

## Form inglese al posto del vestito (2026-10-20)

**Sintomo:** h1 vuoto, bottone `+`, etichette «Email address» / «Sign in».
Niente `data-dc`, titolo documento solo «FixCity».

**Causa:** `@volt('auth.login')` scrive un solo fragment
(`storage/framework/views/{md5}.blade.php`). `pages/counter.blade.php` e
`Modules/Fixcity/.../prova.blade.php` usavano lo stesso nome: l'ultimo
compile ha sostituito il corpo della pagina di login. Il sorgente giusto
(`x-page` → `components/pages/auth-login`) non veniva ricompilato perché
più vecchio della view compilata. `meta.layout` nel JSON non è il campo
`layout` dello schema Page, quindi non agganciava lo scope Design Comuni.

**Fix:** nomi volt propri sui prototipi; pagina login con `data-dc` e
`.auth-login-card`; `layout` in radice del JSON. Stesso scope su
register (`auth-register`), e il prototipo TwentyOne non riusa `auth.register`.

## Bottone submit + campo password (2026-06-04)

## Bottone submit + campo password (2026-06-04)

**Sintomi:** submit blu/ambra; password senza bordo (email sì).

**Cause:** asset Vite non ribuildati (`public_html/themes/Sixteen`); `.bg-primary` legacy = blu `#0066CC`; bordo Filament sul wrapper, password con suffix reveal.

**Fix:**

- `14-auth-login.css`: bordo unico su `.fi-input-wrp` (flex + `:has(.fi-revealable)`); input interni `border: none`
- Submit: `<x-filament::button color="primary">` + `PaDesignColors` (no hex in `14-auth-login.css`)
- `PaDesignColors` + `FilamentColor::register()` in `XotServiceProvider`
- `npm run build` in `Themes/Sixteen` (obbligatorio dopo edit CSS)

**Contrasto CTA:** ~4.5:1+ su verde PA + testo bianco.

**Validazione:** PageSpeed Insights + Mauve (STORY-137 MCP) su URL login.

## Problemi risolti

| Issue | Fix |
|-------|-----|
| Skip link `#main-container` senza target | `id="main-container"` + `tabindex="-1"` su `<main>` in `layouts/app.blade.php` |
| Errore login mostrava solo hint password | Alert mostra `$loginError` + hint separato |
| Doppio H1/H2 ridondante | Un solo `h1` pagina; titolo form → `p#auth-login-form-heading` |
| CTA registrazione duplicata | Rimossa banda pagina; link restano nel widget |
| Touch target / focus Filament | `app/14-auth-login.css` scoped ai component hook `.auth-login-card` |
| Meta description | `:metaDescription` da traduzione pagina |

## Pattern

- Pagina: `pages/auth/login.blade.php` + `LoginWidget` (Filament-first)
- Stili: `fo-filament-form-shell.css` + `14-auth-login.css` (layout only)
- ADR: [fo-pa-tokens-uniformity.md](../architecture/fo-pa-tokens-uniformity.md)

## Anti-pattern rimosso (architettura)

**Vietato** il blocco CSS con selettori pagina (`body[data-page='auth-login']`,
`main[data-page=...]`) o override per-pagina sui colori. Il componente usa hook
riutilizzabili `.auth-login-card` e `.auth-register-card`, in linea con
[design-comuni-pagine-statiche](https://github.com/italia/design-comuni-pagine-statiche).
Vedi ADR root [fo-pa-tokens-no-per-page-hex.md](../../../../../docs/wiki/decisions/fo-pa-tokens-no-per-page-hex.md).

## Pass 2026-10-20 — componente login riusabile

- eliminato `body-page="auth-login"`, che rendeva il CSS dipendente dallo stato
  globale della pagina;
- eliminati i selettori `body[data-page=...]` da `14-auth-login.css`;
- mantenuti i contratti condivisi `.auth-login-card`, `.auth-register-card` e
  `.fo-filament-form-shell`;
- sostituita la favicon Google remota con SVG inline: nessuna richiesta immagine
  esterna e nessuna violazione della Content Security Policy;
- aggiunto `AuthLoginStyleContractTest` per impedire il ritorno del pattern.
- fissato `data-slug="auth.login"` nel wrapper statico: il precompiler Volt
  inserisce marker di linea prima delle interpolazioni Blade nel primo nodo del
  fragment; renderizzarli avrebbe prodotto markup visibile invalido.

## Verifica

## Pass 2026-11-12 — reinvio verifica email

Il pulsante «clicca qui per richiederne un altro» della pagina `/it/auth/verify`
usa `wire:target="resend"`: durante l’invio viene disabilitato, mostra lo
spinner Filament e il testo localizzato «invio in corso…». Lo stato è esposto
anche con `aria-busy` e `role="status"`, così l’utente non ripete il click e
il feedback è comprensibile anche con tecnologia assistiva.

## Pass 2026-10-20 — checkbox Ricordami

`Checkbox::make('remember')` c'era. In pagina il riquadro non si vedeva: `body:has(.page-content[data-dc]) input` impone `-webkit-appearance: none` e le utility Filament (sfondo, anello, spunta) non dipingono, quindi il controllo da 20px è trasparente. Il vestito disegna il riquadro su `.fi-checkbox-input`, spunta bianca su verde quando è selezionato. Nessun checkbox scritto a mano.

## Pass 2026-10-20 — password rivelabile Filament

Lo schema aveva già `->password()->revealable()`. In pagina l'occhio stava sotto il campo (wrapper `display: block`, icona senza misura) perché lo sheet compilato non dipinge `.fi-input-wrp` dentro `data-dc`. Il vestito del componente rimette la suffix action dentro il campo. Nessun toggle Alpine scritto a mano.

**Verifica 2026-10-20 (live fixcity.eu):** markup con `passwordRevealFormComponent()`,
classe `fi-revealable`, bottoni Filament «Mostra password» / «Nascondi password»
e SVG occhio. Contratto: [filament-blueprint.md](../bmad/filament-blueprint.md),
decisione D25 in [decision-log.md](../bmad/decision-log.md), test
`LoginPasswordRevealableTest` nel modulo User.

**Verifica 2026-10-20 (live fixcity.eu):** markup con `passwordRevealFormComponent()`,
classe `fi-revealable`, bottoni Filament «Mostra password» / «Nascondi password»
e SVG occhio. Contratto: [filament-blueprint.md](../bmad/filament-blueprint.md),
decisione D25 in [decision-log.md](../bmad/decision-log.md), test
`LoginPasswordRevealableTest` nel modulo User.

## Non è ancora la pagina del riferimento

La colonna qui sotto toglie la chiave cruda e i marcatori `|---LINE:---|`. Non è Design Comuni. Il riferimento e il piano sono in [auth-login-design-comuni.md](../plans/auth-login-design-comuni.md).

## Pass 2026-10-20 — colonna civica

La chiave `fixcity::auth.login.create_account.text` mancava: in pagina si leggeva il nome della chiave. Ora è «Crea un account» (en, de, es accanto). Il vestito non usa utility Tailwind né `main[data-page]`. Lo sheet compilato esclude `.page-content[data-dc]`, quindi la colonna sta nello `<style>` del componente, classi `.auth-login-shell` e `.auth-login-card`, stessa misura della registrazione. Il pulsante Google è lo stesso partial. «Accesso in corso…» resta nascosto finché il submit non è in corso.

## Pass 2026-10-20 — contenuto CMS e stato del widget

Il renderer `components/pages/auth-login.blade.php` usa `heading` e `intro`
forniti dal blocco JSON `auth.login`, con fallback localizzato sicuro. Non
mantiene più uno spinner Alpine scollegato dal submit reale: loading, disabled,
`aria-busy` e `aria-live` appartengono al `LoginWidget` Filament. Anche il
provider Google viene reso dal widget, evitando due owner per la stessa azione.

## Pass 2026-10-20 — traduzioni e recupero password

Il blocco login è un componente CMS del tema, quindi usa le chiavi già
registrate dal modulo User (`user::auth.login.*`) per i testi di fallback e
per le azioni. Le chiavi `fixcity::auth.login.*` non fanno parte del contratto
di traduzione del modulo: in produzione venivano mostrate letteralmente.

Il link di recupero usa `/forgot-password`, che è il path effettivo della
route localizzata `password.request`; `/auth/password/forgot` non è una route
registrata e produceva un 404.

```bash
curl -sL http://127.0.0.1:8000/it/auth/login | grep -c 'id="main-container"'
# atteso: >= 1
```

## Collegamenti

- [login-agid-fix-complete.md](./login-agid-fix-complete.md)
- [filament-first-frontoffice.md](../wiki/concepts/filament-first-frontoffice.md)
