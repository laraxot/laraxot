---
title: "BMAD Brainstorming — Sixteen"
type: bmad-brainstorming
status: synthesized
theme: Sixteen
track: bmad-method
created: 2026-10-16
updated: 2026-10-16
tags: [bmad, brainstorming, scamper, starbursting, second-brain, sixteen]
qmd: "bmad brainstorming sixteen perche insight rischi"
related:
  - ./bmad/ticket-detail-planning.md
  - ./bmad/content-detail-planning.md
  - ./bmad/brainstorming.md
  - ./bmad/README.md
  - ./bmad-planning.md
  - ./bmad/filament-blueprint.md
---

# Domanda guida

Come ottenere parity Design Comuni con Tailwind/DaisyUI/Lit senza reintrodurre Bootstrap o logica dominio?

## 5 Whys (sintesi)

1. L'utente sente frizione o opacita sul valore di **Sixteen**.
2. La frizione nasce da confini poco chiari tra owner e consumer.
3. I confini si offuscano quando UI, persistenza e policy vivono nello stesso file "comodo".
4. Senza contratto, ogni fix locale crea una seconda verita.
5. Serve planning BMAD + blueprint Filament + docs owner come unica memoria operativa.

## Starbursting

- **Chi:** guest, cittadino autenticato, redazione (solo consumo)
- **Cosa:** Presentazione FO FixCity allineata a Design Comuni: renderizza dati modulo, non possiede regole Ticket.
- **Quando:** a ogni incremento che tocca questo owner; decisioni in decision-log.
- **Dove:** `laravel/Themes/Sixteen/docs/` (+ `docs/bmad/`).
- **Perché:** parallelismo sicuro, DRY, auditabilita, nessun `bmad-output/`.
- **Come:** track `bmad-method` -> planning -> (architecture/UX se serve) -> stories ready-for-dev.

## SCAMPER

| Leva | Idea | Impatto | Fattibilita |
|---|---|---:|---:|
| Substitute | Bootstrap -> Tailwind/DaisyUI/Lit senza cambiare contratto dati | H | M |
| Combine | Header/footer/blocks CMS + pagine Folio in una composizione | H | H |
| Adapt | Pattern Design Comuni (conferma, passaggi, prossimi passi) come UI only | H | H |
| Modify | Ridurre clutter hero/first viewport restando brand-first | M | H |
| Eliminate | Logica dominio e Controllers dal tema | H | H |
| Reverse | Partire dai dati che non devono mai comparire in FO | H | H |

## Reverse brainstorming — come romperlo

- Copiare planning Ticket in questo owner (gia successo: stub da correggere).
- Mettere business rules nel tema o Controllers.
- Inventare store paralleli (es. timeline duplicata).
- Estendere Filament core invece di `XotBase*`.
- Scrivere docs in `bmad-output/`.

## Insight selezionati

1. Lo scopo di **Sixteen** e: Presentazione FO FixCity allineata a Design Comuni: renderizza dati modulo, non possiede regole Ticket.
2. Tutto cio che e in **Out** va linkato all'owner vero, non reimplementato.
3. Filament e chrome/admin o widget FO montato: non e il dominio.
4. Il second brain vive accanto al codice owner; QMD trova, non possiede.

## Prossimo step BMAD

Vedi [bmad-planning.md](./bmad-planning.md) — eseguire la Wave 1 e aggiornare il decision-log owner.

## Iniziativa Head e readiness front office

Il PO ha selezionato tutte le otto tecniche BMAD per il workstream Head Cms + audit guest/autenticato. La sintesi completa e' nel [report dedicato](./bmad/front-office-readiness-brainstorming.md); il piano operativo e' [front-office-production-readiness-plan.md](./bmad/front-office-production-readiness-plan.md). Le differenze deliberate e la decisione D-7 aperta sono nella [discussion #93](https://github.com/laraxot/theme_sixteen_fila5/discussions/93).

## Sessione 85: difetti muti dello sweep anonimo (2026-11-12)

Tecniche usate: 5 Perche', reverse brainstorming, matrice di scelta delle guardie. Evidenze: [sweep anonimo](./how-to/front-office-anonymous-sweep.md), [sprite](./stories/icon-sprite-deployability.story.md), [griglia Novita'](./stories/home-news-grid-specificity.story.md). Decisioni e alternative scartate: [discussion #95](https://github.com/laraxot/theme_sixteen_fila5/discussions/95).

### 5 Perche'

| Difetto | 1 | 2 | 3 | 4 | 5 (radice) |
|---|---|---|---|---|---|
| Sprite icone in 404 su ogni pagina | il file non e' nel repo | `.gitignore` lo escludeva due volte (riga esplicita + `dist/`) | git non rientra in una cartella esclusa | `deploy.sh` non costruisce il tema e controllava solo `manifest.json` | **nessuno verifica cio' che il server dovra' servire** |
| Home «Novita'» a 2 card per riga | la terza card va a capo | le regole inline del blocco non vincono | lo scope del tema pesa 0,3,1 (`:has()` prende la specificita' del suo argomento) | i blocchi sono scritti contro Bootstrap, non contro lo scope | **nessun controllo misura il layout** |
| Canonical/hreflang spariti su 36 URL | il nuovo head li emette solo sulle pagine indicizzabili | la «parita'» non li elencava come obbligo | il diff dell'head si faceva a mano | nessuna linea di base presa prima della migrazione | **la parita' era un'intenzione, non un contratto misurabile** |
| 5 strumenti del gate cancellati | la sync ha adottato le cancellazioni dell'altro lato | nessuna revisione sulle cancellazioni di `bashscripts/tools/` | gli strumenti non sono protetti da nulla | il gate semplicemente non parte, senza errore | **uno strumento mancante non fa fallire niente** |

Filo comune: ogni difetto era **muto** (nessun errore, nessun test rosso). La prevenzione e' una guardia che interroga il sistema reale, non il sorgente.

### Reverse brainstorming: come riprodurli di proposito

1. Aggiungere un asset sotto una cartella ignorata e non toccare `deploy.sh`.
2. Ridisegnare un `.row` con `gap` dentro un `<style>` di blocco.
3. Migrare l'head senza salvare prima lo sweep «prima».
4. Lasciare la sync libera di adottare cancellazioni.
5. Lanciare il go-live gate sul `.env` di sviluppo e leggere il verde come verde di produzione.

### Matrice delle guardie (scelta)

| Idea | Cosa intercetta | Costo | Falsi positivi | Esito |
|---|---|---|---|---|
| Smoke post-deploy (6 URL + asset del tema) | asset mancanti, 500 | basso | pochi (timeout) | **scelta, onda 1** |
| Guardia degli id icona (`<use href=...#id>` esiste nello sprite) | icone rotte | basso | pochi | **scelta, onda 1** |
| Censimento dei `<style>` che ridisegnano un `.row` | trappole di specificita' | basso | medi (serve `getComputedStyle`) | **scelta, onda 1** |
| Sweep come strumento versionato con test | tutto cio' che si vede | medio | medi | **scelta, onda 3** |
| Diff dell'head in CI | regressioni di parita' | medio | pochi | scelta, onda 2 (dopo DEC-62) |
| `!important` come regola generale | niente | basso | n/a | **scartata**: nasconde la causa e peggiora la specificita' |
| Screenshot diff su ogni pagina | layout | alto | **molti** (lazy-load, carico della macchina) | **scartata** |

Piano: [front-office-hardening-plan](./plans/front-office-hardening-plan.md). Decisioni aperte: registro DCP-200, sezione F2 (DEC-62..72).
