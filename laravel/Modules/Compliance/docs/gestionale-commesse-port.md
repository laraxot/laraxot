---
title: "Porting gestionale_commesse — Compliance"
type: concept
tags: [import, gestionale-commesse, compliance, reshape]
created: 2026-07-23
updated: 2026-07-23
qmd: "porting gestionale_commesse Compliance reshape XotBase"
related:
  - "./import-status.md"
  - "./README.md"
---

# Porting gestionale_commesse — Compliance

## Contesto

Il modulo era stato riportato a una copia grezza DDD (Domain/Application/Infrastructure,
namespace singolare) da un processo concorrente che ha annullato un precedente reshape
XotBase. Questa sessione ha rifatto il reshape:

- Modelli in `app/Models` che estendono `XotBaseModel` (già presenti, verificati).
- Provider `ComplianceServiceProvider` estende `XotBaseServiceProvider`; presenti anche
  `EventServiceProvider` (`XotBaseEventServiceProvider`) e `RouteServiceProvider`
  (`XotBaseRouteServiceProvider`) con stub route in `routes/api.php` e `routes/web.php`.
- Filament Resources in `app/Filament/Resources` estendono `XotBaseResource`.
- Tutte le Actions in `app/Actions` ora usano il trait
  `Spatie\QueueableAction\QueueableAction` (mancava in tutti i file).
- Rimossa la dichiarazione `final readonly class` su
  `AttachComplianceDocumentFileAction` (incompatibile con il trait QueueableAction,
  che ha una proprietà `$job` non readonly) — ora `final class`.
- Aggiunte cartelle `lang/it` e `lang/en` con `module.php` minimale (mancavano).
- Aggiunto test di fumo Pest in `tests/Unit/ComplianceModuleSmokeTest.php`
  (verifica esistenza classi core: provider + modelli).
- `module.json`/`composer.json` già corretti (namespace, provider, PSR-4).

## Nota importante — separazione da Gdpr

Compliance riguarda la conformità tecnica di impianti/sistemi (POS — Piano di
Manutenzione/controllo, RCEE — Rapporto di Controllo Efficienza Energetica,
caldaie, sistemi di riscaldamento). **Non è stato toccato né unito** al modulo
`Modules/Gdpr` (conformità privacy/dati personali), che resta completamente separato
come richiesto.

## PHPStan

Eseguito `./vendor/bin/phpstan analyse Modules/Compliance ... --memory-limit=-1`.
Errori residui concentrati soprattutto in `database/seeders` (tipizzazione argomenti,
property.notFound) e in alcune Actions/Models con PHPDoc generics da rifinire.
Non rincorsi oltre il livello "sistemare gli errori semplici": la correzione completa
richiederebbe uno sforzo sproporzionato per un modulo a bassa priorità.

## Stato modulo

`modules_statuses.json`: `false` (disabilitato) — confermato dormiente per questa sessione.

## Rilevanza per la base clienti

Compliance (POS/RCEE impianti termici) è una verticale di nicchia rispetto al core
gestionale (commesse/lavori). Non risultano segnali nella base clienti attuale di
un'esigenza specifica per questo dominio (gestione manutenzioni caldaie/RCEE).
Suggerito restare dormiente fino a una richiesta esplicita di un cliente in questo
settore (impiantistica termica/manutenzione).
