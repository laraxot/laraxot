---
title: "On-Demand Pattern — Module Notify"
type: documentation
created: 2026-05-11
updated: 2026-05-11
tags: [on-demand, pattern, wiki, qmd]
related:
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
=======
=======
>>>>>>> a988596b (first)
  - "./00-index-1.md"
  - "./00-index-2.md"
  - "./00-index.md"
  - "./absolute-completion-100.md"
  - "./acronym-naming-conventions-1.md"
  - "./acronym-naming-conventions-2.md"
  - "./acronym-naming-conventions.md"
  - "./action-plan-immediate.md"
---

# On-Demand Pattern — Module **Notify**

**Fonte canonica**: [../../docs/wiki/rules/on-demand-pattern.md](../../docs/wiki/rules/on-demand-pattern.md)

## Principio

Questo module segue il **pattern on-demand** per rules, skills, commands e memories:

- ✅ **Vivono solo nel wiki** — ../../docs/wiki/
- ✅ **Caricati on-demand** — via trigger map o `qmd search`
- ❌ **NON pre-caricati** — mai embeddare nei bootstrap files
- ❌ **Nessuna duplicazione** — wiki = sorgente di verità unica

## Perché On-Demand?

1. **Contesto minimo** — Carico solo le regole pertinenti al task corrente
2. **Token efficient** — Nessun carico inutile all'avvio (~50K → ~2K token)
3. **Scalabile** — Aggiungere nuove rules non richiede modifiche ai bootstrap
4. **Manutenibile** — Una sola fonte di verità (wiki)

## Come Funziona

### Step-by-Step

