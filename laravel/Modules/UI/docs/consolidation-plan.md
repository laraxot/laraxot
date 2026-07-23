---
title: "Piano Consolidamento Documentazione - Modulo UI"
type: concept
tags: [consolidation, plan]
created: 2026-07-14
updated: 2026-07-14
qmd: "consolidation-plan piano consolidamento documentazione - modulo ui"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index-1.md"
  - "./00-index.md"
  - "./04-datas.md"
  - "./advanced-form-components-1.md"
  - "./advanced-form-components.md"
  - "./agent-confidence-discipline.md"
  - "./agent-confidence-protocol.md"
  - "./agent-edit-discipline.md"
---

# Piano Consolidamento Documentazione - Modulo UI

## Obiettivo

Consolidare la documentazione rimuovendo duplicati e file obsoleti, mantenendo una sola fonte di verità per ogni argomento.

## Regole di Consolidamento

1. **Convenzione naming**: Usare sempre trattini (`-`) invece di underscore (`_`)
2. **Nomi file**: Maiuscole solo per `README.md`, tutto il resto minuscolo
3. **Date nei nomi**: Rimuovere date dai nomi file (spostare in archive se necessario)
4. **Versione da mantenere**: Mantenere sempre la versione più completa e aggiornata

## File Duplicati da Consolidare

### 1. Advanced Form Components
- ❌ `advanced-form-components.md` → Eliminare
- ✅ `advanced-form-components.md` → Mantenere

### 2. Blade Components
- ❌ `blade-data-handling-2.md` → Eliminare
- ✅ `blade-data-handling.md` → Mantenere
- ✅ `blade-components.md` → Mantenere
- ✅ `blade-icons.md` → Mantenere

### 3. Filament Components
- ❌ `filament-4x-upgrade-1.md` → Eliminare
- ❌ `filament-4x-upgrade-report-1.md` → Eliminare
- ✅ `filament-4x-upgrade.md` → Mantenere
- ✅ `filament-4x-upgrade-report.md` → Mantenere

- ❌ `filament-blade-components-usage-2.md` → Eliminare
- ✅ `filament-blade-components-usage.md` → Mantenere

- ❌ `filament-components-2.md` → Eliminare
- ❌ `filament-components-errors-2.md` → Eliminare
- ❌ `filament-components-location-studio-2.md` → Eliminare
- ❌ `filament-components-usage-1.md` → Eliminare
- ✅ `filament-components.md` → Mantenere
- ✅ `filament-components-errors.md` → Mantenere
- ✅ `filament-components-location-studio.md` → Mantenere
- ✅ `filament-components-usage.md` → Mantenere

- ❌ `filament-fileupload-2.md` → Eliminare
- ❌ `filament-fileupload-components-2.md` → Eliminare
- ✅ `filament-fileupload.md` → Mantenere
- ✅ `filament-fileupload-components.md` → Mantenere

### 4. Conflict Resolution
- ❌ `conflict-resolution-iconstatecolumn-1.md` → Eliminare
- ❌ `conflict-resolution-locationselector-1.md` → Eliminare
- ❌ `conflict-resolution-tablelayoutenum-1.md` → Eliminare
- ❌ `conflict-resolution-translation-files-1.md` → Eliminare
- ✅ `conflict-resolution-iconstatecolumn.md` → Mantenere
- ✅ `conflict-resolution-locationselector.md` → Mantenere
- ✅ `conflict-resolution-tablelayoutenum.md` → Mantenere
- ✅ `conflict-resolution-translation-files.md` → Mantenere

### 5. Naming Conventions
- ❌ `convenzioni-naming-campi-2.md` → Eliminare
- ❌ `naming-conventions-2.md` → Eliminare
- ❌ `naming-rules-2.md` → Eliminare
- ✅ `convenzioni-naming-campi.md` → Mantenere
- ✅ `naming-conventions.md` → Mantenere
- ✅ `naming-rules.md` → Mantenere
- ✅ `file-naming-rules.md` → Mantenere

### 6. PHPStan Documentation
- ❌ `phpstan-fixes-2025-1.md` → Eliminare (duplicato)
- ❌ `phpstan_fixes_gennaio_2025.md` → Eliminare (duplicato)
- ❌ `phpstan-corrections-summary-1.md` → Eliminare (duplicato)
- ❌ `phpstan-radio-badge-fix-2.md` → Eliminare (duplicato)
- ✅ `phpstan-compliance.md` → Mantenere (documento principale)
- ✅ `phpstan-fixes-2025.md` → Mantenere (se contiene info aggiuntive)
- ✅ `phpstan-corrections-summary.md` → Mantenere
- ✅ `phpstan-corrections.md` → Mantenere
- ✅ `phpstan-fixes.md` → Mantenere
- ✅ `phpstan-fixes-summary.md` → Mantenere

