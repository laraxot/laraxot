---
title: "RelationX: derivazione automatica dei pivot"
description: >-
  Analisi del trait Modules\Xot\Models\Traits\RelationX: come deriva la classe
  pivot dai nomi dei modelli, come gestisce le relazioni cross-database e quali
  difetti presenta la sua superficie pubblica.
type: analysis
module: Xot
scope: module:Xot
status: draft
tags: [relationx, pivot, belongstomany, morphtomany, multi-database, eloquent]
canonical: laravel/Modules/Xot/docs/relationx-trait.md
related:
  - laravel/Modules/Xot/docs/custom-relation.md
issues: []
discussions: []
---

# RelationX: derivazione automatica dei pivot

File: `laravel/Modules/Xot/app/Models/Traits/RelationX.php` (213 righe)
Usato da: `XotBaseModel`, `BaseUser`, `Role`, `Permission` — quindi da ogni modello del progetto che eredita da `XotBaseModel`.

## Il problema che risolve

Laravel chiede di dichiarare a mano tabella pivot, chiavi e classe `Pivot` a ogni relazione molti-a-molti. In un monorepo con venti moduli e tre database questo produce ripetizione e, soprattutto, errori silenziosi quando pivot e modelli vivono su connessioni diverse.

`RelationX` sostituisce la dichiarazione manuale con una derivazione: dati due modelli, la classe pivot si ricava dai loro nomi.

## Come deriva il nome del pivot

Il cuore è in `guessPivot()`:

```php
$model_names = [class_basename($class), class_basename($related)];
sort($model_names);
$pivot_name = implode('', $model_names);
```

L'ordinamento alfabetico è la scelta di progetto più importante del trait: rende la derivazione **simmetrica**. `User->customers()` e `Customer->users()` producono entrambi `CustomerUser`, quindi la stessa classe pivot e la stessa tabella, senza che i due lati debbano accordarsi.

`guessPivotFullClass()` cerca poi la classe in tre punti, in ordine:

1. namespace del modello corrente
2. namespace del modello correlato
3. risalita alla classe padre (`tryParentClassPivot`)

Il terzo passo esiste perché i modelli concreti spesso estendono una base (`Quaeris\Models\User extends BaseUser`): se il pivot è dichiarato accanto alla base, va trovato lo stesso.

## Gestione cross-database

`belongsToManyX()` confronta tre connessioni — pivot, modello corrente, modello correlato — e se divergono antepone il database al nome tabella:

```php
if ($pivotDbName !== $dbName || $relatedDbName !== $dbName) {
    if ('sqlite' !== $pivotDriver) {
        $table = $pivotDbName.'.'.$table;
    }
}
```

Non è teoria: nel log query del pannello Quaeris la join compare come `quaeris_data.customer_user`, prodotta esattamente da questo ramo. L'esclusione di SQLite è corretta — non supporta la sintassi `database.tabella`.

## Difetti rilevati

### 1. `morphToManyX()` non applica il prefisso cross-database

Il difetto più serio. Il metodo calcola le stesse variabili di `belongsToManyX`:

```php
$pivotDbName = $pivot->getConnection()->getDatabaseName();
$dbName = $this->getConnection()->getDatabaseName();
```

e poi **non le usa mai**. Manca l'intero blocco `if` che antepone il database. Una relazione polimorfa il cui pivot vive su una connessione diversa da quella del modello genera SQL che punta alla tabella sbagliata, o fallisce con "table not found". In un progetto a tre database è una trappola che scatta solo in produzione, sui moduli che usano pivot morph cross-connessione.

Le due variabili morte sono la prova che il blocco è stato dimenticato in una copia, non omesso di proposito.

### 2. Codice morto in `morphToManyX()`

```php
$table = $pivot->getTable();   // riga 101, ritorna sempre string
// ...
if (null === $table) {          // riga 107, mai vera
    $table = $pivot->getTable();
}
```

`getTable()` dichiara `string`, quindi la condizione è sempre falsa. Va rimossa: sopravvive perché `treatPhpDocTypesAsCertain: false` in `phpstan.neon` impedisce a PHPStan di considerare certi i tipi da PHPDoc, e quindi di segnalarla.

### 3. Parametri pubblici che non fanno nulla

`belongsToManyX(..., ?string $_table = null, ...)`: `$_table` non viene mai letto. La riga 49 lo scavalca con `$table = $pivot->getTable()`.
`guessMorphPivot(string $related, ?string $_class = null)`: `$_class` non viene mai letto. La riga 129 usa `$this::class`.

Il prefisso `_` segnala l'intenzione "non usato", ma questi restano parametri di **metodi pubblici**: chi li valorizza si aspetta un effetto e ottiene un no-op silenzioso. È peggio di un parametro assente, perché non produce nessun errore. O si rimuovono dalla firma, o si onorano.

### 4. `withTimestamps()` incondizionato

Entrambi i metodi chiudono con `->withTimestamps()`. Se la tabella pivot non ha `created_at`/`updated_at`, ogni `attach()` fallisce con "unknown column". La derivazione automatica del pivot dovrebbe verificare le colonne prima di imporre il comportamento, oppure la convenzione "ogni pivot ha i timestamp" va scritta e verificata da un test.

### 5. Fallback finale senza verifica di esistenza

`tryParentClassPivot()` termina con:

```php
return $this->buildPivotClassName($class, $pivot_name);
```

senza `class_exists()`. Il chiamante fa `app($pivot_class)` su una classe che può non esistere: l'errore che arriva è del container, non di `RelationX`, e non nomina né i due modelli né il pivot atteso. `Assert::isInstanceOf` sulla riga successiva non viene mai raggiunto. Un messaggio esplicito qui risparmierebbe ore di ricerca a chi aggiunge una relazione nuova.

### 6. Convenzione di namespace implicita

`buildPivotClassName()` prende il namespace del contesto meno l'ultimo segmento e vi appende il nome pivot: la classe pivot **deve** stare nello stesso namespace del modello (`Modules\<Modulo>\Models\`). È una convenzione ragionevole ma non documentata da nessuna parte: chi mette il pivot in un sottonamespace ottiene il fallimento opaco del punto 5.

## Ordine di intervento suggerito

| Priorità | Intervento | Motivo |
|---|---|---|
| Alta | Prefisso cross-database in `morphToManyX` | Difetto funzionale su progetto multi-database |
| Alta | Messaggio esplicito nel fallback di `guessPivotFullClass` | Trasforma un errore opaco in uno diagnostico |
| Media | Rimuovere `$_table` e `$_class` dalle firme | Elimina una trappola di API pubblica |
| Media | Rendere condizionale `withTimestamps()` | Evita fallimenti su pivot senza timestamp |
| Bassa | Rimuovere il ramo morto riga 107 | Pulizia |
| Bassa | Documentare la convenzione di namespace del pivot | Prevenzione |

Ogni intervento va coperto da un test Pest: il trait è usato da `XotBaseModel`, quindi da tutti i modelli del progetto, e una regressione qui si propaga ovunque.

## Nota sui collegamenti GitHub

I campi `issues` e `discussions` del frontmatter sono vuoti perché `gh auth status` riporta *"You are not logged into any GitHub hosts"*: non è stato possibile creare né collegare issue e discussion del repository `laraxot/module_xot_fila5`. Vanno popolati dopo `gh auth login`, senza inventare URL.
