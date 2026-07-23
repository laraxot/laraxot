# Merge Conflict Resolution Guidelines

## Overview
This document outlines the standard procedures for resolving merge conflicts in the codebase, based on recent resolutions and best practices.

## General Guidelines
1. Always review both versions of conflicting code before resolving
2. Keep the more complete implementation when it adds functionality without breaking existing features
3. Maintain consistent code style across resolutions
4. Document significant merge decisions in this file

## Recent Resolutions

### Base Resource Classes (2025-02-03)
The following files were resolved with conflicts:

1. `XotBaseResource.php`:
   - Resolved by keeping clean syntax without extra semicolons
   - Maintained consistent code style with PHP PSR standards

2. `XotBaseCreateRecord.php` and `XotBaseEditRecord.php`:
   - Kept the more complete implementation from dev branch
   - Retained form schema methods and proper type hints
   - Maintained consistent method organization
   - Preserved important form configuration options in comments

### Activity Module (2025-02-03)
1. `ActivityResource.php`:
   - Kept complete implementation with proper form schema
   - Organized imports for better readability
   - Maintained comprehensive class documentation

2. `ListActivities.php`:
   - Resolved conflicts in table columns configuration
   - Kept comprehensive implementation with proper labels and sorting
   - Maintained consistent code style with other list pages
   - Preserved all necessary column definitions

3. `BaseActivity.php`:
   - Resolved nested merge conflicts
   - Kept strict_types declaration
   - Maintained proper class documentation
   - Used consistent code style

4. `Snapshot.php`:
   - Removed commented out code
   - Kept clean implementation
   - Maintained proper type hints

### User Module Language Files (2025-02-03)
1. All language files in `Modules/User/lang/it/`:
   - Kept strict_types declarations
   - Maintained consistent file structure
   - Preserved proper type safety

### Notify Module (2025-02-03)
1. `NotificationResource.php`:
   - Combined necessary imports from both branches
   - Kept XotBaseResource inheritance
   - Maintained complete form schema implementation

### TechPlanner Module (2025-02-03)
1. Rimossi file `.old` obsoleti:
   - `LegalOfficeResource/ListRecords.old`
   - `MedicalDirectorResource/ListRecords.old`
   - `LegalRepresentativeResource/ListRecords.old`
   - Questi file sono stati sostituiti da implementazioni più recenti che seguono il pattern XotBaseListRecords

### Correzioni (2025-02-03)
1. `ListSnapshots.php`:
   - Corretto errore nella colonna state dove la variabile $view non era definita
   - Aggiunto il percorso corretto della view: 'activity::filament.tables.columns.state'

## Best Practices
1. Always keep proper PHP type hints and docblocks
2. Maintain consistent method organization
3. Preserve important configuration options, even if commented
4. Follow PSR coding standards
5. Document significant architectural decisions

## Conflict Prevention
1. Coordinate with team members when working on base classes
2. Keep feature branches up to date with main branch
3. Make smaller, focused commits
4. Document API changes proactively

# Risoluzione dei Conflitti Git

## Linee Guida Generali

1. **Analisi Manuale**: Ogni conflitto deve essere analizzato manualmente
2. **Documentazione**: Ogni risoluzione deve essere documentata
3. **Test**: Verificare che tutto funzioni dopo la risoluzione
4. **Commit**: Commit atomici e ben documentati

## Tipi di Conflitti

### 1. File di Configurazione

#### Composer.json
- Mantenere le dipendenze più recenti
- Standardizzare i nomi dei pacchetti
- Documentare le versioni specifiche

#### Package.json
- Allineare con i requisiti di Filament 3.x
- Mantenere le dipendenze di sviluppo necessarie
- Standardizzare gli script

#### Tailwind.config.js
- Mantenere la configurazione base
- Aggiungere i percorsi dei componenti del tema
- Documentare le personalizzazioni

#### Vite.config.js
- Mantenere la configurazione base
- Aggiungere gli alias necessari
- Supportare TypeScript e PostCSS

### 2. Documentazione

#### README.md
- Mantenere la documentazione generica per progetti multipli
- Aggiungere riferimenti incrociati
- Standardizzare il formato Markdown

#### File di Configurazione
- Documentare le scelte architetturali
- Mantenere la coerenza tra i file
- Aggiungere commenti esplicativi

### 3. Codice Sorgente

#### PHP
- Mantenere la compatibilità con Laravel 10+
- Seguire le best practices di Filament 3.x
- Documentare le modifiche

#### JavaScript/TypeScript
- Mantenere la compatibilità con Filament 3.x
- Seguire le best practices di Vite
- Documentare le modifiche

#### Blade Templates
- Mantenere la compatibilità con Filament 3.x
- Seguire le best practices di Laravel
- Documentare le modifiche

## Processo di Risoluzione

1. **Analisi**
   - Identificare i file in conflitto
   - Analizzare le differenze
   - Consultare la documentazione

2. **Risoluzione**
   - Applicare le linee guida
   - Documentare le scelte
   - Verificare la coerenza

3. **Test**
   - Verificare la compilazione
   - Testare le funzionalità
   - Controllare la documentazione

4. **Commit**
   - Commit atomici
   - Messaggi descrittivi
   - Riferimenti alla documentazione

## Best Practices

1. **Documentazione**
   - Mantenere aggiornata
   - Riferimenti incrociati
   - Esempi di codice

2. **Configurazione**
   - Standardizzare i nomi
   - Documentare le scelte
   - Mantenere la coerenza

3. **Codice**
   - Seguire le best practices
   - Documentare le modifiche
   - Mantenere la compatibilità

## Struttura della Documentazione

```
docs/
├── README.md
├── merge_conflict_resolution.md
├── architecture.md
├── installation.md
├── configuration.md
├── development.md
├── deployment.md
├── maintenance.md
├── troubleshooting.md
├── faq.md
├── changelog.md
├── contributing.md
└── license.md
```

## Riferimenti

- [Laravel Documentation](https://laravel.com/docs)
- [Filament Documentation](https://filamentphp.com/docs)
- [Vite Documentation](https://vitejs.dev/guide)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs)
