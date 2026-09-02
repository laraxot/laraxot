# Perfection Audit — Xot Module

**Data**: 2026-09-01
**Scope**: `laravel/Modules/Xot/docs/`
**Metodo**: BMAD investigate + document-project + fix_docs_naming_convention.sh

---

## TL;DR

Xot module docs sono **2,346+ file .md** di cui **1,170 stub vuoti** (~50% bloat). Ogni file uppercase viola la convenzione `kebab-case.md`. Risultato: impossibile onboarding, refusi, duplicati case-sensitivity (`AGENTS.md` vs `agents.md`, `INDEX.md` vs `index.md`).

**Stato attuale**: lontano dalla perfezione. Richiede bonifica chirurgica.

---

## Numeri

| Metrica | Valore | Soglia salute |
|---------|--------|---------------|
| File .md totali | 2,346 | <200 |
| Stub (<100 byte) | 1,170 | 0 |
| File vuoti (0 byte) | ~400 | 0 |
| Uppercase .md | ~28 visibili dopo rename script | 0 (eccetto README.md) |
| File rinominati da fix script | 44+ | — |

---

## Problemi P0 (critici)

### P0.1 — Bloat da iterazioni agenti
1,170 stub vuoti sono residui di iterazioni LLM. Esempi:
```
docs/adjacency-list-vs-nested-set.md   # 0 byte
docs/actions.md                         # 1 byte
docs/00-index.md (1 byte in posti)
docs/aggregati.md                       # 1 byte
```

### P0.2 — Duplicati case-sensitivity
Wiki Xot ha entrambi `AGENTS.md` e `agents.md`, `INDEX.md` e `index.md`. Git può tenere entrambi su Linux ma confusione su filesystem case-insensitive (deploy Windows/macOS).

### P0.3 — Sottocartelle non bonificate
Script `fix_docs_naming_convention.sh` ha preso `Xot/docs/*.md` ma non ricorsivamente. Rimangono:
- `llm-wiki/INDEX.md`
- `raw/INDEX.md`
- `wiki/INDEX.md`
- `roadmap/INDEX.md`
- `models/INDEX.md`
- `traits/INDEX.md`
- `consolidated/INDEX.md`

Più file uppercase tipo `ACTIONS.md`, `CHANGELOG.md`, `RelationX.md`, `SCHEMA.md` in subdir.

---

## Pattern di qualità esistenti (mantenere)

- `docs/accessor-refactoring-complete-guide.md` — guida completa
- `docs/phpstan-level10.md` — best practices PHPStan
- `docs/dry-kiss.md` — principi DRY/KISS
- `docs/filament-form-schema-conventions.md` — convenzioni Filament v5
- `docs/module-structure.md` — struttura modulo canonica
- `docs/dry-kiss.md`, `docs/patterns.md` — pattern riusabili

Questi vanno preservati durante la bonifica.

---

## Piano bonifica consigliato

### Fase 1 — Emergency (1-2 ore)
```bash
# Rimuovi stub vuoti
find laravel/Modules/Xot/docs -name "*.md" -size -100c -delete

# Rimuovi directory vuote
find laravel/Modules/Xot/docs -type d -empty -delete
```
**Effetto atteso**: -1,170 file, -X cartelle vuote.

### Fase 2 — Rename ricorsivo (2-3 ore)
```bash
# Rinomina ricorsivamente in sottocartelle
find laravel/Modules/Xot/docs -type f -name "*.md" \
  -not -name "README.md" \
  | while read f; do
      dir=$(dirname "$f")
      base=$(basename "$f" .md)
      [ "$base" = "$(echo "$base" | tr '[:upper:]' '[:lower:]')" ] && continue
      lower=$(echo "$base" | tr '[:upper:]' '[:lower:]' | tr '_' '-')
      mv "$f" "$dir/$lower.md"
  done
```

### Fase 3 — Dedup case-sensitivity (1 ora)
```bash
# Trova duplicati
find laravel/Modules/Xot/docs -type f | awk -F/ '{print tolower($NF)}' | sort | uniq -c | sort -rn | awk '$1>1'
```

### Fase 4 — Consolidamento (ongoing)
- Merge file simili (es. `00-INDEX.md`, `00-index.md`, `00-master-index.md`, `00-MASTER-INDEX.md`, `00-index-v2.md` → uno solo)
- Sposta tutto ciò che è regola/pattern in `docs/wiki/rules/` o secondo livello

---

## Cosa NON fare

- **Non rinominare** `README.md` (è l'unico case speciale ammesso)
- **Non cancellare** file in `docs/_integration/` senza leggere prima (contengono snippets da importare)
- **Non toccare** `docs/phpstan-*` senza fix PHPStan in sync
- **Non** fare commit mega — meglio commit atomici per fase

---

## Soglia di accettabilità (perfezione)

| Metrica | Target |
|---------|--------|
| File .md totali in Xot/docs | <200 |
| Stub vuoti | 0 |
| File uppercase (no README) | 0 |
| Duplicati case-sensitivity | 0 |
| Indice consolidato | 1 (`00-index.md`) |
| Ogni file ha frontmatter + sezione "Scopo" | 100% |

---

## Riferimenti

- `laravel/Modules/docs/DOCUMENTATION_AUDIT.md` (audit preesistente)
- `docs/super-mucca/SKILL.md` (regime qualità)
- `docs/wiki/rules/00-TRIGGER_MAP.md` (regole attivazione)
- Skill: `bmad-document-project` (riproducibilità audit)
