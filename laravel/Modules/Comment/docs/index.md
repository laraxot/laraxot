---
title: "Comment: indice della documentazione"
type: index
status: active
module: Comment
created: 2026-10-16
updated: 2026-10-26
tags: [docs, comment, indice]
qmd: "comment indice documentazione commenti front office bmad stories fo-comments"
issues: []
discussions: []
---

# Comment: indice

Questo e' l'indice canonico. `00-index.md` rimandava qui e questa pagina rimandava a `00-INDEX.md`, che non esiste: il giro a vuoto e' chiuso il 2026-10-26.
La descrizione del modulo (identita', cosa contiene, limiti) e' in [README.md](./README.md).

## Stato (2026-10-26)

Il front office dei commenti (blog, eventi, segnalazioni) permette di pubblicare, rispondere al singolo commento, modificare ed eliminare, con il `MarkdownEditor` di Filament.
Prove, difetti trovati e decisioni aperte: [CMT-022](./stories/cmt-022.fo-comments-write-reply-markdown-editor.story.md). Decisioni che aspettano l'utente: D-CMT-1..6 in fondo alla story.

## Dove si trova cosa

| Cosa | Dove |
|---|---|
| Dossier BMAD dei commenti del front office (brief, PRD, architettura con ADR-001..008, UX, epic, registro decisioni, stato sprint) | [bmad/fo-comments/README.md](./bmad/fo-comments/README.md) |
| Story del modulo, `cmt-NNN.slug.story.md` (le `phpstan-*` sono storia dei lavori di qualita') | [stories/](./stories/) |
| Regole stabili (config, policy, mount del widget, sottocartelle di Actions e widget) | [concepts/](./concepts/) |
| Wiki del modulo (architettura, comandi, decisioni, entita') | [wiki/](./wiki/00-index.md) |
| Notifiche e commenti dei ticket | [ticket-comments.md](./ticket-comments.md) |
| Regola sul CSS di terzi nelle pagine Design Comuni (esenzione nel guard PostCSS) | [Sixteen: vendor-css-on-data-dc-pages-needs-guard-exemption](../../../Themes/Sixteen/docs/wiki/concepts/vendor-css-on-data-dc-pages-needs-guard-exemption.md) |

## Come si prova

```bash
cd laravel
bash ../bashscripts/tools/pest-scratch-db.sh Modules/Comment/tests          # mai sul DB di sviluppo
./vendor/bin/phpstan analyse Modules/Comment                                # configurazione unica laravel/phpstan.neon
# nel browser, su un'istanza con DB di prova (scrive commenti):
ORIGIN=http://127.0.0.1:8091 FO_PATH=/it/blog/<slug> FO_COOKIES=<file privato> FO_OUT=<cartella> \
  node ../bashscripts/tools/visual/fo-comment-flow-probe.mjs
```

## Cartelle superate

`docs/bmad/architecture-comment-enhancements.md`, `docs/bmad/brainstorming/` e `docs/bmad/planning/` sono marcate `superseded` e rimandano al dossier `bmad/fo-comments/`; restano come cronologia.
