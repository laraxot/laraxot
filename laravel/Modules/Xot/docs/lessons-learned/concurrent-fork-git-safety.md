---
name: concurrent-fork-git-safety
description: "Mai git add -A o git reset largo su un repo dove altre sessioni/fork lavorano in parallelo: usare sempre path espliciti"
metadata:
  type: lesson-learned
  created: 2026-09-15
  github_issues: []
---

# Working tree condiviso fra fork concorrenti: solo path espliciti

## L'errore che si è ripetuto

Durante una sessione con molteplici agenti in background che lavorano sullo stesso repo
(es. `Modules/Xot`), un `git diff`/`git reset` grezzo ha rischiato di mescolare o
unstaged-are commit di altri agenti in corso. In un caso, un `git reset` (mixed) durante
un'investigazione ha rimosso dall'index file che un'altra fork aveva già in staging —
nessun dato perso (working tree intatto), ma interferenza reale con stato concorrente.

## Perché è sbagliato

`git add -A` o `git reset` senza path specifico operano su TUTTO il working tree, incluse
le modifiche di altri processi che non hai scritto tu. In una sessione con fork parallele
sullo stesso modulo, il working tree può avere centinaia di file modificati da agenti
diversi nello stesso momento: un comando "largo" ti rende involontariamente responsabile
di commit/scarti che non ti competono.

## Come si fa correttamente

1. Prima di ogni `git add`/`commit`, verifica lo stato:
   ```bash
   git status --short
   ```
   Se il numero di file modificati è molto più alto di quelli che hai effettivamente
   toccato tu, è un segnale di lavoro concorrente.

2. Usa **sempre** path espliciti:
   ```bash
   git add path/specifico/al/tuo/file.php path/altro/file.md
   git commit -m "..."
   ```
   Mai `git add -A`, `git add .`, `git add docs/` a cartelle larghe se non sei certo che
   contengano solo le tue modifiche.

3. Se devi ispezionare un diff per isolare le tue modifiche da quelle altrui, fallo per
   singolo file (`git diff -- path/file.php`), non con un reset dell'intero staging.

## Come riconoscerlo in futuro

Prima di un comando git "largo" (`add -A`, `reset` senza path, `checkout .`), chiediti:
"so con certezza che ogni file toccato da questo comando è mio?". Se no, restringi lo
scope con path espliciti.

## Riferimenti

- Story `docs-frontmatter-batch-enrichment-pattern.story.md` (`Modules/Xot/docs/stories/`)
- Story `5.95-refactoring-lesson-file-positioning-discovery` (verification checkpoint)
