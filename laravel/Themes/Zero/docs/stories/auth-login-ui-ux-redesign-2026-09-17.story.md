---
title: "Zero theme - Redesign UI/UX pagina login (/it/auth/login)"
type: story
module: Zero
epic: "Auth pages UI/UX"
story_id: auth-login-ui-ux-redesign
slug: auth-login-ui-ux-redesign-2026-09-17
status: done
created: "2026-09-17"
updated: "2026-09-17"
depends_on: []
feeds: []
---

# Story — Redesign UI/UX pagina login

## Contesto e motivazione

Richiesta utente: migliorare UI/UX di `http://.../it/auth/login`, studiando e usando
gsap-skills, threejs-skills, design-dna, motion-design-skill, genjutsu, e uno skill
"ui-ux-pro-max" (poi risultato gia' installato). Processo seguito: skill `genjutsu:cast`
(pipeline motion/UX), che impone thesis validata prima di scrivere codice — thesis
proposta all'utente via domanda mood (risposta: combinare le opzioni, fiducia al
giudizio dell'agente).

## Bug reali trovati e risolti (non solo estetica)

1. **Doppia istanza Alpine.js sitewide**: `resources/js/app.js` importava e avviava
   una propria istanza Alpine (`import Alpine from 'alpinejs'; Alpine.start()`) in
   conflitto con quella già bundlata e avviata da Livewire (che include
   persist/collapse/focus/intersect, vedi CLAUDE.md root). Causava
   `TypeError: Alpine.$persist is not a function` e `ReferenceError: $wire is not
   defined` su ogni componente Filament con `x-data`, su OGNI pagina del tema, non solo
   login. Fix: rimossa l'istanza duplicata, il tema ora usa `window.Alpine` esposto da
   Livewire.
2. **`resources/js/bootstrap.js` con sintassi Laravel Mix** (`require('lodash')`,
   `require('axios')`), incompatibile con Vite → `ReferenceError: require is not
   defined` ad ogni page load, bloccava l'esecuzione del resto di `app.js`. `lodash` non
   e' nemmeno in `package.json` ne' usato altrove. Fix: riscritto in ESM, rimosso lodash
   inutilizzato.
3. **CSS componenti Filament assente fuori panel**: `LoginWidget` (Filament schema
   widget) viene renderizzato su una pagina Folio fuori da qualunque Filament Panel;
   `@filamentStyles` in quel contesto emette solo variabili colore/wire:loading/
   nprogress, MAI il bundle Tailwind compilato per pannello che contiene le vere regole
   `.fi-icon`/`.fi-btn`/`.fi-input`. Risultato: icona "mostra password" renderizzata a
   ~124px invece di ~16px, bottoni/input non stilizzati. Fix: linkato direttamente
   `public/css/filament/filament/app.css` (bundle reale del pannello, non una
   reimplementazione a numeri magici) nel nuovo layout auth.
4. **Font mismatch**: `tailwind.config.js` dichiarava `fontFamily.sans: ['Figtree']` ma
   solo Nunito viene caricato da Google Fonts nei layout → `font-sans` risolveva al
   fallback di sistema. Fix: allineato a Nunito.
5. **Heading duplicato**: la pagina renderizzava un h1 "Accedi al tuo account" e
   `LoginWidget` ne renderizza uno proprio (stesso testo, sottotitolo leggermente
   diverso) → doppia intestazione. Fix: rimosso l'h1 duplicato lato pagina, lasciato
   solo quello del widget.
6. **Link morti**: `href="{{-- route('terms') --}}"` — un commento Blade dentro
   l'attributo produce `href=""`; le route `terms`/`privacy` non esistono. Fix:
   rimossi i link finché le route non esistono davvero.
7. **Palette incoerente**: la card mischiava `indigo-600`/`blue-600` invece dei token
   `primary-*` del tema. Fix (poi esteso da sessione parallela con override
   `[data-auth-page]` per una palette calda scoped alle pagine auth).

## Cosa e' stato aggiunto

- Nuovo layout minimale `components/layouts/auth.blade.php`: card centrata, niente
  header/footer marketing con contenuti placeholder ("info@example.com" ecc.), sfondo
  ambientale CSS-only (blob sfocati animati, statici sotto `prefers-reduced-motion`).
- `resources/js/pages/auth-login.js`: entrata GSAP (stagger subtle, preset validato via
  skill `ui-ux-pro-max --domain gsap`), shake della card su errore di validazione
  (osserva `[role="alert"], .fi-fo-field-wrp-error-message`), tutto disattivato sotto
  `prefers-reduced-motion`. No-op completo se il markup della pagina non e' presente
  (sicuro da importare globalmente).
- Dipendenza `gsap` aggiunta a `Themes/Zero/package.json`.
- Scelta esplicita: **niente Three.js** per questa pagina — genjutsu regola 7 ("stack
  senza lib di animazione → preferire API native prima di una dipendenza") e
  proporzione costo/beneficio per una pagina di login; sfondo ambientale CSS-only
  copre lo stesso bisogno "ambient/immersive" scelto dall'utente.

## Verifica (evidenza)

- Screenshot desktop pre/post via Chrome tool: icona correggeva da ~124px a
  dimensione corretta, heading duplicato sparito, sfondo ambientale visibile.
- Console browser: 0 errori dopo i fix (prima: 8 eccezioni per page load).
- Flusso errore reale testato (email/password errate): messaggio
  "Le credenziali inserite non sono corrette." mostrato correttamente, form conserva
  lo stato.
- `php -l` su tutti i file Blade toccati: nessun errore di sintassi.
- Build Vite: 0 errori, bundle passa da contenere `require("axios")`/`require("lodash")`
  a 0 occorrenze di `require(`.

## Audit onesto (pattern genjutsu: checked-with-evidence vs handoff)

**Verificato qui:**
- Reduced motion: guardia presente in `auth-login.js` (`matchMedia` check) e in
  `app.css` (`@media (prefers-reduced-motion: reduce)` sui blob). Evidence: file
  sopra citati.
- No animazione di proprietà di layout: entrata usa `opacity`/`y` (transform), shake
  usa `x` (transform). Evidence: `auth-login.js`.
- Console pulita post-fix. Evidence: `read_console_messages` prima/dopo.

**Non verificato — richiede intervento umano/tool non disponibili qui:**
- Performance reale (frame time) via Chrome DevTools Performance recording — non
  disponibile in questa sessione.
- Viewport reali 375/768/1024/1440 — il resize tentato non ha cambiato la risoluzione
  dello screenshot catturato; da riverificare con un vero device/emulazione.
- Contrasto AA calcolato sulla palette calda `[data-auth-page]` introdotta da sessione
  parallela — non calcolato da questa sessione (la sessione parallela dichiara di
  averlo verificato, non ri-controllato qui).
- **Errore di validazione Filament (`.fi-fo-field-wrp-error-message`) non ha
  `role="alert"`/`aria-live`**: screen reader non lo annuncia. E' rendering core di
  Filament, non di questo tema — fix richiederebbe toccare `Modules/User` (fuori scope
  di un intervento a livello tema). Flag per story dedicata.

## Collisione con sessione parallela

Un'altra sessione ha modificato in tempo reale gli stessi file
(`components/layouts/auth.blade.php`, `resources/css/app.css`) durante questo lavoro:
ha aggiunto una palette calda scoped `[data-auth-page]` con verifica contrasto AA
dichiarata, sostituito l'icona SVG inline con `<x-heroicon-o-building-storefront>`, e
introdotto classi `fo-auth-input`/`fo-auth-checkbox` lato `Modules/User` Form. Le due
modifiche sono confluite senza conflitti distruttivi; risultato finale verificato via
screenshot dopo rebuild.

## Ownership File/Module Scope

- `Themes/Zero/resources/views/components/layouts/auth.blade.php` (nuovo, poi esteso
  da sessione parallela)
- `Themes/Zero/resources/views/pages/auth/login.blade.php`
- `Themes/Zero/resources/js/app.js`, `resources/js/bootstrap.js`,
  `resources/js/pages/auth-login.js` (nuovo)
- `Themes/Zero/resources/css/app.css` (sezione ambient, poi estesa da sessione
  parallela)
- `Themes/Zero/tailwind.config.js` (fontFamily fix)
- `Themes/Zero/package.json` (+gsap)

## Accettazione

- [x] Pagina non più 404/500, console pulita
- [x] Icona/bottoni Filament dimensionati correttamente
- [x] Nessun heading duplicato, nessun link morto
- [x] Animazioni GSAP con guardia reduced-motion
- [x] Bug Alpine doppio-istanza risolto (impatto sitewide, non solo login)
- [ ] Contrasto AA e viewport reali — handoff, non verificato in questa sessione
- [ ] `role="alert"` sull'errore di validazione — handoff, richiede modifica Modules/User
