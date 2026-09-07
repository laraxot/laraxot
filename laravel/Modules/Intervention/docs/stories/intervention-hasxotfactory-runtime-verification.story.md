---
name: intervention-hasxotfactory-runtime-verification
description: Runtime proof that Intervention::factory() works after redundant HasXotFactory removal, plus unused import cleanup
metadata:
  type: story
  status: done
  module: Intervention
  claimed_by: claude-sonnet-5 (this session)
  supersedes: none
  related: INT-001.fix-intervention-hasxotfactory-redundant.story.md, ARX-001.hasxotfactory-inheritance-audit.story.md
---

# Story: verify + close the Intervention HasXotFactory fix

## Richiesta utente

`Intervention.php` deve estendere `Modules\Intervention\Models\BaseModel`,
`BaseModel` deve estendere `Modules\Xot\Models\XotBaseModel`, quindi
`Intervention.php` non deve avere `use HasXotFactory;` — con richiesta
esplicita di capire il perche', mettere in discussione, analizzare a
fondo.

## Controllo altri agenti (obbligatorio, non fidarsi)

`git log -- app/Models/Intervention.php`: commit `2fc1c1b`
("chore(intervention): remove redundant use HasXotFactory") gia' presente,
story `INT-001.fix-intervention-hasxotfactory-redundant.story.md` gia'
scritta e completa (non vuota, non duplicata). La richiesta dell'utente
descrive uno stato GIA' RAGGIUNTO oggi da un'altra sessione — verificato
sul codice, non sul solo commit message.

## Perche' la regola (capito, non assunto)

`XotBaseModel` dichiara `use HasXotFactory;` una sola volta, alla radice
della catena di ereditarieta'. Ogni model che estende
`Xxx\Models\BaseModel extends XotBaseModel` eredita il trait
automaticamente — PHP non richiede (ne' permette in modo sicuro)
ridichiarare `use HasXotFactory;` in una classe figlia: il trait e' gia'
nel MRO (method resolution order). Ridichiararlo localmente e' innocuo a
runtime finche' l'implementazione resta identica, ma e' un rischio latente
di drift silenzioso — se in futuro la versione locale e quella ereditata
divergono (es. un fix applicato solo a una delle due copie), il modello
userebbe silenziosamente la versione sbagliata a seconda dell'ordine di
risoluzione del trait, senza alcun errore PHPStan o runtime a segnalarlo.
Rimuoverla forza un'unica fonte di verita' (DRY reale, non solo estetico).

## Verifica reale (root cause del "perche' capire prima di fare")

La cronaca di oggi (`docs/chat/2026-09-07-URGENT-hasxotfactory-*.md`, 5
regressioni distinte sullo stesso trait) mostra che rimuovere
`HasXotFactory` in altri contesti (dalla trait stessa, da `XotBaseModel`)
ha rotto `Model::factory()` a runtime SENZA alcun errore PHPStan — PHP non
segnala un metodo chiamato con piu' argomenti di quelli dichiarati, si
limita a scartarli. Per questo non basta fidarsi di "PHPStan e' verde",
serve una prova runtime:

```php
$f = \Modules\Intervention\Models\Intervention::factory();
// => Modules\Intervention\Database\Factories\InterventionFactory (risolto correttamente)
```

Verificato via `php artisan tinker` (nessun test Pest dedicato trovato per
isolare solo questo — i test Feature esistenti che usano
`Intervention::factory()` falliscono per limiti pre-esistenti
dell'ambiente di test condiviso, tabella `roles` mancante sulla
connessione `mariadb`, scorrelato). `factory()` risolve correttamente la
classe concreta della factory: prova che l'ereditarieta' del trait
funziona senza la ridichiarazione locale.

## Fix aggiuntivo trovato durante la verifica

`use Modules\Intervention\Database\Factories\InterventionFactory;` era
rimasto come import orfano (nessun altro riferimento nel file) dopo la
rimozione precedente del trait — probabile residuo della stessa modifica.
Rimosso.

## Verifica

`phpstan analyse Modules/Intervention`: 0 errori (cache pulita).
`phpmd Modules/Intervention/app/Models/Intervention.php`: 2 findings
pre-esistenti non correlati (`CouplingBetweenObjects`, 2x `LongVariable`),
nessuno introdotto da questa modifica. Runtime `factory()`: verificato
funzionante come sopra.

## Esito

Stato gia' corretto (catena `Intervention extends BaseModel extends
XotBaseModel`, nessun `HasXotFactory` locale). Aggiunta prova runtime
mancante nella story precedente + cleanup import orfano.
