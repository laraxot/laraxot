# Indice documentazione — Modulo Employee

Indice per argomento di `Modules/Employee/docs/` (293 file `.md`). Nessun file esistente è stato spostato, rinominato o cancellato: questo indice si limita a collegare e a segnalare i doppioni noti.

Vedi anche [INDEX.md](INDEX.md) (nota rapida preesistente su canoni/duplicati) e [docs-structure.md](docs-structure.md) / [maintenance/docs-structure.md](maintenance/docs-structure.md) per la storia della riorganizzazione.

## Punto di partenza

- [README.md](README.md) — overview del modulo, stack tecnico, regole di compliance
- [getting_started.md](getting_started.md) — installazione e primo avvio
- [philosophy.md](philosophy.md), [philosophy-complete.md](philosophy-complete.md) — filosofia del modulo
- [roadmap.md](roadmap.md) — roadmap funzionale

## Architettura

- [architecture.md](architecture.md), [architecture-patterns.md](architecture-patterns.md) — visione d'insieme
- [architecture/README.md](architecture/README.md)
- [architecture/data_architecture.md](architecture/data_architecture.md)
- [architecture/model_architecture.md](architecture/model_architecture.md)
- [architecture/technical_architecture.md](architecture/technical_architecture.md)
- [architecture/module_structure.md](architecture/module_structure.md)
- [architecture/naming-standards.md](architecture/naming-standards.md)
- [architecture/feature_comparison.md](architecture/feature_comparison.md)
- [architecture/xotbase_extension_rules.md](architecture/xotbase_extension_rules.md) — attenzione: **diverge** dalla variante in root, vedi Storico
- [architectural_rules/no_redundant_static_methods.md](architectural_rules/no_redundant_static_methods.md)
- [concepts/xotbase-never-extend-filament.md](concepts/xotbase-never-extend-filament.md)
- [data-flows.md](data-flows.md) — workflow di timbratura e diagrammi mermaid
- [case-variant-collisions.md](case-variant-collisions.md) — collisioni di path per sola differenza maiuscole/minuscole (rilevante anche per i doppioni elencati sotto)
- [nestedset-migration-best-practices.md](nestedset-migration-best-practices.md)
- [laraxot_actions_pattern.md](laraxot_actions_pattern.md)
- [webmozart-assert-guidelines.md](webmozart-assert-guidelines.md)
- [no-ai-tool-scaffold-dirs.md](no-ai-tool-scaffold-dirs.md)

## Business logic

Cluster con tre alberi paralleli su contenuti correlati ma **non identici** (divergenza reale, non semplice copia): vanno letti e riconciliati, non scelti a caso.

- [business_logic.md](business_logic.md) — indice/overview storico in root
- [business_logic/README.md](business_logic/README.md)
- [business_logic/overview.md](business_logic/overview.md)
- [business_logic/employee_management.md](business_logic/employee_management.md)
- [business_logic/time_tracking.md](business_logic/time_tracking.md)
- [business_logic/security_authorization.md](business_logic/security_authorization.md)
- [business_logic_new/README.md](business_logic_new/README.md)
- [business_logic_new/overview.md](business_logic_new/overview.md) — diverge da `business_logic/overview.md`
- [business_logic_new/employee_management.md](business_logic_new/employee_management.md) — diverge da `business_logic/employee_management.md`
- [business_logic_new/time_tracking.md](business_logic_new/time_tracking.md) — diverge da `business_logic/time_tracking.md`
- [business-logic-overview.md](business-logic-overview.md)
- [business-logic-employee-management.md](business-logic-employee-management.md)
- [business-logic-security.md](business-logic-security.md)
- [business-logic-time-tracking.md](business-logic-time-tracking.md)
- [business-overview.md](business-overview.md)

## Feature e requisiti

- [features/README.md](features/README.md)
- [features/features_specification.md](features/features_specification.md)
- [features/functional_requirements.md](features/functional_requirements.md)
- [features/functional_strategy.md](features/functional_strategy.md)
- [features/work_hour.md](features/work_hour.md)
- [features/workhour_implementation.md](features/workhour_implementation.md)
- [features/time_tracking_widget.md](features/time_tracking_widget.md)
- [features/time-tracking-widget.md](features/time-tracking-widget.md)
- [features/time_clock_widget_enhanced.md](features/time_clock_widget_enhanced.md)

