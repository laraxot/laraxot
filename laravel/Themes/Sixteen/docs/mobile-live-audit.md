---
title: "Audit mobile misurato (390px) e correzioni dei target tattili"
type: note
theme: Sixteen
status: active
created: 2026-10-28
updated: 2026-10-29
tags: [docs, sixteen, mobile, accessibility, touch-target]
qmd: "sixteen mobile audit 390px target tattili 44px cookiebar viewport zoom playwright fixcity two"
related:
  - ./mobile-demo-accessibility-contract.md
  - ./mobile-tablet-improvements.md
  - ./header-mobile-overlay.md
  - ../../Two/docs/mobile-live-audit.md
---

# Audit mobile misurato (390px)

> **Ambito.** Questa misura riguarda Sixteen **come lo serve il progetto FixCity** (`base_fixcity_fila5`, `http://127.0.0.1:8000`,
> titolo «FixCity»), non il front-office di techplanner. Qui il tenant `local/techplanner` serve il tema **Two**
> (`http://192.168.1.40:8000`): la sua misura e' in [Two](../../Two/docs/mobile-live-audit.md). Sixteen resta una replica ad alta
> parita' HTML di Design Comuni; in questo progetto la UX mobile si modifica in Two, non qui.

Il contratto di `mobile-demo-accessibility-contract.md` fissa soglie (390/768/1440 px, nessun overflow, target >= 44px) ma
nessun documento diceva se le pagine reali le rispettano. Questo file registra una misura, non un'opinione, e ciò che ne è
seguito.

## Metodo (ripetibile)

`AUDIT_BASE=<server> node bashscripts/tools/mobile-audit.mjs <cartella-output> [pagine]` apre `<server>/it` con Chromium headless
(`AUDIT_BASE` e' obbligatorio, senza default: prima di misurare verificare quale progetto serve la porta con
`ss -ltnp | grep :8000` e `readlink /proc/<pid>/cwd`; qui `127.0.0.1:8000` era FixCity) emulato iPhone (390x844, DPR 3, touch), segue i link interni (solo GET, nessun submit, nessun login) e per ogni pagina misura:
overflow orizzontale, target interattivi sotto 44px (e sotto 24px), campi con font sotto 16px (zoom automatico iOS), testo sotto
12px, elementi fissi alti piu' di un quarto dello schermo, immagini senza `alt`, errori in console, risposte 4xx/5xx. Salva uno
screenshot per pagina e `audit.json`.

Il browser dell'MCP chrome-devtools non parte senza display: serve Playwright headless (vedi lo strumento per dove trovarlo).

## Risultato su 12 pagine pubbliche (/it, /en, /de, /es, cookie-policy, login, segnalazioni, blog, eventi, FAQ)

| Controllo | Esito |
|---|---|
| `viewport` con `width=device-width` | tutte |
| Scroll orizzontale della pagina (`scrollWidth` = 390) | nessuna pagina |
| Campi con font < 16px | 0 |
| Immagini senza `alt`, errori console, risposte 4xx/5xx | 0 |
| Un solo `h1` per pagina | tutte |
| Elementi che superano la larghezza | solo dentro contenitori che li tagliano: tile di Leaflet, tabella della cookie-policy (nel wrapper scorrevole) |
| Target non inline sotto 44px | 21-29 per pagina (vedi sotto) |

La parte strutturale (viewport, overflow, campi, alt) era gia' a posto: il lavoro delle storie 7-10, 7-28 e DCP-228 ha
funzionato. I difetti residui sono di dimensione dei target, non di layout.

## Proposto per FixCity, non applicato in questo progetto

Le tre regole sotto sono state verificate nel browser (iniezione di CSS) sul Sixteen servito da FixCity, poi **tolte** da
`resources/css/homepage-mobile-qa.css` di techplanner: qui il tema da modificare e' Two e Sixteen non si cambia per una richiesta
di questo progetto. Restano come proposta per chi mantiene FixCity (stesso repo `theme_sixteen_fila5`), da applicare in
`homepage-mobile-qa.css` dopo `ticket-parity.css`, solo con `(pointer: coarse)` e sotto 992px:

