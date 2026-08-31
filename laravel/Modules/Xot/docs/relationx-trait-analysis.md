---
id: relationx-trait-analysis
slug: relationx-trait-analysis
title: "RelationX — analisi del trait di inferenza dei pivot"
description: "Studio approfondito di Modules/Xot/app/Models/Traits/RelationX.php: convenzione di naming dei Pivot, risoluzione cross-database, asimmetrie fra belongsToManyX e morphToManyX, e i punti in cui il fallimento e' silenzioso o illeggibile."
document_type: analysis
category: architecture
status: active
version: 1.0.0
language: it-IT
ecosystem: Laraxot
module: Xot
priority: high
source_of_truth: true
created_at: '2026-08-06'
updated_at: '2026-08-06'
tags: [xot, traits, eloquent, pivot, relations, multi-database, morph]
related:
  - ./stories/1-1-relationx-hardening.md
  - ../../../../docs/wiki/rules/009-one-migration-per-model.md
github:
  repository: git@github.com:laraxot/module_xot_fila5.git
  issues: https://github.com/laraxot/module_xot_fila5/issues
  discussions: https://github.com/laraxot/module_xot_fila5/discussions
---

# RelationX — analisi

`Modules/Xot/app/Models/Traits/RelationX.php`, 213 righe. Consumato da
`User\BaseUser`, `User\BaseTenant`, `User\BaseTeam`, `Quaeris\Profile`: e' codice
portante, non un'utility marginale. `BaseUser::tenants()` passa di qui.

## Cosa fa, in una riga

Sostituisce la configurazione esplicita delle relazioni molti-a-molti con
l'inferenza: dato il modello correlato, **deduce la classe Pivot**, e da quella
ricava tabella, campi e connessione.

## La convenzione di naming

```php
$model_names = [class_basename($class), class_basename($related)];
sort($model_names);                       // <- ordine alfabetico
$pivot_name = implode('', $model_names);  // User + Profile => "ProfileUser"
```

Il `sort()` e' la scelta di design centrale: rende il nome del pivot
**indipendente dal lato da cui si parte**. `User::profiles()` e
`Profile::users()` deducono entrambi `ProfileUser`, quindi un solo file Pivot
serve entrambe le direzioni. E' il motivo per cui il trait funziona.

Il prezzo e' che il nome dipende solo dal `class_basename`: due modelli omonimi
in namespace diversi collidono sulla stessa classe Pivot, senza alcun segnale.

## La catena di risoluzione

`guessPivotFullClass()` prova tre strade, in ordine:

1. namespace del **modello corrente** + nome pivot
2. namespace del **modello correlato** + nome pivot
3. `tryParentClassPivot()` — risale la catena di ereditarieta'

Il terzo passo e' quello che fa funzionare l'ereditarieta' Laraxot: `Quaeris\User`
estende `User\BaseUser`, il pivot non esiste sotto `Quaeris\`, ma risalendo al
parent si ricalcola `$new_pivot_name` con il basename del parent e si ritenta.
La ricorsione termina perche' `get_parent_class()` prima o poi restituisce
`false`.

### La traccia reale: `User` ↔ `Role`

La descrizione sopra e' corretta ma sottostima quanto in alto arriva la risalita.
Eseguendo l'algoritmo sulla coppia realmente in uso (`Modules\Quaeris\Models\User`
e `Modules\User\Models\Role`, la relazione dietro `HasRoles::roles()`):

```
nome pivot iniziale: RoleUser                         (['Role','User'] ordinati)
  Modules\Quaeris\Models\RoleUser                     no
  Modules\User\Models\RoleUser                        no
  parent -> Modules\User\Models\BaseUser
    nome ricalcolato: BaseUserRole
    Modules\User\Models\BaseUserRole                  no
    parent -> Illuminate\Foundation\Auth\User
      nome ricalcolato: RoleUser
      Illuminate\Foundation\Auth\RoleUser             no
      Modules\User\Models\RoleUser                    no
      parent -> Illuminate\Database\Eloquent\Model
        nome ricalcolato: ModelRole
        Modules\User\Models\ModelRole                 ESISTE