## Sviluppo per dominio funzionale (`development/`)

- [development/README.md](development/README.md)
- Numerati: [01-employee-management.md](development/01-employee-management.md) · [01-gestione-anagrafica-dipendenti.md](development/01-gestione-anagrafica-dipendenti.md) · [01-sistema-timbratura-presenze.md](development/01-sistema-timbratura-presenze.md) · [02-gestione-dipartimenti.md](development/02-gestione-dipartimenti.md) · [02-gestione-presenze.md](development/02-gestione-presenze.md) · [02-gestione-presenze-assenze.md](development/02-gestione-presenze-assenze.md) · [02-organizational-management.md](development/02-organizational-management.md) · [02-time-tracking.md](development/02-time-tracking.md) · [03-attendance-management.md](development/03-attendance-management.md) · [03-gestione-ferie.md](development/03-gestione-ferie.md) · [03-gestione-ferie-permessi.md](development/03-gestione-ferie-permessi.md) · [03-gestione-posizioni.md](development/03-gestione-posizioni.md) · [04-gestione-presenze.md](development/04-gestione-presenze.md) · [04-gestione-turni-lavoro.md](development/04-gestione-turni-lavoro.md) · [04-leave-management.md](development/04-leave-management.md) · [05-document-management.md](development/05-document-management.md) · [05-gestione-buste-paga-documenti.md](development/05-gestione-buste-paga-documenti.md) · [06-contract-management.md](development/06-contract-management.md) · [06-sistema-note-spese-rimborsi.md](development/06-sistema-note-spese-rimborsi.md) · [07-bacheca-digitale-comunicazioni.md](development/07-bacheca-digitale-comunicazioni.md) · [08-dashboard-analytics-reporting.md](development/08-dashboard-analytics-reporting.md) · [09-sistema-ruoli-autorizzazioni.md](development/09-sistema-ruoli-autorizzazioni.md) · [10-app-mobile-pwa.md](development/10-app-mobile-pwa.md) · [11-integrazione-consulenti-lavoro.md](development/11-integrazione-consulenti-lavoro.md)
- Sotto-domini con README dedicato: [01-anagrafica-dipendenti/README.md](development/01-anagrafica-dipendenti/README.md) · [communication/README.md](development/communication/README.md) · [document-management/README.md](development/document-management/README.md) · [employee-management/README.md](development/employee-management/README.md) · [integrations/README.md](development/integrations/README.md) · [leave-management/README.md](development/leave-management/README.md) · [mobile/README.md](development/mobile/README.md) · [organizational/README.md](development/organizational/README.md) · [reporting/README.md](development/reporting/README.md) · [security/README.md](development/security/README.md) · [time-tracking/README.md](development/time-tracking/README.md)
- Reporting: [reporting/dashboard_hr_widgets_implementation.md](development/reporting/dashboard_hr_widgets_implementation.md) · [reporting/dashboard-widgets-specification.md](development/reporting/dashboard-widgets-specification.md) · [reporting/filament_widgets.md](development/reporting/filament_widgets.md)
- Time tracking / timbrature (versione corrente, sotto `time-tracking/`): [time-tracking/timbrature/README.md](development/time-tracking/timbrature/README.md) · [time-tracking/timbrature/01-migration-time-entries.md](development/time-tracking/timbrature/01-migration-time-entries.md) · [time-tracking/timbrature/models/attendance.php.md](development/time-tracking/timbrature/models/attendance.php.md) · [time-tracking/timbrature/migrations/create_attendances_table.php.md](development/time-tracking/timbrature/migrations/create_attendances_table.php.md)
- Timbrature (versione precedente, flat sotto `development/`, contenuto duplicato in `time-tracking/timbrature/`): [timbrature/README.md](development/timbrature/README.md) · [timbrature/01-migration-time-entries.md](development/timbrature/01-migration-time-entries.md) · [timbrature/models/attendance.php.md](development/timbrature/models/attendance.php.md)
- Trasversali: [english_naming_standards.md](development/english_naming_standards.md) · [filament3_widget_patterns.md](development/filament3_widget_patterns.md) · [phpstan-corrections-summary.md](development/phpstan-corrections-summary.md) · [xotbase_widget_checklist.md](development/xotbase_widget_checklist.md)

