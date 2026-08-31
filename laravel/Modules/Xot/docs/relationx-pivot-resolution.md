---
title: "RelationX — risoluzione automatica dei pivot e relazioni cross-database"
slug: relationx-pivot-resolution
module: Xot
type: analysis
status: active
language: it-IT
updated: 2026-08-06
source_of_truth: true
tags: [eloquent, relations, pivot, multi-database, belongsToMany, morphToMany, xot]
repository: git@github.com:laraxot/module_xot_fila5.git
issues: https://github.com/laraxot/module_xot_fila5/issues?q=is%3Aissue+RelationX
discussions: https://github.com/laraxot/module_xot_fila5/discussions?discussions_q=RelationX
related:
  - custom-relation.md
  - stories/story-relationx-pivot-resolution-hardening.md
---

# RelationX — risoluzione automatica dei pivot

`Modules\Xot\Models\Traits\RelationX` risolve due problemi che Eloquent da solo
non copre in questo progetto:

1. **Convenzione al posto della configurazione.** `$this->belongsToManyX(Team::class)`
   senza tabella, senza chiavi, senza `->using()`: il pivot lo deduce il trait.
2. **Relazioni fra database diversi.** Qui `mysql`, `user`, `limesurvey` e
   `quaeris` sono database distinti sullo stesso server MySQL: una `belongsToMany`
   standard genera `join pivot` senza prefisso e cerca la tabella nel database
   sbagliato.

## L'algoritmo

### Nome del pivot: ordinamento alfabetico

```php
$model_names = [class_basename($class), class_basename($related)];
sort($model_names);
$pivot_name = implode('', $model_names);
```

`Customer` + `User` → `CustomerUser`. `Role` + `Permission` → `PermissionRole`.

L'ordinamento non è estetica: rende il nome **simmetrico**. Chiamando la relazione
da un lato o dall'altro si ottiene lo stesso pivot, quindi esiste una sola classe
per una sola tabella. È la parte più solida del design.

### Catena di fallback per il namespace

`guessPivotFullClass()` prova, in ordine:

1. namespace del modello chiamante — `Modules\User\Models\CustomerUser`
2. namespace del modello correlato — `Modules\Quaeris\Models\CustomerUser`
3. namespace della classe padre, ricorsivamente (`tryParentClassPivot`), con un
   caso speciale: se il padre finisce per `Morph` non si risale oltre

Il terzo passo è ciò che fa funzionare l'ereditarietà `Quaeris\User extends
User\BaseUser`: il pivot può stare nel modulo che definisce la classe base.

### Prefisso cross-database

```php
if ($pivotDbName !== $dbName || $relatedDbName !== $dbName) {
    if ('sqlite' !== $pivotDriver) {
        $table = $pivotDbName.'.'.$table;
    }
}
```

Se pivot o related vivono in un altro database, la tabella diventa
`database.tabella`. L'esclusione di SQLite è corretta: SQLite non ha la sintassi
`db.table`. Su questo progetto i test girano su MySQL, quindi il ramo SQLite è
di fatto morto — resta come rete di sicurezza.

## Cosa ho verificato leggendo il codice e i log

### 1. Il parametro `$_table` è ignorato, e qualcuno lo passa davvero

`belongsToManyX()` accetta `?string $_table = null` e **non lo usa mai**: la
tabella arriva sempre da `$pivot->getTable()`. Non è teoria — c'è un chiamante
che ci crede:

```php
// Modules/User/app/Models/Traits/HasRoles.php
Assert::string($pivotTable = config('permission.table_names.model_has_roles'));

return $this->belongsToManyX(Role::class, $pivotTable, 'model_id', 'role_id')
```

Il valore letto dalla config di spatie/permission viene scartato. La prova sta
nel query log dell'applicazione in esecuzione:

```sql
select roles.*, model_has_role.model_id ...
from roles inner join model_has_role on roles.id = model_has_role.role_id
```

La tabella usata è `model_has_role` (singolare), mentre la config di Spatie
dichiara `model_has_roles` (plurale, default upstream). Vince il pivot indovinato
— `ModelHasRole` — non la config.

**Il punto critico**: le tabelle di questo progetto seguono la convenzione
singolare, quindi il comportamento attuale è quello *giusto*. Ma è giusto per
caso. Chi "sistemasse" il parametro ignorato facendolo finalmente funzionare
romperebbe i ruoli su tutta l'applicazione, perché la config punta alla tabella
plurale che qui non esiste. Il parametro va **rimosso**, non onorato.

### 2. `morphToManyX()` non applica il prefisso cross-database

Calcola le variabili e poi non le usa:

```php
$pivotDbName = $pivot->getConnection()->getDatabaseName();
$dbName = $this->getConnection()->getDatabaseName();
// ...nessun uso: $table resta senza prefisso
```

Asimmetria rispetto a `belongsToManyX()`: un pivot polimorfico su un database
diverso da quello del modello non viene raggiunto. PHPStan non lo segnala perché
una variabile assegnata e non usata non è un errore.

Nello stesso metodo c'è un ramo irraggiungibile:

```php
$table = $pivot->getTable();   // già valorizzato qui
// ...
if (null === $table) {          // mai vero
    $table = $pivot->getTable();
}
```

### 3. `withTimestamps()` è incondizionato

Entrambi i metodi chiudono con `->withTimestamps()`. Ogni pivot deve quindi
avere `created_at` e `updated_at`, altrimenti il primo `attach()` fallisce con
errore SQL su colonna inesistente. È una convenzione implicita che nessuna
migrazione dichiara: andrebbe resa esplicita (controllo su `$pivot->timestamps`)
o documentata come requisito obbligatorio dei pivot.

### 4. `guessPivotFullClass()` può restituire una classe inesistente

L'ultimo fallback costruisce un nome e lo restituisce senza verificarlo. Se il
pivot non esiste, l'errore arriva da `app()` come *Target class does not exist*,
puntando al container invece che al vero problema: manca il model pivot
`Modules\X\Models\NomeIndovinato`. Il tipo di ritorno dichiarato è `string`, non
`class-string`: PHPStan non può aiutare.

### 5. Parametri morti minori

`guessMorphPivot(string $related, ?string $_class = null)`: `$_class` non è usato.

## Mappa dei rischi

| Intervento | Effetto |
|---|---|
| Onorare `$_table` in `belongsToManyX` | **Rompe i ruoli**: la config Spatie punta a `model_has_roles`, la tabella reale è `model_has_role` |
| Rimuovere `$_table` dalla firma | Sicuro, ma richiede di aggiornare i chiamanti che lo passano (`HasRoles`) |
| Aggiungere il prefisso cross-DB a `morphToManyX` | Sicuro e necessario: oggi quel caso è semplicemente rotto |
| Rendere condizionale `withTimestamps()` | Sicuro; verificare prima che nessun pivot dipenda dall'aggiornamento implicito |

## Perché il design regge comunque

Il trait toglie dai model una quantità di configurazione ripetitiva e la
sostituisce con due regole memorizzabili: *il pivot si chiama come i due model in
ordine alfabetico* e *vive nel namespace di uno dei due*. Il costo è che gli
errori si manifestano lontano dalla causa — un pivot mancante diventa un errore
del container, una tabella sbagliata diventa una query silenziosamente diversa da
quella che il chiamante credeva di aver chiesto.

Il lavoro di irrobustimento è tracciato in
[story-relationx-pivot-resolution-hardening](./stories/story-relationx-pivot-resolution-hardening.md).
