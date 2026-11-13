---
title: "Story — Zero theme: verifica dedup docs peer + frontmatter audit"
type: story
module: Themes/Zero
epic: quality
story_id: "zero-docs-dedup-frontmatter"
status: ready
track: quality/docs
qmd: "Themes Zero docs dedup frontmatter YAML audit peer zero-theme-rebase-fix convenzione markdown"
related:
  - ../../00-index.md
id: zero-docs-bmad-stories-theme-docs-dedup-frontmatter-story
slug: theme-docs-dedup-frontmatter-story
description: "Peer zero-theme-rebase-fix ha deduplicato docs (2026-09-22). Da verificare"
document_type: story
category: bmad-story
tags: []
created: "2026-09-24"
updated: "2026-10-08"
issues: []
discussions: []
---

# zero-docs-dedup-frontmatter

## Perche'

Peer `zero-theme-rebase-fix` ha deduplicato docs (2026-09-22). Da verificare:
esito completo? E audit frontmatter YAML su tutti i `.md` del tema
(convenzione repo: ogni md ha frontmatter, nomi senza date).

## Task

- [ ] `git -C laravel/Themes/Zero log --oneline -10` + diff dedup peer
- [ ] Scan md senza frontmatter: file con prima riga non `---`
- [ ] Scan filename con date/timestamp (viola convenzione)
- [ ] Indice `00-index.md`/`00-INDEX.md` duplicati? (entrambi esistono — case dup)
- [ ] `qmd update` post-fix

## AC

- [ ] Zero md senza frontmatter in Themes/Zero/docs
- [ ] Dup `00-index`/`00-INDEX` risolto (case-insensitive filesystem = bug latente)

## Nota di verifica 2026-10-08

Eseguito in [2026-10-08-zero-docs-health](../../stories/2026-10-08-zero-docs-health.story.md). Esito misurato: 0 `.md` senza front matter e 0 incompleti su 210; l'unico nome con data fuori dalle story (`git-conflict-resolution-2026-07-31.md`) e' stato rinominato in `git-conflict-resolution-audit.md`. Non esiste piu' una coppia `00-index`/`00-INDEX` nella stessa cartella; il gemello per maiuscola rimasto e' `bmad/README.md` e `bmad/readme.md`, da decidere. Le caselle sopra non sono state spuntate: la prova e' nella story collegata. `qmd update` resta al coordinatore.
