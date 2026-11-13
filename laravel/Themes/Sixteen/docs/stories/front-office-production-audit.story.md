---
title: "[STORY] Audit del front office per la messa in produzione (anonimo e loggato)"
type: story
module: Sixteen
status: superseded
superseded_by: "./front-office-production-readiness-audit.story.md"
priority: high
created: 2026-11-11
updated: 2026-11-11
tags: [bmad, ecc, audit, front-office, produzione, go-live, ui-ux, i18n, a11y, asset]
related:
  - "./front-office-completeness-audit.story.md"
  - "../../../../Modules/Fixcity/docs/stories/dcp-154-gate-di-messa-in-produzione.story.md"
  - "../../../../Modules/Fixcity/docs/stories/dcp-377-controllo-qualita-giro-front-office-e-sync-git.story.md"
  - "../../../../Modules/Fixcity/docs/stories/fo-link-coherence-audit.story.md"
  - "../../../../Modules/Cms/docs/stories/head-sixteen-layout-migration.story.md"
---

# [STORY] Audit del front office per la messa in produzione

> **Superata (2026-11-11).** Nata cinque secondi dopo [front-office-production-readiness-audit.story.md](./front-office-production-readiness-audit.story.md)
> dalla stessa richiesta dell'utente. Il lavoro continua **lì**: i difetti F-01…F-05 di questa pagina, con causa e prova aggiornate
> (F-01 e F-02 chiusi, F-03 ripristinato), sono nella sezione «Difetti sessione 85». Resta qui il **metodo dello sweep** a 4 lingue e
> 2 viewport, come registro di cosa è stato misurato e con quali strumenti; la tabella sotto è lo stato iniziale, non quello attuale.

## Scopo e perché

Richiesta dell'utente (2026-11-11): controllare **integralmente** il front office, da utente anonimo e da utente loggato, con tutto
ciò che serve (installare e configurare gli strumenti), **per andare in produzione**; ciò che è da migliorare si migliora, ciò che è
da correggere si corregge, compresa la qualità del codice e la UI/UX; tutto con BMAD + ECC + second brain + Filament Blueprint, e le
cartelle `docs/` di moduli e temi vanno studiate, aggiornate e migliorate.

Il senso: oggi la «prontezza» del FO è un'opinione sparsa in molte story (DCP-154, DCP-186, DCP-377, DCP-400…). Serve **una misura
ripetibile sullo stato attuale dell'albero**, perché l'albero cambia sotto i piedi (sessioni parallele, sync dell'altra macchina
che committa cancellazioni e marker). Un audit vale solo se è rieseguibile con un comando.

Non sostituisce le story vicine, le esegue sull'albero di oggi: [completezza FO](./front-office-completeness-audit.story.md)
(2026-10-26, in review), [coerenza dei link](../../../../Modules/Fixcity/docs/stories/fo-link-coherence-audit.story.md) (2026-10-22) e
il [gate di messa in produzione DCP-154](../../../../Modules/Fixcity/docs/stories/dcp-154-gate-di-messa-in-produzione.story.md).

Filone gemello, **solo documentazione** per esplicita richiesta: [migrazione del `<head>` su `cms::head`](../../../../Modules/Cms/docs/stories/head-sixteen-layout-migration.story.md).
Questa story **non** implementa quella migrazione: ne usa il baseline come uno dei controlli.

## Metodo (misurare, non opinare)

