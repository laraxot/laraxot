# Laravel 13 Package Boundary For Themes

## Scopo

Chiarire il confine tema/modulo durante la reintroduzione dipendenze dopo upgrade a Laravel 13.

## Regola

- I pacchetti applicativi si dichiarano nel modulo owner (es. `Modules/Xot`), non nel tema.
- Il tema consuma il runtime risultante, non governa il lock Composer cross-modulo.

## Stato corrente

- Reintroduzione effettuata in owner corretto: `fruitcake/laravel-debugbar` in `Modules/Xot`.
- Nessuna nuova dipendenza Composer aggiunta in `Themes/Sixteen`.

## Perche'

- Riduce coupling tra layer visuale e layer applicativo.
- Mantiene DRY/KISS: il tema resta owner di CSS/Blade/asset parity, non di dependency graph backend.

## Riferimenti

- [root modular package decision](../../../../../../docs/wiki/concepts/laravel13-modular-package-reintroduction.md)
- [xot compatibility matrix](../../../../Modules/Xot/docs/wiki/concepts/laravel13-modular-package-compatibility-matrix.md)
