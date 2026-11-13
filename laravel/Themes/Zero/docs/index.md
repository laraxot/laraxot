---
id: zero-docs-index
slug: index
title: "Indice della Documentazione - Tema Zero"
description: "00-index.md (canonico, aggiornato 2026-03-28). Usare 00-index.md come riferimento"
document_type: index
type: index
category: documentation
status: superseded
tags: []
created: "2026-09-24"
updated: "2026-10-08"
qmd: "indice della documentazione - tema zero"
issues: []
discussions: []
superseded_by: README.md
---

# Indice della Documentazione - Tema Zero

> **Nota 2026-07-24**: indice storico senza frontmatter, ridondante rispetto a
> [00-index.md](./bmad/00-index.md) (canonico, aggiornato 2026-03-28). Usare `00-index.md` come riferimento
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
- [AI Methodologies](./wiki/how-to/ai-methodologies.md)
- [Documentazione Generale Progetto](../../../docs/README.md) (docs: replace project-specific references with generic placeholders across documentation)
- [Collegamenti Documentazione](../../../docs/collegamenti-documentazione.md)
- [Standard di Documentazione](../../../docs/DOCUMENTATION_STANDARDS.md)
- [Modulo UI](../../Modules/UI/docs/README.md)
- [Modulo Xot](../../Modules/Xot/docs/README.md)

### Moduli Integrati
- [Performance Actions Reference](./bmad/performance-actions-reference.md) - Riferimento action calcolo performance

## Categorie Principali

### Architettura e Struttura
- [README](./README.md) - Panoramica generale del tema
- [Architettura](./bmad/architecture.md) - Architettura generale del tema
- [Struttura](./bmad/layouts.md) - Struttura delle directory e dei layout
- [Componenti](./bmad/components.md) - Componenti Blade disponibili

### Personalizzazione
- [Personalizzazione](./bmad/customization.md) - Guida alla personalizzazione del tema
- [Readonly Field Styling](./bmad/readonly-field-styling.md) - Pattern UI/UX per campi readonly/calcolati
- [Esempi](./bmad/examples.md) - Esempi pratici di personalizzazione
- [Autenticazione](./bmad/authentication.md) - Componenti di autenticazione
- [Esempi Autenticazione](./bmad/auth-examples.md) - Esempi di pagine di autenticazione

### Sviluppo e Configurazione
- [Configurazione](./configuration.md) - Configurazione del tema
- [Compilazione Assets](./asset-compilation.md) - Guida alla compilazione degli assets
- [TailwindCSS](./tailwind.md) - Configurazione e personalizzazione Tailwind
- [Vite](./vite.md) - Configurazione e ottimizzazione Vite

### Traduzioni
- [Sistema Traduzioni](./bmad/translations.md) - Sistema di traduzioni del tema
- [File Lingua](./language-files.md) - Gestione dei file di traduzione
- [Localizzazione](./localization.md) - Localizzazione del tema

### Testing e Qualità
- [Testing](./testing.md) - Strategie e approcci per il testing del tema
- [ide-helper-phpdoc-boundary](./bmad/ide-helper-phpdoc-boundary.md) - Confine PHPDoc moduli ↔ tema
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
- [Panoramica Architettura](./bmad/architecture.md)
- [Personalizzazione](./bmad/customization.md)
- [Componenti](./bmad/components.md)
- [Esempi](./bmad/examples.md)
- [Troubleshooting](./bmad/troubleshooting.md)

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

> **Note 2026-07-24**: this index is redundant with [00-index.md](./bmad/00-index.md) (canonical, updated
> 2026-03-28, aligned with current stack: Filament 5, Livewire 4, Volt, Tailwind v4). Kept only for the
> `archive/duplicates` links below which are not referenced elsewhere.

## Archive
- [archive/duplicates/conflict-resolution-summary](./bmad/conflict-resolution-summary.md)

## Legacy
- [legacy/duplicates/conflict-resolution-summary](./bmad/conflict-resolution-summary.md)

## Outputs
- [outputs/README](./outputs/README.md)

## Raw
- [raw/README](./raw/README.md)

## Roadmap
- [roadmap/accessibility-standards](./wiki/rules/accessibility-standards.md)
- [roadmap/advanced-features](./wiki/overviews/advanced-features.md)
- [roadmap/component-library](./wiki/concepts/component-library.md)
- [roadmap/performance-optimization](./wiki/rules/performance-optimization.md)
- [roadmap/responsive-system](./wiki/concepts/responsive-system.md)
- [roadmap/theme-customization](./wiki/how-to/theme-customization.md)

