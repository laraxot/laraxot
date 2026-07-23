# XotBaseField View Resolution Rule (Theme)

## Regola

Quando un field Filament estende `XotBaseField`, la view non va forzata nel field con `$view`: e' calcolata a runtime.

## Impatto per il tema

- Il tema non deve "compensare" field non conformi con override strani.
- Se un field non renderizza, si risolve il mapping view nel resolver (Xot), non nel Blade del tema.
