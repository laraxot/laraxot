---
title: "Porting gestionale_commesse — EnergyBroker"
type: concept
tags: [import, gestionale-commesse, energybroker, reshape]
created: 2026-07-23
updated: 2026-07-23
qmd: "porting gestionale_commesse EnergyBroker reshape XotBase"
related:
  - "./import-status.md"
  - "./README.md"
---

# Porting gestionale_commesse — EnergyBroker

## Contesto

Il modulo era stato riportato a una copia grezza DDD da un processo concorrente che
ha annullato un precedente reshape XotBase. Questa sessione ha rifatto il reshape:

- Modelli in `app/Models` che estendono `XotBaseModel` (già presenti, verificati).
- Provider `EnergyBrokerServiceProvider` estende `XotBaseServiceProvider`;
  `EventServiceProvider`/`RouteServiceProvider` XotBase-derivati con stub route.
- Filament Resources in `app/Filament/Resources` estendono `XotBaseResource`.
- Tutte le Actions in `app/Actions` ora usano il trait
  `Spatie\QueueableAction\QueueableAction` (mancava in tutti i file).
- Rimossa la dichiarazione `final readonly class` su
  `ConfirmEnergyBrokerDocumentImportAction` (incompatibile con il trait
  QueueableAction) — ora `final class`.
- Aggiunte cartelle `lang/it` e `lang/en` con `module.php` minimale (mancavano).
- Aggiunto test di fumo Pest in `tests/Unit/EnergyBrokerModuleSmokeTest.php`.
- Corretto `getNavigationGroup(): ?string` → `: string` su
  `EnergyBrokerDocumentResource` (errore PHPStan `return.unusedType`).
- `module.json`/`composer.json` già corretti (namespace, provider, PSR-4).

## PHPStan

Eseguito `./vendor/bin/phpstan analyse Modules/EnergyBroker ... --memory-limit=-1`.
Errori residui concentrati soprattutto nelle Actions (import/analisi documenti,
integrazioni AI), nei Jobs e negli Http Controllers (grosso volume di tipizzazione
da rifinire, tipico di un modulo con logica di import massivo/AI). Non rincorsi oltre
la correzione degli errori semplici, sforzo sproporzionato per un modulo a bassa
priorità.

## Stato modulo

`modules_statuses.json`: `false` (disabilitato) — confermato dormiente per questa sessione.

## Rilevanza per la base clienti

EnergyBroker (intermediazione energetica, punti di fornitura, importazione bollette/
documenti con AI) è una verticale specialistica del settore energia, distante dal core
gestionale commesse. Nessun segnale di domanda specifica nella base clienti attuale.
Suggerito restare dormiente fino a una richiesta esplicita da un cliente broker
energetico.
