---
title: "Audit UI/UX del front office e coerenza con Design Comuni"
type: compliance
status: in-progress
module: Sixteen
created: 2026-10-22
updated: 2026-10-22
tags: [bmad, ui-ux, design-comuni, accessibilita, i18n, front-office, filament-blueprint]
qmd: "audit ui ux front office design comuni chrome auth contenuti breadcrumb gravatar contatti data-dc"
related:
  - 03-front-office-scheda-per-pagina.md
  - 02-matrice-linee-guida-design-cap4.md
  - ../../../../Modules/Fixcity/docs/frontoffice-link-conventions.md
  - ../page-directory-structure.md
  - ../plans/front-office-ui-ux-design-comuni.md
  - ../bmad/filament-blueprint.md
  - ../rules/reusable-components.md
---

# Scopo

Giudicare la UI/UX di tutto il front office e la sua coerenza con il modello Design Comuni. Questa scheda
**non sostituisce** la 03 (scheda per pagina): raccoglie cio' che l'audit del 2026-10-22 ha trovato di nuovo o ha
trovato ancora vero, con la prova. Metodo: BMAD + second brain + filament blueprint (tema veste, moduli owner);
sito su `http://127.0.0.1:8081` (mattina) e riverifica sera su `https://fixcity.eu` (curl status + `data-dc`);
Playwright (Chromium), 3 viewport, 4 lingue.

| Gruppo | Stato |
|---|---|
| A chrome comune (header, footer, ricerca, cookie) | consegnato, 19 finding |
| B ticket pubblici (elenco, dettaglio, tracciamento) | consegnato, 17 finding (sezione «Gruppo B» sotto) |
| C accesso (login, registrazione) | consegnato, 17 finding |
| D contenuti e pagine legali | consegnato, 20 finding |

Fuori da questa scheda: `/auth/password/*` (pagina Volt grezza del modulo User, non ancora riscritta) e le pagine dopo il login.

# Riverifica sera (`https://fixcity.eu`, curl)

| Path | HTTP | `data-dc` | Nota |
|---|---|---|---|
| `/it`, `/en` | 200 | **0** → fix home | home senza scope DC |
| `/it/tickets`, FAQ, blog, events, services, lista-categorie, mappa-sito | 200 | ≥1 | OK |
| `/it/auth/login`, register | 200 | ≥1 | OK |
| `/it/contatti` | 200 | 1 | soft page; CTA blog/eventi non devono puntarci |
| `/it/tickets/track` | 200 | **0** → fix | B-11 |
| `/it/tickets/create` (guest) | 200 | login | redirect atteso |

Filament blueprint: shell Folio + blocchi; niente Resource nel tema. Coerenza DC = `data-dc` + markup `cmp-*` + i18n + `FrontofficeRoutes`.

# Corretti in questo giro

| ID | Cosa | Prova |
|---|---|---|
| D-11 | chip categoria «ACCESSIBILITà» con la «à» minuscola: `strtoupper` non gestisce UTF-8 | `Str::upper` in `blocks/blog/detail.blade.php` e `blocks/events/detail.blade.php`; `curl` su un articolo e un evento stampa «ACCESSIBILITÀ E INCLUSIONE» **[V]** |
| (link) | CTA della home en/de/es verso `/it/...`; «Password dimenticata» verso una non-rotta | vedi `frontoffice-link-conventions.md`, sezione «Nota locale» |
| (home `data-dc`) | homepage senza `data-dc` → CSS Design Comuni fuori scope | `pages/index.blade.php` wrapper `page-content` + `data-dc` |
| B-11 | `/tickets/track` senza telaio DC | Folio slug0: `ticket-track` → `layouts.design-comuni` + `data-dc` |
| A-14 (parziale) | breadcrumb `aria-label="breadcrumb"` hardcoded | `report-partials/breadcrumb` → `__('fixcity::global.breadcrumb')` |
| D-03 | Gravatar terzi sui commenti FO | `comment-item` + `InteractsWithComments` → iniziali locali; Filament widget allineato |
| D-12 (parziale) | «Richiedi assistenza» → `/contatti` | JSON blog/events → `/tickets/create` |