## Root
- [CHANGELOG](./changelog.md)
- [CONFLICT-RESOLUTION-SUMMARY](./bmad/conflict-resolution-summary.md)
- [CONFLICT-RESOLUTION-SUMMARY](./bmad/conflict-resolution-summary.md)
- [METODI-DUPLICATI-ANALISI](./bmad/metodi-duplicati-analisi.md)
- [METODI-DUPLICATI-ANALISI](./bmad/metodi-duplicati-analisi.md)
- [accessor-delegation-pattern](./bmad/accessor-delegation-pattern.md)
- [agent-confidence-discipline](./wiki/rules/agent-confidence-discipline.md)
- [agent-confidence-protocol](./wiki/rules/agent-confidence-protocol.md)
- [agent-edit-discipline](./wiki/rules/agent-edit-discipline.md)
- [ai-development-guide](./bmad/ai-development-guide.md)
- [ai-handoff](./wiki/concepts/ai-handoff.md)
- [ai-methodologies](./wiki/how-to/ai-methodologies.md)
- [analisi-completa-tema](./analisi-completa-tema.md)
- [architecture-rules](./wiki/rules/architecture-rules.md)
- [architecture](./bmad/architecture.md)
- [auth-examples](./bmad/auth-examples.md)
- [authentication](./bmad/authentication.md)
- [changelog](./changelog.md)
- [chartjs-datalabels-background-styling](./bmad/chartjs-datalabels-background-styling.md)
- [chartjs-datalabels-filament5-implementation](./bmad/chartjs-datalabels-filament5-implementation.md)
- [chartjs-datalabels-multiple-labels-complete-guide](./bmad/chartjs-datalabels-multiple-labels-complete-guide.md)
- [chartjs-datalabels-theme-integration](./bmad/chartjs-datalabels-theme-integration.md)
- [chartjs-export-theme-integration](./bmad/chartjs-export-theme-integration.md)
- [chartjs-plugin-datalabels-filament5](./bmad/chartjs-plugin-datalabels-filament5.md)
- [code-quality-improvements](./bmad/code-quality-improvements.md)
- [code-redundancy-audit](./bmad/code-redundancy-audit.md)
- [components](./bmad/components.md)
- [comprehensive-theme-analysis](./bmad/comprehensive-theme-analysis.md)
- [conflict-resolution-summary](./bmad/conflict-resolution-summary.md)
- [conflict-resolution](./bmad/conflict-resolution.md)
- [customization](./bmad/customization.md)
- [database-governance](./bmad/database-governance.md)
- [doc-first-workflow](./bmad/doc-first-workflow.md)
- [docs-archive-policy](./bmad/docs-archive-policy.md)
- [docs-confidence-audit](./bmad/docs-confidence-audit.md)
- [docs-deduplication](./bmad/docs-deduplication.md)
- [dry-kiss-analysis](./bmad/dry-kiss-analysis.md)
- [dry-kiss-best-practices-historic](./dry-kiss-best-practices-historic.md)
- [dry-kiss-best-practices](./bmad/dry-kiss-best-practices.md)
- [dual-label-chart-widget-implementation](./dual-label-chart-widget-implementation.md)
- [duplicate-methods-report](./bmad/duplicate-methods-report.md)
- [duplicate-methods](./bmad/duplicate-methods.md)
- [duplicate-methods](./bmad/duplicate-methods.md)
- [duplicate-methods-report](./bmad/duplicate-methods-report.md)
- [env-development-configuration](./bmad/env-development-configuration.md)
- [examples](./bmad/examples.md)
- [filament-5-nested-resources-complete-guide](./bmad/filament-5-nested-resources-complete-guide.md)
- [filament-chart-integration](./bmad/filament-chart-integration.md)
- [filament-infolist-pattern](./bmad/filament-infolist-pattern.md)
- [filament-resource-schemas-tables](./bmad/filament-resource-schemas-tables.md)
- [filament-version](./bmad/filament-version.md)
- [index-consolidated](./index-consolidated.md)
- [jpgraph-chartjs-theme-integration](./bmad/jpgraph-chartjs-theme-integration.md)
- [jpgraph-class-reference-comprehensive-analysis](./bmad/jpgraph-class-reference-comprehensive-analysis.md)
- [jpgraph-integration-guide](./bmad/jpgraph-integration-guide.md)
- [laravel-13-composer-boundary](./bmad/laravel-13-composer-boundary.md)
- [laravel-13-upgrade](./bmad/laravel-13-upgrade.md)
- [launch-plan](./bmad/launch-plan.md)
- [layouts](./bmad/layouts.md)
- [limesurvey-charts-pdf-integration](./bmad/limesurvey-charts-pdf-integration.md)
- [mail-layouts](./bmad/mail-layouts.md)
- [manage-related-records](./bmad/manage-related-records.md)
- [model-docs-governance](./bmad/model-docs-governance.md)
- [model-usage-in-themes](./bmad/model-usage-in-themes.md)
- [modern-theme-architecture](./bmad/modern-theme-architecture.md)
- [naming-conventions](./bmad/naming-conventions.md)
- [no-phpstan-probe-policy](./bmad/no-phpstan-probe-policy.md)
- [packages-integration](./bmad/packages-integration.md)
- [performance-actions-reference](./bmad/performance-actions-reference.md)
- [performance-calcolo-quota-troubleshooting](./bmad/performance-calcolo-quota-troubleshooting.md)
- [philosophy](./bmad/philosophy.md)
- [php-quality-gates-rule](./bmad/php-quality-gates-rule.md)
- [phpstan-compliance-status](./bmad/phpstan-compliance-status.md)
- [phpstan-dry-kiss-guidelines](./phpstan-dry-kiss-guidelines.md)
- [phpstan-dry-kiss-theme-guidelines-historic](./phpstan-dry-kiss-theme-guidelines-historic.md)
- [phpstan-dry-kiss-theme-guidelines](./bmad/phpstan-dry-kiss-theme-guidelines.md)
- [phpstan-level10-analysis](./bmad/phpstan-level10-analysis.md)
- [phpstan-level10-theme-compliance](./bmad/phpstan-level10-theme-compliance.md)
- [phpstan-merge-conflicts](./bmad/phpstan-merge-conflicts.md)
- [phpstan](./bmad/phpstan.md)
- [prd](./bmad/prd.md)
- [product-launch-plan](./bmad/product-launch-plan.md)
- [product-requirements](./bmad/product-requirements.md)
- [product-roadmap](./bmad/product-roadmap.md)
- [product-strategy](./bmad/product-strategy.md)
- [product-launch-plan](./bmad/product-launch-plan.md)
- [product-roadmap](./bmad/product-roadmap.md)
- [product-strategy](./bmad/product-strategy.md)
- [readonly-field-styling](./bmad/readonly-field-styling.md)
- [release-marketing-standard](./bmad/release-marketing-standard.md)
- [roadmap](./bmad/roadmap.md)
- [schema](./schema.md)
- [schemaless-attributes](./bmad/schemaless-attributes.md)
- [second-brain](./bmad/second-brain.md)
- [simplechartwidget-problems-analysis](./bmad/simplechartwidget-problems-analysis.md)
- [simplechartwidget-quality-analysis](./bmad/simplechartwidget-quality-analysis.md)
- [spatie-permission-team-context](./bmad/spatie-permission-team-context.md)
- [spatie-permission-teams-boundary](./bmad/spatie-permission-teams-boundary.md)
- [sprint-planning-meeting](./bmad/sprint-planning-meeting.md)
- [sprint-planning](./bmad/sprint-planning.md)
- [sprint-planning](./bmad/sprint-planning.md)
- [strategy](./bmad/strategy.md)
- [theme-architecture-best-practices](./bmad/theme-architecture-best-practices.md)
- [theme-documentation-standard](./bmad/theme-documentation-standard.md)
- [theme-documentation](./bmad/theme-documentation.md)
- [themes-system-complete-guide](./bmad/themes-system-complete-guide.md)
- [troubleshooting](./bmad/troubleshooting.md)
- [user-research](./bmad/user-research.md)
- [user-research](./bmad/user-research.md)

