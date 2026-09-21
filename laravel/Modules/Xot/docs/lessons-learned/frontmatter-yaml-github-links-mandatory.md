---
name: frontmatter-yaml-github-links-mandatory
description: "Ogni .md di design/ADR/pattern/story richiede github_issues/github_discussions nel frontmatter, risolti verificando la repo reale del file"
metadata:
  type: lesson-learned
  created: 2026-09-15
  github_issues: []
---

# Frontmatter YAML con GitHub link è obbligatorio, non opzionale

## L'errore che si è ripetuto

Creati più file `.md` (design doc, ADR) senza frontmatter YAML, o con frontmatter privo dei
campi `github_issues`/`github_discussions`. In un caso, assunta la repo sbagliata
(`laraxot/...` invece di quella realmente configurata come remote) senza verificarla.

## Perché è sbagliato

Un `.md` senza frontmatter non è indicizzabile/collegabile al resto del second brain. Un
link GitHub verso una repo sbagliata è peggio di nessun link: sembra corretto ma punta al
posto sbagliato, ed è un errore silenzioso finché qualcuno non prova a cliccarlo.

## Come si fa correttamente

Prima di scrivere il frontmatter di un nuovo `.md`, verifica SEMPRE la repo reale del
modulo che lo ospita:

```bash
cd <path della cartella del file> && git remote -v
```

Se esistono più remote, `laraxot` ha precedenza su `provtv` se presente; altrimenti usa
quello disponibile. Solo dopo aver verificato, popola:

```yaml
---
name: kebab-case-slug
description: "One-line summary"
metadata:
  type: design|architecture|lesson-learned|story
  github_issues:
    - <owner>/<repo>#<N>
  github_discussions:
    - <owner>/<repo>/discussions/<N>
---
```

Se l'issue/discussion non esiste ancora, creala con `gh issue create` / `gh api ... discussions`
prima di scrivere il link — non inventare numeri.

## Come riconoscerlo in futuro

```bash
# .md senza frontmatter
find Modules/<Modulo>/docs -name "*.md" -exec sh -c 'head -1 "$1" | grep -q "^---$" || echo "$1"' _ {} \;
```

## Riferimenti

- Memory: `frontmatter-yaml-enforcement-complete.md`, `markdown-github-links-enforcement.md`
- Story 5.106, 5.108 (`Modules/Xot/docs/stories/`)
