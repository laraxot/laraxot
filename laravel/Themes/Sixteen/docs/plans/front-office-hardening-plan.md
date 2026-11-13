---
title: "Piano: guardie e strumenti perche' i difetti dell'audit del front office non si ripetano"
type: plan
status: proposed
module: Sixteen
created: 2026-11-12
updated: 2026-11-12
tags: [bmad, ecc, plan, sixteen, front-office, hardening, guardie, tooling, go-live]
qmd: "piano hardening front office guardie sprite griglia sweep strumento gate onde owner cancelli decisioni DEC"
related:
  - ../stories/front-office-production-readiness-audit.story.md
  - ../stories/icon-sprite-deployability.story.md
  - ../stories/home-news-grid-specificity.story.md
  - ../how-to/front-office-anonymous-sweep.md
  - ../bmad/front-office-production-readiness-plan.md
  - front-office-ux-performance-backlog-plan.md
  - ../../../../Modules/Cms/docs/stories/head-sixteen-parity-closeout.story.md
  - ../../../../Modules/Fixcity/docs/stories/dcp-455-strumenti-del-gate-sopravvivono-alla-sync.story.md
  - ../../../../Modules/Fixcity/docs/stories/dcp-200-registro-unico-delle-decisioni-aperte.story.md
issues: ["https://github.com/laraxot/theme_sixteen_fila5/issues/113", "https://github.com/laraxot/theme_sixteen_fila5/issues/111", "https://github.com/laraxot/theme_sixteen_fila5/issues/112"]
discussions: ["https://github.com/laraxot/theme_sixteen_fila5/discussions/95"]
---

# Piano: guardie e strumenti dopo l'audit del front office