# Aperti, in ordine di peso

## Alta

| ID | Cosa | Evidenza | Direzione (riuso, non rattoppo) |
|---|---|---|---|
| D-03 | le pagine con commenti chiamano `www.gravatar.com` (IP del visitatore a terzi); la cookie-policy dice «0 host terzi» | `Modules/Comment/.../comment-item.blade.php:7` costruisce l'URL **[V]**; richieste misurate dall'agente | avatar locale (iniziali/SVG) nel componente commenti, unico punto; il testo della cookie-policy lo decide il titolare |
| D-01 | `/services`, `/lista-categorie`, `/mappa-sito` senza utility Tailwind dentro `[data-dc]` (griglia `display:block`, card senza padding) | computed style misurato; le regole esistono ma sono dentro un `:not(:where(body:has(.page-content[data-dc])...))` | riscrivere con il markup Design Comuni gia' usato da blog e FAQ, riusando i blocchi, non classi nuove |
| D-02 | `/lista-categorie` non elenca categorie: h1 «Esplora le segnalazioni», title «Categorie di servizio», una sola card | crawl in 4 lingue | allineare h1/title o togliere la pagina (duplica `/tickets`): **decisione di prodotto**. Aggiornamento 2026-10-27: la pagina e' una replica per parita' visiva del template Design Comuni; la decisione «tenerla come directory delle categorie di segnalazione» e' presa (D-LC-01), resta aperto come collegare categoria e filtro (D-LC-02: `Category` non ha legame con i ticket, che si classificano con `TicketTypeEnum`). Vedi `Modules/Fixcity/docs/stories/dcp-408-lista-categorie-senso-origine-e-difetti-misurati.story.md` |
| A-01 | skip-link fuori schermo in home (`y=-96`), visibile nelle altre pagine | misurato dopo Tab | stessa regola `visually-hidden-focusable` delle pagine `data-dc` |
| A-02 | modale di ricerca della home non cliccabile (`pointer-events:none`), due chiusure, etichetta 3,2:1 | `elementFromPoint` sul campo restituisce l'hero | rendere la modale della home uguale a quella di `/it/tickets`, che funziona |
| A-03 | selettore lingua senza focus visibile | outline 0, contrasto 1,1:1 | ripristinare l'outline in `:focus-visible` |
| C-01 | «Accedi con Google» in registrazione: icona 126 px, link alto 184 px (in login 44 px) | `getBoundingClientRect` | spostare lo stile nel partial `components/utilities/google-button`, non duplicarlo per pagina |
| C-02 | errori di registrazione con nomi di campo grezzi («first name è richiesto») in it/de/es | testo letto dopo invio vuoto | etichette tradotte per gli attributi di validazione |
| C-03 | errori di registrazione non collegati ai campi (`aria-invalid` assente) | DOM dopo invio vuoto | allineare alla struttura del login, che li ha |
| C-04, C-05 | durante l'invio il pulsante diventa un blocco alto 210-406 px; in registrazione compare il verbo del login | risposta Livewire ritardata | vincolare le dimensioni dell'icona nel vestito del componente |
| C-06 | banner cookie `fixed` copre i campi che ricevono il focus | stop Tab sotto il banner | `scroll-padding-bottom` pari all'altezza, o banner in flusso |

## Media

