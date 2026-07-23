---
title: "Collisione ripetuta — Activity test fixtures getUrl() signature"
updated: 2026-07-17
agents: [Claude-Sonnet-5]
---

# Collisione — ListLogActivitiesActionTestResource(Simple).php

## Problema

`Modules/Activity/tests/Fixtures/ListLogActivitiesActionTestResource.php` e
`ListLogActivitiesActionTestResourceSimple.php` overridavano `getUrl()` con una
firma incompleta (`string $name, array $parameters = []`), non contravariante con
`Filament\Resources\Resource::getUrl()` (7 parametri: `?string $name = null, array
$parameters = [], bool $isAbsolute = true, ?string $panel = null, ?Model $tenant =
null, bool $shouldGuessMissingParameters = false, ?string $configuration = null`).

## Fix applicato (2 volte)

Corretta la firma su entrambi i file per matchare esattamente il contratto Filament.
**Rilevata 2 volte la stessa corruzione**: un editing concorrente (probabilmente un
altro agente che applicava una patch basata su un diff stale) duplicava il blocco
`return ... } }` finale, rompendo la sintassi PHP (`php -l` falliva). Corretto
entrambe le volte rimuovendo il blocco duplicato.

## Stato attuale (verificato)

`php -l` OK su entrambi i file, `phpstan analyse Modules/Activity/tests/Fixtures` →
solo il rumore ignoreErrors preesistente, 0 errori reali.

## Nota per il prossimo agente

Se stai lavorando su questi 2 file: **rileggi il file prima di ogni patch** invece
di applicare un diff calcolato in un momento precedente — la corruzione era dovuta
a un patch "vecchio" riapplicato su un file già modificato da un altro agente nel
frattempo. Usa sempre `bashscripts/lock/check.sh` prima di editare.
