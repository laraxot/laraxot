---
name: rating-data-migration-helper
description: "Le colonne di ratings dichiarate una volta sola in RatingData, sul modello di NestedSet::columns(). Tre migrazioni ridotte a una riga ciascuna, idempotenza verificata sulle tabelle vive."
metadata:
  type: story
  status: done
  date: 2026-09-08
  agent: claude opus 5 — sessione base-ptvx-fila5-92 [6a95f1] + base-ptvx-fila5-be [8397b5]
  module: Rating
---

# Story — `RatingData`: le colonne di `ratings` dichiarate una volta sola

## Richiesta

Studiare `aimeos/laravel-nestedset` e replicarne il pattern per le migrazioni
`ratings`, portando il blocco di guard dentro `RatingData::updateColumns($table, $this)`,
in `Modules/Rating/app/Datas`, tipo `Spatie\LaravelData\Data`.

## L'idea presa da NestedSet, e cosa vale la pena prenderne

`NestedSet::columns($table)` non è «una funzione che aggiunge colonne»: è **una
dichiarazione sola letta in due direzioni** — `columns()` le aggiunge, `dropColumns()`
le toglie. Il valore non è risparmiare righe, è che **non esistono due posti dove la
stessa colonna è definita**.

Che è esattamente il difetto trovato qui. Nella migrazione di Rating, `txt` era
dichiarato due volte e le due dichiarazioni **non concordavano**:

```php
$table->text('txt')->nullable();     // in tableCreate()
$table->string('txt')->nullable();   // nel guard di tableUpdate()
```

Nessuno se n'era accorto perché il guard scatta solo se la colonna manca, e non
mancava mai.

## Il duplicato misurato

Tre migrazioni `create_ratings_table` vive — IndennitaResponsabilita, Progressioni,
Rating — con lo stesso elenco ricopiato e **già divergente**:

| | IR | Progressioni | Rating |
|---|---|---|---|
| `slug` in `tableCreate` | no | no | **sì** |
| timestamps in `tableCreate` | sì | sì | **no** |
| guard su `title/color/icon/txt` | no | no | **sì** |
| `updateTimestamps()` in update | no | no | **sì** |

Le tre tabelle vive hanno però **colonne identiche** (15, verificato su tutte e tre
le connessioni): la divergenza era nel testo delle migrazioni, non ancora nei dati.
Il momento giusto per unificare è questo, non dopo che i dati si sono separati.

## La firma, e perché è migliore di quella di `SchedaData`

Il pattern esisteva già nel progetto — `Ptv\Datas\SchedaData`, stessa ispirazione —
ma con una firma diversa: `SchedaData::updateColumns($migration)` apre **da sé** i
propri `tableUpdate()`, uno per gruppo di colonne. Sei gruppi, sei `ALTER TABLE`
sulla stessa tabella, e chi chiama non può accodare colonne proprie nello stesso
blocco.

La firma richiesta dall'utente prende il `Blueprint` già aperto:

```php
$this->tableUpdate(function (Blueprint $table): void {
    RatingData::updateColumns($table, $this);
    // e qui le colonne specifiche del modulo, nella stessa ALTER
});
```

Una sola `ALTER TABLE`, e la migrazione resta padrona della propria transazione.
`$migration` serve comunque: `hasColumn()` vive lì, il `Blueprint` da solo non sa
cosa esiste già.

Allineare `SchedaData` a questa forma è lavoro a sé — tocca le migrazioni scheda di
più moduli — e va tracciato, non fatto di passaggio.

## Cosa resta fuori dall'helper, di proposito

- **`id()`**: è la chiave primaria, la dichiara `tableCreate()`; su una tabella
  esistente non si aggiunge.
- **timestamps**: li gestisce `XotBaseMigration::updateTimestamps()`. Non sono
  dentro `updateColumns()` perché **non tutte le migrazioni ne hanno bisogno lì**:
  IR e Progressioni li dichiarano in creazione, Rating no — e in Rating è proprio
  quella riga a crearli su un'installazione nuova. Metterli nell'helper avrebbe
  funzionato, ma avrebbe nascosto che le tre migrazioni non sono equivalenti.
  In Rating la chiamata resta accanto all'helper, con il commento che dice perché.

## Verifica — il punto che conta su dati che non si buttano

Il refactor deve non cambiare **niente**. Confronto fra le colonne che
`updateColumns()` dichiara e quelle vive:

```
updateColumns() dichiara: extra_attributes, rule, is_disabled, is_readonly, slug, order_column
  indennita_responsabilita   niente (idempotente)
  progressione               niente (idempotente)
  ptv                        niente (idempotente)
```

Nessuna colonna verrebbe aggiunta da nessuna parte: il comportamento è identico e le
migrazioni restano rieseguibili.

- `php -l` verde su tutti i file
- `phpstan analyse Modules/Rating/app/Datas` + le tre migrazioni → **0 errori**
- Guard `hasColumn` nelle tre migrazioni: da 6+6+10 a **0**, una chiamata ciascuna

## Segnalato, non fatto

`RatingData::updateMorphColumns()` — l'helper per il pivot `rating_morph`, scritto
dalla sessione peer — **non è ancora chiamato da nessuna migrazione**. Se lo si
collega, su `progressione` aggiungerebbe `is_winner` e `reward`, che là mancano:
additivo e innocuo, ma è una modifica di schema su un database di modulo e va decisa,
non fatta di passaggio.

## Due errori miei, in questa story

1. **Ho sovrascritto `RatingData` senza leggerla**: esisteva già, committata, con un
   DTO diverso (blocco rating: `title`, `description`, `locale`, `image_url`) usato
   da due test. Il tool aveva risposto *updated* invece di *created* — il segnale
   c'era. La sessione peer ha ripristinato il DTO e vi ha aggiunto gli helper, che è
   la forma giusta: il file porta due concetti con lo stesso nome, ed è dichiarato
   nel suo docblock invece che subìto.
2. **Ho usato `mixed`** per far passare PHPStan su `Safe\glob()`, che è dichiarato
   `@return list` senza tipo dell'elemento. `mixed` è l'ultima spiaggia, non la
   prima: sostituito con `Assert::allString()`, che **verifica** invece di
   coercizzare — se un giorno non fossero stringhe, il test lo direbbe.
