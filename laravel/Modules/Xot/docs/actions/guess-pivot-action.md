# GuessPivotAction Location Correction

## What was wrong
The GuessPivotAction was incorrectly placed in `Modules/Xot/app/Models/Traits/RelationX.php`.

## Correct location
It should be in `Modules/Xot/app/Actions/<context>/GuessPivotAction.php`, for example:
- `Modules/Xot/app/Actions/Relation/GuessPivotAction.php`
- `Modules/Xot/app/Actions/Morphology/GuessPivotAction.php`

## Why the mistake happened
- The action was added without regard for the BMAD rule that Actions belong to an actor‑specific folder.
- No actor/context awareness was used when determining the placement.
- Existing Action folder structure was not consulted before creation.

## How to avoid in future
- Always locate Actions under `Modules/<Module>/app/Actions/<Actor>/` where `<Actor>` reflects the responsibility.
- Verify uniqueness with `grep -R "class.*GuessPivotAction"` before adding.
- Update the module’s `composer.json` if a new namespace is introduced.

_This correction ensures consistent placement and prevents similar errors._