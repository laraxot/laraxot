# Story: StatusFactory missing generics

## Scopo (BMAD: scopo prima dell'errore)
StatusFactory estende Factory generico per generare Status di test. Lo scopo non era solo passare PHPStan ma fornire un seed per test con tipi veri.

## Causa radice
Class extends generic Factory senza parametri generics; `protected $model` non è tipizzato come class-string.

## Fix
Aggiungere `/** @extends Factory<Status> */` e tipizzare `$model`.