```

La risalita attraversa **due classi del framework** e si ferma solo perche'
`class_basename(Illuminate\Database\Eloquent\Model)` vale `Model`, che combinato
con `Role` produce `ModelRole` — una classe che nel modulo User esiste davvero.

Tre conseguenze che non si vedono leggendo il trait:

1. **Il nome del pivot non viene dal dominio ma dalla classe base di Eloquent.**
   `ModelRole`, `ModelHasRole`, `ModelHasPermission` sembrano una convenzione
   Spatie; per l'algoritmo sono solo il risultato di `Model` + `Role`. La
   somiglianza con i nomi Spatie e' una coincidenza fortunata, non un progetto.
2. **La risoluzione dipende da quanti livelli di ereditarieta' separano il modello
   concreto dalla classe base.** Un `User` che estendesse direttamente
   `Illuminate\Foundation\Auth\User` risolverebbe un pivot diverso a parita' di
   dominio. La relazione e' stabile solo finche' nessuno tocca la gerarchia.
3. **Il livello che vince e' il quarto tentativo su sette.** I primi sei
   `class_exists()` falliscono a ogni boot della relazione: e' lavoro silenzioso e
   ripetuto, e soprattutto significa che l'intenzione ("il pivot di User e Role")
   non e' esprimibile in modo diretto.

### `$_table` ignorato: perche' non e' ancora esploso

`HasRoles::roles()` chiama
`belongsToManyX(Role::class, $pivotTable, 'model_id', 'role_id')` passando
`config('permission.table_names.model_has_roles')`. Quel secondo argomento e'
`$_table` e viene **scartato**: la tabella usata e' `ModelRole::getTable()`.

Il sistema funziona lo stesso perche' `ModelRole::getTable()` legge *la stessa*
chiave di configurazione. Sono due percorsi indipendenti che oggi convergono sullo
stesso valore (`model_has_role`). Chi passasse una tabella diversa da quella
dichiarata dal pivot non otterrebbe un errore: otterrebbe silenziosamente la
tabella del pivot. Vedi il punto 4 fra i difetti.

## Punti critici

### 1. `guessPivotFullClass()` puo' restituire una classe inesistente

E' il difetto piu' costoso. I primi due rami sono guardati da `class_exists()`,
il terzo no: `tryParentClassPivot()` in due casi su tre restituisce
`buildPivotClassName(...)` **senza verificare che la classe esista**.

Il valore torna a `guessPivot()`, che fa `app($pivot_class)`. Il container
solleva una `BindingResolutionException` con un messaggio che parla di
risoluzione di dipendenze, non di pivot mancante. Chi legge lo stack trace non
ha modo di capire che il vero problema e' un file Pivot mai creato, e con quale
nome andava creato.

Il contratto dichiarato (`: string` che rappresenta una class-string) non e'
rispettato: la funzione promette una classe risolvibile e puo' restituire una
stringa qualsiasi.

### 2. `morphToManyX()` non gestisce il cross-database

`belongsToManyX()` confronta tre connessioni — pivot, modello corrente, modello
correlato — e prefissa la tabella con il nome del database quando divergono:

```php
if ($pivotDbName !== $dbName || $relatedDbName !== $dbName) {
    if ('sqlite' !== $pivotDriver) {
        $table = $pivotDbName.'.'.$table;
    }
}
```

`morphToManyX()` calcola `$pivotDbName` e `$dbName` **e poi non li usa**. Le due
variabili restano assegnate e morte, e il prefisso non viene mai applicato.

Su questo progetto la cosa non e' teorica: le connessioni attive sono almeno
quattro (`user`, `quaeris`, `quaeris_data`, `limesurvey`). Una relazione morph
il cui pivot vive su un database diverso da quello del modello produce una query
con tabella non qualificata, quindi un "table doesn't exist" che punta al posto
sbagliato.

L'asimmetria fra i due metodi non e' documentata da nessuna parte.

### 3. Ramo irraggiungibile in `morphToManyX()`

```php
$table = $pivot->getTable();   // riga 101
// ...
if (null === $table) {         // riga 107
    $table = $pivot->getTable();
}
```

`Model::getTable()` ha tipo di ritorno `string`: non restituisce mai `null`. Il
blocco non e' mai eseguito. E' il residuo di una versione in cui `$table`
arrivava dal parametro `$_table`.

### 4. Il parametro `$_table` e' accettato e ignorato

Entrambi i metodi dichiarano `?string $_table = null` e non lo usano mai: la
tabella viene sempre dal pivot dedotto. Un chiamante che passa una tabella
esplicita — magari proprio per aggirare un'inferenza sbagliata — la vede
scartare in silenzio.

Il prefisso `_` segnala la volonta' di ignorarlo, ma un parametro che non fa
nulla e' peggio di un parametro assente: promette un controllo che non esiste.

### 5. `withTimestamps()` incondizionato

Entrambi i metodi chiamano `->withTimestamps()` sempre. Se la tabella pivot non
ha `created_at`/`updated_at`, ogni `attach()`/`sync()` fallisce con "unknown
column". La condizione naturale sarebbe interrogare il pivot dedotto — che il
trait ha gia' in mano — invece di assumere.

### 6. Il guard su SQLite descrive il sintomo, non la regola

`if ('sqlite' !== $pivotDriver)` e' corretto (SQLite non supporta
`database.table`), ma esprime la regola al contrario: prefissa per tutti i
driver tranne uno noto. Un driver futuro senza quella sintassi romperebbe in
silenzio. La forma robusta e' una allowlist dei driver che la supportano.

Nota di contesto: il canon di progetto vieta SQLite nei test
(`Modules/Xot/docs/testing/mysql-only-testing-rule.md`), quindi quel ramo non e'
esercitato dalla suite.

## Verifica sul runtime

I punti sopra nascono dalla lettura del codice. Questa sezione riporta cosa
succede davvero, misurato istanziando i modelli reali del progetto e chiamando
`guessPivot()` su `Modules\Quaeris\Models\User`.

| Chiamata | Pivot dedotto | Tabella | `getFillable()` |
|---|---|---|---|
| `guessPivot(Role::class)` | `Modules\User\Models\ModelRole` | `model_has_role` | `id, post_id, post_type, related_type, user_id, note` |
| `guessPivot(Device::class)` | `Modules\User\Models\DeviceUser` | `device_user` | `id, device_id, user_id, login_at, logout_at, push_notifications_token, push_notifications_enabled` |
| `guessPivot(Team::class)` | `Modules\User\Models\TeamUser` | `team_user` | `[]` (vuoto) |

Tre conferme e due difetti nuovi, che dalla sola lettura non si vedevano.

### 7. Il caso `Role` risolve per coincidenza, non per convenzione

Il nome atteso dalla convenzione e' `RoleUser`: non esiste ne' sotto
`Modules\Quaeris\Models\`, ne' sotto `Modules\User\Models\`. La risoluzione va a
buon fine solo perche' `tryParentClassPivot()` risale la catena di ereditarieta'
fino a `Illuminate\Database\Eloquent\Model`, il cui `class_basename()` e'
letteralmente `Model`: da li' il nome ricalcolato diventa `ModelRole`, che esiste
perche' segue la convenzione di Spatie Permission, non quella di RelationX.

La risalita, pensata per gestire l'ereditarieta' Laraxot (`Quaeris\User` che
estende `User\BaseUser`), arriva fino alla classe base di Eloquent e li' produce
un match semantico casuale. Funziona finche' il nome combacia, e nessun test
copre il perche'.

C'e' un secondo effetto: `ModelRole` estende `BaseMorphPivot`, quindi e' un
`MorphPivot`, ma viene passato a `->using()` di una `belongsToMany` normale.
`Assert::isInstanceOf($pivot, Pivot::class)` non lo intercetta, perche'
`MorphPivot` estende `Pivot`. Un pivot polimorfico usato in una relazione non
polimorfica passa il controllo.

### 8. `withPivot(getFillable())` chiede colonne che non esistono

`model_has_role` ha queste colonne reali:

```
id, role_id, model_type, model_id, team_id, created_at, updated_at, updated_by, created_by
```

Il `fillable` di `ModelRole` dichiara invece `post_id`, `post_type`,
`related_type`, `user_id`, `note`: **nessuna** di queste esiste nella tabella.
Poiche' il trait fa `->withPivot($pivot->getFillable())`, la relazione chiede
alla query colonne inesistenti, e l'errore arriva a runtime al primo accesso,
non alla definizione della relazione.

All'estremo opposto, `TeamUser` ha `fillable` vuoto: `withPivot([])` non espone
nulla, quindi i dati del pivot esistono nel database ma sono invisibili al
modello, in silenzio.

La lezione: `fillable` e' un contratto di mass assignment, non l'elenco delle
colonne. Usarlo come proiezione della tabella e' l'assunzione sbagliata alla
radice di entrambi i casi. La fonte corretta e' lo schema
(`Schema::getColumnListing()`), oppure una proprieta' esplicita sul pivot.

### 9. Il parametro ignorato ha gia' un chiamante reale

`Modules/User/app/Models/Traits/HasRoles.php` chiama:

```php
Assert::string($pivotTable = config('permission.table_names.model_has_roles'));