**Issue:** [#113](https://github.com/laraxot/theme_sixteen_fila5/issues/113) (il piano), [#111](https://github.com/laraxot/theme_sixteen_fila5/issues/111) (sprite), [#112](https://github.com/laraxot/theme_sixteen_fila5/issues/112) (griglia «Novita'»).

**Discussione (decisioni e alternative scartate):** [#95](https://github.com/laraxot/theme_sixteen_fila5/discussions/95), complementare a [#93](https://github.com/laraxot/theme_sixteen_fila5/discussions/93) (head SEO) e [#94](https://github.com/laraxot/theme_sixteen_fila5/discussions/94) (audit, area loggata).

## Fase BMAD

**Plan** (nessun codice). Raccoglie cio' che resta dopo l'audit anonimo del 2026-11-11 e dice **come impedire che le stesse classi di difetto tornino**.
Il piano generale di readiness del tema, scritto dall'altra sessione ([front-office-production-readiness-plan.md](../bmad/front-office-production-readiness-plan.md)),
definisce la matrice rotte x lingua x stato di accesso e i cancelli di accettazione; questo piano non la duplica: aggiunge le **guardie** contro i difetti muti.
Il piano UX/prestazioni/consenso della stessa sessione ([front-office-ux-performance-backlog-plan.md](front-office-ux-performance-backlog-plan.md)) raccoglie le story
`DCP-447`..`DCP-454` (tema ricostruibile, accessibilita', salto di layout del wizard, peso degli asset, viste cancellate, residui di UX, codice morto, terze parti prima del consenso):
questo piano non le duplica. Il passo 1 del wizard e' in [DCP-456](../../../../Modules/Fixcity/docs/stories/ticket-create-privacy-avviso-breve.story.md) e
[DCP-457](../../../../Modules/Fixcity/docs/stories/dcp-457-guardia-e-misure-del-primo-livello-informativa-passo-1.story.md) (modulo Fixcity).
*Nota di correzione (2026-11-12):* in una versione precedente di questo piano le story `DCP-449`..`DCP-454` risultavano assenti dal disco; sono state create dopo e i link sono stati ripristinati.

## Perche' (il senso)

Quasi tutti i difetti trovati erano **muti**: nessun errore, nessun test rosso, nessun log. Lo sprite mancava e nessuno se ne accorgeva; la terza card andava a capo
con il CSS «giusto» sul sorgente; canonical e hreflang sono spariti dopo una migrazione che aveva «parita'» come obiettivo; cinque strumenti del gate erano stati
cancellati da una sync. Un difetto muto si previene solo con una **guardia che chiede al sistema reale** (git, il browser, il server), non al sorgente.

| Classe di difetto | Esempio | Guardia che oggi manca |
|---|---|---|
| Asset non versionato o ignorato | F-01 sprite | Esiste per lo sprite; non per gli altri asset serviti da `public/` |
| Layout che il sorgente non dice | F-06 griglia | Solo per la home; non per gli altri blocchi con `<style>` su `.row` |
| Regressione di parita' dopo migrazione | D-7 head | Nessuna: il diff dell'head e' manuale |
| Strumento di rilascio cancellato dalla sync | F-03, F-09 | Nessuna (vedi [DCP-455](../../../../Modules/Fixcity/docs/stories/dcp-455-strumenti-del-gate-sopravvivono-alla-sync.story.md)) |
| Ambiente di destinazione sbagliato | `ENV-01..06` | Il gate li segnala, ma solo se lanciato sul `.env` di destinazione |

## Onde

| Onda | Cosa | Owner | Perimetro | Cancello di uscita | Dipende da | Stima |
|---|---|---|---|---|---|---|
| **0 (fatta)** | Sprite + `deploy.sh`; griglia «Novita'»; strumenti del gate ripristinati; diff dell'head e gate misurati | Sixteen, Fixcity | vedi le due story | 3/3 Pest sprite; spec 3/3; gate rilanciato 2 volte | — | fatta |
| **1** | **Smoke post-deploy**: dopo `php artisan up`, 6 URL (home 4 lingue, `/it/tickets`, `/it/auth/login`) e gli asset del tema (sprite, `manifest.json`, i due file che cita) devono dare 200, altrimenti `deploy.sh` esce con errore e lo dice | Sixteen (`deploy.sh`) | `deploy.sh` | uno smoke rosso su una copia con lo sprite tolto | — | S |
| **1** | **Guardia degli id icona**: ogni `<use href="...sprites.svg#id">` citato nelle viste del tema esiste nello sprite (test da file, senza app) | Sixteen `tests/Unit` | un test | rosso su un id inventato | — | S |
| **1 (censimento fatto 2026-11-12)** | **Censimento dei `<style>` che ridisegnano un `.row`** nei blocchi (`gap`, `display:grid`, `padding:0`, `width:auto` su `.row` o sui suoi `col-*`) e test che lo vieta | Sixteen | `resources/views/components/blocks/**` | censimento a zero o ogni caso con specificita' > 0,3,1 e motivo | — | S |
| | *Esito del censimento:* 2 blocchi candidati (eventi, blog), entrambi con selettore `main[data-page=…] .row.g-4` (specificita' sopra lo scope) e misurati 3/3/1 card a 1440/800/375 senza overflow; 7 falsi positivi del grep. Resta il **test che lo vieta** (richiede una allowlist motivata, perche' i due casi sono legittimi) | | | | | |
| **2** | Correggere i blocchi trovati dal censimento, uno per volta, con la spec Playwright del blocco (3/2/1 colonne a 1440/800/375) | Sixteen | i blocchi elencati | spec rossa prima, verde dopo, per blocco | onda 1 (censimento) | M |
| **2** | **H-5 completo**: diff dell'head sul DOM e da utente loggato, screenshot 1440/375 chiaro e scuro | Sixteen `tests/` + Cms | vedi [parity closeout](../../../../Modules/Cms/docs/stories/head-sixteen-parity-closeout.story.md), C-3 | 0 differenze oltre a quelle deliberate | **DEC-62** (D-7) | M |
| **3** | **Promuovere lo sweep a strumento versionato**: raccolta (Playwright), analisi e diff dell'head in `bashscripts/tools/visual/` con un test sulle regole di analisi e documentazione in `bashscripts/docs/` | Fixcity (rilascio) | `bashscripts/tools/visual/` | strumento che gira da un checkout pulito e riproduce i numeri del 2026-11-11 (128 URL, 36 senza canonical prima di D-7) | onda 1 | M |
| **3** | Agganciare lo sweep al go-live gate come stadio opzionale (`--sweep`), con soglie: 0 asset in 4xx, 0 chiavi grezze, 0 overflow, canonical presente sulle pagine indicizzabili | Fixcity | `go-live-gate.py` | un giro del gate con lo stadio acceso | onda 3 (strumento) | S |
| **4** | Applicare le decisioni: D-7 (Cms), F-07 (Cms/Blog), F-08 (Blog), `pest-scratch-db.sh`, codice morto, terze parti prima del consenso | secondo la decisione | vedi registro | gate rilanciato, nessun FAIL riconducibile a queste voci | **DEC-62..72** | secondo la decisione |

## Cosa non e' in questo piano

- I FAIL di ambiente del gate (`ENV-*`, `NET-01`) sono del deploy (DCP-154/155); `REPO-02` e' di DCP-201 (DEC-51); i testi legali dell'ente di DCP-130/162/178/282.
- Accessibilita' residua, peso degli asset, terze parti, codice morto del tema: story `DCP-449`..`DCP-454` nel [piano UX/prestazioni](front-office-ux-performance-backlog-plan.md) e cancelli del [piano di readiness](../bmad/front-office-production-readiness-plan.md). Passo 1 del wizard: [DCP-456](../../../../Modules/Fixcity/docs/stories/ticket-create-privacy-avviso-breve.story.md) / [DCP-457](../../../../Modules/Fixcity/docs/stories/dcp-457-guardia-e-misure-del-primo-livello-informativa-passo-1.story.md).

## Chi decide cosa

Le decisioni che sbloccano l'onda 4 sono nel registro unico [DCP-200](../../../../Modules/Fixcity/docs/stories/dcp-200-registro-unico-delle-decisioni-aperte.story.md), sezione F2, righe **DEC-62..DEC-72**.

## Rischi

| Rischio | Mitigazione |
|---|---|
| Lo smoke post-deploy da' un falso rosso se il dev server e' lento | timeout largo e 2 tentativi; la causa si legge nell'output, non si ignora |
| La guardia degli id icona rompe su icone legittime caricate altrove | partire dall'elenco reale delle viste, escludere solo con un motivo scritto |
| Il censimento dei `<style>` segnala falsi positivi | ogni caso va guardato con `getComputedStyle`, non solo col grep |
| La sync cancella lo strumento promosso (onda 3) | guardia di esistenza in [DCP-455](../../../../Modules/Fixcity/docs/stories/dcp-455-strumenti-del-gate-sopravvivono-alla-sync.story.md) |

## Accettazione del piano

- [ ] Onda 1 eseguita e verificata (tre guardie, ciascuna rossa su una copia rotta, poi verde).
- [ ] Decisioni DEC-62..72 con esito datato nel registro.
- [ ] Onda 3: strumento riproduce i numeri dell'audit da un checkout pulito.
