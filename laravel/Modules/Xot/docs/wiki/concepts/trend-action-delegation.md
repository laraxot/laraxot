---
title: "Trend Action: delegare al pacchetto owner"
type: concept
module: Xot
tags: [xot, trend, queueable-action, phpstan, reuse]
created: 2026-07-13
updated: 2026-07-13
qmd: "Xot BuildTrendCollectionAction flowframe laravel trend delegate PHPStan generics"
issues:
  - "https://github.com/laraxot/base_fixcity_fila5/issues/372"
discussions:
  - "https://github.com/laraxot/base_fixcity_fila5/discussions/273"
related:
  - ./queueable-action-trait-mandatory.md
  - ./xot-services-support-to-actions.md
---

# Trend Action: delegare al pacchetto owner

`BuildTrendCollectionAction` è il confine applicativo uniforme:
`app(BuildTrendCollectionAction::class)->execute(...)`. Non deve copiare il motore SQL,
la selezione del driver o la costruzione di `CarbonPeriod` già forniti dal pacchetto
installato `flowframe/laravel-trend`.

## Decisione

- l'Action conserva `QueueableAction` e il solo ingresso pubblico `execute()`;
- il calcolo viene delegato a `Flowframe\Trend\Trend`;
- gli aggregati ammessi sono chiusi a `avg`, `min`, `max`, `sum` e `count`;
- il risultato non tipizzato del pacchetto viene verificato a runtime come `TrendValue`
  e trasformato nel DTO di modulo `TrendData`;
- `Builder<TModel>` e `Collection<int, TrendData>` descrivono il flusso reale senza
  baseline, ignore o cast PHPDoc locali.

## Perché

La precedente conversione da Service duplicava quasi integralmente il pacchetto già
installato. Questo produceva due implementazioni da mantenere e dodici segnalazioni
PHPStan su generics, callback `mixed`, espressioni SQL e connessioni. Il confine Action
serve; la seconda implementazione del motore Trend no.

La ricerca repo-wide non ha trovato chiamanti diretti dell'Action al 2026-07-13. Il
contratto resta disponibile per chiamate dinamiche Laravel e per la migrazione graduale
dei widget, senza estendere nuovamente `Services/`.

## Verifica minima

```bash
cd laravel
php -l Modules/Xot/app/Actions/Trend/BuildTrendCollectionAction.php
php -d memory_limit=2048M ./vendor/bin/phpstan analyse \
  Modules/Xot/app/Actions/Trend/BuildTrendCollectionAction.php --no-progress
```