**Nota**: File con date nei nomi (`phpstan-level-10-cleanup-[DATE].md`, `dry-kiss-analysis-[DATE].md`) dovrebbero essere spostati in `archive/` o consolidati.

### 7. VSCode Documentation
- ❌ `vscode-filament-extension-1.md` → Eliminare
- ❌ `vscode-filament-plugin-2.md` → Eliminare
- ✅ `vscode-filament-extension.md` → Mantenere
- ✅ `vscode-filament-plugin.md` → Mantenere
- ✅ `vscode-php-setup.md` → Mantenere

### 8. Altri Duplicati
- ❌ `algolia-docsearch.md` → Eliminare
- ✅ `algolia-docsearch.md` → Mantenere

- ❌ `architecture-rules-1.md` → Eliminare
- ✅ `architecture-rules.md` → Mantenere

- ❌ `auth-pages-1.md` → Eliminare
- ✅ `auth-pages.md` → Mantenere

- ❌ `base-components-1.md` → Eliminare
- ✅ `base-components.md` → Mantenere

- ❌ `best-practices-2.md` → Eliminare
- ✅ `best-practices.md` → Mantenere

- ❌ `blocks-system-2.md` → Eliminare
- ✅ `blocks-system.md` → Mantenere
- ✅ `blocks.md` → Mantenere

- ❌ `chart-components-2.md` → Eliminare
- ✅ `chart-components.md` → Mantenere

- ❌ `components-guide-1.md` → Eliminare
- ✅ `components-guide.md` → Mantenere
- ✅ `components.md` → Mantenere

- ❌ `cms-link-2.md` → Eliminare
- ❌ `cms-themes-link-2.md` → Eliminare
- ✅ `cms-link.md` → Mantenere
- ✅ `cms-themes-link.md` → Mantenere

## File con Date nei Nomi (da Spostare in Archive)

1. `dry-kiss-analysis-[DATE].md` → `archive/dry-kiss-analysis.md`
2. `phpstan-level-10-cleanup-[DATE].md` → `archive/phpstan-level-10-cleanup.md`
3. `phpstan-fixes-gennaio-2025.md` → Consolidare in `phpstan-compliance.md`

## File con Maiuscole (da Rinominare)

1. `metodi-duplicati-analisi-2.md` → `metodi-duplicati-analisi.md` (già esiste `metodi-duplicati-analisi.md`)

## Processo di Consolidamento

### Fase 1: Backup
```bash
# Creare backup prima di eliminare
mkdir -p archive/backup-$(date +%Y%m%d)
cp *.md archive/backup-$(date +%Y%m%d)/
```

### Fase 2: Verifica Contenuti
Per ogni coppia di file duplicati:
1. Confrontare contenuti
2. Identificare informazioni uniche
3. Consolidare in un unico file
4. Aggiornare collegamenti

### Fase 3: Eliminazione
1. Eliminare file con underscore
2. Eliminare file duplicati
3. Spostare file con date in archive/

### Fase 4: Aggiornamento Collegamenti
1. Cercare riferimenti ai file eliminati
2. Aggiornare tutti i collegamenti
3. Verificare README.md

## Checklist Pre-Consolidamento

- [ ] Backup completo creato
- [ ] Contenuti confrontati per ogni coppia
- [ ] Informazioni uniche identificate
- [ ] File consolidati creati
- [ ] Collegamenti aggiornati
- [ ] README.md aggiornato

## Checklist Post-Consolidamento

- [ ] File duplicati eliminati
- [ ] File con date spostati in archive/
- [ ] File con maiuscole rinominati
- [ ] Tutti i collegamenti funzionano
- [ ] README.md aggiornato
- [ ] Documentazione verificata

## Note Importanti

1. **Mai eliminare senza backup**: Sempre creare backup prima di eliminare
2. **Verificare contenuti**: Non assumere che file con stesso nome abbiano stesso contenuto
3. **Aggiornare collegamenti**: Cercare tutti i riferimenti ai file eliminati
4. **Mantenere storia**: File storici importanti vanno in archive/, non eliminati

---

**Data creazione**: [DATE]
**Status**: Piano da implementare
**Priorità**: Alta