## Implementazione

- [implementation/README.md](implementation/README.md)
- [implementation/implementation_plan.md](implementation/implementation_plan.md), [implementation/implementation-results.md](implementation/implementation-results.md)
- [implementation/module_setup_guide.md](implementation/module_setup_guide.md), [implementation/module_setup_implementation.md](implementation/module_setup_implementation.md)
- [implementation/configuration.md](implementation/configuration.md)
- [implementation/technical_implementation.md](implementation/technical_implementation.md), [implementation/technical_implementation_guide.md](implementation/technical_implementation_guide.md)
- [implementation/workflows_and_best_practices.md](implementation/workflows_and_best_practices.md)
- [implementation/custom_icon_implementation.md](implementation/custom_icon_implementation.md)
- [implementation/svg_icon_standards.md](implementation/svg_icon_standards.md), [implementation/svg-icon-system.md](implementation/svg-icon-system.md), [implementation/svg-icon-system-complete.md](implementation/svg-icon-system-complete.md), [implementation/unicode_arrows_vs_heroicons.md](implementation/unicode_arrows_vs_heroicons.md)
- PHPStan Level 10: [implementation/phpstan-level-10-analysis-and-recommendation.md](implementation/phpstan-level-10-analysis-and-recommendation.md) · [implementation/phpstan-level-10-comprehensive-strategy.md](implementation/phpstan-level-10-comprehensive-strategy.md) · [implementation/phpstan-level-10-fixes.md](implementation/phpstan-level-10-fixes.md) · [implementation/phpstan-level-10-complete-implementation.md](implementation/phpstan-level-10-complete-implementation.md)
- Time clock widget (implementazione): [implementation/time_clock_widget_badge_implementation.md](implementation/time_clock_widget_badge_implementation.md) · [implementation/time_clock_widget_final.md](implementation/time_clock_widget_final.md) · [implementation/time_clock_widget_final_implementation.md](implementation/time_clock_widget_final_implementation.md) · [implementation/time_clock_widget_flex_layout_lessons.md](implementation/time_clock_widget_flex_layout_lessons.md) · [implementation/time_clock_widget_perfect_solution.md](implementation/time_clock_widget_perfect_solution.md) · [implementation/time_clock_widget_success_report.md](implementation/time_clock_widget_success_report.md) · [implementation/time_tracking_widget_completed.md](implementation/time_tracking_widget_completed.md)
- [implementation_summary.md](implementation_summary.md) — riassunto in root

## Manutenzione e PHPStan

- [maintenance/README.md](maintenance/README.md)
- [maintenance/phpstan-fixes.md](maintenance/phpstan-fixes.md), [maintenance/phpstan-eloquent-relations-fix.md](maintenance/phpstan-eloquent-relations-fix.md), [maintenance/phpstan_covariance_issues.md](maintenance/phpstan_covariance_issues.md), [maintenance/phpstan_remaining_errors.md](maintenance/phpstan_remaining_errors.md)
- [maintenance/xotbase-method-visibility-errors.md](maintenance/xotbase-method-visibility-errors.md)
- [maintenance/enum_migration_guide.md](maintenance/enum_migration_guide.md), [maintenance/workhour_enum_implementation.md](maintenance/workhour_enum_implementation.md)
- [maintenance/foreign_key_constraint_fix.md](maintenance/foreign_key_constraint_fix.md)
- [maintenance/corrections-made.md](maintenance/corrections-made.md), [maintenance/docs-structure.md](maintenance/docs-structure.md)
- Cluster PHPStan/qualità in root (stato avanzamento storico, non ancora consolidato in `maintenance/`): [phpstan-compliance.md](phpstan-compliance.md) · [phpstan-compliance-status.md](phpstan-compliance-status.md) · [phpstan-corrections.md](phpstan-corrections.md) · [phpstan-corrections-summary-final.md](phpstan-corrections-summary-final.md) · [phpstan-errors-resolution-roadmap.md](phpstan-errors-resolution-roadmap.md) · [phpstan-final-success-report.md](phpstan-final-success-report.md) · [phpstan_level10_compliance_plan.md](phpstan_level10_compliance_plan.md) · [phpstan-level10-errors-analysis.md](phpstan-level10-errors-analysis.md) · [phpstan-level10-execution-plan.md](phpstan-level10-execution-plan.md) · [phpstan-level10-fixes.md](phpstan-level10-fixes.md) · [phpstan-module-config-env.md](phpstan-module-config-env.md)
- [code-errors-analysis.md](code-errors-analysis.md), [code-quality-improvement-report.md](code-quality-improvement-report.md), [code-quality-report.md](code-quality-report.md), [error_analysis.md](error_analysis.md)
- [employee-module-all-errors-complete.md](employee-module-all-errors-complete.md), [employee-module-corrections-completed.md](employee-module-corrections-completed.md), [employee-module-critical-fixes.md](employee-module-critical-fixes.md), [employee-module-optimizations.md](employee-module-optimizations.md)
- [git-conflicts-resolution-summary.md](git-conflicts-resolution-summary.md)
- [constants-to-enum-migration.md](constants-to-enum-migration.md), [model-migration-seeder-coverage.md](model-migration-seeder-coverage.md)
- [reports/phpstan_level10_fixes_log.md](reports/phpstan_level10_fixes_log.md)
- [session-summary.md](session-summary.md), [session-summary-phpstan-fixes.md](session-summary-phpstan-fixes.md)

