# livewire cache table rate limiter

## Contesto tema

Le pagine frontoffice del tema (`segnalazione-crea` inclusa) invocano update Livewire.
Quando `RateLimiter` di Livewire usa cache database, la mancanza tabella `cache`
porta a errore 500 anche se la UI tema è corretta.

## Dipendenza runtime

Il tema dipende dal corretto stato infrastrutturale Laravel:

- `cache`
- `cache_locks`

senza queste tabelle, le interazioni Livewire possono fallire.

## Hardening applicato

- Migrazione cache principale eseguita.
- Migrazione duplicata `2026_04_21_112114_create_cache_table` resa idempotente
  (guard `hasTable('cache')`) per prevenire future rotture su `php artisan migrate`.

## Allineamento con modulo Fixcity

Vedi regola modulo:
- [livewire-cache-table-rate-limiter](../../../../Modules/Fixcity/docs/wiki/concepts/livewire-cache-table-rate-limiter.md)