return $this->belongsToManyX(Role::class, $pivotTable, 'model_id', 'role_id')
```

Il secondo argomento e' la tabella configurata da Spatie Permission, e viene
scartato: la tabella usata e' quella dedotta dal pivot. Finche' le due
coincidono nessuno se ne accorge; il giorno in cui la configurazione cambia, la
relazione continua a puntare alla tabella vecchia senza alcun segnale. Il punto
4 non e' teorico.

### Come riprodurre

```php
$u = new Modules\Quaeris\Models\User();
$p = $u->guessPivot(Modules\User\Models\Role::class);
echo get_class($p), ' | ', $p->getTable(), ' | ', implode(',', $p->getFillable());
```

## Cosa non e' un difetto

Il `sort()` alfabetico, la risalita al parent e l'uso di `Assert` per validare i
tipi dedotti sono scelte coerenti e vanno mantenute: sono cio' che rende il
trait utile. La critica riguarda i bordi, non l'impianto.

## Direzione consigliata

Il tema comune dei punti 1, 3 e 4 e' lo stesso: **il trait fallisce in silenzio
o in modo illeggibile**. Un'inferenza e' accettabile solo se, quando sbaglia,
dice esattamente cosa si aspettava di trovare.

Ordine di valore decrescente:

1. `guessPivotFullClass()` deve verificare l'esistenza prima di restituire, e
   sollevare un errore che nomini la classe Pivot attesa e i tre namespace
   tentati.
2. Allineare `morphToManyX()` a `belongsToManyX()` sul cross-database, oppure
   documentare esplicitamente perche' non deve farlo.
3. Rimuovere il ramo irraggiungibile e decidere su `$_table`: onorarlo o
   toglierlo dalla firma.
4. Rendere `withTimestamps()` condizionale alle colonne reali del pivot.
