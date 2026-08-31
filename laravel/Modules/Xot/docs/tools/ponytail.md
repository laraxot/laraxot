---
title: "Ponytail — minimal code generation skill"
module: "xot"
type: reference
status: approved
tags: [ai, skill, minimal-code, yagni, ponytail, cursor, claude-code]
created: 2026-08-19
updated: 2026-08-19
qmd: "ponytail minimal code ai skill yagni reuse stdlib native one-line less code cursor claude codex install plugin"
related:
  - "./omniroute.md"
  - "./claude-mem.md"
  - "./claude-code-setup.md"
---

# Ponytail

> [github.com/dietrichgebert/ponytail](https://github.com/dietrichgebert/ponytail) — MIT

## Scopo

Skill per agenti AI: "scrivi solo il codice che serve davvero". Riduce ~54% LOC,
~20% costi, ~27% tempo — misurato su sessioni reali Claude Code.

## Filosofia (la "scala")

Prima di scrivere codice, l'agente si ferma al primo gradino che regge:

1. Serve davvero? → no: salta (YAGNI)
2. Già nel codebase? → riusa
3. La stdlib lo fa? → usala
4. Feature nativa piattaforma? → usala
5. Dipendenza installata? → usala
6. Una riga basta? → una riga
7. Solo allora: il minimo che funziona

**Non si taglia mai**: validazione, sicurezza, error handling, accessibilità.

## Installazione Cursor

Copiare `.cursor/rules/ponytail.md` dal repo nella propria cartella `.cursor/rules/`.

## Installazione Claude Code

```
/plugin marketplace add DietrichGebert/ponytail
/plugin install ponytail@ponytail
```

## Comandi

| Comando | Effetto |
|---------|---------|
| `/ponytail [lite\|full\|ultra\|off]` | Imposta intensità |
| `/ponytail-review` | Review diff per over-engineering |
| `/ponytail-audit` | Audit intero repo |
| `/ponytail-debt` | Ledger shortcut rimandati |

## Integrazione progetto

Ponytail è complementare al principio DRY+KISS già applicato nelle user rules di questo
progetto. Installabile per tutti gli agenti che operano su questo repo. L'impostazione
`PONYTAIL_DEFAULT_MODE=full` è raccomandata.

## Metriche benchmark

| vs baseline | LOC | token | costo | tempo | safe |
|---|--:|--:|--:|--:|--:|
| ponytail | -54% | -22% | -20% | -27% | 100% |
| caveman (prose) | -20% | +7% | +3% | +2% | 100% |
| YAGNI prompt | -33% | -14% | -21% | -30% | 95% |
