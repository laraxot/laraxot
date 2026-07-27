---
title: "Ogni modulo: minimo 2 provider, in composer.json E module.json"
type: concept
module: Xot
tags: [filament, provider, composer, module-json, module-convention]
created: 2026-07-27
updated: 2026-07-27
related:
  - ./module-adminpanelprovider-mandatory.md
  - ./module-dashboard-page-mandatory.md
  - ./module-config-config-php.md
---

# Doppia registrazione provider — `composer.json` + `module.json`

## Regola

Ogni modulo deve elencare **almeno 2 provider**, negli **stessi** in entrambi i file:

```json
"providers": [
    "Modules\\{Nome}\\Providers\\{Nome}ServiceProvider",
    "Modules\\{Nome}\\Providers\\Filament\\AdminPanelProvider"
]
```

- `composer.json` → chiave `extra.laravel.providers` (auto-discovery Composer/Laravel
  quando il modulo è installato/risolto come package, es. `repositories` path-type tra
  moduli — vedi `TimberBilling/composer.json` che richiede `../Xot` così).
- `module.json` → chiave `providers` (meccanismo di boot interno di
  `nwidart/laravel-modules`, letto da `ModuleManifest`/`FileActivator`).

## Perché due file, non uno solo

Sono **due meccanismi di scoperta indipendenti**: un modulo installato/risolto solo
come dipendenza Composer (path repository) userebbe l'auto-discovery di
`composer.json`; lo stesso modulo eseguito dentro l'app via `nwidart/laravel-modules`
usa `module.json`. In questo monorepo entrambi i percorsi sono attivi
contemporaneamente (i moduli si richiedono a vicenda via `repositories` path in
`composer.json`, es. `TimberBilling` → `../Xot`), quindi **serve la stessa lista in
entrambi** — un provider registrato in uno solo dei due file boota in un contesto ma
non nell'altro, con sintomi silenziosi e difficili da diagnosticare (nessun errore
esplicito, semplicemente il panel/le risorse non compaiono in un contesto specifico).

## I due provider minimi e perché servono entrambi

1. **`{Nome}ServiceProvider`** — boot generico del modulo (config merge, rotte, view,
   traduzioni, migration path, ecc.).
2. **`Providers\Filament\AdminPanelProvider`** — registra il panel Filament dedicato
   del modulo (vedi
   [module-adminpanelprovider-mandatory.md](./module-adminpanelprovider-mandatory.md)).
   Senza questo in **entrambi** i file, l'intera UI Filament del modulo resta
   irraggiungibile in almeno uno dei due contesti di caricamento.

## Audit

```bash
for d in laravel/Modules/*/; do
  m=$(basename "$d")
  [ -f "$d/module.json" ] || continue
  c_ok=$(grep -q "AdminPanelProvider" "$d/composer.json" 2>/dev/null && echo yes || echo no)
  m_ok=$(grep -q "AdminPanelProvider" "$d/module.json" 2>/dev/null && echo yes || echo no)
  [ "$c_ok" = "no" ] || [ "$m_ok" = "no" ] && echo "$m: composer.json=$c_ok module.json=$m_ok"
done
```

Validare anche la sintassi JSON di entrambi i file dopo qualunque modifica manuale:

```bash
php -r 'json_decode(file_get_contents("Modules/{Nome}/composer.json")); echo json_last_error()===JSON_ERROR_NONE?"ok\n":"INVALID\n";'
```

## Incidente (2026-07-27)

`TimberBilling/composer.json` aveva solo `TimberBillingServiceProvider`, mancava
`AdminPanelProvider` (presente invece in `module.json`) — asimmetria tra i due file
individuata seguendo l'istruzione esplicita dell'utente di controllare entrambi.
Verificato che tutti i 38 moduli reali ora hanno gli stessi 2 provider in entrambi i
file, JSON valido ovunque.

## Non fare

- Non assumere che aggiornare `module.json` sia sufficiente — verificare sempre anche
  `composer.json` con lo stesso audit.
- Non inventare provider aggiuntivi "per sicurezza" — il minimo di 2 è quello che
  serve; provider extra vanno aggiunti solo quando il modulo ne registra davvero altri
  (event service provider, route service provider dedicati, ecc.), non per convenzione
  di questo documento.
