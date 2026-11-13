---
title: "Architecture — commenti FO Filament-only"
type: architecture
status: active
module: Comment
created: 2026-10-16
updated: 2026-10-26
tags: [bmad, architecture, filament, comment]
qmd: "architecture CommentsWidget HasComments CanComment no Livewire FO"
related:
  - ./prd.md
  - ./filament-blueprint.md
  - ./decision-log.md
  - ../../concepts/no-livewire-filament-widgets-only.md
issues: []
discussions: []
---

# Architecture — FO comments

## Drivers

- FR-01…09: un solo entry point UI FO.
- NFR: PHPStan 0, a11y, privacy, no second engine.
- Policy repo: Filament widget only (no `Http/Livewire`).

## Component map

```text
Article|Ticket (HasComments / Commentable)
        │ morph
        ▼
   Comment model + CommentPolicy
        │
        ▼
 CommentsWidget (XotBaseSchemaWidget)
   ├─ CommentWidget (un commento: risposta annidata, modifica, elimina, reazioni; Rispondi apre l'editor)
   │     └─ CommentTextForm (MarkdownEditor Filament, schema condiviso da nuovo commento, risposta e modifica)
   └─ MentionSearchWidget (opt-in config)
        │
        ▼
 FO Blade: Sixteen blog/events detail · Fixcity ticket-comments
 BO: CommentResource (Form · Infolist · Table)
```

## ADR-001 — Filament widget SSOT

- **Decision:** Solo `Modules\Comment\Filament\Widgets\Commentable\CommentsWidget`.
- **Reject:** `Http/Livewire/CommentsComponent` e pacchetti Spatie Livewire.
- **Consequences:** FO monta con `@livewire(CommentsWidget::class, …)`; Livewire
  sotto è runtime Filament, non dominio custom.

## ADR-002 — CanComment sul User di dominio

- **Decision:** `Modules\Fixcity\Models\User implements CanComment` +
  `InteractsWithComments`. `BaseUser` resta libero da Comment.
- **Why:** boundary User base senza dipendenza modulo opzionale.

## ADR-003 — Props canoniche

| Prop | Uso |
|---|---|
| `commentableType` + `commentableKey` | Persistenza Livewire nested (preferito FO) |
| `model` | Shortcut se già in memoria |
| `readOnly` | `true` guest / `false` auth |

Vietato affidarsi a `writable` come prop pubblica (non guida `uiConfig`).

## ADR-004 — Sempre montare la sezione

- **Decision:** Non wrappare il widget in `@if ($comments->isNotEmpty())`.
- **Why:** altrimenti impossibile avviare il thread (gap P0).

## ADR-005 — Dettaglio Article richiede `data-dc`

- **Decision:** FO blog/events detail usa `layouts.app` + `data-dc`
  (pattern ticket-detail), anche senza CMS `blog.view`. `design-comuni` è il
  valore storico del contenuto, non una shell del tema Sixteen.
- **Why:** senza scope DC, Filament styles del widget + CSS legacy fanno
  sparire il body sotto l’immagine.
- **Also:** query FO `approved()`; view widget esplicita.

## ADR-006 — Rispondere al singolo commento (CMT-022)

- **Decision:** `CommentWidget` mostra «Rispondi» per commento; il form si monta solo dopo il clic (`isReplying`), non uno per commento.
  La risposta e' un commento con `parent_id`; profondita' massima `comments.ui.max_reply_depth` (3), controllata dal browser **e** dal server
  (`CheckReplyAllowedAction`). Ogni azione autorizza con `CommentPolicy`, passa da `EnsureCommentRateLimitAction` (5 invii/60 s) e usa proprieta' `#[Locked]`.
- **Why:** una chiamata Livewire diretta non deve poter aggirare cio' che il browser non mostra; le proprieta' pubbliche di Livewire sono scrivibili dal client.
- **Also:** i parametri di mount del widget figlio sono scalari. Livewire 4 assegna ogni parametro che coincide con una proprieta' pubblica: un array dato a una proprieta'
  tipizzata `CommentWidgetUiData` e' un `TypeError` (500). La vista di un componente ha UN solo elemento radice sempre presente.

## ADR-007 — Editor Markdown alla Filament e CSS sulle pagine Design Comuni (CMT-022)

- **Decision:** il campo e' il `MarkdownEditor` di Filament (`CommentTextForm`), non una textarea. Il sorgente resta in `original_text`, l'HTML sanificato in `text` (pipeline esistente).
  Su una pagina `data-dc` il CSS `fi-*` e' spento da `Themes/Sixteen/postcss/isolate-legacy-important.cjs`: l'editor funziona perche' i nomi del vendor (`.CodeMirror*`,
  `.EasyMDEContainer`, `.editor-*`, `.cm-*`, `.fi-fo-markdown-editor`) sono esenti (`isEditorVendorSelector`, con test); pulsanti, link e campo li disegna il CSS `cmt-*` del modulo.
- **Why:** senza l'esenzione l'editor aveva pulsanti larghi 0 px; senza CSS proprio i pulsanti Filament restavano testo semplice.
- **Rule:** un componente di vendor su una pagina `data-dc` si prova nel browser a 375, 768 e 1280 px, non solo con i test. Regola completa e trappole: [vendor-css-on-data-dc-pages-needs-guard-exemption.md](../../../../../Themes/Sixteen/docs/wiki/concepts/vendor-css-on-data-dc-pages-needs-guard-exemption.md).

## ADR-008 — Dall'ospite al commento (CMT-022)

- **Decision:** «Accedi per commentare» e' `<x-comment::login-link />`: porta `?redirect=<uri corrente>` e `Modules\User\Actions\Auth\StoreIntendedUrlAction` lo salva in `url.intended`
  solo se e' un percorso interno (no schema, host, `//`, `\`, a capo).
- **Why:** senza `url.intended`, `LoginWidget::redirectIntended()` portava alla dashboard e l'ospite perdeva il punto in cui voleva scrivere. Il parametro e' dell'utente: va validato.

## FR coverage

| FR | Design |
|---|---|
| FR-01/02 | Sixteen detail + ADR-004 |
| FR-03 | Fixcity ticket-comments / infolist |
| FR-04 | `@guest` CTA + `readOnly` |
| FR-05 | `CommentPolicy::see` in view widget |
| FR-06 | `HasComments` su Article/Ticket |
| FR-07 | CommentResource triad |
| FR-08 | delete Http/Livewire |
| FR-09 | ADR-003 |
| FR-10 (rispondere, modificare, eliminare) | ADR-006 |
| FR-11 (editor Markdown) | ADR-007 |
| FR-12 (ospite, login, ritorno) | ADR-008 |

## Data

Morph `comments` (`commentable_type/id`, `commentator_type/id`, `parent_id`,
`approved_at`, `text`). Moderazione = presenza `approved_at`.
