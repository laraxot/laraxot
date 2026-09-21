# Indice Documentazione - Tema Sixteen

Indice unico e canonico della documentazione sotto `Themes/Sixteen/docs/` (1436 file `.md` complessivi, 412 direttamente in questa cartella). Sostituisce, come punto di ingresso, i precedenti tentativi di indice root (`INDEX.md`, `00-index.md`, `00-INDEX.md`, `DOCUMENTATION_INDEX.md`, `MASTER_DOCUMENTATION.md`), che restano in repo e sono elencati in [Storico / da consolidare](#storico--da-consolidare) senza essere stati cancellati o rinominati.

## Come e organizzato questo indice

- I 412 file `.md` alla radice di `docs/` sono raggruppati per argomento e linkati singolarmente.
- Le sottocartelle piccole (poche unita di file) sono elencate per intero nella sezione di argomento pertinente.
- Le sottocartelle grandi che contengono artefatti generati (screenshot, diff HTML, report per singola pagina, una cartella per slug) sono descritte come pattern con link alla cartella e ai relativi indici/README locali, invece di elencare ogni singolo file: replicare qui tutti i file interni avrebbe prodotto centinaia di link quasi identici (`comparison.md`, `report.md`, `diff.md`, ecc.) senza reale valore di navigazione. Vedi la sezione [Cantieri di confronto HTML/CSS e parity visiva](#cantieri-di-confronto-htmlcss-e-parity-visiva).
- Nessun file esistente e stato rinominato, spostato o cancellato per produrre questo indice.

## Documenti essenziali

- [ai-handoff.md](ai-handoff.md)
- [bmad-method.md](bmad-method.md)
- [critical-rules.md](critical-rules.md)
- [launch.md](launch.md)
- [PROJECT-STRUCTURE.md](PROJECT-STRUCTURE.md)
- [QUICK_REFERENCE.md](QUICK_REFERENCE.md)
- [readme-en.md](readme-en.md)
- [README-HOMEPAGE-REPLICATION.md](README-HOMEPAGE-REPLICATION.md)
- [README.md](README.md)
- [repo.md](repo.md)
- [repositories.md](repositories.md)
- [START_HERE.md](START_HERE.md)

## Second brain interno (wiki/, llm-wiki/)

- [wiki/index.md](wiki/index.md) - indice del wiki tematico interno del tema (concetti, regole, memorie, troubleshooting, comparazioni)
- [wiki/README.md](wiki/README.md)
- [wiki/AGENTS.md](wiki/AGENTS.md) / [wiki/agents.md](wiki/agents.md) - variante di case, vedi Storico
- Sottocartelle principali del wiki (con propri indici dove presenti): [wiki/concepts/INDEX.md](wiki/concepts/INDEX.md) (169 file), [wiki/rules/INDEX.md](wiki/rules/INDEX.md) (13 file), [wiki/commands/INDEX.md](wiki/commands/INDEX.md), [wiki/skills/INDEX.md](wiki/skills/INDEX.md), [wiki/memories/INDEX.md](wiki/memories/INDEX.md), wiki/comparisons/ (14 file), wiki/troubleshooting/ (con [README](wiki/troubleshooting/README.md)), wiki/stories/, wiki/overviews/, wiki/entities/, wiki/design/, wiki/governance/, wiki/how-to/, wiki/improvements/, wiki/redundancy/, wiki/sources/, wiki/_templates/
- [llm-wiki/index.md](llm-wiki/index.md) - struttura parallela a `wiki/` (log, agents, _templates, concepts): verificarne la relazione con `wiki/`, vedi Storico
- [llm-wiki.md](llm-wiki.md) - puntatore root a `llm-wiki/`

## Regole e policy

- [BODY_CLASS_RULE.md](BODY_CLASS_RULE.md)
- [component-page-alias-rule.md](component-page-alias-rule.md)
- [contracts-naming.md](contracts-naming.md)
- [file-naming-rules.md](file-naming-rules.md)
- [html-parity-body-policy.md](html-parity-body-policy.md)
- [json-content-blocks-rule.md](json-content-blocks-rule.md)
- [laraxot-panel-provider-rules.md](laraxot-panel-provider-rules.md)
- [mustache-variables.md](mustache-variables.md)
- [no-ai-tool-scaffold-dirs.md](no-ai-tool-scaffold-dirs.md)
- [ON-DEMAND-PATTERN.md](ON-DEMAND-PATTERN.md)
- [root-file-policy.md](root-file-policy.md)
- [root-files-hygiene.md](root-files-hygiene.md)
- [route-structure-rules.md](route-structure-rules.md)
- [SET_BODY_TAG_SIMPLE.md](SET_BODY_TAG_SIMPLE.md)
- [sixteen-agid-naming-fundamental-rule.md](sixteen-agid-naming-fundamental-rule.md)
- [sixteen-agid-naming-rules.md](sixteen-agid-naming-rules.md)
- [sixteen-theme-naming-conventions.md](sixteen-theme-naming-conventions.md)
- [STEPPER_MOBILE_FIRST_RULE.md](STEPPER_MOBILE_FIRST_RULE.md)
- [svg-icon-convention.md](svg-icon-convention.md)
- [tests-placement-policy.md](tests-placement-policy.md)
- [theme-namespace-issue.md](theme-namespace-issue.md)
- [theme-namespace-rules.md](theme-namespace-rules.md)

Regole aggiuntive in sottocartella dedicata:
- [rules/BODY-NO-CLASSES.md](rules/BODY-NO-CLASSES.md)
- [rules/NO-INLINE-JS.md](rules/NO-INLINE-JS.md)
- [rules/tests-pages-architecture.md](rules/tests-pages-architecture.md)
- [clean-code/schema-clean-code-rule.md](clean-code/schema-clean-code-rule.md)

## Architettura, layout, routing, Folio

- [agid-layout-usage-rules.md](agid-layout-usage-rules.md)
- [ARCHITECTURE-QUEUEABLE-ACTION.md](ARCHITECTURE-QUEUEABLE-ACTION.md)
- [architecture-testing-env.md](architecture-testing-env.md)
- [BMAD_LAYOUT_CORRECTION.md](BMAD_LAYOUT_CORRECTION.md)
- [christmas-email-layout.md](christmas-email-layout.md)
- [component-page-runtime.md](component-page-runtime.md)
- [FIX_TESTS_PAGES_ARCHITECTURE.md](FIX_TESTS_PAGES_ARCHITECTURE.md)
- [folio-page-pattern.md](folio-page-pattern.md)
- [FOLIO_HELPERS_RULE.md](FOLIO_HELPERS_RULE.md)
- [FOLIO_MOUNT_ERROR_FIX.md](FOLIO_MOUNT_ERROR_FIX.md)
- [FOLIO_PAGES_ARCHITECTURE.md](FOLIO_PAGES_ARCHITECTURE.md)
- [layout-architecture.md](layout-architecture.md)
- [LAYOUT-CENTERING-FIX.md](LAYOUT-CENTERING-FIX.md)
- [layout-fix-summary.md](layout-fix-summary.md)
- [layout-hierarchy.md](layout-hierarchy.md)
- [LAYOUT-ISSUES-ANALYSIS.md](LAYOUT-ISSUES-ANALYSIS.md)
- [layout-namespace-correction.md](layout-namespace-correction.md)
- [layout-runtime-contract.md](layout-runtime-contract.md)
- [layout-usage-correction.md](layout-usage-correction.md)
- [layout-usage-patterns.md](layout-usage-patterns.md)
- [layout-usage-rules.md](layout-usage-rules.md)
- [layout-variants-cleanup-complete.md](layout-variants-cleanup-complete.md)
- [LAYOUT_ARCHITECTURE_AND_NAMESPACE.md](LAYOUT_ARCHITECTURE_AND_NAMESPACE.md)
- [LAYOUT_ARCHITECTURE_MAP.md](LAYOUT_ARCHITECTURE_MAP.md)
- [LAYOUT_CORRECTION_COMPLETE.md](LAYOUT_CORRECTION_COMPLETE.md)
- [LAYOUT_FIX_COMPLETE_BMAD.md](LAYOUT_FIX_COMPLETE_BMAD.md)
- [mail-layouts-natale.md](mail-layouts-natale.md)
- [page-component-conflict.md](page-component-conflict.md)
- [page-directory-structure.md](page-directory-structure.md)
- [pages-directory-structure.md](pages-directory-structure.md)
- [pages-directory-verified.md](pages-directory-verified.md)
- [PHPSTAN_LAYOUT_FIX_COMPLETE.md](PHPSTAN_LAYOUT_FIX_COMPLETE.md)
- [route-correction-summary.md](route-correction-summary.md)
- [route-pattern-correction.md](route-pattern-correction.md)
- [route-patterns.md](route-patterns.md)
- [routing-patterns-correction.md](routing-patterns-correction.md)
- [UI-LAYOUT-VISIBILITY-ISSUES-ANALYSIS.md](UI-LAYOUT-VISIBILITY-ISSUES-ANALYSIS.md)

Cartella `architecture/` (entry point [architecture/README.md](architecture/README.md)):
- [architecture/CMS-DRIVEN-BLOCKS.md](architecture/CMS-DRIVEN-BLOCKS.md)
- [architecture/component-namespace.md](architecture/component-namespace.md)
- [architecture/css-filename-english-naming.md](architecture/css-filename-english-naming.md)
- [architecture/CSS-SCOPING-RULE.md](architecture/CSS-SCOPING-RULE.md)
- [architecture/fo-pa-tokens-uniformity.md](architecture/fo-pa-tokens-uniformity.md)
- [architecture/layout-architecture.md](architecture/layout-architecture.md)
- [architecture/page-component-architecture.md](architecture/page-component-architecture.md)
- [architecture/PAGE_ROUTING_ARCHITECTURE.md](architecture/PAGE_ROUTING_ARCHITECTURE.md)
- [architecture/STEPPER-RESPONSIVE-RULE.md](architecture/STEPPER-RESPONSIVE-RULE.md)
- [architecture/tailwind-alpine-philosophy.md](architecture/tailwind-alpine-philosophy.md)
- [architecture/wizard-parity.md](architecture/wizard-parity.md)
- [architecture/wizard-philosophy-analysis.md](architecture/wizard-philosophy-analysis.md)
- [architecture/wizard-step-visibility.md](architecture/wizard-step-visibility.md)

Concetti correlati: [concepts/map-lit-ticket-api.md](concepts/map-lit-ticket-api.md), [concepts/xotbase-never-extend-filament.md](concepts/xotbase-never-extend-filament.md)

## AGID e Bootstrap Italia (Design Comuni PA)

- [agid-checklist.md](agid-checklist.md)
- [agid-compliance-summary.md](agid-compliance-summary.md)
- [agid-components-reorganization.md](agid-components-reorganization.md)
- [agid-filament-4x-implementation-complete.md](agid-filament-4x-implementation-complete.md)
- [agid-filament-4x-integration.md](agid-filament-4x-integration.md)
- [agid-gap-analysis.md](agid-gap-analysis.md)
- [agid-implementation-complete.md](agid-implementation-complete.md)
- [agid-implementation-summary.md](agid-implementation-summary.md)
- [agid-login-battle-plan.md](agid-login-battle-plan.md)
- [agid-login-compliance-analysis.md](agid-login-compliance-analysis.md)
- [agid-login-implementation-complete.md](agid-login-implementation-complete.md)
- [agid-login-implementation-plan.md](agid-login-implementation-plan.md)
- [agid-login-implementation.md](agid-login-implementation.md)
- [agid-login-problems-analysis.md](agid-login-problems-analysis.md)
- [agid-login-refactoring-plan.md](agid-login-refactoring-plan.md)
- [agid-naming-cleanup-complete.md](agid-naming-cleanup-complete.md)
- [agid-naming-correction-complete.md](agid-naming-correction-complete.md)
- [agid_checklist.md](agid_checklist.md)
- [agid_checklist_100.md](agid_checklist_100.md)
- [AGID_CHECKLIST_100.md](AGID_CHECKLIST_100.md)
- [agid_components_reorganization.md](agid_components_reorganization.md)
- [AGID_COMPONENTS_REORGANIZATION.md](AGID_COMPONENTS_REORGANIZATION.md)
- [auth-agid-component-fix.md](auth-agid-component-fix.md)
- [bootstrap-english-compliance-analysis-updated.md](bootstrap-english-compliance-analysis-updated.md)
- [bootstrap-english-compliance-analysis.md](bootstrap-english-compliance-analysis.md)
- [bootstrap-english-compliance-final-report.md](bootstrap-english-compliance-final-report.md)
- [bootstrap-english-examples.md](bootstrap-english-examples.md)
- [bootstrap-english-implementation.md](bootstrap-english-implementation.md)
- [bootstrap-english-to-tailwind.md](bootstrap-english-to-tailwind.md)
- [bootstrap-italia-examples.md](bootstrap-italia-examples.md)
- [bootstrap-italia-implementation.md](bootstrap-italia-implementation.md)
- [bootstrap-italia-to-tailwind.md](bootstrap-italia-to-tailwind.md)
- [daisyui-summary.md](daisyui-summary.md)
- [DAISYUI.md](DAISYUI.md)
- [it-homepage-agid-alignment.md](it-homepage-agid-alignment.md)
- [NO_BOOTSTRAP_ITALIA.md](NO_BOOTSTRAP_ITALIA.md)

Cartella `agid/` (nessun indice locale):
- [agid/agid-compliance-analysis.md](agid/agid-compliance-analysis.md)
- [agid/agid-municipal-gap-analysis.md](agid/agid-municipal-gap-analysis.md)
- [agid/agid-static-pages-analysis.md](agid/agid-static-pages-analysis.md)
- [agid/complete-agid-compliance-analysis.md](agid/complete-agid-compliance-analysis.md)
- [agid/gap-analysis-sixteen-vs-agid.md](agid/gap-analysis-sixteen-vs-agid.md)
- [agid/migliori-siti-comunali-agid.md](agid/migliori-siti-comunali-agid.md)

## Autenticazione e login

- [auth-best-practices.md](auth-best-practices.md)
- [auth-pages-analysis.md](auth-pages-analysis.md)
- [auth_best_practices.md](auth_best_practices.md)
- [filament-4-login-widget-implementation.md](filament-4-login-widget-implementation.md)
- [login-agid-analysis.md](login-agid-analysis.md)
- [login-agid-correct-implementation.md](login-agid-correct-implementation.md)
- [login-agid-fix-complete.md](login-agid-fix-complete.md)
- [login-agid-problems-analysis.md](login-agid-problems-analysis.md)
- [login-agid-refactoring-plan.md](login-agid-refactoring-plan.md)
- [login-analysis-summary.md](login-analysis-summary.md)
- [login-card-component-analysis.md](login-card-component-analysis.md)
- [login-correction-implementation.md](login-correction-implementation.md)
- [login-implementation-analysis.md](login-implementation-analysis.md)
- [login-implementation-complete.md](login-implementation-complete.md)
- [login-implementation-plan.md](login-implementation-plan.md)
- [login-page-analysis.md](login-page-analysis.md)
- [login-page-implementation-plan.md](login-page-implementation-plan.md)
- [login-refactoring-implementation.md](login-refactoring-implementation.md)
- [login-refactoring-plan.md](login-refactoring-plan.md)
- [login1-agid-implementation-complete.md](login1-agid-implementation-complete.md)
- [login2-agid-optimal-implementation.md](login2-agid-optimal-implementation.md)
- [login3-agid-implementation-complete.md](login3-agid-implementation-complete.md)
- [login4-agid-implementation.md](login4-agid-implementation.md)
- [login4-analysis-and-improvements.md](login4-analysis-and-improvements.md)
- [login4-component-verification.md](login4-component-verification.md)
- [login4-improvements-analysis.md](login4-improvements-analysis.md)
- [login4-translations-fix.md](login4-translations-fix.md)
- [login_correction_implementation.md](login_correction_implementation.md)
- [login_implementation_analysis.md](login_implementation_analysis.md)
- [r2-ux-register-form-stacked-password.md](r2-ux-register-form-stacked-password.md)

Cartella `auth/` (18 file, nessun indice locale):
- [auth/auth-agid-component-fix.md](auth/auth-agid-component-fix.md)
- [auth/login1-agid-implementation-complete.md](auth/login1-agid-implementation-complete.md)
- [auth/login2-agid-optimal-implementation.md](auth/login2-agid-optimal-implementation.md)
- [auth/login3-agid-implementation-complete.md](auth/login3-agid-implementation-complete.md)
- [auth/login4-agid-implementation.md](auth/login4-agid-implementation.md)
- [auth/login-agid-analysis.md](auth/login-agid-analysis.md)
- [auth/login-agid-correct-implementation.md](auth/login-agid-correct-implementation.md)
- [auth/login-agid-fix-complete.md](auth/login-agid-fix-complete.md)
- [auth/login-agid-problems-analysis.md](auth/login-agid-problems-analysis.md)
- [auth/login-agid-refactoring-plan.md](auth/login-agid-refactoring-plan.md)
- [auth/login-implementation-guide.md](auth/login-implementation-guide.md)
- [auth/login-ux-fixes.md](auth/login-ux-fixes.md) e variante datata [auth/login-ux-fixes-2026-06-04.md](auth/login-ux-fixes-2026-06-04.md) (vedi Storico)
- [auth/login-widget-form-binding.md](auth/login-widget-form-binding.md)
- [auth/register-ux-audit.md](auth/register-ux-audit.md) e variante datata [auth/register-ux-audit-2026-06-04.md](auth/register-ux-audit-2026-06-04.md) (vedi Storico)
- [auth/register-ux-design-handoff.md](auth/register-ux-design-handoff.md)
- [auth/sixteen-agid-naming-fundamental-rule.md](auth/sixteen-agid-naming-fundamental-rule.md)
- [auth/sixteen-agid-naming-rules.md](auth/sixteen-agid-naming-rules.md)

## Accessibilita

- [accessibility-components.md](accessibility-components.md)
- [accessibility-implementation-guide.md](accessibility-implementation-guide.md)
- [accessibility.md](accessibility.md)
- [accessibility_implementation_guide.md](accessibility_implementation_guide.md)
- [ACCESSIBILITY_IMPLEMENTATION_GUIDE.md](ACCESSIBILITY_IMPLEMENTATION_GUIDE.md)
- [PHASE-10-ACCESSIBILITY-AUDIT.md](PHASE-10-ACCESSIBILITY-AUDIT.md)

## Componenti, blocchi, wizard, Filament

- [ALPINE-JS-COMPONENTS.md](ALPINE-JS-COMPONENTS.md)
- [block-view-bridge-missing-pages.md](block-view-bridge-missing-pages.md)
- [BLOCK_IMPLEMENTATION_GUIDE.md](BLOCK_IMPLEMENTATION_GUIDE.md)
- [BLOCKS-NAMING-CONVENTION.md](BLOCKS-NAMING-CONVENTION.md)
- [blocks-system.md](blocks-system.md)
- [BLOCKS_IMPLEMENTATION.md](BLOCKS_IMPLEMENTATION.md)
- [complete-component-categorization.md](complete-component-categorization.md)
- [component-reorganization-plan.md](component-reorganization-plan.md)
- [component-structure-reorganization.md](component-structure-reorganization.md)
- [component-usage-guide.md](component-usage-guide.md)
- [components-directory-structure.md](components-directory-structure.md)
- [components-status.md](components-status.md)
- [components-update.md](components-update.md)
- [components.md](components.md)
- [components_update_2025-10.md](components_update_2025-10.md)
- [COMPONENTS_UPDATE_2025-10.md](COMPONENTS_UPDATE_2025-10.md)
- [comprehensive-components-reorganization.md](comprehensive-components-reorganization.md)
- [comprehensive_components_reorganization.md](comprehensive_components_reorganization.md)
- [COMPREHENSIVE_COMPONENTS_REORGANIZATION.md](COMPREHENSIVE_COMPONENTS_REORGANIZATION.md)
- [design-comuni-census-blocks.md](design-comuni-census-blocks.md)
- [filament-4x-integration-complete.md](filament-4x-integration-complete.md)
- [filament-4x-styles-configuration.md](filament-4x-styles-configuration.md)
- [filament-icon-guide.md](filament-icon-guide.md)
- [filament-integration.md](filament-integration.md)
- [filament-summary-infolist-guidance.md](filament-summary-infolist-guidance.md)
- [filament-widget-styling-fix.md](filament-widget-styling-fix.md)
- [filament_4x_upgrade_report.md](filament_4x_upgrade_report.md)
- [implementation-plan-missing-components.md](implementation-plan-missing-components.md)
- [implemented-components-update.md](implemented-components-update.md)
- [missing-components-roadmap.md](missing-components-roadmap.md)
- [missing-municipality-components-implementation-plan.md](missing-municipality-components-implementation-plan.md)
- [municipality-components-implementation-complete.md](municipality-components-implementation-complete.md)
- [new-components-implementation-summary.md](new-components-implementation-summary.md)
- [PHASE-1-DECISION-BLOCKERS.md](PHASE-1-DECISION-BLOCKERS.md)
- [ticket-wizard-filament-refactor.md](ticket-wizard-filament-refactor.md)
- [ui-components-reorganization.md](ui-components-reorganization.md)
- [ui_components_reorganization.md](ui_components_reorganization.md)
- [UI_COMPONENTS_REORGANIZATION.md](UI_COMPONENTS_REORGANIZATION.md)
- [vite-lit-integration.md](vite-lit-integration.md)
- [WEB-COMPONENTS-AND-BUILD-SYSTEM.md](WEB-COMPONENTS-AND-BUILD-SYSTEM.md)
- [wizard-component.md](wizard-component.md)
- [wizard-refactor-explanation.md](wizard-refactor-explanation.md)

Cartella `components/` (entry: file sotto, nessun indice dedicato):
- [components/badge-components-implementation.md](components/badge-components-implementation.md)
- [components/bootstrap-italia-components.md](components/bootstrap-italia-components.md)
- [components/form-components.md](components/form-components.md)
- [components/layout-components.md](components/layout-components.md)
- [components/modal.md](components/modal.md)
- [components/navigation-components.md](components/navigation-components.md)
- [components/tab_system.md](components/tab_system.md)
- components/ui/ e components/ui/marketing/ - sottocartelle aggiuntive

Cartella `blocks/` (nessun indice dedicato):
- [blocks/block-taxonomy.md](blocks/block-taxonomy.md)
- [blocks/blocks-implementation.md](blocks/blocks-implementation.md)
- [blocks/blocks-structure-convention.md](blocks/blocks-structure-convention.md)
- [blocks/folder-vocabulary.md](blocks/folder-vocabulary.md)
- [blocks/confirmation/README.md](blocks/confirmation/README.md)

Wizard: [wizard/form-tag-fix.md](wizard/form-tag-fix.md), [best-practices/filament-wizard-patterns.md](best-practices/filament-wizard-patterns.md)

## CSS, build, Vite, rimozione JS inline

- [build-commands-guide.md](build-commands-guide.md)
- [build-issues-resolution.md](build-issues-resolution.md)
- [build-system-best-practices.md](build-system-best-practices.md)
- [BUILD-WORKFLOW.md](BUILD-WORKFLOW.md)
- [BUILD_TROUBLESHOOTING.md](BUILD_TROUBLESHOOTING.md)
- [CSS-FIXES-PHASE2.md](CSS-FIXES-PHASE2.md)
- [CSS-FIXES-PROGRESS.md](CSS-FIXES-PROGRESS.md)
- [css-js-parity.md](css-js-parity.md)
- [css-js-phase-report.md](css-js-phase-report.md)
- [css-js-phase-status.md](css-js-phase-status.md)
- [CSS-MAPPING-ANALYSIS-REPORT.md](CSS-MAPPING-ANALYSIS-REPORT.md)
- [FOOTER-CSS-FIX-2026-04-02.md](FOOTER-CSS-FIX-2026-04-02.md)
- [footer-css-fix.md](footer-css-fix.md)
- [FOOTER-CSS-FIX.md](FOOTER-CSS-FIX.md)
- [INLINE_JS_REMOVAL_MAIN.md](INLINE_JS_REMOVAL_MAIN.md)
- [REMOVING_INLINE_JS.md](REMOVING_INLINE_JS.md)
- [text-paragraph-font-fix.md](text-paragraph-font-fix.md)
- [vite-asset-loading-correction.md](vite-asset-loading-correction.md)
- [vite-audit-report-2025.md](vite-audit-report-2025.md)
- [vite-audit-report.md](vite-audit-report.md)
- [vite-config-fix-summary.md](vite-config-fix-summary.md)
- [vite-configuration-correction-complete.md](vite-configuration-correction-complete.md)
- [vite-configuration-guide.md](vite-configuration-guide.md)
- [vite-configuration-rules.md](vite-configuration-rules.md)
- [vite-correction-summary.md](vite-correction-summary.md)
- [vite-theme-integration.md](vite-theme-integration.md)
- [VITE_MANIFEST_ERROR_FIX.md](VITE_MANIFEST_ERROR_FIX.md)
- [VITE_MANIFEST_FIX_COMPLETE.md](VITE_MANIFEST_FIX_COMPLETE.md)
- [VITE_MANIFEST_ROOT_CAUSE.md](VITE_MANIFEST_ROOT_CAUSE.md)
- [VITE_SECOND_PARAMETER_GUIDE.md](VITE_SECOND_PARAMETER_GUIDE.md)

Cartella `css/` - sessioni di parity CSS datate, ognuna con variante "corrente" e variante con suffisso data (vedi Storico per gli accoppiamenti esatti):
- [css/batch-parity.md](css/batch-parity.md)
- [css/file-upload-fix.md](css/file-upload-fix.md)
- [css/font-fix.md](css/font-fix.md)
- [css/parity-session.md](css/parity-session.md)
- [css/session-report.md](css/session-report.md)

Cartella `css-js-parity/`:
- [css-js-parity/segnalazione-01-privacy-css-fix.md](css-js-parity/segnalazione-01-privacy-css-fix.md)
- [css-js-parity/ticket-01-privacy-css-fix.md](css-js-parity/ticket-01-privacy-css-fix.md) (coppia segnalazione/ticket, vedi Storico)

## Segnalazioni / ticket (flusso cittadino)

- [area-personale-pratiche-placeholder.md](area-personale-pratiche-placeholder.md)
- [segnalazione-01-privacy-css-js-parity.md](segnalazione-01-privacy-css-js-parity.md)
- [segnalazione-comparison-analysis.md](segnalazione-comparison-analysis.md)
- [segnalazione-crea-header-stepper-responsive.md](segnalazione-crea-header-stepper-responsive.md)
- [segnalazione-css-diff-2026-04-07.md](segnalazione-css-diff-2026-04-07.md)
- [segnalazione-css-diff.md](segnalazione-css-diff.md)
- [segnalazione-flow-html-parity-complete.md](segnalazione-flow-html-parity-complete.md)
- [segnalazione-visual-parity.md](segnalazione-visual-parity.md)
- [ticket-01-privacy-css-js-parity.md](ticket-01-privacy-css-js-parity.md)
- [ticket-comparison-analysis.md](ticket-comparison-analysis.md)
- [ticket-crea-header-stepper-responsive.md](ticket-crea-header-stepper-responsive.md)
- [ticket-css-diff-2026-04-07.md](ticket-css-diff-2026-04-07.md)
- [ticket-css-diff.md](ticket-css-diff.md)
- [ticket-flow-html-parity-complete.md](ticket-flow-html-parity-complete.md)
- [ticket-visual-parity.md](ticket-visual-parity.md)

Nota: diversi documenti esistono in coppia con prefisso `segnalazione-` e `ticket-` sullo stesso argomento (es. `segnalazione-css-diff.md` / `ticket-css-diff.md`) - probabile rinominazione di terminologia in corso, da verificare e consolidare senza cancellare (vedi Storico).

## Design Comuni - cantiere HTML/CSS/parity (root)

- [AMMINISTRAZIONE_HTML_COMPARISON.md](AMMINISTRAZIONE_HTML_COMPARISON.md)
- [design-common-static-pages-analysis.md](design-common-static-pages-analysis.md)
- [design-comuni-compliance.md](design-comuni-compliance.md)
- [design-comuni-html-match-css-js-plan.md](design-comuni-html-match-css-js-plan.md)
- [design-comuni-html-parity-plan.md](design-comuni-html-parity-plan.md)
- [design-comuni-implementation-complete.md](design-comuni-implementation-complete.md)
- [design-comuni-implementation.md](design-comuni-implementation.md)
- [design-comuni-integration.md](design-comuni-integration.md)
- [design-comuni-italia-integration.md](design-comuni-italia-integration.md)
- [design-comuni-page-census.md](design-comuni-page-census.md)
- [DESIGN-COMUNI-PAGES-COMPARISON.md](DESIGN-COMUNI-PAGES-COMPARISON.md)
- [DESIGN_COMUNI_AMMINISTRAZIONE_ANALYSIS.md](DESIGN_COMUNI_AMMINISTRAZIONE_ANALYSIS.md)
- [DESIGN_COMUNI_HTML_REPLICATION.md](DESIGN_COMUNI_HTML_REPLICATION.md)
- [DESIGN_COMUNI_HTML_REPLICATION_ACTION_PLAN.md](DESIGN_COMUNI_HTML_REPLICATION_ACTION_PLAN.md)
- [design_comuni_integration.md](design_comuni_integration.md)
- [DESIGN_COMUNI_INTEGRATION.md](DESIGN_COMUNI_INTEGRATION.md)
- [design_comuni_italia_integration.md](design_comuni_italia_integration.md)
- [DESIGN_COMUNI_ITALIA_INTEGRATION.md](DESIGN_COMUNI_ITALIA_INTEGRATION.md)
- [DESIGN_COMUNI_PROJECT_SUMMARY.md](DESIGN_COMUNI_PROJECT_SUMMARY.md)
- [DESIGN_COMUNI_REPLICATION_INDEX.md](DESIGN_COMUNI_REPLICATION_INDEX.md)
- [DESIGN_COMUNI_REPLICATION_PLAN.md](DESIGN_COMUNI_REPLICATION_PLAN.md)
- [DESIGN_COMUNI_TEAM_GUIDE.md](DESIGN_COMUNI_TEAM_GUIDE.md)

La cartella [design-comuni/](design-comuni/README.md) e' il cantiere piu' grande del tema (179 file `.md`), con proprio indice storico in [design-comuni/00-INDEX.md](design-comuni/00-INDEX.md) / [design-comuni/00-index.md](design-comuni/00-index.md) (due varianti di case, vedi Storico) e un README aggiornato in [design-comuni/README.md](design-comuni/README.md). Contiene inoltre oltre 150 file `.md` root al suo interno (report di sessione, parity, analisi, molti con doppione datato/non datato, vedi Storico) e le sottocartelle:
- [design-comuni/analysis/INDEX.md](design-comuni/analysis/INDEX.md) (11 file)
- design-comuni/pages/ (indice: [00-index.md](design-comuni/pages/00-index.md) / [00-INDEX.md](design-comuni/pages/00-INDEX.md))
- design-comuni/visual-comparison/ (5 file)
- [design-comuni/segnalazione-parity/README.md](design-comuni/segnalazione-parity/README.md) (4 file)
- [design-comuni/segnalazioni-elenco/README.md](design-comuni/segnalazioni-elenco/README.md)
- design-comuni/html-structure-analysis/
- design-comuni/blocks/ (indice: [00-index.md](design-comuni/blocks/00-index.md) / [00-INDEX.md](design-comuni/blocks/00-INDEX.md))
- [design-comuni/segnalazione-flow/README.md](design-comuni/segnalazione-flow/README.md)
- [design-comuni/segnalazione-dettaglio/README.md](design-comuni/segnalazione-dettaglio/README.md)
- design-comuni/html-comparisons/
- [design-comuni/batch-body-parity/README.md](design-comuni/batch-body-parity/README.md) - una sottocartella per ogni pagina censita (amministrazione, argomenti, homepage, servizi, segnalazione-*, ecc. circa 60 slug), ognuna con `local-body.html` / `reference-body.html`
- design-comuni/screenshots/, design-comuni/raw/, design-comuni/html/, design-comuni/html-homepage/, design-comuni/live-body-parity/, design-comuni/risultati-ricerca-parity/, design-comuni/faq-parity/, design-comuni/argomento-parity/, design-comuni/argomenti-parity/ - cartelle di soli asset o senza `.md` proprio (screenshot/HTML grezzi)

## Homepage e parity HTML/CSS (root)

- [00-CSS-JS-VISUAL-FIX-PLAN.md](00-CSS-JS-VISUAL-FIX-PLAN.md)
- [00-HOMEPAGE-REPLICATION-INDEX.md](00-HOMEPAGE-REPLICATION-INDEX.md)
- [COMPLETE-VISUAL-PARITY-REPORT.md](COMPLETE-VISUAL-PARITY-REPORT.md)
- [faq-comparison-2026-04-03.md](faq-comparison-2026-04-03.md)
- [faq-comparison.md](faq-comparison.md)
- [FAQ-PARITY.md](FAQ-PARITY.md)
- [FAQ_REPLICATION_STATUS.md](FAQ_REPLICATION_STATUS.md)
- [FINAL-VISUAL-PARITY-REPORT.md](FINAL-VISUAL-PARITY-REPORT.md)
- [FOOTER-FIXES-REPORT.md](FOOTER-FIXES-REPORT.md)
- [HOMEPAGE-CSS-JS-FIXES-COMPLETE.md](HOMEPAGE-CSS-JS-FIXES-COMPLETE.md)
- [HOMEPAGE-FIX-ANALYSIS.md](HOMEPAGE-FIX-ANALYSIS.md)
- [homepage-parity-2026-04-02.md](homepage-parity-2026-04-02.md)
- [homepage-parity.md](homepage-parity.md)
- [HOMEPAGE-VISUAL-ANALYSIS.md](HOMEPAGE-VISUAL-ANALYSIS.md)
- [HOMEPAGE_404_FIX.md](HOMEPAGE_404_FIX.md)
- [HOMEPAGE_HTML_BODY_COMPARISON_FINAL.md](HOMEPAGE_HTML_BODY_COMPARISON_FINAL.md)
- [HOMEPAGE_HTML_COMPARISON.md](HOMEPAGE_HTML_COMPARISON.md)
- [HTML-PARITY-WORKFLOW.md](HTML-PARITY-WORKFLOW.md)
- [HTML_BODY_COMPARISON.md](HTML_BODY_COMPARISON.md)
- [HTML_BODY_IDENTITY_FIX.md](HTML_BODY_IDENTITY_FIX.md)
- [HTML_COMPARISON_REPORT.md](HTML_COMPARISON_REPORT.md)
- [HTML_COMPARISON_URGENT_FIXES.md](HTML_COMPARISON_URGENT_FIXES.md)
- [PARITY-ASSESSMENT-FINDINGS.md](PARITY-ASSESSMENT-FINDINGS.md)
- [PHASE-2-VISUAL-ENHANCEMENT.md](PHASE-2-VISUAL-ENHANCEMENT.md)
- [visual-parity-plan.md](visual-parity-plan.md)
- [VISUAL-VERIFICATION-GUIDE.md](VISUAL-VERIFICATION-GUIDE.md)

- [homepage/homepage-implementation.md](homepage/homepage-implementation.md)

## Fasi di progetto (Phase report, GSD, UI fixes)

- [00-IMPLEMENTATION-STATUS.md](00-IMPLEMENTATION-STATUS.md)
- [00-PROJECT-SETUP-SUMMARY.md](00-PROJECT-SETUP-SUMMARY.md)
- [GSD-PHASE-1-EXECUTION.md](GSD-PHASE-1-EXECUTION.md)
- [HYBRID-EXECUTION-TRACK-A-B.md](HYBRID-EXECUTION-TRACK-A-B.md)
- [implementation-guide.md](implementation-guide.md)
- [IMPLEMENTATION-REPORT.md](IMPLEMENTATION-REPORT.md)
- [implementation-roadmap-from-official-analysis.md](implementation-roadmap-from-official-analysis.md)
- [IMPLEMENTATION_MASTER_PLAN.md](IMPLEMENTATION_MASTER_PLAN.md)
- [PHASE-1-EXECUTION-STATUS.md](PHASE-1-EXECUTION-STATUS.md)
- [PHASE-1-STRATEGY.md](PHASE-1-STRATEGY.md)
- [PHASE-10-PERFORMANCE-REPORT.md](PHASE-10-PERFORMANCE-REPORT.md)
- [PHASE-10-VERIFICATION-REPORT.md](PHASE-10-VERIFICATION-REPORT.md)
- [PHASE-12-IMPLEMENTATION-STRATEGY.md](PHASE-12-IMPLEMENTATION-STRATEGY.md)
- [PHASE-2-COMPREHENSIVE-STRATEGY.md](PHASE-2-COMPREHENSIVE-STRATEGY.md)
- [PHASE-2-STRATEGY-FRAMEWORK.md](PHASE-2-STRATEGY-FRAMEWORK.md)
- [PHASE-7-COMPLETION-REPORT.md](PHASE-7-COMPLETION-REPORT.md)
- [PHASE-8-ALPINE-INVESTIGATION.md](PHASE-8-ALPINE-INVESTIGATION.md)
- [PHASE-8-ALPINE-ROOT-CAUSE.md](PHASE-8-ALPINE-ROOT-CAUSE.md)
- [PHASE-8-PLAN.md](PHASE-8-PLAN.md)
- [PHASE-9-IMPLEMENTATION-REPORT.md](PHASE-9-IMPLEMENTATION-REPORT.md)
- [PHASE-9-ROOT-CAUSE-FOUND.md](PHASE-9-ROOT-CAUSE-FOUND.md)
- [PHASE-9-SCREENSHOT-ANALYSIS.md](PHASE-9-SCREENSHOT-ANALYSIS.md)
- [PHASE2-CSS-FIXES-COMPLETE.md](PHASE2-CSS-FIXES-COMPLETE.md)
- [PHASE3-ALPINE-PLAN.md](PHASE3-ALPINE-PLAN.md)
- [PHASE6-ALPINE-IMPLEMENTATION.md](PHASE6-ALPINE-IMPLEMENTATION.md)
- [PHASE6-ALPINE-STATUS.md](PHASE6-ALPINE-STATUS.md)
- [PHASE_1_CHECKPOINT.md](PHASE_1_CHECKPOINT.md)
- [PHASE_1_COMPLETE.md](PHASE_1_COMPLETE.md)
- [service-provider-enhancement-implementation.md](service-provider-enhancement-implementation.md)

- [phases/README.md](phases/README.md), [phases/01-PHASE-A-DISCOVERY.md](phases/01-PHASE-A-DISCOVERY.md)
- [implementation/STRATEGY.md](implementation/STRATEGY.md), [implementation/TEAM_COORDINATION.md](implementation/TEAM_COORDINATION.md)
- Cartella `ui-fixes/` (entry [ui-fixes/INDEX.md](ui-fixes/INDEX.md)): BMAD-STRATEGIC-DECISIONS.md, CONTAINER-CENTERING-ISSUE.md, GSD-PLAN-IMPLEMENTATION.md, PHASE1-VERIFICATION-REPORT.md, PHASE2-VERIFICATION-REPORT.md, SEARCH-MODAL-VISIBILITY-ISSUE.md, SOCIAL-ICONS-VISIBILITY-ISSUE.md, UI-FIXES-MASTER-PLAN.md

## Qualita codice, refactor, conflitti, PHPStan

- [analisi-metodi-duplicati.md](analisi-metodi-duplicati.md)
- [code-quality-analysis.md](code-quality-analysis.md)
- [code-quality-improvement-report.md](code-quality-improvement-report.md)
- [code-quality-report.md](code-quality-report.md)
- [code-quality-tools.md](code-quality-tools.md)
- [code_quality_analysis.md](code_quality_analysis.md)
- [CODE_QUALITY_ANALYSIS.md](CODE_QUALITY_ANALYSIS.md)
- [code_quality_tools.md](code_quality_tools.md)
- [CODE_QUALITY_TOOLS.md](CODE_QUALITY_TOOLS.md)
- [codex-error-fix.md](codex-error-fix.md)
- [conflict-resolution.md](conflict-resolution.md)
- [copilot-redundancy-audit-2026-05-25.md](copilot-redundancy-audit-2026-05-25.md)
- [copilot-redundancy-audit.md](copilot-redundancy-audit.md)
- [dry-kiss-analysis.md](dry-kiss-analysis.md)
- [git-conflicts-report.md](git-conflicts-report.md)
- [merge-conflict-marker-cleanup.md](merge-conflict-marker-cleanup.md)
- [merge-conflicts-list.md](merge-conflicts-list.md)
- [merge-conflicts-resolution.md](merge-conflicts-resolution.md)
- [MERGE_CONFLICT_RESOLUTION_LOG.md](MERGE_CONFLICT_RESOLUTION_LOG.md)
- [phpstan-compliance-status.md](phpstan-compliance-status.md)
- [ponytail-audit-over-engineering.md](ponytail-audit-over-engineering.md)
- [quality-tools.md](quality-tools.md)
- [redundancy-audit-2026-05-21.md](redundancy-audit-2026-05-21.md)
- [redundancy-audit.md](redundancy-audit.md)
- [REDUNDANCY_ANALYSIS.md](REDUNDANCY_ANALYSIS.md)

- [root-txt-files/phpstan_result.md](root-txt-files/phpstan_result.md) (nella cartella e' presente anche `phpstan_result.txt`, non `.md`)

## Template email

- [advanced-email-templates.md](advanced-email-templates.md)
- [christmas-email-template-implementation.md](christmas-email-template-implementation.md)
- [email-templates-2025-improvement-plan.md](email-templates-2025-improvement-plan.md)
- [email-templates-improvement-plan.md](email-templates-improvement-plan.md)
- [luxury-email-design-masterclass-2025.md](luxury-email-design-masterclass-2025.md)
- [luxury-email-design-masterclass.md](luxury-email-design-masterclass.md)

## Traduzioni

- [translation-plan.md](translation-plan.md)
- [translation-system-rules.md](translation-system-rules.md)
- [translation_plan.md](translation_plan.md)
- [TRANSLATION_PLAN.md](TRANSLATION_PLAN.md)
- [translations.md](translations.md)
- [translations_implementation.md](translations_implementation.md)

## Roadmap, prodotto, strategia

- [prd.md](prd.md)
- [product-strategy.md](product-strategy.md)
- [product_launch_plan.md](product_launch_plan.md)
- [product_roadmap.md](product_roadmap.md)
- [product_strategy.md](product_strategy.md)
- [REPLIKATE-MASTER-INDEX.md](REPLIKATE-MASTER-INDEX.md)
- [roadmap-2025.md](roadmap-2025.md)
- [roadmap-and-issues.md](roadmap-and-issues.md)
- [roadmap.md](roadmap.md)
- [ROADMAP.md](ROADMAP.md)
- [roadmap_2025.md](roadmap_2025.md)
- [ROADMAP_2025.md](ROADMAP_2025.md)
- [roadmap_complete.md](roadmap_complete.md)
- [sprint.md](sprint.md)
- [sprint_planning.md](sprint_planning.md)
- [strategy.md](strategy.md)
- [user_research.md](user_research.md)

- [roadmap/README.md](roadmap/README.md), [roadmap/2025-q4-roadmap.md](roadmap/2025-q4-roadmap.md) / [roadmap/2025-Q4-ROADMAP.md](roadmap/2025-Q4-ROADMAP.md) (varianti di case, vedi Storico)
- Cartella `product/` con un README per sotto-argomento: [product/launch-plan/README.md](product/launch-plan/README.md), [product/prd/README.md](product/prd/README.md), [product/roadmap/README.md](product/roadmap/README.md), [product/spring-planning/README.md](product/spring-planning/README.md), [product/strategy/README.md](product/strategy/README.md), [product/user-research/README.md](product/user-research/README.md) - verificare sovrapposizione con gli omonimi file root `product_*.md` (vedi Storico)

## Strumenti AI, BMAD, MCP

- [ai-assisted-coding-style-guide.md](ai-assisted-coding-style-guide.md)
- [context-compression-plugin-runtime.md](context-compression-plugin-runtime.md)
- [context-compression-plugin.md](context-compression-plugin.md)
- [gestionale-panels-vs-themes.md](gestionale-panels-vs-themes.md)
- [llm-wiki.md](llm-wiki.md)
- [MCP_SERVERS.md](MCP_SERVERS.md)
- [MCP_TOOLS_FOR_THEME.md](MCP_TOOLS_FOR_THEME.md)
- [MULTI_AGENT_COORDINATION.md](MULTI_AGENT_COORDINATION.md)
- [QMD-SETUP.md](QMD-SETUP.md)
- [supermemory.md](supermemory.md)

- [mcp/MCP-THEME-SETUP.md](mcp/MCP-THEME-SETUP.md)

## Varie

- [address-item-enum-frontend.md](address-item-enum-frontend.md)
- [analysis-and-improvement-plan.md](analysis-and-improvement-plan.md)
- [assets.md](assets.md)
- [cache-troubleshooting.md](cache-troubleshooting.md)
- [complete-theme-analysis.md](complete-theme-analysis.md)
- [composer-modules-not-themes.md](composer-modules-not-themes.md)
- [design-system-conversion.md](design-system-conversion.md)
- [designers-english-resources.md](designers-english-resources.md)
- [examples.md](examples.md)
- [header-mobile-overlay.md](header-mobile-overlay.md)
- [HEADER_BOOT_BUILD_INTEGRATION.md](HEADER_BOOT_BUILD_INTEGRATION.md)
- [icon-error-correction.md](icon-error-correction.md)
- [impeccable-audit-fixes-2026-07-13.md](impeccable-audit-fixes-2026-07-13.md)
- [mappa-implementazione.md](mappa-implementazione.md)
- [migration-guide.md](migration-guide.md)
- [official-laravel-design-theme-analysis.md](official-laravel-design-theme-analysis.md)
- [PERFORMANCE-OPTIMIZATION.md](PERFORMANCE-OPTIMIZATION.md)
- [REMOVED_PRIORITY_FIELD.md](REMOVED_PRIORITY_FIELD.md)
- [research.md](research.md)
- [scripts.md](scripts.md)
- [seo-frontend-optimization-guide.md](seo-frontend-optimization-guide.md)
- [seo_frontend_optimization_guide.md](seo_frontend_optimization_guide.md)
- [SEO_FRONTEND_OPTIMIZATION_GUIDE.md](SEO_FRONTEND_OPTIMIZATION_GUIDE.md)
- [sixteen-theme-completion.md](sixteen-theme-completion.md)
- [solution-summary.md](solution-summary.md)
- [standardization-summary.md](standardization-summary.md)
- [theme-completion-plan.md](theme-completion-plan.md)
- [theme-improvements-analysis.md](theme-improvements-analysis.md)
- [THEME_INTEGRATION_PATTERNS.md](THEME_INTEGRATION_PATTERNS.md)
- [themes.md](themes.md)
- [UCFIRST_ARRAY_ERROR_FIX.md](UCFIRST_ARRAY_ERROR_FIX.md)
- [ui-kit-english-integration-plan.md](ui-kit-english-integration-plan.md)
- [wordpress-theme-analysis.md](wordpress-theme-analysis.md)

- [computing/php-enum-string-conversion-error.md](computing/php-enum-string-conversion-error.md)
- [mappings/README.md](mappings/README.md)
- [sources/README.md](sources/README.md)
- [scripts/README.md](scripts/README.md) - indice della cartella `scripts/` (16 file, note e log di script di verifica/screenshot)

## Cantieri di confronto HTML/CSS e parity visiva

Queste cartelle sono generate da script di confronto automatico tra la homepage/pagine locali del tema e il riferimento Design Comuni: contengono screenshot, HTML estratti, diff e report ripetuti secondo lo stesso schema per ogni pagina (slug). Si linkano qui la cartella e i relativi indici/README, non ogni singolo file interno.

- **pages/** - 53 sottocartelle, una per pagina (`amministrazione`, `argomenti`, `homepage`, `servizi`, `segnalazione-*`, `pagamento`, `evento-dettaglio`, ecc.), ognuna con `VISUAL-ASSESSMENT.md` e/o `VISUAL-DIFF.md`/`STRUCTURE-COMPARISON.md` + screenshot PNG.
- **visual-parity-screenshots/** - [SUMMARY.md](visual-parity-screenshots/SUMMARY.md) + una sottocartella per pagina (30 slug) con screenshot desktop/tablet/mobile e `comparison.md`.
- **design-comuni/batch-body-parity/** - vedi sezione Design Comuni sopra, ~60 slug con `local-body.html` / `reference-body.html`.
- **html-parity-reports/** - 7 sottocartelle (pagine `segnalazione-*`) con `report.md` + `diff.md`.
- **body-structure-comparison/** - entry [INDEX.md](body-structure-comparison/INDEX.md) e [README.md](body-structure-comparison/README.md), 12 sottocartelle `segnalazione-*` con `report.md` e strutture JSON/HTML di confronto.
- **html-structure-comparison/** - entry [README.md](html-structure-comparison/README.md), sottocartella `segnalazioni-elenco/`.
- **html-structure-analysis/** - entry [INDEX.md](html-structure-analysis/INDEX.md) (4 file).
- **html-compare/segnalazioni-elenco/** - confronto dedicato con screenshot.
- **comparisons/** - [comparisons/comparison-summary.md](comparisons/comparison-summary.md) + comparisons/html-structure/, comparisons/screenshots/.
- **screenshots/** - entry [README.md](screenshots/README.md); file di analisi root: [ANALYSIS.md](screenshots/ANALYSIS.md), [HTML_PARITY_ANALYSIS_HOMEPAGE.md](screenshots/HTML_PARITY_ANALYSIS_HOMEPAGE.md), [domande-frequenti-analysis.md](screenshots/domande-frequenti-analysis.md), [domande-frequenti-comparison.md](screenshots/domande-frequenti-comparison.md); sottocartelle screenshot per data/pagina (`2026-04-02/`, `argomenti/`, `audit-it/`, `css-js-phase/`, `faq/`, `faq-final/`, `homepage/`, `risultati-ricerca/`, `segnalazione-pages/` con una sottocartella per slug, `segnalazioni-elenco/`).
- **visual-analysis/** - entry [INDEX.md](visual-analysis/INDEX.md), 17 file di analisi + sottocartelle per pagina (`segnalazione-01-privacy/` con viewport desktop/mobile/tablet, `segnalazione-02-dati/`, `segnalazione-crea-privacy/`, `screenshots/`).
- **visual-comparison/** - entry [README.md](visual-comparison/README.md), 14 file root + sottocartelle `header/`, `screenshots/` (con un README per pagina), `section-analysis/`, `sections/`, `segnalazione-pages/`, `structure-analysis/`.
- **visual-parity/** - entry [00-INDEX.md](visual-parity/00-INDEX.md), 6 file root + `screenshots/`, `scripts/`.
- **prompts/** - archivio dei prompt AI usati per fase/pagina, con propri README per sotto-argomento: [prompts/homepage/index.md](prompts/homepage/index.md), [prompts/segnalazione-01-privacy/README.md](prompts/segnalazione-01-privacy/README.md), [prompts/segnalazione-02-dati/README.md](prompts/segnalazione-02-dati/README.md), [prompts/segnalazione-dettaglio/index.md](prompts/segnalazione-dettaglio/index.md), [prompts/segnalazione-area-personale/README.md](prompts/segnalazione-area-personale/README.md), [prompts/segnalazione-crea/README.md](prompts/segnalazione-crea/README.md), [prompts/segnalazione_disservizio/README.md](prompts/segnalazione_disservizio/README.md); file root: [prompts/header.md](prompts/header.md), [prompts/replikate.md](prompts/replikate.md), [prompts/structure.md](prompts/structure.md), [prompts/bmad.md](prompts/bmad.md), [prompts/mcp.md](prompts/mcp.md), [prompts/wizard.md](prompts/wizard.md), [prompts/segnalazioni-html-parity-summary.md](prompts/segnalazioni-html-parity-summary.md) (esistono anche omonimi `.txt`, non indicizzati qui perche' non `.md`).
- **analysis/** - entry [README.md](analysis/README.md), 16 file + `analysis/batch-analysis/`, `analysis/batch-results/` (con `summary.md` e screenshot).
- **raw/** - entry [raw/README.md](raw/README.md) e [raw/index.md](raw/index.md); materiale grezzo/sorgenti (articles/, comparisons/, concepts/, entities/, notes/, overviews/, papers/, sources/, summaries/).

## Materiale fuori standard (non documentazione tema)

- **Main_files/** - contiene un intero progetto incorporato (`Main_files/five/`: `package.json`, `vite.config.ts`, `tailwind.config.js`, pagine HTML, `src/`, `assets/`, `public/` e una propria [Main_files/five/docs/README.md](Main_files/five/docs/README.md) con `alpinejs-integration.md` e `conversion-log.md`). Non e' documentazione del tema Sixteen ma un progetto di riferimento/scaffold salvato per errore sotto `docs/`: lasciato intatto, da valutare se spostare fuori da `docs/` in una sessione dedicata.
- **root-md-files/** - [CHANGELOG.md](root-md-files/CHANGELOG.md) e [README-HOMEPAGE-REPLICATION.md](root-md-files/README-HOMEPAGE-REPLICATION.md) gia' esiste anche alla radice di `docs/` (vedi Storico).
- **root-txt-files/** - `phpstan_result.md` e `phpstan_result.txt`: file di appoggio, non organizzati per argomento.

## Storico / da consolidare

Nessuno di questi file e' stato cancellato, rinominato o spostato. Sono elencati qui come cluster di probabile duplicato/superato, da verificare e consolidare in una sessione dedicata (non in questo task, che tocca solo l'indice).

### Indici storici alla radice (superati da questo file)

- [INDEX.md](INDEX.md)
- [00-index.md](00-index.md)
- [00-INDEX.md](00-INDEX.md)
- [DOCUMENTATION_INDEX.md](DOCUMENTATION_INDEX.md)
- [MASTER_DOCUMENTATION.md](MASTER_DOCUMENTATION.md)
- [SCRIPTS_INDEX.md](SCRIPTS_INDEX.md) (indice storico limitato a `scripts/`, oggi sostituito da [scripts/README.md](scripts/README.md))
- [README-old.md](README-old.md) (variante superata di [README.md](README.md))

### Varianti di case/separatore (stesso nome normalizzato, root)

- [accessibility-implementation-guide.md](accessibility-implementation-guide.md), [accessibility_implementation_guide.md](accessibility_implementation_guide.md), [ACCESSIBILITY_IMPLEMENTATION_GUIDE.md](ACCESSIBILITY_IMPLEMENTATION_GUIDE.md)
- [agid-checklist.md](agid-checklist.md), [agid_checklist.md](agid_checklist.md)
- [agid_checklist_100.md](agid_checklist_100.md), [AGID_CHECKLIST_100.md](AGID_CHECKLIST_100.md)
- [agid-components-reorganization.md](agid-components-reorganization.md), [agid_components_reorganization.md](agid_components_reorganization.md), [AGID_COMPONENTS_REORGANIZATION.md](AGID_COMPONENTS_REORGANIZATION.md)
- [auth-best-practices.md](auth-best-practices.md), [auth_best_practices.md](auth_best_practices.md)
- [code-quality-analysis.md](code-quality-analysis.md), [code_quality_analysis.md](code_quality_analysis.md), [CODE_QUALITY_ANALYSIS.md](CODE_QUALITY_ANALYSIS.md)
- [code-quality-tools.md](code-quality-tools.md), [code_quality_tools.md](code_quality_tools.md), [CODE_QUALITY_TOOLS.md](CODE_QUALITY_TOOLS.md)
- [components_update_2025-10.md](components_update_2025-10.md), [COMPONENTS_UPDATE_2025-10.md](COMPONENTS_UPDATE_2025-10.md)
- [comprehensive-components-reorganization.md](comprehensive-components-reorganization.md), [comprehensive_components_reorganization.md](comprehensive_components_reorganization.md), [COMPREHENSIVE_COMPONENTS_REORGANIZATION.md](COMPREHENSIVE_COMPONENTS_REORGANIZATION.md)
- [design-comuni-integration.md](design-comuni-integration.md), [design_comuni_integration.md](design_comuni_integration.md), [DESIGN_COMUNI_INTEGRATION.md](DESIGN_COMUNI_INTEGRATION.md)
- [design-comuni-italia-integration.md](design-comuni-italia-integration.md), [design_comuni_italia_integration.md](design_comuni_italia_integration.md), [DESIGN_COMUNI_ITALIA_INTEGRATION.md](DESIGN_COMUNI_ITALIA_INTEGRATION.md)
- [footer-css-fix.md](footer-css-fix.md), [FOOTER-CSS-FIX.md](FOOTER-CSS-FIX.md)
- [login-correction-implementation.md](login-correction-implementation.md), [login_correction_implementation.md](login_correction_implementation.md)
- [login-implementation-analysis.md](login-implementation-analysis.md), [login_implementation_analysis.md](login_implementation_analysis.md)
- [product-strategy.md](product-strategy.md), [product_strategy.md](product_strategy.md)
- [roadmap.md](roadmap.md), [ROADMAP.md](ROADMAP.md)
- [roadmap-2025.md](roadmap-2025.md), [roadmap_2025.md](roadmap_2025.md), [ROADMAP_2025.md](ROADMAP_2025.md)
- [seo-frontend-optimization-guide.md](seo-frontend-optimization-guide.md), [seo_frontend_optimization_guide.md](seo_frontend_optimization_guide.md), [SEO_FRONTEND_OPTIMIZATION_GUIDE.md](SEO_FRONTEND_OPTIMIZATION_GUIDE.md)
- [translation-plan.md](translation-plan.md), [translation_plan.md](translation_plan.md), [TRANSLATION_PLAN.md](TRANSLATION_PLAN.md)
- [ui-components-reorganization.md](ui-components-reorganization.md), [ui_components_reorganization.md](ui_components_reorganization.md), [UI_COMPONENTS_REORGANIZATION.md](UI_COMPONENTS_REORGANIZATION.md)

### Varianti di case in sottocartelle

- `design-comuni/` -> [design-comuni/00-INDEX.md](design-comuni/00-INDEX.md), [design-comuni/00-index.md](design-comuni/00-index.md)
- `design-comuni/` -> [design-comuni/SESSION_SUMMARY_.md](design-comuni/SESSION_SUMMARY_.md), [design-comuni/session-summary.md](design-comuni/session-summary.md)
- `design-comuni/` -> [design-comuni/VISUAL-COMPARISON-REPORT.md](design-comuni/VISUAL-COMPARISON-REPORT.md), [design-comuni/visual-comparison-report.md](design-comuni/visual-comparison-report.md)
- `design-comuni/blocks/` -> [design-comuni/blocks/00-INDEX.md](design-comuni/blocks/00-INDEX.md), [design-comuni/blocks/00-index.md](design-comuni/blocks/00-index.md)
- `design-comuni/pages/` -> [design-comuni/pages/00-INDEX.md](design-comuni/pages/00-INDEX.md), [design-comuni/pages/00-index.md](design-comuni/pages/00-index.md)
- `llm-wiki/` -> [llm-wiki/AGENTS.md](llm-wiki/AGENTS.md), [llm-wiki/agents.md](llm-wiki/agents.md)
- `prompts/homepage/blocks/` -> [prompts/homepage/blocks/00-INDEX.md](prompts/homepage/blocks/00-INDEX.md), [prompts/homepage/blocks/00-index.md](prompts/homepage/blocks/00-index.md)
- `roadmap/` -> [roadmap/2025-Q4-ROADMAP.md](roadmap/2025-Q4-ROADMAP.md), [roadmap/2025-q4-roadmap.md](roadmap/2025-q4-roadmap.md)
- `wiki/` -> [wiki/AGENTS.md](wiki/AGENTS.md), [wiki/agents.md](wiki/agents.md)

Nota separata: **llm-wiki/** e **wiki/** condividono struttura e nomi di file (`log.md`, `agents.md`/`AGENTS.md`, `_templates/`, `concepts/`): sembrano due generazioni dello stesso second-brain. Da verificare quale sia la fonte attiva.

### Report datati vs. equivalente senza data (stesso contenuto presunto in evoluzione)

- (root) -> [copilot-redundancy-audit.md](copilot-redundancy-audit.md), [copilot-redundancy-audit-2026-05-25.md](copilot-redundancy-audit-2026-05-25.md)
- (root) -> [faq-comparison.md](faq-comparison.md), [faq-comparison-2026-04-03.md](faq-comparison-2026-04-03.md)
- (root) -> [footer-css-fix.md](footer-css-fix.md), [FOOTER-CSS-FIX.md](FOOTER-CSS-FIX.md), [FOOTER-CSS-FIX-2026-04-02.md](FOOTER-CSS-FIX-2026-04-02.md)
- (root) -> [homepage-parity.md](homepage-parity.md), [homepage-parity-2026-04-02.md](homepage-parity-2026-04-02.md)
- (root) -> [redundancy-audit.md](redundancy-audit.md), [redundancy-audit-2026-05-21.md](redundancy-audit-2026-05-21.md)
- (root) -> [segnalazione-css-diff.md](segnalazione-css-diff.md), [segnalazione-css-diff-2026-04-07.md](segnalazione-css-diff-2026-04-07.md)
- (root) -> [ticket-css-diff.md](ticket-css-diff.md), [ticket-css-diff-2026-04-07.md](ticket-css-diff-2026-04-07.md)
- `auth/` -> [auth/login-ux-fixes.md](auth/login-ux-fixes.md), [auth/login-ux-fixes-2026-06-04.md](auth/login-ux-fixes-2026-06-04.md)
- `auth/` -> [auth/register-ux-audit.md](auth/register-ux-audit.md), [auth/register-ux-audit-2026-06-04.md](auth/register-ux-audit-2026-06-04.md)
- `css/` -> [css/batch-parity.md](css/batch-parity.md), [css/batch-parity-2026-04-09.md](css/batch-parity-2026-04-09.md)
- `css/` -> [css/file-upload-fix.md](css/file-upload-fix.md), [css/file-upload-fix-2026-04-09.md](css/file-upload-fix-2026-04-09.md)
- `css/` -> [css/font-fix.md](css/font-fix.md), [css/font-fix-2026-04-09.md](css/font-fix-2026-04-09.md)
- `css/` -> [css/parity-session.md](css/parity-session.md), [css/parity-session-2026-04-09.md](css/parity-session-2026-04-09.md)
- `css/` -> [css/session-report.md](css/session-report.md), [css/session-report-2026-04-09.md](css/session-report-2026-04-09.md)
- `design-comuni/` -> [design-comuni/argomenti-parity.md](design-comuni/argomenti-parity.md), [design-comuni/argomenti-parity-2026-04-02.md](design-comuni/argomenti-parity-2026-04-02.md)
- `design-comuni/` -> [design-comuni/argomento-parity.md](design-comuni/argomento-parity.md), [design-comuni/argomento-parity-2026-04-02.md](design-comuni/argomento-parity-2026-04-02.md)
- `design-comuni/` -> [design-comuni/batch-body-parity.md](design-comuni/batch-body-parity.md), [design-comuni/batch-body-parity-2026-04-03.md](design-comuni/batch-body-parity-2026-04-03.md)
- `design-comuni/` -> [design-comuni/batch-structure-audit.md](design-comuni/batch-structure-audit.md), [design-comuni/batch-structure-audit-2026-04-03.md](design-comuni/batch-structure-audit-2026-04-03.md)
- `design-comuni/` -> [design-comuni/bmad-gsd-status.md](design-comuni/bmad-gsd-status.md), [design-comuni/bmad-gsd-status-2026-04-02.md](design-comuni/bmad-gsd-status-2026-04-02.md), [design-comuni/bmad-gsd-status-2026-04-03.md](design-comuni/bmad-gsd-status-2026-04-03.md)
- `design-comuni/` -> [design-comuni/css-fix-plan.md](design-comuni/css-fix-plan.md), [design-comuni/css-fix-plan-2026-04-02.md](design-comuni/css-fix-plan-2026-04-02.md)
- `design-comuni/` -> [design-comuni/css-js-parity.md](design-comuni/css-js-parity.md), [design-comuni/css-js-parity-2026-04-04.md](design-comuni/css-js-parity-2026-04-04.md)
- `design-comuni/` -> [design-comuni/css-js-pass.md](design-comuni/css-js-pass.md), [design-comuni/css-js-pass-2026-04-04.md](design-comuni/css-js-pass-2026-04-04.md)
- `design-comuni/` -> [design-comuni/domande-frequenti-parity.md](design-comuni/domande-frequenti-parity.md), [design-comuni/domande-frequenti-parity-2026-04-03.md](design-comuni/domande-frequenti-parity-2026-04-03.md)
- `design-comuni/` -> [design-comuni/homepage-structure-diff.md](design-comuni/homepage-structure-diff.md), [design-comuni/homepage-structure-diff-2026-04-02.md](design-comuni/homepage-structure-diff-2026-04-02.md)
- `design-comuni/` -> [design-comuni/html-structure-comparison.md](design-comuni/html-structure-comparison.md), [design-comuni/html-structure-comparison-2026-04-02.md](design-comuni/html-structure-comparison-2026-04-02.md)
- `design-comuni/` -> [design-comuni/live-parity.md](design-comuni/live-parity.md), [design-comuni/live-parity-2026-04-04.md](design-comuni/live-parity-2026-04-04.md)
- `design-comuni/` -> [design-comuni/risultati-ricerca-parity.md](design-comuni/risultati-ricerca-parity.md), [design-comuni/risultati-ricerca-parity-2026-04-03.md](design-comuni/risultati-ricerca-parity-2026-04-03.md)
- `design-comuni/` -> [design-comuni/SEGNALAZIONE-FIX-SESSION.md](design-comuni/SEGNALAZIONE-FIX-SESSION.md), [design-comuni/SEGNALAZIONE-FIX-SESSION-2026-04-07.md](design-comuni/SEGNALAZIONE-FIX-SESSION-2026-04-07.md)
- `design-comuni/` -> [design-comuni/SESSIONE-FINALE.md](design-comuni/SESSIONE-FINALE.md), [design-comuni/SESSIONE-FINALE-2026-04-07.md](design-comuni/SESSIONE-FINALE-2026-04-07.md)
- `design-comuni/` -> [design-comuni/SESSION-REPORT.md](design-comuni/SESSION-REPORT.md), [design-comuni/SESSION-REPORT-2026-04-04.md](design-comuni/SESSION-REPORT-2026-04-04.md), [design-comuni/SESSION-REPORT-2026-04-07.md](design-comuni/SESSION-REPORT-2026-04-07.md)
- `design-comuni/` -> [design-comuni/session-summary.md](design-comuni/session-summary.md), [design-comuni/session-summary-2026-03-30.md](design-comuni/session-summary-2026-03-30.md)
- `design-comuni/` -> [design-comuni/structural-plan.md](design-comuni/structural-plan.md), [design-comuni/structural-plan-2026-04-06.md](design-comuni/structural-plan-2026-04-06.md)
- `design-comuni/` -> [design-comuni/visual-comparison-analysis.md](design-comuni/visual-comparison-analysis.md), [design-comuni/visual-comparison-analysis-2026-04-02.md](design-comuni/visual-comparison-analysis-2026-04-02.md)
- `design-comuni/` -> [design-comuni/visual-comparison-report.md](design-comuni/visual-comparison-report.md), [design-comuni/VISUAL-COMPARISON-REPORT.md](design-comuni/VISUAL-COMPARISON-REPORT.md), [design-comuni/visual-comparison-report-2026-04-02.md](design-comuni/visual-comparison-report-2026-04-02.md)
- `design-comuni/` -> [design-comuni/visual-fix-report.md](design-comuni/visual-fix-report.md), [design-comuni/visual-fix-report-2026-04-02.md](design-comuni/visual-fix-report-2026-04-02.md)
- `design-comuni/` -> [design-comuni/visual-parity-report.md](design-comuni/visual-parity-report.md), [design-comuni/visual-parity-report-2026-04-02.md](design-comuni/visual-parity-report-2026-04-02.md)
- `design-comuni/` -> [design-comuni/VISUAL-PARITY-STATUS.md](design-comuni/VISUAL-PARITY-STATUS.md), [design-comuni/VISUAL-PARITY-STATUS-2026-04-03.md](design-comuni/VISUAL-PARITY-STATUS-2026-04-03.md)
- `design-comuni/` -> [design-comuni/wizard-summary-infolist-runtime-fix.md](design-comuni/wizard-summary-infolist-runtime-fix.md), [design-comuni/wizard-summary-infolist-runtime-fix-2026-04-22.md](design-comuni/wizard-summary-infolist-runtime-fix-2026-04-22.md)
- `design-comuni/html-structure-analysis/` -> [design-comuni/html-structure-analysis/segnalazioni-flow.md](design-comuni/html-structure-analysis/segnalazioni-flow.md), [design-comuni/html-structure-analysis/segnalazioni-flow-2026-04-06.md](design-comuni/html-structure-analysis/segnalazioni-flow-2026-04-06.md)
- `design-comuni/visual-comparison/` -> [design-comuni/visual-comparison/homepage-visual-report.md](design-comuni/visual-comparison/homepage-visual-report.md), [design-comuni/visual-comparison/homepage-visual-report-2026-04-03.md](design-comuni/visual-comparison/homepage-visual-report-2026-04-03.md)
- `visual-analysis/` -> [visual-analysis/HOMEPAGE-VISUAL-DIFF-ANALYSIS.md](visual-analysis/HOMEPAGE-VISUAL-DIFF-ANALYSIS.md), [visual-analysis/HOMEPAGE-VISUAL-DIFF-ANALYSIS-2026-04-07.md](visual-analysis/HOMEPAGE-VISUAL-DIFF-ANALYSIS-2026-04-07.md)
- `visual-comparison/` -> [visual-comparison/ANALISI.md](visual-comparison/ANALISI.md), [visual-comparison/ANALISI-2026-04-02.md](visual-comparison/ANALISI-2026-04-02.md)
- `wiki/comparisons/` -> [wiki/comparisons/header-visual-parity-audit.md](wiki/comparisons/header-visual-parity-audit.md), [wiki/comparisons/header-visual-parity-audit-2026-05-04.md](wiki/comparisons/header-visual-parity-audit-2026-05-04.md)
- `wiki/comparisons/` -> [wiki/comparisons/segnalazione-crea-step-dati-screenshot-audit.md](wiki/comparisons/segnalazione-crea-step-dati-screenshot-audit.md), [wiki/comparisons/segnalazione-crea-step-dati-screenshot-audit-2026-04-28.md](wiki/comparisons/segnalazione-crea-step-dati-screenshot-audit-2026-04-28.md)
- `wiki/comparisons/` -> [wiki/comparisons/ticket-crea-step-dati-screenshot-audit.md](wiki/comparisons/ticket-crea-step-dati-screenshot-audit.md), [wiki/comparisons/ticket-crea-step-dati-screenshot-audit-2026-04-28.md](wiki/comparisons/ticket-crea-step-dati-screenshot-audit-2026-04-28.md)
- `wiki/troubleshooting/` -> [wiki/troubleshooting/git-merge-conflict-inventory.md](wiki/troubleshooting/git-merge-conflict-inventory.md), [wiki/troubleshooting/git-merge-conflict-inventory-2026-04-28.md](wiki/troubleshooting/git-merge-conflict-inventory-2026-04-28.md)

### Cluster tematici da verificare (nomi diversi, stesso argomento presunto)

- Coppie `segnalazione-*` / `ticket-*` root con lo stesso suffisso: [segnalazione-01-privacy-css-js-parity.md](segnalazione-01-privacy-css-js-parity.md) / [ticket-01-privacy-css-js-parity.md](ticket-01-privacy-css-js-parity.md); [segnalazione-comparison-analysis.md](segnalazione-comparison-analysis.md) / [ticket-comparison-analysis.md](ticket-comparison-analysis.md); [segnalazione-crea-header-stepper-responsive.md](segnalazione-crea-header-stepper-responsive.md) / [ticket-crea-header-stepper-responsive.md](ticket-crea-header-stepper-responsive.md); [segnalazione-flow-html-parity-complete.md](segnalazione-flow-html-parity-complete.md) / [ticket-flow-html-parity-complete.md](ticket-flow-html-parity-complete.md); [segnalazione-visual-parity.md](segnalazione-visual-parity.md) / [ticket-visual-parity.md](ticket-visual-parity.md); [segnalazione-css-diff.md](segnalazione-css-diff.md) / [ticket-css-diff.md](ticket-css-diff.md) (gia' in cluster datato sopra).
- File root `product_launch_plan.md`, `product_roadmap.md`, `product-strategy.md`/`product_strategy.md`, `user_research.md` rispetto alle cartelle omonime `product/launch-plan/`, `product/roadmap/`, `product/strategy/`, `product/user-research/`.
- Cluster `agid-login-*.md` (root) vs `login-agid-*.md` (root) sullo stesso argomento con ordine delle parole invertito: verificare se sono fasi diverse o duplicati.
- [root-md-files/README-HOMEPAGE-REPLICATION.md](root-md-files/README-HOMEPAGE-REPLICATION.md) duplica [README-HOMEPAGE-REPLICATION.md](README-HOMEPAGE-REPLICATION.md) alla radice di `docs/`.

---

**Story BMAD collegata**: [stories/docs-index-audit.story.md](stories/docs-index-audit.story.md)

**Ultimo aggiornamento indice**: 2026-09-03