## Modelli dati

- [models/employee_models_reference.md](models/employee_models_reference.md)
- [models/time_entry_status_refactoring.md](models/time_entry_status_refactoring.md)
- [models/timeentry_refactoring_plan.md](models/timeentry_refactoring_plan.md), [models/timeentry_refactoring_completed.md](models/timeentry_refactoring_completed.md)
- [models/timeentry.md](models/timeentry.md) — vedi Storico per le varianti duplicate

## Pagine Filament

- [pages/README.md](pages/README.md)
- [pages/dashboard.md](pages/dashboard.md)
- [pages/workhours_page.md](pages/workhours_page.md), [pages/work_hours_page_corrected_analysis.md](pages/work_hours_page_corrected_analysis.md)

## Widget dashboard

- [widgets/README.md](widgets/README.md)
- [widgets/timebalancewidget.md](widgets/timebalancewidget.md), [widgets/le_mie_rimanenze_di_settembre_widget.md](widgets/le_mie_rimanenze_di_settembre_widget.md)
- [widgets/todo-widget-documentation.md](widgets/todo-widget-documentation.md), [widgets/taskswidget.md](widgets/taskswidget.md), [widgets/cose_da_fare_widget.md](widgets/cose_da_fare_widget.md)
- [widgets/upcoming-schedule-widget-documentation.md](widgets/upcoming-schedule-widget-documentation.md), [widgets/upcomingschedulewidget.md](widgets/upcomingschedulewidget.md), [widgets/prossimi_7_giorni_widget.md](widgets/prossimi_7_giorni_widget.md)
- [widgets/pending-requests-widget-documentation.md](widgets/pending-requests-widget-documentation.md), [widgets/pendingrequestswidget.md](widgets/pendingrequestswidget.md), [widgets/le_mie_richieste_in_attesa_widget.md](widgets/le_mie_richieste_in_attesa_widget.md)
- [widgets/time-off-balance-widget-documentation.md](widgets/time-off-balance-widget-documentation.md)
- [widgets/today-presence-widget-documentation.md](widgets/today-presence-widget-documentation.md), [widgets/whoisintodaywidget.md](widgets/whoisintodaywidget.md), [widgets/chi_ce_oggi_widget.md](widgets/chi_ce_oggi_widget.md)
- [widgets/gestione_orari_dipendenti.md](widgets/gestione_orari_dipendenti.md), [widgets/work_hours_board_widget.md](widgets/work_hours_board_widget.md), [widgets/work_hours_page.md](widgets/work_hours_page.md), [widgets/workhours_page_analysis.md](widgets/workhours_page_analysis.md), [widgets/weekly_time_table_widget_analysis.md](widgets/weekly_time_table_widget_analysis.md)
- [widgets/filament-widget-3-column-best-practices.md](widgets/filament-widget-3-column-best-practices.md)
- TimeClock widget (dettaglio implementazione/troubleshooting): [widgets/timeclock-widget-final-implementation.md](widgets/timeclock-widget-final-implementation.md) · [widgets/timeclock-widget-implementation-summary.md](widgets/timeclock-widget-implementation-summary.md) · [widgets/timeclock-widget-layout-troubleshooting.md](widgets/timeclock-widget-layout-troubleshooting.md) · [widgets/timeclock-widget-ui-ux-improvements.md](widgets/timeclock-widget-ui-ux-improvements.md)
- TimeClock widget (root, stessa famiglia di problemi/soluzioni ma file non duplicati byte-a-byte): [timeclock_widget_master.md](timeclock_widget_master.md) — indice della famiglia · [timeclock-widget-layout-fix.md](timeclock-widget-layout-fix.md) · [timeclock-widget-refactoring-final.md](timeclock-widget-refactoring-final.md) · [timeclock-widget-variable-error.md](timeclock-widget-variable-error.md)

