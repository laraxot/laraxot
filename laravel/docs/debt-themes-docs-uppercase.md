---
title: "Debito D9 — naming UPPERCASE in Themes/docs"
type: how-to
tags: [technical-debt, themes, docs, naming, second-brain, qmd]
created: 2026-07-24
updated: 2026-07-24
qmd: "debt themes docs uppercase rename lowercase readme only playbook"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
related:
  - "./gestionale-technical-debt.md"
  - "../Themes/docs/README.md"
  - "../Themes/docs/gestionale-docs-index.md"
  - "../Themes/docs/ridondanze-documentazione-temi.md"
---

# Debito D9 — file UPPERCASE in `Themes/docs`

## Perché

Convenzione progetto (e user rule): nomi `.md` **lowercase kebab-case**, unica eccezione `README.md`.  
File UPPERCASE rompono:

- disciplina agenti (grep/QMD inconsistenti);
- link relativi fragili (`DOCUMENTATION_INDEX` vs `documentation-index`);
- igiene second-brain.

## Inventario hub `laravel/Themes/docs/` (baseline)

Eseguire e aggiornare la tabella quando si lavora:

```bash
cd laravel/Themes/docs
find . -maxdepth 1 -name '*.md' | rg '/[A-Z0-9_-]+\.md$' || true
```

Esempi noti:

| File legacy | Destinazione suggerita | Note |
|---|---|---|
| `DOCUMENTATION_INDEX.md` | Assorbire in `README.md` poi stub/remove | Evitare secondo index |
| `ON-DEMAND-PATTERN.md` | Puntare a `docs/wiki/rules/on-demand-pattern.md` (root) | Spesso duplicato |
| `QMD-SETUP.md` | `qmd-setup.md` o wiki QMD | |
| `PROJECT-STRUCTURE.md` | `project-structure.md` | Merge se esiste già |
| `PERFORMANCE-OPTIMIZATION.md` | `performance-optimization.md` | |
| `THEME_PHILOSOPHY_COMPLETE.md` | `theme-philosophy.md` | Tagliare ridondanze |

Anche sotto `Themes/{Sixteen,Zero,…}/docs/` esistono file UPPERCASE e report con **date nel nome** — stesso playbook, batch separato per tema.

## Come risolvere (batch sicuro, senza perdita)

### Principio

1. **Mai** delete contenuto unico senza grep backlink.
2. Preferire `git mv` lowercase kebab-case.
3. Se duplicato semantico → merge nel canon + stub corto o delete dopo link fix.
4. **Niente date** nel nuovo filename.
5. Lock multi-agente sulla cartella docs se altri agenti scrivono Themes.

### Procedura per un singolo file

```text
1. rg -n "NOME_FILE" laravel/Themes docs  # backlink
2. Esiste già lowercase sullo stesso topic? → merge
3. Altrimenti: git mv Old.md new-kebab.md
4. Aggiornare link nei file trovati al passo 1
5. Verificare link relativi del diff
6. Aggiornare Themes/docs/README.md se era in indice
```

### Ordine di lavoro consigliato

1. Hub `Themes/docs/*UPPERCASE*` (pochi file, alto impatto navigazione).
2. Sixteen (FO attivo) — solo file di policy, **non** i dump `body-structure-comparison/report_YYYY…` in prima battuta (archivio: vedere `docs-archive-policy` se presente; altrimenti lasciare e documentare come “archivio storico”).
3. Zero / TwentyOne / Meetup / Barthelemy.

### Archivio con date nel filename

Non rinomare cento report storici in un PR misto. Opzioni:

| Opzione | Quando |
|---|---|
| Lasciare archivio + README che dice “storico, non canon” | Debito basso accettato |
| Spostare sotto `docs/archive/` **solo se la cartella esiste già** | Mai creare nuove `docs/` |
| Rinominare kebab senza data (`segnalazione-elenco-comparison.md`) | Se il report è ancora citato |

## DoD

- [ ] Zero basename `[A-Z]` sotto `Themes/docs/` (maxdepth 1) eccetto `README.md`
- [ ] Link da `Themes/docs/README.md` e `gestionale-docs-index.md` ok
- [ ] Nessun contenuto unico perso (review diff)
- [ ] Issue #14 commentato con lista file rinominati

## Non fare

- Delete di massa.
- Creare `documentation-index-2026.md`.
- Rinominare durante fix PHPStan non correlato.
- Toccare `Modules/*/docs` in questa story (fuori scope D9).
