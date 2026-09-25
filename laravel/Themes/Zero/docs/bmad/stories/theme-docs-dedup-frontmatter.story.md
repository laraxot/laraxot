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
