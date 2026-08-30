# Import status — Costing

Modulo **nuovo** della seconda tranche (sorgente `gestionale_commesse/Modules/Costing`).

| Sorgente | Destinazione |
|---|---|
| `Domain/Support/MarkupResolver.php` | `app/Support/MarkupResolver.php` |
| `Domain/Support/MarkupDecision.php` | `app/Datas/MarkupDecision.php` |
| `Infrastructure/Models/WorkCenter.php` | `app/Models/WorkCenter.php` (estende `XotBaseModel`) |
| `Database/Migrations/...create_costing_work_centers_table` | `database/migrations/2026_08_30_220000_...` (`XotBaseMigration`) |
| `Database/Factories/WorkCenterFactory.php` | `database/factories/WorkCenterFactory.php` |

Non portata la migrazione `move_module_work_centers_into_costing`: sposta le righe
dalle tabelle di Signage e MetalWorks, che in questa base non esistono ancora.
Quando i due moduli arriveranno nasceranno già sulla tabella condivisa.

`$model_class` è dichiarato esplicitamente nella migrazione: il nome derivato dal
filename sarebbe `CostingWorkCenter`, ma il prefisso della tabella è il modulo e il
modello si chiama solo `WorkCenter`.

Test: 10 verdi (7 unit sul resolver, 3 feature sul modello). PHPStan: pulito.
