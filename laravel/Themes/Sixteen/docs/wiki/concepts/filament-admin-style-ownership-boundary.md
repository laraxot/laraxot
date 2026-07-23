# Filament admin style ownership boundary

## Scopo

Evitare assunzioni errate tra stile frontoffice Sixteen e rendering del pannello Filament admin.

## Regola

Sixteen e' owner del frontoffice.  
Il panel admin Filament ha un contesto UI separato: fix CSS del tema non garantiscono automaticamente fix nel panel.

## Implicazioni

- bug mappa in route admin (`/fixcity/admin/...`) vanno verificati nel panel stesso
- la parity frontoffice non puo' essere usata come unico criterio di chiusura bug admin
- la documentazione tema deve esplicitare questo boundary per ridurre diagnosi fuorvianti

## Riferimenti

- [fixcity admin ticket create map visual contract](../../../Modules/Fixcity/docs/wiki/concepts/admin-ticket-create-map-visual-contract.md)
- [geo admin panel map visibility contract](../../../Modules/Geo/docs/wiki/concepts/filament-admin-panel-map-visibility-contract.md)
