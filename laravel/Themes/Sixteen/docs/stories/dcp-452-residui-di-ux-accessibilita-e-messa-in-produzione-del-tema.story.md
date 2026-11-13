---
id: dcp-452
title: "DCP-452: residui di UX, accessibilità e messa in produzione del tema («Demo», favicon, forced-colors, immagini, target, file .old)"
type: story
status: open
module: Sixteen
created: 2026-11-12
updated: 2026-11-12
tags: [bmad, story, sixteen, front-office, a11y, ux, produzione, favicon, ente]
qmd: "residui ux accessibilita Demo header region favicon 0 byte ente.php forced-colors target 24px immagini width height srcset file old public_html"
related:
  - ./front-office-production-readiness-audit.story.md
  - ../../../../Modules/Fixcity/docs/stories/dcp-200-registro-unico-delle-decisioni-aperte.story.md
  - ../../../../Modules/Fixcity/docs/stories/dcp-208-messa-online-compiti-del-fornitore-e-dell-ente.story.md
  - ./dcp-448-accessibilita-del-front-office-aria-faq-focus-e-menu.story.md
issues: ["https://github.com/laraxot/theme_sixteen_fila5/issues/104"]
discussions: ["https://github.com/laraxot/theme_sixteen_fila5/discussions/94", "https://github.com/laraxot/theme_sixteen_fila5/discussions/109"]
---

# DCP-452: residui di UX, accessibilità e messa in produzione del tema

Status: **aperta** (2026-11-12). Fase BMAD: Analysis fatta (audit F del 2026-11-11, voci UX-05, 06, 08, 11, 14…25); Solution da decidere sulle due righe che
toccano i dati dell'ente. Registro delle decisioni: DEC-71 e DEC-72 in DCP-200.

I numeri e le cause sono quelli dell'audit di Sixteen (`front-office-production-readiness-audit.story.md`, «Audit F»). Qui sono **riverificati oggi** solo i fatti
indicati come tali; il resto è come misurato l'11 novembre e va rimisurato prima di correggere (regola dell'audit: si riproduce la causa prima del fix).

## Cosa resta e perché conta

| ID | Cosa | Stato della prova |
|---|---|---|
| UX-05 | «Demo» nella fascia alta del front office (la barra «regione»): il blocco `region` ha `name: "Demo"` in `config/local/fixcity/database/content/sections/header.json` (righe 27 e 168, it ed en) | **riverificato oggi** nel file. Visibile anche da loggato |
| UX-14…25 (parte) | `public_html/favicon.ico` da **0 byte** | **riverificato oggi**: 0 byte, data 2026-09-24 |
| UX-14…25 (parte) | file `.old` nel docroot `public_html/`: `info.php.old`, `hot.stale-20260927.old`, `hot.stale2-20260927.old`, `serve-root-test.txt.old`, `test.txt.old` | **riverificato oggi** l'elenco; il rapporto dice che `info.php.old` contiene `phpinfo()`: non l'ho aperto oggi |
| UX-14…25 (parte) | `heading-order` del footer; landmark senza nome (`it-header-slim-right-zone`); `aria-label="breadcrumb"` in inglese; `role="group"` sul form del wizard; «Vai ai contenuti» che punta al breadcrumb; copy «pratiche»/«segnalazioni»; refuso `title-snall-semi-bold` | dal rapporto grezzo, non rimisurati. Il refuso è la classe del riferimento Design Comuni: **non rinominare** senza controllare il CSS |
| UX-06 | `forced-colors`: logo bianco su Canvas, `.btn` senza bordo; esiste già una regola in `20-dc-components.css` (DCP-291 FO-5-20, «da rimisurare») | da provare con Windows High Contrast **reale**, non con l'emulazione |
| UX-08 | immagini senza `width`/`height`/`srcset`, PNG ingrandite, `map-placeholder.svg` da 612 KB nel blog, niente `loading="lazy"` in blog ed eventi | dal rapporto, non rimisurato |
| UX-11 | target sotto 24 px (`a.category` 21, «Leggi tutto» 21), testo sotto 14 px, pulsante cerca 36×36 | dal rapporto; tocca il layout: si fa con screenshot |

## Decisioni aperte (DEC-71 e DEC-72)

**DEC-71, etichetta «Demo».** `ente.php` ha già il campo `region_name` («Barra alta del sito: nome e indirizzo del portale della regione. Senza nome la barra mostra solo lingua
e accesso»), ma `header.json` porta `name: "Demo"` come valore fisso: la barra mostra quindi un nome inventato invece di nascondersi.
Opzioni: (a) togliere il blocco `region` dal JSON finché l'ente non fornisce nome e indirizzo, così la barra mostra solo lingua e accesso come prevede `ente.php`; (b) leggere
`region_name` e `region_url` da `ente.php` invece del valore fisso; (c) tenerlo (solo per ambienti di prova). Proposta: (a) subito, (b) quando l'ente compila i dati.
Non l'ho cambiato: è contenuto del tenant e DCP-289 dice «non inventare valori».

**DEC-72, favicon e dati dell'ente.** `favicon.ico` da 0 byte, e `config/local/fixcity/ente.php` ha **tutte** le chiavi vuote (riverificato: nome, regione, indirizzo,
recapiti, DPO, social). Non è un difetto del tema ma un dato che l'ente deve fornire (DCP-208). Opzioni: (a) l'ente fornisce favicon e dati prima della messa online e il go-live gate
lo verifica; (b) favicon provvisoria del progetto. Proposta: (a). Resta che il nome del Comune manca nell'avviso del passo 1 del wizard (vedi DCP-457).

## Cosa NON faccio

- Non modifico `header.json` né `ente.php` (dati dell'ente) e non cancello i `.old` (i file nel docroot si ritirano con approvazione).
- Non rinomino la classe `title-snall-semi-bold`.

## Criteri di accettazione (quando si lavora)

- [ ] DEC-71 e DEC-72 decise nel registro DCP-200; la barra alta non mostra «Demo» in produzione.
- [ ] `favicon.ico` non vuoto, controllato dal go-live gate.
- [ ] Nessun `.old` in `public_html/` (o decisione scritta di tenerli).
- [ ] Per ogni voce UX-06/08/11/14…25 scelta: misura prima e dopo con la stessa tecnica, e una guardia dove il difetto può tornare.

## Verifica

Nessuna correzione fatta in questa story. I fatti marcati «riverificato oggi» sono stati letti sul disco il 2026-11-12.