## Testing

- [testing/pest-testing-guide.md](testing/pest-testing-guide.md)
- [testing-rules.md](testing-rules.md)

## Analisi e UI

- [analysis/README.md](analysis/README.md)
- [analysis/modules_overview.md](analysis/modules_overview.md)
- [analysis/dipendentincloud_analysis.md](analysis/dipendentincloud_analysis.md), [analysis/language_best_practices.md](analysis/language_best_practices.md)
- [ui-analysis-dipendentincloud.md](ui-analysis-dipendentincloud.md)
- [time_entries_page_analysis.md](time_entries_page_analysis.md), [time_entries_page_plan.md](time_entries_page_plan.md)

## Refactoring e riepiloghi

- [refactoring/timeentry-refactoring-summary.md](refactoring/timeentry-refactoring-summary.md)
- [reports/time_entry_refactoring_summary.md](reports/time_entry_refactoring_summary.md)
- [refactoring_master_plan.md](refactoring_master_plan.md)
- [docs_refactoring_completed.md](docs_refactoring_completed.md), [docs-refactoring-plan.md](docs-refactoring-plan.md)
- [integration-apis.md](integration-apis.md)

## Story BMAD

- [stories/7.1.phpstan-paramtype-coverage.story.md](stories/7.1.phpstan-paramtype-coverage.story.md)
- [stories/docs-index-audit.story.md](stories/docs-index-audit.story.md) — story di questo audit

## Wiki interno (second brain di modulo)

- [wiki/index.md](wiki/index.md), [wiki/log.md](wiki/log.md)
- [wiki/concepts/index.md](wiki/concepts/index.md), [wiki/memories/index.md](wiki/memories/index.md), [wiki/rules/index.md](wiki/rules/index.md), [wiki/skills/index.md](wiki/skills/index.md)

---

## Storico / da consolidare

File non cancellati né rinominati (per vincolo del task), raggruppati perché duplicati o superati. Chi consoliderà in futuro deve leggere prima di rimuovere.

### Duplicati esatti (byte-identici, verificati con `md5sum`)

