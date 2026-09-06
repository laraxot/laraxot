---
<<<<<<< HEAD
title: "Xot Module — Doctrine"
type: doctrine
tags: [xot, foundation, module-doctrine]
created: 2026-09-05
updated: 2026-09-05
qmd: "Xot module doctrine BMAD analysis purpose religion philosophy policy why zen gap enhancements split merge"
related:
  - "./quality-roadmap.md"
  - "./import-status.md"
  - "../../../docs/wiki/concepts/laraxot-module-canonical-structure.md"
---

=======
<<<<<<< HEAD
module: theme
topic: module
canonical: ../../../Themes/docs/shared-components/module-analysis-Modules.md
---

See canonical documentation: ../../../Themes/docs/shared-components/module-analysis-Modules.md
=======
title: "Xot Module — Doctrine"
type: doctrine
tags: [xot, foundation, module-doctrine]
created: 2026-09-05
updated: 2026-09-05
qmd: "Xot module doctrine BMAD analysis purpose religion philosophy policy why zen gap enhancements split merge"
related:
  - "./quality-roadmap.md"
  - "./import-status.md"
  - "../../../docs/wiki/concepts/laraxot-module-canonical-structure.md"
---

>>>>>>> 28b0298a (fix: phpstan issues)
# Xot Module — Doctrine

## Scope (Scopo)

Xot è il fondamento assoluto di tutto il monorepo Laraxot. Fornisce le classi base (`XotBaseModel`, `XotBaseResource`, `XotBaseAction`, `XotBaseServiceProvider`, `XotBaseMigration`, `XotBaseTestCase`) che ogni altro modulo estende o utilizza. È il livello più basso dell'astrazione: senza Xot, nessun altro modulo potrebbe esistere.

## Religion (Religione)

**"Una sola classe base, 47 moduli trasformati."** La convinzione non negoziabile è che tutte le modifiche al comportamento comune debbano avvenire in XotBase, così che un singolo cambio si propaghi automaticamente a tutti i moduli. Xot è la Bibbia del monorepo: chi tocca Xot, tocca tutto.

## Philosophy (Filosofia)

- **DRY al livello framework**: mai duplicare logica comune in due moduli
- **Mai usare Filament direttamente**: tutte le estensioni passano per XotBaseResource o XotBaseWidget
- **Actions > Services**: ogni logica operativa va in una Queueable Action con metodo `execute()`
- **PHPStan Level 10**: zero tolleranza per tipi `mixed`, `any`, o `null` non dichiarati
- **PSR-12**: codice che parla lo stesso linguaggio ovunque

## Policy (Politica)

- Tutti i modelli devono estendere `XotBaseModel`
- Nessun `Domain/` directory (debito tecnico da gestionale_commesse, da migrare)
- Ogni modulo ha provider, route, filamente, factory, seeder, test
- Autori obbligatori nel composer.json: Marco Sottana + Davide Cavallini
- Repositori path a Xot, Tenant, UI obbligatori

## Why (Perché)

Xot esiste perché senza un layer di base comune, ogni modulo avrebbe duplicato modelli, servizi, configurazioni, test setup. Con Xot, un bugfix in XotBaseModel si riflette su 47 moduli immediatamente. È il principio della singola fonte di verità applicato all'infrastruttura.

## Zen

*"DRY e KISS portati all'estremo logico: una classe base, 47 moduli, zero ridondanza."*

## Gap

- Test coverage per edge actions (PDF, traduzioni complesse)
- Widget variants aggiuntivi per Filament 5
- Documentazione architetturale interna più dettagliata

## Add

- Più varianti di widget per Filament 5 (tables, charts, widgets)
- Script di migrazione automatica per il debito `Domain/` residuo
- Template di generazione moduli con XotBase integrato

## Split/Merge

**Mantenere come-is.** Xot è il cuore del monorepo. Non può essere né spezzato né fuso: è il livello di astrazione più basso e ogni frammentazione romperebbe il contratto `XotBase*` su cui si regge tutto il resto.

## Future Enhancements

1. **XotBaseResource v2**: supporto nativo per Filament 5 con nuovo schema di risorse
2. **XotBaseAction**: aggiungere pattern `HandleErrors`, `WithCache`, `WithEvents` come trait composabili
3. **XotBaseMigration**: aggiungere metodi `addSoftDeletes()`, `addAuditColumns()`, `addTenantColumn()`
4. **XotBaseTestCase**: aggiungere assert specifici per modelli, resource, action
5. **Schema Registry**: un file centrale che registra tutti i modelli, le loro relazioni, e le chiavi straniere — generabile da PHPStan
6. **Module Generator**: comando Artisan `make:laraxot-module` che genera tutto lo scaffold con XotBase integrato
7. **AI-assisted code generation**: integrare LLM per generare Action, Resource, Model dai migration
<<<<<<< HEAD
=======
>>>>>>> 7f6cf6be (.)
>>>>>>> 28b0298a (fix: phpstan issues)
