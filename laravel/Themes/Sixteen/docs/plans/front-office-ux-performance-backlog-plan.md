---
id: plan-sixteen-front-office-ux-performance-backlog
title: "Piano BMAD: backlog UX, prestazioni e consenso del front office (onde, dipendenze, criteri di chiusura)"
type: plan
status: proposal
module: Sixteen
created: 2026-11-12
updated: 2026-11-12
tags: [bmad, piano, sixteen, backlog, ux, performance, consenso, gdpr, go-live]
qmd: "piano backlog ux prestazioni consenso front office onde decisioni guardie misure DCP-449 DCP-450 DCP-451 DCP-452 DCP-453 DCP-454"
related:
  - ./front-office-hardening-plan.md
  - ../bmad/front-office-production-readiness-plan.md
  - ../bmad/ux-performance-consent-brainstorming.md
  - ../stories/front-office-production-readiness-audit.story.md
  - ../../../../Modules/Fixcity/docs/stories/dcp-154-gate-di-messa-in-produzione.story.md
  - ../../../../Modules/Fixcity/docs/stories/dcp-200-registro-unico-delle-decisioni-aperte.story.md
issues: []
discussions: ["https://github.com/laraxot/theme_sixteen_fila5/discussions/94"]
---

# Piano: backlog UX, prestazioni e consenso

Stato: **proposta** (2026-11-12). È l'ordine che propongo per i residui dell'audit; **non è approvato** e non sostituisce i due piani di rilascio e di indurimento (`front-office-hardening-plan.md`, `../bmad/front-office-production-readiness-plan.md`).
Le correzioni già fatte sono in DCP-447 (tema ricostruibile) e DCP-448 (accessibilità).

## Principio di ordinamento

Prima le **decisioni** (nessun codice, sbloccano tutto), poi le **guardie** (rischio basso, valore alto), poi le **misure a macchina libera** (senza cui i numeri non valgono), infine le **modifiche** che cambiano il comportamento.

## Onde

| Onda | Contenuto | Story | Dipende da | Chi |
|---|---|---|---|---|
| 0: decisioni | DEC-69 terze parti; DEC-70 codice morto; DEC-71 «Demo»; DEC-72 favicon e dati ente; scelta su `place()` del banner | DCP-454, 453, 452, 449 | nulla | titolare+DPO, PO, ente |
| 1: guardie | guardia sui riferimenti a viste cancellate (estende `PartsIncludesResolveTest`/`WizardIncludesResolveTest`); axe rilanciato dopo le correzioni | DCP-451, 448 | nulla (si può fare subito) | sviluppo |
| 2: misure | CLS a 390/1440 px; byte per rotta su build di produzione; header di cache sul server di destinazione; `go-live-gate.py` sul `.env` di destinazione | DCP-449, 450, DCP-154 | macchina libera (F-05) | sviluppo + server |
| 3: modifiche | barra cookie/wizard (DEC su `place()`); bundle separati e purge; terze parti; ritiro `.old`; rimozione «Demo» | DCP-449, 450, 454, 453, 452 | onde 0 e 2 | sviluppo |
| 4: chiusura | prova inversa di ogni guardia, PHPStan zero, Pint sui file toccati, suite Sixteen, spec browser | tutte | onda 3 | sviluppo |

## Cosa può partire senza decisioni

- DCP-451 (guardia): non cambia il comportamento del sito. Prima si misura la lista dei riferimenti pendenti, poi la guardia.
- Il rilancio di axe e la misura di partenza (onda 2): sono sola lettura.

## Cosa NON parte senza una decisione scritta

- Tutto ciò che tocca il banner cookie, la CSP, l'informativa o i contenuti dell'ente (`header.json`, `ente.php`): DEC-69, DEC-71, DEC-72 e `place()`.
- Cancellare codice o test: DEC-70 e approvazione esplicita.

## Criteri di chiusura del piano

- [ ] Nel registro DCP-200 DEC-69…72 hanno un esito e un proprietario.
- [ ] Ogni story dell'onda 3 ha prima/dopo misurati con la stessa tecnica, **guardia e prova inversa**.
- [ ] Nessuna misura di tempo presa con carico macchina 30-40 (F-05): una misura «a carico» si etichetta come tale.
- [ ] `go-live-gate.py` lanciato sul `.env` di destinazione (DCP-154), non solo sul dev.
- [ ] Docs di tema e moduli aggiornate; nulla committato senza richiesta dell'utente.

## Rischi del piano

| Rischio | Mitigazione |
|---|---|
| Una modifica del banner peggiora il consenso | la decisione resta di Gdpr e del titolare; guardia di DCP-445 come rete |
| Il purge del CSS rompe pagine in silenzio | screenshot prima/dopo sulle pagine chiave prima del taglio |
| I mirror dell'altra macchina riportano file rotti | `php -l` e le guardie di DCP-447; il remoto a monte va corretto |
| Due sessioni scrivono lo stesso file | claim nella story, `stat`/`git diff` prima di scrivere |
