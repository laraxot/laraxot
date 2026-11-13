---
id: dcp-288
title: "DCP-288: ricerca del sito, intestazione accessibile e metadati SEO del front office"
type: story
epic: go-live-fo-audit
status: in-progress
priority: P1
owner: theme:Sixteen
module: Sixteen
created: 2026-10-26
updated: 2026-10-26
qmd: "dcp 288 ricerca modale search-modal name q autocomplete-two focus trap Esc ritorno focus z-index leaflet ctrl-btn menu mobile menu-wrapper skip link breadcrumb target size marker title suffisso FixCity canonical robots theme-color og:image favicon"
related: [dcp-143, dcp-152, dcp-104, dcp-151, dcp-166, dcp-273]
tags: [ricerca, accessibilita, seo, intestazione, front-office, tdd, bmad]
---

# DCP-288: ricerca del sito, intestazione accessibile e metadati SEO

Origine: audit front office del 2026-10-26, riscontri FO-1-01, 06, 07, 19, 20, 22, 24, 25, 28, 29 (gruppo fo-1) e FO-6-10, 12, 15, 17, 18, 20, 22, 25 (gruppo fo-6). Ogni affermazione dell'audit e' stata riverificata prima di toccare. Etichette: **MISURATO** (comando o browser eseguito, con output), **DEDOTTO** (letto nel codice, non eseguito).

## Fase BMAD

Build (dev-story) con TDD: per ogni punto prima il test rosso o la misura nel browser su :8091, poi il codice, poi il test verde e la nuova misura. Questa story sta nel tema Sixteen perche' la modale, il layout e gli stili sono del tema; dove un punto tocca un altro proprietario (Geo per l'area sensibile e l'isolamento dei controlli mappa, Cms per le sorgenti di ricerca) lo dico nello scope.

## Stato

In corso. L'esito per punto, con i comandi eseguiti, e' nella sezione Esito in fondo.

## Fatti misurati prima di toccare

### FO-1-01, la ricerca non cerca

1. **MISURATO (curl su :8091).** `/it/tickets?autocomplete-two=zzzzqq` risponde "12 Risultati"; `/it/tickets?q=zzzzqq` risponde "0 Risultati" e "Nessuna segnalazione trovata"; `/it/tickets?q=buca` risponde "1 Risultato". L'elenco legge `q`.
2. **MISURATO (HTML di /it).** Il campo e' `<input type="search" ... id="autocomplete-two" name="autocomplete-two">` dentro un `<form>` senza `action` e senza `method`; il pulsante "Cerca" e' `type="button"` e non invia nulla.
3. **MISURATO (Chromium, script m1).** Dalla pagina `/it/tickets`: Invio nel campo porta a `/it/tickets?autocomplete-two=zzzzqq` con "12 Risultati" (il filtro non c'e'). Dalla home `/it`: Invio porta a `/it?autocomplete-two=zzzzqq`, cioe' resta sulla home (nessun risultato, nessun avviso): peggio di quanto diceva l'audit, che aveva provato solo da /it/tickets. Il clic sul pulsante "Cerca" non cambia l'indirizzo in nessuna delle due pagine.
4. **DEDOTTO.** La vista Tailwind `components/sections/search-modal.blade.php` usa gia' `action=ticketsIndex()`, `name="q"`, `type="submit"`: la vista Design Comuni, quella servita, non e' stata allineata. Il registro dice "cablata su q": vero per la vista Tailwind, falso per quella usata.
5. **MISURATO.** Testi del campo: `config/local/fixcity/database/content/sections/search-modal.json` ha in it `label`/`placeholder` "Cerca nel sito" (en "Search the site", de "Website durchsuchen", es "Buscar en el sitio"). L'unica destinazione e' l'elenco delle segnalazioni pubbliche. Il pulsante di apertura in intestazione ha `aria-label="Cerca nel sito"` dal file `header.json` (non e' mio: DCP-289).
6. **DEDOTTO.** Non esiste una sorgente di ricerca su pagine, FAQ, articoli ed eventi da riusare: il blog ha un campo "Cerca per parola chiave" proprio dentro il suo blocco, nessuna Action condivisa. Una ricerca su tutto il sito richiede un widget e una Action nuovi (vedi Decisioni, DA DECIDERE).

### FO-1-07, accessibilita da tastiera della modale

