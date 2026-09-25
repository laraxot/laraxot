---
title: "Rating Module — Documentation Index"
type: guide
module: Rating
updated: 2026-09-22
---

# Rating Module — Index

## Lavoro attivo (BMAD)

→ **[bmad/README.md](bmad/README.md)** — Select «altro» + note (`''` / placeholder `null` / `selectIsOther`).

| Pack | Link |
|------|------|
| Architecture | [bmad/architecture/rating-select-altro-note.md](bmad/architecture/rating-select-altro-note.md) |
| Spec codice | [bmad/architecture/rating-select-altro-implementation-spec.md](bmad/architecture/rating-select-altro-implementation-spec.md) |
| Story 5.99 | [bmad/stories/5.99-select-altro-note-obbligatoria.story.md](bmad/stories/5.99-select-altro-note-obbligatoria.story.md) |
| Story 5.141 D-8 | [bmad/stories/5.141-has-rating-values-filter-note.story.md](bmad/stories/5.141-has-rating-values-filter-note.story.md) |
| Design | [stories/rating-altro-option-conditional-textarea-design.story.md](stories/rating-altro-option-conditional-textarea-design.story.md) |

## Altri hub

- [README.md](README.md)
- [architecture.md](architecture.md)
- [criteri-a-scelta-multipla.md](criteri-a-scelta-multipla.md)
- [wiki/](wiki/)

## Debito docs

**2026-09-22 — risolto.** I 38 file sotto `docs/` con marker di merge non risolti
(`<<<<<<<`/`=======`/`>>>>>>>`, incluso `architecture.md`) sono stati fusi e ripuliti
(verifica: `grep -rl '^<<<<<<< \|^=======$\|^>>>>>>> ' docs/` → nessun risultato).
Duplicati case-sensitive noti (es. `INDEX.md`/`index.md`, `BEST_PRACTICES.md`/`best_practices.md`)
non sono stati deduplicati in questo passaggio: sono fuori scope, tracciati in
`root-files-hygiene.md`.

Ancora aperto (fuori scope per questo cleanup, non toccare qui):
- root `README.md` del modulo ha marker di merge non risolti (righe 3-19, 122-133) —
  file fuori `docs/`, serve una story dedicata.
- regressione pulizia root (`ARCHITECTURE.md`/`CHANGELOG.md`/`LICENSE.md` duplicati in
  root) — vedi `root-files-hygiene.md`.
- possibile corruzione repo Git (commit mancante, vedi `git fsck`) — da investigare
  prima di qualsiasi rewrite di history su questo modulo.