- **`things-to-develop/` = copia esatta di `development/`**: tutti i file numerati 01–11, tutti i `README.md` di sotto-dominio, `things-to-develop/timbrature/*` (incluso `things-to-develop/timbrature/models/Attendance.php.md` vs `attendance.php.md`, ulteriore variante di sola maiuscola). L'intero albero `things-to-develop/` è ridondante rispetto a `development/`.
- Coppie root ↔ sottocartella, contenuto identico: `dipendentincloud_analysis.md` ↔ `analysis/dipendentincloud_analysis.md`; `language_best_practices.md` ↔ `analysis/language_best_practices.md`; `data_architecture.md` ↔ `architecture/data_architecture.md`; `model_architecture.md` ↔ `architecture/model_architecture.md`; `technical_architecture.md` ↔ `architecture/technical_architecture.md`; `module_structure.md` ↔ `architecture/module_structure.md`; `naming-standards.md` ↔ `architecture/naming-standards.md`; `feature_comparison.md` ↔ `architecture/feature_comparison.md`; `features_specification.md` ↔ `features/features_specification.md`; `functional_requirements.md` ↔ `features/functional_requirements.md`; `functional_strategy.md` ↔ `features/functional_strategy.md`; `work_hour.md` ↔ `features/work_hour.md`; `workhour_implementation.md` ↔ `features/workhour_implementation.md`; `implementation_plan.md` ↔ `implementation/implementation_plan.md`; `module_setup_guide.md` ↔ `implementation/module_setup_guide.md`; `module_setup_implementation.md` ↔ `implementation/module_setup_implementation.md`; `configuration.md` ↔ `implementation/configuration.md`; `technical_implementation.md` ↔ `implementation/technical_implementation.md`; `technical_implementation_guide.md` ↔ `implementation/technical_implementation_guide.md`; `workflows_and_best_practices.md` ↔ `implementation/workflows_and_best_practices.md`; `custom_icon_implementation.md` ↔ `implementation/custom_icon_implementation.md`; `svg_icon_standards.md` ↔ `implementation/svg_icon_standards.md`; `svg-icon-system.md` ↔ `implementation/svg-icon-system.md`; `svg-icon-system-complete.md` ↔ `implementation/svg-icon-system-complete.md`; `corrections-made.md` ↔ `maintenance/corrections-made.md`; `docs-structure.md` ↔ `maintenance/docs-structure.md`; `phpstan-fixes.md` ↔ `maintenance/phpstan-fixes.md`; `phpstan-eloquent-relations-fix.md` ↔ `maintenance/phpstan-eloquent-relations-fix.md`; `phpstan_covariance_issues.md` ↔ `maintenance/phpstan_covariance_issues.md`; `xotbase-method-visibility-errors.md` ↔ `maintenance/xotbase-method-visibility-errors.md`.
- `models/timeentry.md` = `models/TimeEntry.md` = `models/time-entry.md` — tre varianti (case + separatore) dello stesso contenuto. Collisione case-sensitive pericolosa su filesystem non case-sensitive: vedi [case-variant-collisions.md](case-variant-collisions.md).
- `phpmd-fixes.md` = `phpmd-fixes-2025-12-30.md` — stesso contenuto, variante col suffisso data (viola anche la regola "niente date nei filename").
- `wiki/concepts/index.md` = `wiki/concepts/INDEX.md`; `wiki/memories/index.md` = `wiki/memories/INDEX.md`; `wiki/rules/index.md` = `wiki/rules/INDEX.md`; `wiki/skills/index.md` = `wiki/skills/INDEX.md` — quattro coppie case-variant identiche nel wiki interno.
- `development/timbrature/01-migration-time-entries.md` = `development/time-tracking/timbrature/01-migration-time-entries.md` = `things-to-develop/timbrature/01-migration-time-entries.md`; `development/timbrature/models/attendance.php.md` = `development/time-tracking/timbrature/models/attendance.php.md` — `development/timbrature/` (flat) risulta superato da `development/time-tracking/timbrature/` (naming inglese, versione più recente).

### Bozza `refactored/` (quasi-duplicato di README/getting_started in root)

- `refactored/README.md` — quasi identico a [README.md](README.md) (285 vs 281 righe, testo diverso in alcuni punti: verificare quale versione è più aggiornata prima di consolidare)
- `refactored/getting_started.md` — bozza precedente di [getting_started.md](getting_started.md) (236 vs 285 righe)

### Contenuto divergente sotto lo stesso nome logico (da leggere e riconciliare a mano, non scegliere a caso)

- `xotbase_extension_rules.md` (root) vs `architecture/xotbase_extension_rules.md` — stesso argomento, testo diverso.
- `business_logic/overview.md` vs `business_logic_new/overview.md`
- `business_logic/employee_management.md` vs `business_logic_new/employee_management.md`
- `business_logic/time_tracking.md` vs `business_logic_new/time_tracking.md`
- L'intero cluster "business logic" (`business_logic.md`, `business_logic/`, `business_logic_new/`, `business-logic-*.md` in root, `business-overview.md`) è frammentato su tre alberi paralleli: nessuno dei tre è marcato come canonico nei file stessi.

## Canoni dichiarati (da INDEX.md preesistente)

Per la sessione corrente restano validi i canoni indicati in [INDEX.md](INDEX.md): `README.md`, `architecture.md`, `rules-index.md` (quest'ultimo non presente come file distinto in questo albero — verificare in una sessione dedicata). Varianti `*.variant.md`, `*.sumy.md`, `*-variant-*.md`, `*.archive-*.md` non sono presenti in questo modulo al momento dell'audit (2026-09-03).