- **Server:** dev `http://127.0.0.1:8081` (`artisan serve`, vedi nota sul proxy `:8080`). Per il loggato, la fixture isolata
  `bashscripts/tools/visual/scratch-auth-fixture.sh` (DB scratch, mai l'utente reale del DB di sviluppo).
- **Inventario:** sitemap (2108 URL = 4 lingue × ~527) ridotta a **128 URL** (campione: 2 dettagli ticket, 3 articoli, 3 eventi,
  2 servizi per lingua + tutte le pagine statiche) più le pagine private e le 404, in `it/en/de/es`.
- **Sweep** (Playwright, Chrome di sistema `--no-sandbox`, 1440 px e 375 px): per pagina stato HTTP, titolo, `<title>`/canonical/
  description duplicati, h1, errori console, richieste fallite e asset in 4xx, overflow orizzontale (con il primo elemento che sborda),
  chiavi di traduzione grezze nel testo visibile, parole italiane nelle pagine non italiane, immagini senza `alt`, link interni.
- **Cancelli già esistenti da rilanciare** (sono i componenti del «gate unico» di DCP-154, il cui script non c'è più, F-03):
  `PUBLICATION_GATE=1 pest --group=publication-gate` (privacy DCP-130, accessibilità DCP-132), `bashscripts/tools/security/production-checklist.sh <origine>`
  (DCP-139: configurazione e risposta HTTP), guardia cookie (DCP-153), `PublicPagesStructureTest` (struttura delle pagine in 4 lingue).
  Ognuno si rilancia solo dopo aver verificato che esista ancora: la sync ha già cancellato file citati come cancelli.
- **Poi:** axe (spec esistente `tests/browser/a11y-axe.spec.mjs`), controllo dei link interni raccolti, lettura degli screenshot
  `it` a caso e dei casi segnalati, e le suite Pest dei moduli toccati.
- **Limite dichiarato:** lo sweep non giudica il *design*; per questo si guardano gli screenshot e si confronta con la pagina di
  riferimento Design Comuni dove la story di parità lo prevede.

## Stato misurato finora (2026-11-11, prima dei risultati dello sweep completo)

| ID | Difetto | Dove | Gravità | Causa verificata | Stato |
|---|---|---|---|---|---|
| F-01 | `sprites.svg` risponde **404 su ogni pagina** (`/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg`): le icone di header, footer, ricerca e menu utente (`<use href=…sprites.svg#…>`) non si vedono | `public/design-comuni/assets/bootstrap-italia/dist/svg/` | **alta (visibile ovunque)** | Lo sprite sorgente è tracciato in `resources/static/…/sprites.svg`; la copia servita in `public/` è **gitignorata** (build artifact, `publicDir` di Vite) e nel checkout attuale il build è vecchio o incompleto. Lo scarica `prepare-static-assets.mjs` da `cdn.jsdelivr.net` (bootstrap-italia 2.8.8) **solo se manca**: dipende dalla rete al build | da correggere dopo lo sweep (un build durante lo sweep cambierebbe gli asset sotto le misure) |
| F-02 | `deploy.sh:159` verifica che esista `manifest.json` del tema, **non** lo sprite: un deploy con build senza rete serve il sito senza icone e senza alcun errore | `deploy.sh` | **alta (produzione)** | lettura di `deploy.sh` e di `prepare-static-assets.mjs` | da correggere: asserzione sullo sprite nel deploy (stesso modo del manifest) |
| F-03 | Lo script del gate unico `bashscripts/tools/release/go-live-gate.py` **non esiste più** (resta solo `__pycache__/go-live-gate.cpython-314.pyc` del 2026-10-26) ma DCP-154, 176, 200, 231, 280 e 282 lo citano come cancello | `bashscripts/tools/release/` | **alta (processo)** | `git log --all --no-renames` sul repo annidato `bashscripts`: nessun commit contiene il file in quel percorso | da decidere: ricostruirlo da DCP-154 o recuperarlo dall'altra macchina; nel frattempo questo sweep più i cancelli elencati sopra sono il cancello |
| F-04 | 8 database scratch residui di fixture precedenti (`scratch-auth-fixture.sh status`), senza stato che li possegga | MariaDB di sviluppo | bassa | `status`: «nessuna fixture attiva, DB scratch residui: 8» | da ripulire con conferma dell'utente (sono DB, anche se scratch) |
| F-05 | Carico macchina > 30: un giro di 128 URL × 2 viewport impiega decine di minuti; le misure di tempo non sono affidabili | host di sviluppo | info | `uptime` | da annotare nel report: niente giudizi sulle prestazioni da questo giro |

Le differenze del `<head>` fra Sixteen e `cms::head` (brand «Test», separatore del titolo, `tickets/track` indicizzabile, `?page` perso)
stanno nella story del `<head>` e **non** sono ripetute qui: qui si verifica solo che il `<head>` *attuale* sia coerente (un solo title,
un solo canonical, nessun duplicato).

## Piano

1. **Anonimo:** sweep completo desktop + mobile → tabella dei difetti per pagina (in corso).
2. **Loggato:** fixture isolata, stesso inventario + pagine private (`area-personale/*`, `tickets/create`, dettaglio ticket proprio,
   `tickets/track`), header con menu utente.
3. **Correzioni alla fonte** (lang, vista, CSS, deploy), ciascuna con verifica rieseguita sulla stessa pagina; test Pest nel modulo
   owner per ogni difetto di logica; mai correggere un 404 con un file nel tema (regola del progetto).
4. **Docs:** aggiornare `Themes/Sixteen/docs` e i moduli toccati (indice, wiki, story), segnalare i `.md` con nome fuori regola
   ma **non** rinominarli in massa in questa story.
5. **Second brain:** memoria del progetto + eventuale pagina Hindsight a fine giro.

## Cancelli di uscita (cosa deve essere vero per dire «pronto»)

- [ ] 0 pagine in 5xx sull'inventario, 404 solo dove attesi (pagina inesistente)
- [ ] 0 asset in 4xx/5xx su tutte le pagine (F-01 chiuso)
- [ ] 0 chiavi di traduzione grezze nel testo visibile, in 4 lingue
- [ ] 0 overflow orizzontale a 375 px; nessun `<title>`/canonical duplicato
- [ ] axe senza violazioni serie/critiche sulle pagine chiave
- [ ] loggato: percorsi privati raggiungibili, anonimo reindirizzato al login, nessun dato altrui visibile
- [ ] `deploy.sh` rifiuta un tema senza sprite (F-02)
- [ ] ogni correzione ha la prova rieseguita (output, non «fatto»)

## Coordinamento con gli altri agenti

- Claim: **2026-11-11 ~20:45**, terza sessione Claude (sweep + correzioni asset). Perimetro iniziale: nessun file di codice ancora
  toccato. Prima di scrivere in una cartella: `find <dir> -mmin -10`, `git diff`, e il lock di sync `/tmp/fixcity-git-sync.lock.d`.
- Il browser Playwright MCP è unico e condiviso: qui si usa uno script con il pacchetto `playwright` del repo, non il MCP.
- Nessun commit/push da questa sessione senza richiesta esplicita dell'utente.
