---
name: github-repo-wide-audit-mandatory
description: "Non basta linkare la propria issue: prima di chiudere un task su un modulo va fatto audit di TUTTE le issue/discussion aperte nella repo"
metadata:
  type: lesson-learned
  created: 2026-09-15
  github_issues:
    - provtv/module_xot_fila5#3
---

# Audit GitHub repo-wide obbligatorio prima di chiudere un task

## L'errore che si è ripetuto

Workflow tipico seguito: crea issue per il task corrente → lavora → commit → linka quella
issue nella story → STOP. Le altre issue/discussion aperte nella stessa repo, anche se
correlate al lavoro appena fatto, restavano senza commento — a volte per giorni.

## Perché è sbagliato

Una regola esistente (`spiegare-ogni-modifica-su-github.md`, con pre-commit hook)
verificava solo *"il mio lavoro ha un link a un'issue?"* — non *"ho controllato le issue
già aperte da altri, correlate al lavoro appena fatto?"*. Sono due controlli diversi: solo
il primo era coperto. Il workflow normale non aveva uno step esplicito fra "commit" e
"report finale" per l'audit repo-wide.

## Come si fa correttamente

Prima di considerare un task completo su un modulo con repo GitHub propria:

```bash
cd <path del modulo> && git remote -v   # laraxot ha precedenza su provtv se esiste
gh issue list -R <repo> --state open --limit 100
gh api repos/<repo>/discussions --paginate
```

Per ogni issue/discussion aperta **correlata** al lavoro appena fatto (anche se non creata
in questa sessione): commenta con riepilogo + commit hash. Per quelle non correlate:
elencale nel report finale, non ignorarle silenziosamente.

## Come riconoscerlo in futuro

Se il tuo report finale menziona solo l'issue che hai creato tu e nessun'altra issue della
repo, l'audit non è stato fatto — torna indietro ed eseguilo prima di chiudere il task.

## Riferimenti

- Story 5.100 (`Modules/Xot/docs/stories/`), issue `provtv/module_xot_fila5#3`
- Memory: `github-repo-wide-interaction-mandatory.md`
- `implementation-standing-order-complete.md`, Fase 6.5
