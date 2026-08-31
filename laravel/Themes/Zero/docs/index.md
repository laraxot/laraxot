# Indice della Documentazione - Tema Zero

> **Nota 2026-07-24**: indice storico senza frontmatter, ridondante rispetto a
> [00-index.md](./00-index.md) (canonico, aggiornato 2026-03-28). Usare `00-index.md` come riferimento
> primario; questo file non è stato consolidato per evitare di perdere la prosa introduttiva italiana.

## Panoramica
Questo documento serve come indice centrale per il tema Zero, fornendo una guida per la personalizzazione e l'utilizzo del tema all'interno dell'applicazione Laravel. Il tema Zero è un tema basato su TailwindCSS con supporto per Vite e componenti Blade moderni.

## Principi Chiave
1. **Semplicità**: Il tema Zero è progettato per essere semplice e leggero
2. **Personalizzabilità**: Consente facile personalizzazione attraverso configurazioni e sovrascrittura di componenti
3. **Performance**: Ottimizzato per prestazioni elevate con asset minimizzati
4. **Responsive**: Completamente responsive per tutti i dispositivi

## Funzionalità Principali
- **TailwindCSS**: Framework CSS utility-first per uno styling moderno e coerente
- **Vite**: Bundler moderno per la compilazione degli assets
- **Componenti Blade**: Libreria di componenti riutilizzabili per l'interfaccia frontend
- **Layout Flessibili**: Sistema di layout adattivo per diverse tipologie di pagina
- **Traduzioni**: Supporto multilingua integrato
- **Temi Personalizzabili**: Sistema di estensione per creare varianti del tema
- **Integrazione Filament**: Compatibilità completa con i componenti Filament

## Collegamenti Correlati
- [AI Methodologies](./ai-methodologies.md)
- [Documentazione Generale Progetto](../../../docs/README.md) (docs: replace project-specific references with generic placeholders across documentation)
- [Collegamenti Documentazione](../../../docs/collegamenti-documentazione.md)
- [Standard di Documentazione](../../../docs/DOCUMENTATION_STANDARDS.md)
- [Modulo UI](../../Modules/UI/docs/README.md)
- [Modulo Xot](../../Modules/Xot/docs/README.md)

### Moduli Integrati
- [Performance Actions Reference](./performance-actions-reference.md) - Riferimento action calcolo performance

## Categorie Principali

### Architettura e Struttura
- [README](./README.md) - Panoramica generale del tema
- [Architettura](./architecture.md) - Architettura generale del tema
- [Struttura](./layouts.md) - Struttura delle directory e dei layout
- [Componenti](./components.md) - Componenti Blade disponibili

### Personalizzazione
- [Personalizzazione](./customization.md) - Guida alla personalizzazione del tema
- [Readonly Field Styling](./readonly-field-styling.md) - Pattern UI/UX per campi readonly/calcolati
- [Esempi](./examples.md) - Esempi pratici di personalizzazione
- [Autenticazione](./authentication.md) - Componenti di autenticazione
- [Esempi Autenticazione](./auth_examples.md) - Esempi di pagine di autenticazione

### Sviluppo e Configurazione
- [Configurazione](./configuration.md) - Configurazione del tema
- [Compilazione Assets](./asset-compilation.md) - Guida alla compilazione degli assets
- [TailwindCSS](./tailwind.md) - Configurazione e personalizzazione Tailwind
- [Vite](./vite.md) - Configurazione e ottimizzazione Vite

### Traduzioni
- [Sistema Traduzioni](./translations.md) - Sistema di traduzioni del tema
- [File Lingua](./language-files.md) - Gestione dei file di traduzione
- [Localizzazione](./localization.md) - Localizzazione del tema

### Testing e Qualità
- [Testing](./testing.md) - Strategie e approcci per il testing del tema
- [ide-helper-phpdoc-boundary](./ide-helper-phpdoc-boundary.md) - Confine PHPDoc moduli ↔ tema
- [Performance](./performance.md) - Ottimizzazioni e analisi performance
- [Accessibilità](./accessibility.md) - Linee guida per l'accessibilità

## Linee Guida per l'Implementazione

### 1. Struttura del Tema
Il tema Zero segue una struttura standard con directory per componenti, risorse e configurazioni:

```
Zero/
├── app/
│   ├── View/
│   │   └── Components/
├── lang/
├── public/
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
│       ├── components/
│       ├── layouts/
│       └── pages/
└── docs/
```

### 2. Personalizzazione del Tema
Per personalizzare il tema Zero:

1. **Sovrascrivere i componenti**:
   ```bash
   # Copiare un componente esistente
   cp resources/views/components/button.blade.php resources/views/components/custom-button.blade.php
   ```