| Elemento | Prima | Dopo | Nota |
|---|---|---|---|
| Pulsante di ricerca dell'header (`.search-link`) | 36x36 | 44x44 | `ticket-parity.css:4087` e `:4389` lo fissano a 2.25rem con `!important`; si vince per ordine di import a pari specificita'. L'altezza dell'header resta 169px |
| Tab Mappa/Elenco (`#tabDisservizio .nav-link`) | 31px | 44px | testo in alto, l'indicatore della tab attiva resta in basso |
| Link isolati del login (`.auth-login-links a`) | 24px | 44px | "Password dimenticata?", "Crea un account" |

Verificato iniettando le regole nel browser (telefono 390, tablet 768, desktop 1280): nessun overflow, header invariato, con il
mouse nulla cambia. La transizione di 0.3s sul pulsante di ricerca falsa una misura presa subito dopo: attendere 1s.

Se applicate, le regole compaiono solo dopo la ricompilazione: gli asset `Themes/Sixteen/public/assets/app-*.css` sono tracciati
in git e `npm run build` sostituisce i file con hash vecchio (decisione di chi gestisce il progetto, non di un agente).

## Valutato e lasciato com'e'

- **Barra cookie** (`Modules/Gdpr/resources/views/cookie-consent/index.blade.php`): a 390px occupa 231-255px, cioe' il 27-30%
  della prima schermata, e nel login copre il pulsante "Accedi". E' una scelta documentata (DCP-153, WCAG 2.4.11): barra
  informativa che non blocca, `padding-bottom` del corpo aggiornato da `pad()`, layout a due colonne sotto 576px. L'unica leva
  rimasta e' accorciare `alert_text_info` nelle traduzioni: da decidere con chi cura i testi, non con il CSS.
- **Link di testo nel corpo** (breadcrumb "Home" 24px, "Maggiori informazioni"): rispettano WCAG 2.2 AA (2.5.8, minimo 24px);
  portarli a 44px sposterebbe le righe.
- **Attribuzione "Leaflet"** (17px): link di terze parti.

## Chiarimenti e residui

- `resources/views/mobile/**` non è una superficie pubblica verificata: il provider registra i namespace `pub_theme` e `sixteen`,
  non `mobile`, e non è stata trovata una rotta `/mobile`. Non trasformarlo in una feature aggiungendo una rotta implicita:
  le viste contengono dati demo, POST fittizi e fetch non autenticati. Prima serve una story di prodotto e un owner della rotta.
- `Modules/Geo/resources/views/layouts/partials/htmlheader.blade.php` aveva lo stesso frammento obsoleto dentro un commento Blade;
  ora anche quel template inattivo documenta il viewport accessibile, evitando regressioni da copia-incolla. Le pagine statiche
  attive `index.html` e `contribute/index.html` hanno già il viewport accessibile; `contribute/index.html` ora usa anche label HTML
  reali, tastiera mobile (`search`, `tel`, `url`) e autocomplete coerente.
- La copia legacy realmente servita sotto `resources/views/maps/farmshops/**` è stata allineata allo stesso contratto: anche la
  mappa Blade e il relativo contributo non bloccano più lo zoom.
- Per le mappe attive il compromesso corretto è consentire lo zoom della pagina e confinare solo i gesti della mappa al suo
  contenitore con `touch-action`; non introdurre un blocco globale del viewport.
- Il flusso di segnalazione (`/it/tickets/create`) e i pannelli Filament richiedono login: non misurati. Prossima passata con
  un utente di prova su un database `*_test`, mai sui dati reali.
- Un altro agente lavorava in parallelo sull'header (DCP-121: `mobile-header-fix.css`, `header-mobile-nav.js`,
  `header/v1.blade.php`); questi file non sono stati toccati qui.
- Filament Blueprint non e' installato (richiede la licenza a pagamento, che deve fornire l'utente), quindi non e' stato usato.
