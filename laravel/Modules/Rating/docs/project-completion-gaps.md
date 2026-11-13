---
title: "Lacune di completamento: Rating"
type: audit
status: open
created: 2026-10-28
updated: 2026-10-28
tags: [project-readiness, documentation, module]
qmd: "Rating module lacune completamento progetto backlog documentazione qualità gate test integrazione"
related:
  - "../../Xot/docs/project-completion-readiness.md"
---

# Lacune di completamento: Rating

## Esito

Questo censimento documentale non certifica il completamento funzionale del module.
Il completamento resta **non dimostrato** finché non sono chiusi e verificati i criteri
sotto elencati e le story aperte del modulo/tema in `docs/sprint-status.yaml`.

## Evidenza documentale rilevata

- Markdown sotto `docs/`: **567**.
- Story BMAD trovate (`*.story.md`): **149**; il conteggio non implica che siano
  concluse né che coprano tutti i requisiti.
- Frontmatter iniziale presente: **563/567**.
- README/purpose mancanti: Nessun README/purpose mancante nella verifica.
- Controllo frontmatter: 4 Markdown senza frontmatter rilevato con controllo apertura file (`---` iniziale); correggere e validare il frontmatter.

## Lavoro necessario per poter chiudere

1. Riconciliare i requisiti del module con le story BMAD: nessun requisito senza AC,
   owner, dipendenze e stato verificabile.
2. Chiudere le story non concluse nel relativo sprint status; non dedurre `done` dal
   solo fatto che il codice o una story esistano.
3. Verificare le boundary del modulo/tema con i consumatori, permessi, tenant, dati e
   contratti di integrazione applicabili; registrare le prove nella story owner.
4. Eseguire i gate applicabili (Pest, PHPStan per modulo e controlli di qualità) e
   riportare comando, esito e limiti ambientali. Nessuna verifica funzionale è stata
   eseguita da questo censimento documentale.
5. Aggiornare i documenti e gli indici QMD dopo ogni chiusura; i nomi `.md` restano
   kebab-case stabili, senza date o timestamp.

## Criterio di chiusura

Story e acceptance criteria pertinenti chiusi; regressioni assenti nei gate applicabili;
integrazioni reali documentate; documentazione e QMD aggiornati. Questo file va aggiornato
solo con evidenze nuove, non con supposizioni.

## Story BMAD non concluse nel corpus owner

Lettura read-only delle 149 story locali (`*.story.md`): 89 hanno uno stato diverso da done/completed/superseded/invalid o non hanno stato esplicito. I conteggi descrivono il frontmatter delle story, non sostituiscono `docs/sprint-status.yaml`.

| Stato | Numero |
|---|---:|
| `in-progress` | 11 |
| `in_progress` | 2 |
| `open` | 1 |
| `ready-for-dev` | 13 |
| `review` | 4 |
| `draft` | 12 |
| `backlog` | 13 |
| `blocking-lock` | 1 |
| `done` | 48 |
| `implemented-trait-only` | 1 |
| `pointer` | 1 |
| `superseded` | 12 |
| `superseded-pointer` | 3 |
| `unspecified` | 27 |

### Esempi prioritari da riconciliare

- `in-progress`: [`01-41-hasratin-gs-trait-correction.story.md`](stories/01-41-hasratin-gs-trait-correction.story.md)
- `in-progress`: [`01-41-hasratin​gs-trait-correction.story.md`](stories/01-41-hasratin​gs-trait-correction.story.md)
- `in-progress`: [`01-42-verify-frontmatters.story.md`](stories/01-42-verify-frontmatters.story.md)
- `in-progress`: [`01.30.rating-readme-agnostic-zshrc.story.md`](stories/01.30.rating-readme-agnostic-zshrc.story.md)
- `in-progress`: [`2.6.phpstan-silencer-removal-regression-fix.story.md`](stories/2.6.phpstan-silencer-removal-regression-fix.story.md)
- `in-progress`: [`5.87.rating-contract-usage.story.md`](stories/5.87.rating-contract-usage.story.md)
- `in-progress`: [`5.93.rating-contract-al-posto-di-baserating.story.md`](stories/5.93.rating-contract-al-posto-di-baserating.story.md)
- `in-progress`: [`8.24-phpstan-l10-fleet-fix-parallel-level-modules.story.md`](bmad/stories/8.24-phpstan-l10-fleet-fix-parallel-level-modules.story.md)

Prima di lavorare su questi elementi, verificare lo stato canonico nello sprint status, claim/lock e dipendenze. Una story senza `status` non va considerata automaticamente completata.
