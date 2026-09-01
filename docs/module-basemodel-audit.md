---
title: BaseModel di modulo nei moduli portati
status: applicato
data: 2026-09-01
---

# BaseModel di modulo

## Regola

Un modello estende **solo** il `BaseModel` del proprio modulo. Mai
`Illuminate\Database\Eloquent\Model`, mai `XotBaseModel` direttamente.

Il `BaseModel` e' astratto, estende `XotBaseModel` e dichiara la connection del
modulo. E' il punto in cui un modulo puo' cambiare comportamento a tutti i suoi
modelli senza toccare la base condivisa: se non esiste, quella leva non c'e'.

## Cosa mancava

I cinque moduli di questa tranche non avevano un `BaseModel`.

| Modulo | Modelli | Estendevano |
|---|---|---|
| Wts | 11 + 1 pivot | `Model` |
| Signage | 8 | `XotBaseModel` |
| MetalWork | 6 | `XotBaseModel` |
| EnergyBroker | 6 | `XotBaseModel` |
| Costing | 1 | `XotBaseModel` |

Chi estendeva `Model` non aveva nemmeno la connection: scriveva sulla default.

## Il pivot di Wts

`Wts/Models/BasePivot` era una copia letterale di quello di `User`, con dentro
`protected $connection = 'user'`. La tabella `wts_processing_step_unit` e' definita
da una migration di Wts, ma il pivot la cercava sulla connection di User. In test
non si vede — tutte le connection sqlite puntano allo stesso fixture — e in
sviluppo neanche, finche' i due moduli condividono il database. Si sarebbe visto
il giorno in cui vengono separati.

Ora estende `XotBasePivot` con `$connection = 'wts'`.

## Conseguenza: gli id diventano stringhe

`XotBaseModel::casts()` dichiara `'id' => 'string'`. Passando i modelli sotto
`BaseModel` l'id smette di essere un intero, e 23 firme di Wts che dichiaravano
`int $unitId`, `?int $lotId` lo rifiutavano. Sono state allargate a `int|string`,
insieme alle array shape e alle `list<int>` che li trasportavano.

Un confronto stretto e' rimasto rotto anche dopo:

```php
$unit->inbound_lot_id !== $expectedLotId   // int !== string: sempre vero
```

`inbound_lot_id` e' una chiave esterna, non e' castata; `$expectedLotId` arriva
dal modello ed e' una stringa. Il risultato era "All selected units must belong to
the expected lot" su unita' che appartenevano al lotto giusto. Ora il confronto
avviene fra stringhe.

E' la stessa famiglia di difetti descritta in [uuid-user-id-audit.md](uuid-user-id-audit.md):
un tipo dichiarato che non coincide con il tipo reale del valore.

## Verifica

| Modulo | Test |
|---|---|
| Costing | 10 |
| MetalWork | 42 |
| Signage | 53 |
| EnergyBroker | 22 |
| Wts | 208 |

PHPStan: zero errori in `app/` sui cinque moduli.

## Come cercarlo altrove

```bash
# modelli che saltano il BaseModel del modulo
grep -rn "extends XotBaseModel\|extends Model\b" Modules/*/app/Models/*.php | grep -v BaseModel.php

# moduli senza BaseModel ma con modelli
for m in Modules/*/; do
  [ -d "$m/app/Models" ] && [ ! -f "$m/app/Models/BaseModel.php" ] && echo "$m"
done

# pivot con la connection di un altro modulo
grep -rn "connection = " Modules/*/app/Models/BasePivot.php
```
