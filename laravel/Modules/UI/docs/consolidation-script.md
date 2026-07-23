---
title: "Script Consolidamento Documentazione UI"
type: concept
tags: [consolidation, script]
created: 2026-07-14
updated: 2026-07-14
qmd: "consolidation-script script consolidamento documentazione ui"
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

# Script Consolidamento Documentazione UI

## File Vuoti da Eliminare

Questi file sono vuoti (0 bytes) e possono essere eliminati:

```bash
cd laravel/Modules/UI/docs

# File vuoti con underscore
rm -f advanced-form-components.md
rm -f blade-data-handling-2.md
rm -f naming-conventions-2.md
rm -f phpstan-fixes-2025-1.md
```

## File Duplicati da Eliminare

Questi file hanno versioni con trattini che devono essere mantenute:

```bash
# Advanced Form Components
rm -f advanced-form-components.md

# Blade Components
rm -f blade-data-handling-2.md

# Filament Components
rm -f filament-4x-upgrade-1.md
rm -f filament-4x-upgrade-report-1.md
rm -f filament-blade-components-usage-2.md
rm -f filament-components-2.md
rm -f filament-components-errors-2.md
rm -f filament-components-location-studio-2.md
rm -f filament-components-usage-1.md
rm -f filament-fileupload-2.md
rm -f filament-fileupload-components-2.md
rm -f filament-error-fileupload-buttonlabel-2.md
rm -f filament-error-fileupload-icon-2.md
rm -f filament-error-fileupload-prefixicon-2.md
rm -f filament-dropdown-avatar-components-1.md
rm -f filament-dropdown-avatar-usage.md
rm -f filament-pages-refactoring-1.md
rm -f filament-resources-structure-2.md
rm -f filament-vscode-2.md

# Conflict Resolution
rm -f conflict-resolution-iconstatecolumn-1.md
rm -f conflict-resolution-locationselector-1.md
rm -f conflict-resolution-tablelayoutenum-1.md
rm -f conflict-resolution-translation-files-1.md

# Naming Conventions
rm -f convenzioni-naming-campi-2.md
rm -f naming-conventions-2.md
rm -f naming-rules-2.md

# PHPStan
rm -f phpstan-fixes-2025-1.md
rm -f phpstan-corrections-summary-1.md
rm -f phpstan-radio-badge-fix-2.md

# VSCode
rm -f vscode-filament-extension-1.md
rm -f vscode-filament-plugin-2.md

# Altri
rm -f algolia-docsearch.md
rm -f architecture-rules-1.md
rm -f auth-pages-1.md
rm -f base-components-1.md
rm -f best-practices-2.md
rm -f blocks-system-2.md
rm -f chart-components-2.md
rm -f components-guide-1.md
rm -f cms-link-2.md
rm -f cms-themes-link-2.md
```

## File con Date da Spostare in Archive

```bash
mkdir -p archive

# Spostare file con date
mv dry-kiss-analysis-[DATE].md archive/dry-kiss-analysis.md 2>/dev/null
mv phpstan-level-10-cleanup-[DATE].md archive/phpstan-level-10-cleanup.md 2>/dev/null

# Consolidare file PHPStan con date
# phpstan-fixes-gennaio-2025.md → consolidare in phpstan-compliance.md
```

## File con Maiuscole da Rinominare

```bash
# Verificare se metodi-duplicati-analisi-2.md è diverso da metodi-duplicati-analisi.md
# Se sono identici, eliminare quello con maiuscole
if [ -f "metodi-duplicati-analisi-2.md" ]; then
    if cmp -s "metodi-duplicati-analisi-2.md" "metodi-duplicati-analisi.md" 2>/dev/null; then
        rm -f metodi-duplicati-analisi-2.md
    else
        # Se diversi, rinominare quello con maiuscole
        mv metodi-duplicati-analisi-2.md metodi-duplicati-analisi-uppercase.md
    fi
fi
```

## Verifica Finale

```bash
# Verificare che non ci siano più file con underscore
find . -maxdepth 1 -type f -name "*_*.md" | grep -v README

# Verificare che non ci siano più file con date nei nomi (tranne archive/)
find . -maxdepth 1 -type f -name "*2025*.md" -o -name "*november*.md" -o -name "*gennaio*.md" -o -name "*october*.md"

# Verificare file vuoti
find . -maxdepth 1 -type f -name "*.md" -size 0
```

## Aggiornamento Collegamenti

Dopo l'eliminazione, cercare e aggiornare tutti i riferimenti:

```bash
# Cercare riferimenti ai file eliminati
grep -r "advanced_form_components" . --include="*.md"
grep -r "blade_data_handling" . --include="*.md"
grep -r "filament_4x_upgrade" . --include="*.md"
# ... e così via per tutti i file eliminati
```

---

**Nota**: Eseguire questo script con cautela, verificando sempre prima di eliminare.
