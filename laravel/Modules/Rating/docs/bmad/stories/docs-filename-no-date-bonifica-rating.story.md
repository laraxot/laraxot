---
tags: [documentation]
created: 2026-09-26
updated: 2026-09-26
issues: []
discussions: []
title: "Rating: bonifica .md con data/timestamp nel nome file"
type: story
module: Rating
status: backlog
track: docs-hygiene
qmd: "docs filename no date timestamp bonifica rating"
related:
  - ../../../../../../docs/sprint-status.yaml
  - ../../../../Xot/docs/bmad/stories/docs-filename-no-date-bonifica-xot.story.md
---

# Rating: .md senza data/timestamp nel nome (batch trovato 2026-09-22)

## Perche'

Direttiva utente questa sessione: nessun filename `.md` con data o timestamp.
Stesso batch di lavoro di [[docs-filename-no-date-bonifica-xot]] (Xot),
perimetro Rating.

## Scope

- `docs/redundancy-audit-rating.md`
- `docs/copilot-redundancy-audit.md`
- `docs/stories/phpstan-fleet-fix.story.md`
- `docs/stories/phpstan-fix.story.md`
- `docs/stories/phpstan-fix-story.story.md`
- `docs/bmad/stories/cleanup-rating.story.md`

## Acceptance

- [ ] Ogni file rinominato senza data/timestamp o eccezione giustificata
- [ ] Collisioni di nome con file gia' tracciato: riconciliazione contenuto,
      non rename meccanico
- [ ] Nessun link rotto residuo dopo i rename
- [ ] `qmd update`
