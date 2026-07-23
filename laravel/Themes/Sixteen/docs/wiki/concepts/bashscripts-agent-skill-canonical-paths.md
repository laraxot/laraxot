---
title: "Percorsi canonici skill agenti (bashscripts) e tema Sixteen"
module: "theme-sixteen"
type: "concept"
updated: "2026-05-04"
---

# Tema Sixteen e albero skill agenti

## Scopo

Il tema **Sixteen** non ospita una copia parallela delle skill BMAD/Codex: il frontoffice e le Blade del tema **non** devono duplicare `SKILL.md` o workflow sotto `laravel/Themes/Sixteen/`.

## Dove sta la conoscenza operativa

- Inventario mirror **252 / 252** tra `bashscripts/ai/.agents/skills` e `.codex/skills`: [agents-skills-mirror-codex-inventory](../../../../bashscripts/docs/wiki/comparisons/agents-skills-mirror-codex-inventory.md)
- Budget contesto e caricamento selettivo: [agents-context-budget-and-deduplication](../../../../bashscripts/docs/wiki/concepts/agents-context-budget-and-deduplication.md)
- Ponte modulo AI: [bashscripts-agents-codex-skill-mirror](../../../Modules/AI/docs/wiki/comparisons/bashscripts-agents-codex-skill-mirror.md)

## Collegamento al lavoro tema

Per story lunghe e overflow contesto su pagine Folio/Volt del tema, restano valide le discipline già indicizzate in [context compression discipline](../../../../docs/wiki/concepts/context-compression-discipline.md) e [context compression plugin](./context-compression-plugin.md).

---

**Ultimo aggiornamento:** 2026-05-04