## Root-Md-Files
- [root-md-files/conflict-resolution-summary-relocated](./root-md-files/conflict-resolution-summary-relocated.md)
- [root-md-files/conflict-resolution-summary](./bmad/conflict-resolution-summary.md)

## Screenshots
- [screenshots/f1-world-champion-2026-theme-analysis](./wiki/comparisons/f1-world-champion-2026-theme-analysis.md)
- [screenshots/f1-world-champion-theme-analysis](./wiki/comparisons/f1-world-champion-theme-analysis.md)

## Skills
- [skills/README](./skills/README.md)

## Wiki
- [wiki/SCHEMA](./wiki/schema.md)
- [wiki/bmad-method](./wiki/concepts/bmad-method.md)
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
- [wiki/overview](./wiki/overviews/overview.md)
- [wiki/rules/INDEX](./wiki/rules/index.md)
- [wiki/skills/INDEX](./wiki/skills/index.md)
- [wiki/sources/context-compression-and-retrieval](./wiki/sources/context-compression-and-retrieval.md)
- [wiki/sources/laravel13-theme-zero-composer-audit](./wiki/sources/laravel13-theme-zero-composer-audit.md)
- [wiki/sources/theme-zero-product-and-roadmap-docs](./wiki/sources/theme-zero-product-and-roadmap-docs.md)
---
title: "Zero Theme — Documentation Index"
type: index
status: superseded
canonical: ./00-index.md
---

# Indice (puntatore — vietato duplicare)

SSoT: [00-index.md](./bmad/00-index.md).

Questo file era un indice storico duplicato (648 righe, marker di conflitto mai
risolti tra due varianti in italiano). La nota nella versione HEAD lo dichiarava
già ridondante rispetto a `00-index.md` (canonico). Consolidato qui come
puntatore per non rompere i link esistenti.
