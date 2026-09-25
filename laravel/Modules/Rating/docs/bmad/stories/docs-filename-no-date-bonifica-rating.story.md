---
title: "Rating — bonifica .md con data/timestamp nel nome file"
type: story
module: Rating
status: backlog
track: docs-hygiene
qmd: "docs filename no date timestamp bonifica rating"
related:
  - ../../../../../docs/sprint-status.yaml
  - ../../../Xot/docs/bmad/stories/docs-filename-no-date-bonifica-xot.story.md
---

# Rating — .md senza data/timestamp nel nome (batch trovato 2026-09-22)

## Perche'

Direttiva utente questa sessione: nessun filename `.md` con data o timestamp.
Stesso batch di lavoro di [[docs-filename-no-date-bonifica-xot]] (Xot),
perimetro Rating.

## Scope

- `docs/redundancy-audit-2026-05-21.md`
- `docs/copilot-redundancy-audit-2026-05-25.md`
- `docs/stories/phpstan-fleet-fix-2026-09-16.story.md`
- `docs/stories/phpstan-fix-1788762467.story.md`
- `docs/stories/phpstan-fix-1788768735.story.md`
- `docs/bmad/stories/cleanup-rating-2026-09-22.story.md`

## Acceptance

- [ ] Ogni file rinominato senza data/timestamp o eccezione giustificata
- [ ] Collisioni di nome con file gia' tracciato: riconciliazione contenuto,
      non rename meccanico
- [ ] Nessun link rotto residuo dopo i rename
- [ ] `qmd update`
