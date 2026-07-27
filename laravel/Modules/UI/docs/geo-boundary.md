---
title: "Confine UI e Geo"
type: rule
module: UI
created: 2026-07-06
updated: 2026-07-22
related:
  - "./second-brain.md"
  - "./00-index.md"
  - "./filosofia-modulo-ui.md"
---

# Confine UI e Geo

## Perché (religione)

`UI` = design system: componenti visuali generici e riusabili.

Mappe, geocoding, marker, regioni/province/CAP, export GeoJSON/KML = **dominio geografico**.
Quel dominio vive in `Modules/Geo` (quando il progetto lo include), **mai** in `UI`.

Direzione dipendenze: **Geo → UI** (Geo può usare primitive UI). Mai il contrario.

## Questo progetto (`base_ptvx_fila5`)

`laravel/Modules/Geo` **non esiste** e **non deve essere reintrodotto** senza decisione esplicita.
Quindi in UI non devono restare neanche fallback/null-object “per quando Geo manca”: senza Geo non serve il layer.

## Vietato in UI

- Namespace `Modules\Geo\*`
- `app/Adapters/Location/`, `app/Adapters/Map/`
- Contratti `LocationDataProviderContract`, `MapServiceContract`, `GeocodingServiceContract`
- `LocationSelector`, `InteractiveMap` (e view correlate)
- Service/adapter null-object di mappa/geocoding

## Come è stato corretto (2026-07-22)

**Problema:** in UI restavano adapter/contract/selector geografici anche se `Geo` non esiste in questo monorepo — violazione del confine (dominio in design system).

**Fix (forward-only, niente `git restore`):**

1. Eliminati `app/Adapters/Location/` e `app/Adapters/Map/` (e la cartella `app/Adapters/` se vuota).
2. Eliminati i contratti `LocationDataProviderContract`, `MapServiceContract`, `GeocodingServiceContract`.
3. Eliminato `LocationSelector.php` attivo (non reintrodurre come “null-adapter”).
4. Rimosso da `UIServiceProvider` il `bindIf` / registrazione verso null-adapters Geo.
5. Canon aggiornato qui + [second-brain.md](./second-brain.md) + [wiki/concepts/ui-geo-boundary-contracts.md](./wiki/concepts/ui-geo-boundary-contracts.md).
6. Push dual-remote (`laraxot` + `provtv`) a tip `b874935` — vedi [wiki/troubleshooting/git-push-lfs-missing-objects.md](./wiki/troubleshooting/git-push-lfs-missing-objects.md) e [multi-org-sync-laraxot-provtv.md](./multi-org-sync-laraxot-provtv.md).

**Anti-pattern:** ricreare contract+null in UI “perché Geo manca”. Senza Geo non serve il layer.

## Storia (forward-only)

Rimosso il 2026-07-22 da UI (git history = archivio; **no** `docs/archive/`):

- `app/Adapters/Location/`, `app/Adapters/Map/`
- contratti Location/Map/Geocoding
- `LocationSelector.php` attivo
- `bindIf` in `UIServiceProvider` verso null-adapters

Se in un altro monorepo servirà geografia: implementare in `Modules/Geo`, non ricopiare in UI.

## Verifica

```bash
cd laravel/Modules/UI
test ! -d app/Adapters
test ! -f app/Contracts/LocationDataProviderContract.php
test ! -f app/Contracts/MapServiceContract.php
test ! -f app/Contracts/GeocodingServiceContract.php
test ! -f app/Filament/Forms/Components/LocationSelector.php
grep -R "Modules\\\\Geo" app/ --include="*.php" || true
```

## Cross-reference

- [second-brain.md](./second-brain.md)
- [filosofia-modulo-ui.md](./filosofia-modulo-ui.md)