2. **Modificare i layout**:
   ```bash
   # Creare un layout personalizzato
   cp resources/views/layouts/app.blade.php resources/views/layouts/custom.blade.php
   ```

3. **Aggiungere stili personalizzati**:
   ```css
   /* resources/css/custom.css */
   .custom-class {
       @apply bg-blue-500 text-white rounded-lg;
   }
   ```

### 3. Compilazione Assets
```bash
# Sviluppo
npm run dev

# Produzione
npm run build

# Watch mode
npm run watch
```

### 4. Configurazione Tailwind
```javascript
// tailwind.config.js
module.exports = {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            colors: {
                primary: '#3B82F6',
                secondary: '#10B981',
            },
        },
    },
    plugins: [],
}
```

## Problemi Comuni e Soluzioni
- **Assets non caricati**: Verificare che `npm run build` sia stato eseguito
- **Stili non applicati**: Controllare la configurazione di TailwindCSS
- **Componenti mancanti**: Verificare la registrazione corretta dei componenti Blade
- **Traduzioni mancanti**: Controllare la presenza dei file di traduzione

## Documentazione e Aggiornamenti
- Documentare qualsiasi personalizzazione o modifica al tema nella cartella di documentazione
- Aggiornare questo indice se vengono introdotte nuove funzionalità o modifiche significative al tema Zero

## Collegamenti alla Documentazione Correlata
- [Panoramica Architettura](./architecture.md)
- [Personalizzazione](./customization.md)
- [Componenti](./components.md)
- [Esempi](./examples.md)
- [Troubleshooting](./troubleshooting.md)

## Note sulla Manutenzione
Questa documentazione viene aggiornata regolarmente. Prima di apportare modifiche al tema, consultare la documentazione pertinente e aggiornare i documenti correlati.

## Risoluzione Conflitti e Standard
- **Gennaio 2025**: Risoluzione sistematica di tutti i conflitti Git nei file di documentazione
- Il file `lang/it/zero_theme.php` è stato risolto manualmente mantenendo PSR-12, strict_types, array short syntax e solo chiavi effettive, come richiesto dagli standard PHPStan livello 10
- **Filosofia di risoluzione**: Approccio olistico con analisi manuale approfondita, mantenimento integrità architetturale, documentazione bidirezionale aggiornata
- Vedi anche: [../../../docs/README.md](../../../docs/README.md)
- Per dettagli sulle scelte architetturali e funzionali, consultare la doc globale e la sezione "Standard e Traduzioni".

*Ultimo aggiornamento: Gennaio 2025*
- **Aggiunto**: Sistema di documentazione automatica moduli
- **Integrato**: Refresh intelligente form reattivi
- **Migliorato**: Sistema di tracking e audit trail

---

<!-- Merged from INDEX.md, which collided with this file on case-insensitive filesystems. -->

---
title: "Documentation Index — Theme Zero"
type: index
tags: [documentation, index, theme]
created: 2026-07-14
updated: 2026-07-14
qmd: "theme zero documentation index"
related:
  - "./README.md"
---

# Documentation Index — Theme Zero

> **Note 2026-07-24**: this index is redundant with [00-index.md](./00-index.md) (canonical, updated
> 2026-03-28, aligned with current stack: Filament 5, Livewire 4, Volt, Tailwind v4). Kept only for the
> `archive/duplicates` links below which are not referenced elsewhere.

## Archive
- [archive/duplicates/conflict-resolution-summary](./archive/duplicates/conflict_resolution_summary.md)

## Legacy
- [legacy/duplicates/conflict-resolution-summary](./legacy/duplicates/conflict_resolution_summary.md)

## Outputs
- [outputs/README](./outputs/README.md)

## Raw
- [raw/README](./raw/README.md)

## Roadmap
- [roadmap/accessibility-standards](./roadmap/accessibility-standards.md)
- [roadmap/advanced-features](./roadmap/advanced-features.md)
- [roadmap/component-library](./roadmap/component-library.md)
- [roadmap/performance-optimization](./roadmap/performance-optimization.md)
- [roadmap/responsive-system](./roadmap/responsive-system.md)
- [roadmap/theme-customization](./roadmap/theme-customization.md)

