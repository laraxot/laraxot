---
title: "Porting gestionale_commesse — PublicProcurement"
type: concept
tags: [import, gestionale-commesse, publicprocurement, reshape]
created: 2026-07-23
updated: 2026-07-23
qmd: "porting gestionale_commesse PublicProcurement reshape XotBase"
related:
  - "./import-status.md"
  - "./README.md"
---

# Porting gestionale_commesse — PublicProcurement

## Contesto

Il modulo era stato riportato a una copia grezza DDD da un processo concorrente che
ha annullato un precedente reshape XotBase. Questa sessione ha rifatto il reshape:

- Modelli in `app/Models` che estendono `XotBaseModel` (già presenti, verificati).
- Provider `PublicProcurementServiceProvider` estende `XotBaseServiceProvider`;
  `EventServiceProvider`/`RouteServiceProvider` XotBase-derivati con stub route.
- Filament Resources in `app/Filament/Resources` estendono `XotBaseResource`.
- Tutte le Actions in `app/Actions` ora usano il trait
  `Spatie\QueueableAction\QueueableAction` (mancava in tutti i file).
- Aggiunte cartelle `lang/it` e `lang/en` con `module.php` minimale (mancavano).
- Aggiunto test di fumo Pest in `tests/Unit/PublicProcurementModuleSmokeTest.php`.
- Corretto `getNavigationGroup(): ?string` → `: string` su risorse Filament che non
  ritornano mai null (errore PHPStan `return.unusedType`).
- `module.json`/`composer.json` già corretti (namespace, provider, PSR-4).

## PHPStan

Eseguito `./vendor/bin/phpstan analyse Modules/PublicProcurement ... --memory-limit=-1`.
Errori residui concentrati soprattutto nelle Actions (tipizzazione argomenti/proprietà)
e in alcune Filament Resources. Non rincorsi oltre la correzione degli errori semplici,
per non investire uno sforzo sproporzionato su un modulo a bassa priorità.

## Stato modulo

`modules_statuses.json`: `false` (disabilitato) — confermato dormiente per questa sessione.

## Rilevanza per la base clienti

PublicProcurement (gare d'appalto pubbliche, bandi, contratti PA) è una verticale
specialistica non tipica per la base clienti attuale del gestionale (prevalentemente
commesse/lavori privati). Nessun segnale di domanda specifica in questo dominio.
Suggerito restare dormiente fino a una richiesta esplicita da un cliente operante
nel settore appalti pubblici.