| ID | Cosa | Evidenza |
|---|---|---|
| D-12 | «Richiedi assistenza» porta a `/{lang}/contatti` che risponde 404 in 4 lingue | `curl` 404 it/en **[V]**; l'URL e' in `pages/blog/index.json`, `pages/events/index.json`, `blog.index.json` |
| D-04, D-05 | «Invio» del blog 2,59:1; focus invisibile sui `button` (outline bianco 3px) | colore calcolato / computed style |
| D-06 | FAQ: `aria-expanded="true"` su 12 domande chiuse | Playwright prima/dopo click |
| D-08, D-09, D-10 | slug inesistente di blog/eventi risponde 200 con `<main>` vuoto; ricerca senza risultati con messaggio sbagliato; filtro per categoria senza segnale | screenshot e DOM |
| D-13, D-14 | immagini 48 px ingrandite a 374; dettaglio evento senza indice, luogo, contatti; data ripetuta due volte | confronto con `/it/tests/*` |
| D-15 | questionario di gradimento e etichette del blog in italiano in en/de/es | estrazione dal crawl |
| D-16 | `id="main-container"` duplicato in 7 pagine (2 `<main>` sulla 404) | DOM |
| A-04 | banner cookie senza padding laterale a 375, alto 28-39% dello schermo | computed style |
| A-05, A-09 | chiusura del menu mobile fuori schermo (`right=390`, viewport 375); menu lingua a 54 px dal pulsante, senza `lang`/`hreflang` | misurato |
| A-06 | modale di ricerca: il focus non entra nel dialogo, nessun `aria-labelledby` | sequenza Tab |
| A-07, A-08 | «Seguici su» senza link social; barra regione con «Demo» (`header.json:27`) **[V]** | DOM / grep |
| A-12, A-13, A-17 | colonna «Contatti» senza contatti, link duplicati; `<nav>` secondario vuoto; etichette incoerenti | DOM |
| A-14 | `aria-label="breadcrumb"` fisso in ~45 blocchi, non tradotto **[V]** | `grep`; la chiave `fixcity::global.breadcrumb` esiste in it/en/de/es **[V]** |
| A-19 | la ricerca non ha funzione (form senza `action`) | Invio non cambia URL |
| C-07, C-08, C-10 | login e registrazione non sembrano sorelle; bordo password 1,5:1; «Mostra password» solo sul primo campo | screenshot / computed style |
| C-09 | «Mostra password» in italiano in en/de/es: `register.blade.php:224` usa `__('Mostra password')` come chiave letterale **[V]** | `grep` |
| C-11, C-12 | autofocus sul login; dopo errore il focus va su `BODY` | `document.activeElement` |

## Gruppo B (ticket pubblici)