## Root
- [CHANGELOG](./changelog.md)
- [CONFLICT-RESOLUTION-SUMMARY](./CONFLICT-RESOLUTION-SUMMARY.md)
- [CONFLICT-RESOLUTION-SUMMARY](./CONFLICT_RESOLUTION_SUMMARY.md)
- [METODI-DUPLICATI-ANALISI](./METODI-DUPLICATI-ANALISI.md)
- [METODI-DUPLICATI-ANALISI](./METODI_DUPLICATI_ANALISI.md)
- [accessor-delegation-pattern](./accessor-delegation-pattern.md)
- [agent-confidence-discipline](./agent-confidence-discipline.md)
- [agent-confidence-protocol](./agent-confidence-protocol.md)
- [agent-edit-discipline](./agent-edit-discipline.md)
- [ai-development-guide](./ai-development-guide.md)
- [ai-handoff](./ai-handoff.md)
- [ai-methodologies](./ai-methodologies.md)
- [analisi-completa-tema](./analisi-completa-tema.md)
- [architecture-rules](./architecture-rules.md)
- [architecture](./architecture.md)
- [auth-examples](./auth-examples.md)
- [authentication](./authentication.md)
- [changelog](./changelog.md)
- [chartjs-datalabels-background-styling](./chartjs-datalabels-background-styling.md)
- [chartjs-datalabels-filament5-implementation](./chartjs-datalabels-filament5-implementation.md)
- [chartjs-datalabels-multiple-labels-complete-guide](./chartjs-datalabels-multiple-labels-complete-guide.md)
- [chartjs-datalabels-theme-integration](./chartjs-datalabels-theme-integration.md)
- [chartjs-export-theme-integration](./chartjs-export-theme-integration.md)
- [chartjs-plugin-datalabels-filament5](./chartjs-plugin-datalabels-filament5.md)
- [code-quality-improvements](./code-quality-improvements.md)
- [code-redundancy-audit](./code-redundancy-audit.md)
- [components](./components.md)
- [comprehensive-theme-analysis](./comprehensive-theme-analysis.md)
- [conflict-resolution-summary](./conflict-resolution-summary.md)
- [conflict-resolution](./conflict-resolution.md)
- [customization](./customization.md)
- [database-governance](./database-governance.md)
- [doc-first-workflow](./doc-first-workflow.md)
- [docs-archive-policy](./docs-archive-policy.md)
- [docs-confidence-audit](./docs-confidence-audit.md)
- [docs-deduplication](./docs-deduplication.md)
- [dry-kiss-analysis](./dry-kiss-analysis.md)
- [dry-kiss-best-practices-historic](./dry-kiss-best-practices-historic.md)
- [dry-kiss-best-practices](./dry-kiss-best-practices.md)
- [dual-label-chart-widget-implementation](./dual-label-chart-widget-implementation.md)
- [duplicate-methods-report](./duplicate-methods-report.md)
- [duplicate-methods](./duplicate-methods.md)
- [duplicate-methods](./duplicate_methods.md)
- [duplicate-methods-report](./duplicate_methods_report.md)
- [env-development-configuration](./env-development-configuration.md)
- [examples](./examples.md)
- [filament-5-nested-resources-complete-guide](./filament-5-nested-resources-complete-guide.md)
- [filament-chart-integration](./filament-chart-integration.md)
- [filament-infolist-pattern](./filament-infolist-pattern.md)
- [filament-resource-schemas-tables](./filament-resource-schemas-tables.md)
- [filament-version](./filament-version.md)
- [index-consolidated](./index-consolidated.md)
- [jpgraph-chartjs-theme-integration](./jpgraph-chartjs-theme-integration.md)
- [jpgraph-class-reference-comprehensive-analysis](./jpgraph-class-reference-comprehensive-analysis.md)
- [jpgraph-integration-guide](./jpgraph-integration-guide.md)
- [laravel-13-composer-boundary](./laravel-13-composer-boundary.md)
- [laravel-13-upgrade](./laravel-13-upgrade.md)
- [launch-plan](./launch-plan.md)
- [layouts](./layouts.md)
- [limesurvey-charts-pdf-integration](./limesurvey-charts-pdf-integration.md)
- [mail-layouts](./mail-layouts.md)
- [manage-related-records](./manage-related-records.md)
- [model-docs-governance](./model-docs-governance.md)
- [model-usage-in-themes](./model-usage-in-themes.md)
- [modern-theme-architecture](./modern-theme-architecture.md)
- [naming-conventions](./naming-conventions.md)
- [no-phpstan-probe-policy](./no-phpstan-probe-policy.md)
- [packages-integration](./packages-integration.md)
- [performance-actions-reference](./performance-actions-reference.md)
- [performance-calcolo-quota-troubleshooting](./performance-calcolo-quota-troubleshooting.md)
- [philosophy](./philosophy.md)
- [php-quality-gates-rule](./php-quality-gates-rule.md)
- [phpstan-compliance-status](./phpstan-compliance-status.md)
- [phpstan-dry-kiss-guidelines](./phpstan-dry-kiss-guidelines.md)
- [phpstan-dry-kiss-theme-guidelines-historic](./phpstan-dry-kiss-theme-guidelines-historic.md)
- [phpstan-dry-kiss-theme-guidelines](./phpstan-dry-kiss-theme-guidelines.md)
- [phpstan-level10-analysis](./phpstan-level10-analysis.md)
- [phpstan-level10-theme-compliance](./phpstan-level10-theme-compliance.md)
- [phpstan-merge-conflicts](./phpstan-merge-conflicts.md)
- [phpstan](./phpstan.md)
- [prd](./prd.md)
- [product-launch-plan](./product-launch-plan.md)
- [product-requirements](./product-requirements.md)
- [product-roadmap](./product-roadmap.md)
- [product-strategy](./product-strategy.md)
- [product-launch-plan](./product_launch_plan.md)
- [product-roadmap](./product_roadmap.md)
- [product-strategy](./product_strategy.md)
- [readonly-field-styling](./readonly-field-styling.md)
- [release-marketing-standard](./release-marketing-standard.md)
- [roadmap](./roadmap.md)
- [schema](./schema.md)
- [schemaless-attributes](./schemaless-attributes.md)
- [second-brain](./second-brain.md)
- [simplechartwidget-problems-analysis](./simplechartwidget-problems-analysis.md)
- [simplechartwidget-quality-analysis](./simplechartwidget-quality-analysis.md)
- [spatie-permission-team-context](./spatie-permission-team-context.md)
- [spatie-permission-teams-boundary](./spatie-permission-teams-boundary.md)
- [sprint-planning-meeting](./sprint-planning-meeting.md)
- [sprint-planning](./sprint-planning.md)
- [sprint-planning](./sprint_planning.md)
- [strategy](./strategy.md)
- [theme-architecture-best-practices](./theme-architecture-best-practices.md)
- [theme-documentation-standard](./theme-documentation-standard.md)
- [theme-documentation](./theme-documentation.md)
- [themes-system-complete-guide](./themes-system-complete-guide.md)
- [troubleshooting](./troubleshooting.md)
- [user-research](./user-research.md)
- [user-research](./user_research.md)

