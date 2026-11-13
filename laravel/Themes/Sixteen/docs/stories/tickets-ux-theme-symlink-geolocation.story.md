# Tickets FO: asset tema persi, bottone filtri, posizione

- Stato: done (2026-10-07), con un aperto ambientale (HTTP su IP LAN)
- Fase BMAD: Quick Flow (investigate, fix, verifica nel browser)
- Owner: Themes/Sixteen (build, symlink, pagina tickets), Modules/Geo (geolocalizzazione)

## Contesto

`public_html/themes/Sixteen` e' ora un symlink a `laravel/Themes/Sixteen/public/` (voluto):
`public_html/themes` e' gitignorato, `public/` del tema e' tracciato, nessuna copia da tenere allineata.
Il `rm -rf Sixteen` ha pero' cancellato la build vite che viveva li.

## Cause radice

1. Pagina con 404 su `app-CPwOXbJZ.css`, `app-DEMCkssF.css`, `app-Cro-b5z3.js`: il manifest in `public/`
   li elencava, i file no. Senza il JS del tema il bottone posizione non faceva nulla.
2. `pages/tickets/index.blade.php` non passava `bodyPage="ticket-list"`: le 107 regole CSS
   `body[data-page='ticket-list']` non si applicavano e lo `<span>` di "Rimuovi filtri" restava `#1a1a1a`
   su verde. Misurato in Chrome: span `rgb(26,26,26)` prima, `rgb(255,255,255)` dopo.
3. Posizione: su `http://192.168.1.40:8080` il browser rifiuta la geolocalizzazione (origine non sicura,
   errore 1 "Only secure origins are allowed") senza alcun prompt. Il messaggio diceva "Consenti l'accesso",
   che non risolve nulla. Un permesso gia' bloccato per il sito da' lo stesso sintomo.
4. Con il symlink, `outDir` e `publicDir` di vite coincidono e `emptyOutDir: true` svuotava `public/`
   (file tracciati inclusi); `npm run copy` copiava file su se stessi (`cp: same file`).

## Modifiche

- Tema ricostruito: `npm ci` (con `PUPPETEER_SKIP_DOWNLOAD=1`) e `vite build` in scratch, copia additiva in `public/`.
- `Themes/Sixteen/vite.config.js`: `publicDir: false`, `emptyOutDir: false`.
- `Themes/Sixteen/package.json`: `copy` scrive in `./public`, un solo glob per le immagini design-comuni
  (aggiunge `map-pin.svg`, `map-placeholder.svg`), tolto il self-copy di `logo.svg`; `copy:filament` e' `true`.
- `pages/tickets/index.blade.php`: `bodyPage="ticket-list"` (aggiunto in parallelo da un altro processo).
- `Modules/Geo/.../map/controls/geolocation.js`: contesto non sicuro -> "La posizione e' disponibile solo su
  HTTPS o localhost."; permesso `denied` (Permissions API) -> istruzioni per sbloccare dal lucchetto;
  altri errori -> messaggio generico. Messaggio mostrato in `.map-control-status` (render-controls, styles).

## Verifica (Chrome 148 headless, puppeteer-core)

- Tutte le risorse `/themes/Sixteen/...` richieste dalla pagina: 200.
- Origine HTTP non sicura: messaggio HTTPS. Permesso negato: messaggio di sblocco.
  Origine sicura con permesso: mappa centrata, zoom 15, nessun messaggio.

## Aperto

- Per usare il bottone da `http://192.168.1.40:8080` serve un contesto sicuro: tunnel
  `ssh -L 8080:127.0.0.1:8080 zorin@192.168.1.40` e aprire `http://localhost:8080`, oppure in Edge
  `edge://flags/#unsafely-treat-insecure-origin-as-secure` con `http://192.168.1.40:8080`, oppure HTTPS locale.
- `public/` tracciato contiene vecchie build e ora anche la nuova: valutare se gitignorare `public/assets`.
- UX `/it/tickets`: "Rimuovi filtri" resta attivo con 0 filtri; mappa parte su Italia zoom 6 senza risultati.

## Aggiornamento 19:22: "Consenti l'accesso..." persiste, nessun prompt

- L'utente clicca "Usa la mia posizione" e vede "Consenti l'accesso alla posizione nel browser e riprova.", senza alcun prompt del browser.
- Quel testo e' il ramo di ripiego di `geolocation.js`: nel suo browser `isSecureContext` e' true (altrimenti vedrebbe il messaggio HTTPS), l'errore e' code 1 e la Permissions API NON dice `denied`. Il codice non puo' distinguere oltre.
- Riprodotto in headless: origine HTTP -> messaggio HTTPS (corretto), nessun `Permissions-Policy` inviato dal server. Il caso dell'utente non e' riproducibile senza il suo browser.
- Da fare: l'utente incolla l'output della diagnostica in console (`isSecureContext`, `permissions.query`, `error.code` e `error.message`). `error.message` distingue "User denied Geolocation" da "Only secure origins..." o da un errore del servizio di localizzazione del sistema.
- Ipotesi in ordine: servizi di localizzazione del sistema spenti, permesso del sito in blocco senza stato `denied`, flag insecure-origin non effettivo dopo il riavvio del browser.

## Aggiornamento 20:00: popup dei marker e lingue

- Popup con testo bianco su bianco sulla home, popup tagliato e azioni fuori vista: vedi `Modules/Geo/docs/stories/map-popup-contrast-and-fit.story.md`.
  Causa: regola hero `main section:has(#welcome-heading) p {color:#fff !important}` che includeva la mappa.
- Pagine in de/es e `/en/auth/login` in italiano per via del fallback `it`: vedi `Modules/User/docs/stories/auth-i18n-all-locales.story.md`.