7. **MISURATO (script m1, 1280x800, da tastiera).** Apertura con Invio sul pulsante: `activeElement` resta sul pulsante di apertura (`BUTTON[Cerca nel sito]`), non entra nella modale. 8 Tab successivi: il focus va a "Segnalazioni", "Segnala", "Traccia", "Novita", "Eventi", "Domande frequenti", poi al contenuto della pagina dietro la modale: nessun focus trap. Esc chiude la modale (`display: none`) ma il focus resta dove era finito, non torna al pulsante.
8. **MISURATO (script m3, 375x812).** I pulsanti dei controlli mappa (`button.ctrl-btn`, z-index 3002, dentro `div.layer-controls-overlay` z-index 3001, dentro `div.map-container` `position: relative; z-index: auto`) stanno SOPRA la modale (z-index 1055) e sopra il suo fondo: `elementsFromPoint` a (50,540) dice `svg, button.ctrl-btn, div.modal-body, ...`. Screenshot: i cinque pulsanti compaiono sul testo "Novita e comunicazioni" della modale.
9. **Causa (MISURATO + DEDOTTO dal CSS di Geo).** `.map-container` e' `position: relative` senza `z-index` e senza `isolation`: non crea un contesto di impilamento, quindi i z-index 3001 e 3002 dei suoi controlli (`Modules/Geo/resources/js/components/map/map-styles-lit.js`, `map-picker-styles.js`, `styles.js`) competono con la modale nel contesto radice. Il solo `div.geo-map-leaflet` ha `z-index: 1`, ma i controlli non sono dentro di lui. Questo stesso difetto fa comparire i controlli della mappa sopra il menu mobile (FO-1-06).

### FO-1-06, menu mobile senza sfondo

10. **MISURATO (script m4, 375 e 768).** A menu aperto `.navbar-collapsable` e' `position: fixed`, 375x812, z-index 1050; `.menu-wrapper` ha `background-color: rgba(0,0,0,0)`. Lo sfondo scuro `.overlay` c'e' (rgba 0,0,0,0.6). Screenshot: voci bianche su un riquadro verde largo circa 180 px, logo e controlli della mappa visibili sopra.
11. **Causa (MISURATO, regole in cascata via `document.styleSheets`).** Due regole di legacy forzano la trasparenza a ogni larghezza anche se il commento dice "desktop": `.it-header-navbar-wrapper .menu-wrapper { background-color: transparent !important }` in `resources/css/components/header-footer-colors.css` (riga 91) e `.it-header-navbar-wrapper .menu-wrapper { background: transparent !important }` in `resources/css/app/05-homepage-parity-overrides.css` (riga 378). Battono la regola generata del riferimento (`body:has(.page-content[data-dc]) .navbar .navbar-collapsable .menu-wrapper { background: #fff; position: absolute; right: 48px; left: 0 }`), che non ha `!important`. In piu' `.it-header-navbar-wrapper a, .nav-link { color: #fff !important }` rende le voci bianche anche su fondo bianco.

### Metadati SEO (FO-1-24, FO-1-25, FO-6-15, 17, 18, 20, 22, 25)

12. **MISURATO (curl).** Titoli: `/it` "FixCity — Segnala un problema, segui la soluzione", `/it/tickets` "Elenco segnalazioni", `/it/privacy` "Informativa privacy", `/it/nonesiste` "Pagina non trovata", `/it/auth/login` "Accedi a FixCity". `og:site_name` e' "FixCity" ovunque; `og:locale` e' il solo codice lingua ("it"); nessun `<meta name="robots">`, nessun `theme-color`, nessun `<link rel="icon">`.
13. **DEDOTTO.** Il layout servito e' `Themes/Sixteen/resources/views/components/layouts/main.blade.php` (via `x-layouts.main` da `design-comuni`, `design-comuni-bare`, `app`, `test`): non usa `Modules\Cms\View\Components\Metatags` (lo usa solo il vecchio `resources/views/layouts/main.blade.php` con `$_theme->metatags()`). Quindi il punto d'intervento per titolo, og e robots e' il layout del tema e non il componente Cms. Il nome del sito e' gia' calcolato li (`config('app.name')`, con ripiego "FixCity" se vuoto o "Laravel").

## Causa

(Da completare a fine lavoro per ogni punto.)

## Decisioni

(Da completare a fine lavoro.)

## Criteri di accettazione verificabili

1. Il campo della modale di ricerca si chiama `q`, il form fa GET verso l'elenco segnalazioni nella lingua corrente e il pulsante "Cerca" invia. Prova: test Pest sul markup reso e curl `/it/tickets?q=...` che filtra.
2. Il testo della modale non promette piu' "tutto il sito": dice cosa cerca, nelle quattro lingue.
3. All'apertura il focus entra nel campo, Tab e Maiusc+Tab restano dentro la modale, Esc chiude e riporta il focus al pulsante che l'ha aperta. Prova: spec Playwright e misura su :8091 con lo script JS iniettato (la build e' del coordinatore).
4. I controlli della mappa non stanno piu' sopra la modale e sopra il menu mobile.
5. Il pannello del menu mobile ha sfondo bianco e voci leggibili sotto 992 px; da 992 px in su l'intestazione verde non cambia.
6. I titoli delle pagine interne finiscono con `| FixCity`, senza raddoppiare il nome dove c'e' gia'.
7. Le pagine private e di servizio hanno `noindex`; `theme-color` e `og:locale` corretto; nessuna immagine o icona inventata.

## Test

(Elenco dei file di test e dei comandi, con rosso e verde, a fine lavoro.)

## Owned file scope

(Elenco esatto dei file toccati, a fine lavoro.)

## Esito

(A fine lavoro.)
