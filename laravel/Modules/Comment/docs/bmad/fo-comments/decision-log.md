---
title: "Decision log — FO comments"
type: decision-log
status: active
module: Comment
created: 2026-10-16
updated: 2026-10-26
tags: [bmad, decisions, comment]
qmd: "decision log FO comments Filament always mount readOnly"
related:
  - ./architecture.md
  - ./epics.md
issues: []
discussions: []
---

# Decision log

## 2026-10-16 — Filament widget only

Accepted ADR-001: FO comments solo via `CommentsWidget`. Reject Livewire
standalone `Http/Livewire`.

## 2026-10-16 — Always mount section

Accepted ADR-004: mai nascondere la sezione se `$comments` è vuota. Altrimenti
auth non può avviare il thread (gap verificato su Sixteen blog/events).

## 2026-10-16 — Props `readOnly`

Accepted ADR-003: `writable` non è contratto pubblico del widget.

## 2026-10-16 — CanComment su Fixcity User

Accepted ADR-002: non su `BaseUser` (boundary User).

## 2026-10-16 — Adattamento AFC/Asso

Commenti = dialogo civico moderato, non vanity engagement né funnel B2B.
Vedi [research.md](./research.md).

## 2026-10-16 — Docs igiene Comment (naming)

- Story `CMT-001`…`015` rinominate `cmt-*` (kebab minuscolo).
- Root UPPERCASE (`PRD.md`, …) → stub redirect al twin minuscolo.
- Indice unico: `00-INDEX.md` (alias redirect).
- Hub FO: [README.md](./README.md).

## 2026-10-16 — Dettaglio blog/events richiede `data-dc`

**Problema:** montare `CommentsWidget` su dettaglio Article con layout `app`
(senza CMS `blog.view`) faceva sparire il body nel viewport (legacy CSS +
Filament fuori scope DC).

**Decisione:** stesso pattern ticket-detail — Article `blog|events` usa
`layouts.app` + `isDesignComuni`/`data-dc` + header `news`/`live`. Il tema
Sixteen è già Design Comuni: `design-comuni` non è un layout runtime.
Widget: view esplicita + query `approved()` FO.

**Evidence:** [blog-detail-data-dc-comments.md](../../../../../Themes/Sixteen/docs/bmad/investigations/blog-detail-data-dc-comments.md).

## 2026-10-26 — Rispondere al singolo commento e editor Markdown (CMT-022)

- ADR-006, ADR-007, ADR-008 in [architecture.md](./architecture.md). Story: [cmt-022](../../stories/cmt-022.fo-comments-write-reply-markdown-editor.story.md).
- **Causa della regressione:** il ramo delle risposte non era mai stato eseguito da un test; `noReplies` portato a `false` ha dato 500 su blog e eventi. Regola: ogni widget Livewire si prova
  montandolo con tutte le combinazioni di parametri che il front office usa davvero.
- **Aperte, da dare all'utente** (testo completo nella story): D-CMT-1 profondita' 3; D-CMT-2 chi commenta gli articoli; D-CMT-3 moderazione; D-CMT-4 seme con `commentator_type` classe invece di alias `user`;
  D-CMT-5 gli ospiti vedono il thread dei ticket (privacy); D-CMT-6 notifica all'autore del commento a cui si risponde (email a cittadini, consenso).