## Root-Md-Files
- [root-md-files/conflict-resolution-summary-relocated](./root-md-files/conflict-resolution-summary-relocated.md)
- [root-md-files/conflict-resolution-summary](./root-md-files/conflict-resolution-summary.md)

## Screenshots
- [screenshots/f1-world-champion-2026-theme-analysis](./screenshots/f1-world-champion-2026-theme-analysis.md)
- [screenshots/f1-world-champion-theme-analysis](./screenshots/f1-world-champion-theme-analysis.md)

## Skills
- [skills/README](./skills/README.md)

## Wiki
- [wiki/SCHEMA](./wiki/schema.md)
- [wiki/bmad-method](./wiki/bmad-method.md)
- [wiki/commands/INDEX](./wiki/commands/index.md)
- [wiki/concepts/INDEX](./wiki/concepts/index.md)
- [wiki/concepts/code-redundancy-theme](./wiki/concepts/code-redundancy-theme.md)
- [wiki/concepts/context-overflow-prevention](./wiki/concepts/context-overflow-prevention.md)
- [wiki/concepts/method-name-homonyms](./wiki/concepts/method-name-homonyms.md)
- [wiki/concepts/module-directory-structure-boundary](./wiki/concepts/module-directory-structure-boundary.md)
- [wiki/concepts/organizzativa-money](./wiki/concepts/organizzativa-money.md)
- [wiki/concepts/php-method-name-homonyms-theme-impact](./wiki/concepts/php-method-name-homonyms-theme-impact.md)
- [wiki/concepts/ponytail-audit](./wiki/concepts/ponytail-audit.md)
- [wiki/concepts/ponytail-docs-lifecycle](./wiki/concepts/ponytail-docs-lifecycle.md)
- [wiki/concepts/second-brain-local-discipline](./wiki/concepts/second-brain-local-discipline.md)
- [wiki/concepts/theme-zero-operating-focus](./wiki/concepts/theme-zero-operating-focus.md)
- [wiki/index](./wiki/index.md)
- [wiki/log](./wiki/log.md)
- [wiki/memories/INDEX](./wiki/memories/index.md)
- [wiki/overview](./wiki/overview.md)
- [wiki/rules/INDEX](./wiki/rules/index.md)
- [wiki/skills/INDEX](./wiki/skills/index.md)
- [wiki/sources/context-compression-and-retrieval](./wiki/sources/context-compression-and-retrieval.md)
- [wiki/sources/laravel13-theme-zero-composer-audit](./wiki/sources/laravel13-theme-zero-composer-audit.md)
- [wiki/sources/theme-zero-product-and-roadmap-docs](./wiki/sources/theme-zero-product-and-roadmap-docs.md)