| ID | Gravita' | Cosa | Evidenza |
|---|---|---|---|
| B-09 | media | ticket «Aperto» (36) e «Riaperto» (39) mostrano «La segnalazione e' stata presa in carico»: `OPEN` e `REOPENED` sono mappati come `IN_PROGRESS` sulla chiave `processing` | `BuildTicketNextStepsAction.php:20-23` **[V]**; il testo giusto e' una decisione di prodotto |
| B-03 | media | il geojson non e' localizzato: con `?lang=en` restituisce ancora «In lavorazione» e «Arredo Urbano», quindi popup e `title` dei marker restano in italiano in en/de/es | `curl /api/tickets/geojson?lang=en` **[V]**; l'URL dell'API non porta il prefisso lingua per regola |
| B-08 | media | il chip di stato nel dettaglio ha le stesse classi (`chip chip-simple chip-lg`) per ogni stato; sulla mappa lo stesso stato ha 6 colori | markup di `ticket-detail/design-comuni.blade.php:~78` **[V]**; colore per stato = riuso della mappa gia' usata per i marker |
| B-01, B-02, B-05 | media | a 375 il gruppo dei 6 controlli mappa (340 px) sborda la mappa (320 px); il corpo del popup scorre in 99 px con titolo tagliato; i controlli (z-index 3002) stanno sopra la modale filtri (1055) | bounding box e `elementFromPoint` dell'agente; file non identificati |
| B-04 | media | popup: dopo Invio sul marker servono 9 Tab, Esc non chiude, nessun `role=dialog`, `aria-label="Close popup"` in inglese in tutte le lingue | sequenza Tab dell'agente |
| B-06 | media | link della fascia «Hai bisogno di aiuto?» in verde 2,59:1; axe lo vede solo in `?view=list` | colore calcolato; `#00b877` **non** compare nei sorgenti del tema **[V]**: causa non trovata |
| B-11 | media | `/tickets/track` fuori dal telaio Design Comuni (Tailwind, blu non in palette, niente breadcrumb ne' `data-dc`); il pulsante «Cerca» ora e' visibile, quindi la B1 della scheda 03 e' chiusa | computed style e screenshot dell'agente |
| B-07, B-10, B-12..B-17 | bassa | legenda mappa non inclusa; card «Prossimi passi» senza bordo; errore del codice senza formato; 429 con «un minuto» vs `Retry-After: 32`; «Filtra» 24 px e reset sempre visibile; 4 etichette per «crea segnalazione» e pulsanti a 999px nella home contro 4px; `/tickets/create` da ospite senza messaggio sul perche' | misurati dall'agente |

Controllato e OK (agente): 200 su elenco/dettaglio/track in 4 lingue, tile OSM 12/12 e 18/18, nessun overflow a 375/768/1280, stato vuoto della ricerca, modale filtri con `role=dialog` e focus dentro, freno del tracciamento (10 x 200, poi 429 con `Retry-After`), axe 0 violazioni serious/critical su dettaglio e track.

## Bassa

D-17 tipografia delle pagine legali; D-18 tabella cookie non responsive a 375; D-19 chip e «Leggi tutto» alti 21 px;
D-20 `<title>` senza nome del sito; A-15 focus dopo Esc sul menu; A-16 pulsante ricerca 36 px; A-18 slug italiani
in en/de/es; C-13..C-16 messaggio errore doppio, hint nella `<label>`, «Password dimenticata?» in registrazione.

# Il filo comune: la stessa cosa copiata in piu' posti

Quasi tutti i difetti ripetuti hanno la stessa forma: **la stessa logica scritta a mano in ogni blocco**, quindi
corretta in uno e aperta negli altri (regola utente 2026-10-22: componenti riutilizzabili). Esempi misurati:

- **Breadcrumb**: 45+ blocchi con `<nav aria-label="breadcrumb">` proprio. Esistono gia'
  `components/bootstrap-italia/breadcrumb.blade.php` e `components/navigation/breadcrumb.blade.php`: un solo
  componente che usa `__('fixcity::global.breadcrumb')` chiude A-14 in tutti i blocchi insieme.
- **Pulsante Google**: un partial, due vestiti (C-01).
- **Focus di `button`**: regola globale mancante (D-05, A-03), non da correggere pagina per pagina.
- **URL scritti a mano**: `/it/tests/...`, `/contatti`, `/lista-categorie` nei JSON e nei blocchi (D-12).

Proposta di story (una per ambito, file disgiunti, da prenotare con `ls` sul registro DCP prima del numero):
(1) breadcrumb unico; (2) focus e contrasto dei `button` + skip-link; (3) avatar commenti senza terzi;
(4) auth: vestito condiviso login/registrazione, errori collegati, stato di invio; (5) pagine
`services`/`categories`/`sitemap` sul markup Design Comuni; (6) chiavi i18n mancanti di blog/eventi/registrazione.

# Non verificato

Gruppo B: paginazione con piu' di 20 ticket, timeline con voci e foto, vista da loggato (21 marker contro 12 risultati), popup a 375 in de/es, file CSS/JS di B-01/B-02/B-05; lettori di schermo reali; pagine dopo il login e pannello Filament; 429/500 non riprodotti; produzione
(debugbar, CSP, gravatar); cause nei file indicati come «probabili» dagli agenti (solo quelle **[V]** le ho riprodotte).
Nota: il tema e' stato ricompilato durante l'audit (manifest cambiato alle 17:05): alcune misure del gruppo D sono state rifatte dopo.