\`\`\`bash
# 1. Identifico il trigger nel task
# 2. Consulto la trigger map globale
Read ../../docs/wiki/rules/00-TRIGGER_MAP.md

# 3. Carico on-demand la risorsa
Read docs/wiki/rules/<file>.md
# OPPURE
qmd search "<topic>"

# 4. Applico la regola/skill/command/memory
\`\`\`

### Local vs Global

- **Locali** → Usa `docs/wiki/<type>/<file>.md` (module-specific)
- **Globali** → Usa `../../docs/wiki/<type>/<file>.md` (project-wide)

## Struttura di Questo Module

\`\`\`
./laravel/Modules/Notify/docs/
└── wiki/                    # Knowledge base locale
    ├── rules/index.md      # Indice rules modulo-specifiche
    ├── skills/index.md     # Indice skills modulo-specifiche
    ├── commands/index.md   # Indici commands
    └── memories/index.md   # Indice memories
\`\`\`

## Quick Reference

| Bisogno | Azione |
|---------|--------|
| Trigger map globale | `Read ../../docs/wiki/rules/00-TRIGGER_MAP.md` |
| Pattern on-demand | `Read ../../docs/wiki/rules/on-demand-pattern.md` |
| Ricerca locale | `qmd search "topic" -c notify` |
| Wiki locale | `Read ./laravel/Modules/Notify/docs/wiki/index.md` |

## Regole Critiche per Module

<<<<<<< HEAD
=======
1. **Nessun bootstrap pesante** — Non elencare rules in AGENTS.md o claude.md
>>>>>>> a988596b (first)
1. **Nessun bootstrap pesante** — Non elencare rules in agents.md o claude.md
2. **Carica only what you need** — Ogni task carica max 3-5 file
3. **Mantieni la wiki aggiornata** — Dopo ogni task, aggiorna ./laravel/Modules/Notify/docs/wiki/log.md
4. **Rispetta la trigger map** — Se esiste, usala; altrimenti usa qmd search

## Riferimenti

- [Global On-Demand Pattern](../../docs/wiki/rules/on-demand-pattern.md)
- [LLM Wiki Operational Discipline](../../docs/wiki/concepts/llm-wiki-operational-discipline.md)
- [Trigger Map](../../docs/wiki/rules/00-TRIGGER_MAP.md)
- [Module Wiki Index](./wiki/index.md)

---
*Ultimo aggiornamento: 2026-05-11 | Pattern: on-demand via QMD*

---

<!-- Merged from ON-DEMAND-PATTERN.md, which collided with this file on case-insensitive filesystems. -->

---
title: "On-Demand Pattern — Module Notify"
type: documentation
created: 2026-05-11
updated: 2026-05-11
tags: [on-demand, pattern, wiki, qmd]
related:
<<<<<<< HEAD
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
=======
>>>>>>> a377e9e6 (.)
  - ../../docs/wiki/rules/on-demand-pattern.md
  - ../../docs/wiki/concepts/llm-wiki-operational-discipline.md
---

# On-Demand Pattern — Module **Notify**

**Fonte canonica**: [../../docs/wiki/rules/on-demand-pattern.md](../../docs/wiki/rules/on-demand-pattern.md)

## Principio

Questo module segue il **pattern on-demand** per rules, skills, commands e memories:

- ✅ **Vivono solo nel wiki** — ../../docs/wiki/
- ✅ **Caricati on-demand** — via trigger map o `qmd search`
- ❌ **NON pre-caricati** — mai embeddare nei bootstrap files
- ❌ **Nessuna duplicazione** — wiki = sorgente di verità unica

## Perché On-Demand?

1. **Contesto minimo** — Carico solo le regole pertinenti al task corrente
2. **Token efficient** — Nessun carico inutile all'avvio (~50K → ~2K token)
3. **Scalabile** — Aggiungere nuove rules non richiede modifiche ai bootstrap
4. **Manutenibile** — Una sola fonte di verità (wiki)

## Come Funziona

### Step-by-Step

\`\`\`bash
# 1. Identifico il trigger nel task
# 2. Consulto la trigger map globale
Read ../../docs/wiki/rules/00-TRIGGER_MAP.md

# 3. Carico on-demand la risorsa
Read docs/wiki/rules/<file>.md
# OPPURE
qmd search "<topic>"

# 4. Applico la regola/skill/command/memory
\`\`\`

### Local vs Global

- **Locali** → Usa `docs/wiki/<type>/<file>.md` (module-specific)
- **Globali** → Usa `../../docs/wiki/<type>/<file>.md` (project-wide)

## Struttura di Questo Module

\`\`\`
./laravel/Modules/Notify/docs/
└── wiki/                    # Knowledge base locale
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a377e9e6 (.)
    ├── rules/INDEX.md      # Indice rules modulo-specifiche
    ├── skills/INDEX.md     # Indice skills modulo-specifiche
    ├── commands/INDEX.md   # Indici commands
    └── memories/INDEX.md   # Indice memories
<<<<<<< HEAD
=======
=======
>>>>>>> a988596b (first)
    ├── rules/index.md      # Indice rules modulo-specifiche
    ├── skills/index.md     # Indice skills modulo-specifiche
    ├── commands/index.md   # Indici commands
    └── memories/index.md   # Indice memories
<<<<<<< HEAD
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
=======
>>>>>>> a377e9e6 (.)
\`\`\`

## Quick Reference

| Bisogno | Azione |
|---------|--------|
| Trigger map globale | `Read ../../docs/wiki/rules/00-TRIGGER_MAP.md` |
| Pattern on-demand | `Read ../../docs/wiki/rules/on-demand-pattern.md` |
| Ricerca locale | `qmd search "topic" -c notify` |
| Wiki locale | `Read ./laravel/Modules/Notify/docs/wiki/index.md` |

## Regole Critiche per Module

<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
1. **Nessun bootstrap pesante** — Non elencare rules in AGENTS.md o CLAUDE.md
=======
1. **Nessun bootstrap pesante** — Non elencare rules in agents.md o CLAUDE.md
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
1. **Nessun bootstrap pesante** — Non elencare rules in agents.md o CLAUDE.md
>>>>>>> a988596b (first)
=======
1. **Nessun bootstrap pesante** — Non elencare rules in AGENTS.md o CLAUDE.md
>>>>>>> a377e9e6 (.)
2. **Carica only what you need** — Ogni task carica max 3-5 file
3. **Mantieni la wiki aggiornata** — Dopo ogni task, aggiorna ./laravel/Modules/Notify/docs/wiki/log.md
4. **Rispetta la trigger map** — Se esiste, usala; altrimenti usa qmd search

## Riferimenti

- [Global On-Demand Pattern](../../docs/wiki/rules/on-demand-pattern.md)
- [LLM Wiki Operational Discipline](../../docs/wiki/concepts/llm-wiki-operational-discipline.md)
- [Trigger Map](../../docs/wiki/rules/00-TRIGGER_MAP.md)
- [Module Wiki Index](./wiki/index.md)

---
*Ultimo aggiornamento: 2026-05-11 | Pattern: on-demand via QMD*
