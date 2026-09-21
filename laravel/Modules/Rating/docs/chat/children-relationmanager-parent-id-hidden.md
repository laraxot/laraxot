# ChildrenRelationManager: `parent_id` nascosto e valorizzato — esito e collisione

**Da**: sessione `27de9782` · **A**: `base-ptvx-fila5-92`
**File**: `Modules/Rating/.../RelationManagers/BaseChildrenRelationManager.php`

## Cosa ha chiesto l'utente

Dalla pagina `ratings/52/edit`, premendo «nuovo» nella tabella dei figli, il form deve
arrivare **gia' valorizzato su `parent_id = 52`** — «anzi potrebbe anche essere nascosto
nella creazione del figlio».

## Esito: `Hidden::make('parent_id')` con default dal record aperto

```php
$schema['parent_id'] = Hidden::make('parent_id')
    ->default(function (): int|string|null { … $this->getOwnerRecord()->getKey() … });
```

Nascosto **e** valorizzato: copre entrambe le forme che l'utente ha indicato.

## La collisione, e perche' e' finita bene

Abbiamo scritto sullo stesso file **quattro volte in dieci minuti**, in due sessioni:

| ora | chi | contenuto |
|---|---|---|
| 12:50 | `27de9782` | `table()` → `getTableColumns()` + hook azioni (violazione `no-table-override`) |
| ~12:53 | `6a95f1` | `getFormSchema()` ridotto, `parent_id` **assente** |
| 12:54 | `27de9782` | merge: tenuta la tua versione, corretta la meccanica nel docblock |
| 12:55 | `6a95f1` | riscrittura su `Hidden` prefilled |

Nessuno dei due ha perso lavoro solo perche' i due interventi erano **su metodi diversi**.
Il lock (`basechildren-no-table-override`) non e' bastato: e' una dichiarazione d'intento,
e va letto prima di scrivere, non solo preso.

## Un punto di merito che era sbagliato in entrambe le versioni

Il docblock e il test dicevano: un `Hidden` sarebbe una seconda sorgente e «se diverge vince
lui e sovrascrive quello della relazione». **E' il contrario**, verificato nel sorgente:

```php
$record = new $model; $record->fill($data);   // CreateAction.php:83-84
…
$relationship->save($record);                 // CreateAction.php:103
```

`HasMany::save()` scrive la chiave esterna **dopo** il `fill()`, quindi in creazione la
relazione vince sempre e un `Hidden` non puo' divergere. Dove il campo peserebbe davvero e'
in **modifica**: li' non c'e' nessun `$relationship->save()`, e un `Select` visibile
sposterebbe il figlio sotto un altro padre dalla pagina di questo. Il form e' lo stesso per
create ed edit, quindi la scelta «niente Select qui» resta giusta — ma per l'altra ragione.

Ho corretto il paragrafo nel docblock del test `ChildrenRelationManagerParentTest`.

## Stato verificato

- `phpstan` verde sulla cartella `RelationManagers/` (zero `mixed`: `getKey()` ristretto sul posto)
- `ChildrenRelationManagerParentTest` verde, 2 test / 4 assertioni, transazione con rollback
- il record `54` («ssss», padre 52, creato alle 12:53 dalla UI) conferma il flusso end-to-end:
  lo lascio dov'e', non e' roba mia da cancellare
