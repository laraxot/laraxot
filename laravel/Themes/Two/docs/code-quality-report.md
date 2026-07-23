# Code quality — tema Two

Report locale (2026-07-17). Metodo: `phpstan analyse` (sweep repo-wide, incluso nei Themes), `phpmd` (codesize+unusedcode), grep mirati (TODO/FIXME, dd()/dump() nei .blade.php, facade dirette in app/Actions).

## Numeri

- File PHP applicativi (`app/`): 1
- File Blade: 274
- File con TODO/FIXME/@deprecated: 0
- `dd()`/`dump()`/`var_dump()` residui in Blade: 1
- Violazioni PHPMD (codesize+unusedcode): 32
- Facade Laravel dirette in `app/Actions/` (violazione pattern QueueableAction): 0
- PHPStan: incluso nello sweep repo-wide, 0 errori residui noti

### Blade con dd()/dump() da rimuovere

- Themes/Two/docs/Main_files/resources/views/pages/auth/logout.blade.php

## Azioni consigliate

- Rimuovere i `dd()`/`dump()` residui dalle view elencate.
- PHPMD segnala 32 violazioni codesize/unusedcode: rivedere i metodi/classi più complessi (vedi output completo phpmd).
- La qualità delle view Blade/Volt (duplicazione, componenti riusabili) non è stata misurata quantitativamente in questo giro — possibile follow-up con un audit dedicato ai componenti.


## Come migliorare — modifiche effettive da fare

