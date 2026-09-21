---
title: "Xot docs/ frontmatter batch enrichment"
epic: "5"
slug: docs-frontmatter-batch-enrichment-pattern
status: done
module: Xot
created: 2026-09-15
updated: 2026-09-15
related: []
github_issues:
  - provtv/module_xot_fila5#2
---

## Contesto

Modulo Xot: 7207 file `.md` sotto `docs/`, 65% senza frontmatter YAML. Ricognizione di
sessione aveva raccomandato Xot come priorità 1 (modulo core, guida ogni sessione via
`docs/wiki/rules/00-TRIGGER_MAP.md`).

## Approccio

Script PHP (`/tmp/add-frontmatter-xot.php`, non versionato — usa e getta) che:
1. Cammina `docs/` ricorsivamente
2. Esclude prefissi archivio: `_archive`, `legacy`, `historical`, `wiki-archive`, `old_tasks`,
   `raw`, `root-txt-files`, `root-md-files`, `_integration`, `-integration`,
   `root-code-workspace-files`, `root-uppercase-folders`, `_scripts`
3. Salta file con frontmatter già presente (prima riga `---`)
4. Per i restanti: genera `name` (kebab-case dal filename), `description` (da H1 o prima
   riga non vuota), `metadata.type` (dedotto dalla prima sottocartella, es. `stories/` →
   `story`, `design/` → `design`, default → `documentation`)
5. Prepend puro — mai toccato il contenuto esistente

## Candidati e risultato

- **Candidati totali** (senza frontmatter, fuori archivio): 2877
- **Processati** (frontmatter scritto su disco): 2877
- **Committati in modo sicuro**: 1785, in 4 commit atomici

## Perché non tutti i 2877 sono stati committati

Sessione con **fork concorrenti attivi sullo stesso modulo** in parallelo (confermato da
commit interleaved di altri agenti durante l'esecuzione: `18.57 tiny scaffold docs cleanup`,
`story 5.100`, etc.). Un `git diff --stat` post-scrittura ha mostrato 2931 file modificati
(non 2877) e 4045 righe eliminate — segno di modifiche di altri fork mescolate nello stesso
albero.

**Verifica applicata prima di ogni commit**:
1. Parsing del diff completo per isolare file con **zero deletion** (pure aggiunta)
2. Fra questi, verifica che il **primo hunk** contenga esattamente la firma del mio script
   (`---`/`name:`/`description:`/`metadata:`/`  type:`/`---` come prime 6 righe aggiunte)
3. Esclusione dei file con **più di un hunk** (287 file) — un secondo hunk nello stesso file
   indica una modifica concorrente di un altro fork nello stesso file, non isolabile senza
   rischio di commit misto

Risultato: 1785 file verificati come isolati e sicuri, committati in 4 batch da ~450 file
ciascuno con `git add --pathspec-from-file` mirato (mai `git add -A`).

## Incidente minore

Un `git reset` (senza `--hard`) è stato eseguito per errore durante l'investigazione, per
verificare lo stato dell'indice. Non ha toccato il working tree (nessun dato perso), ma ha
**unstaged** eventuali file che altri fork avevano già aggiunto all'indice in quel momento.
Nota per il second brain: mai `git reset` bare in un repo con fork concorrenti attivi, nemmeno
in modalità mixed — usare `git restore --staged <path espliciti>` se serve unstage mirato.

## Cosa resta

~1092 dei 2877 file originariamente processati hanno il frontmatter scritto su disco ma
**non committato** da questo fork (esclusi per hunk multipli o non verificabili in modo
isolato). Restano nel working tree per riconciliazione da parte del coordinatore o di un
fork successivo — non scartati, non sovrascritti.

## Commit

```
8cd32593 docs(xot): add minimal frontmatter to 437 files (batch xot-final-batch-aa)
afa0d46f docs(xot): add minimal frontmatter to 439 files (batch xot-final-batch-ab)
e50b236c docs(xot): add minimal frontmatter to 466 files (batch xot-final-batch-ac)
4a57eb16 docs(xot): add minimal frontmatter to 443 files (batch xot-final-batch-ad)
```

## Note per riuso su altri moduli (es. Notify)

Lo stesso approccio è riutilizzabile, con questi accorgimenti aggiuntivi rispetto al primo
tentativo:
- Verificare SEMPRE i path generati dal parsing diff contro il filesystem reale
  (`[ -f "$path" ]`) prima di `git add --pathspec-from-file` — un diff mal interpretato può
  produrre path concatenati/corrotti
- In presenza di fork concorrenti: isolare per hunk singolo prima di committare, non fidarsi
  del solo "zero deletions" a livello di file
