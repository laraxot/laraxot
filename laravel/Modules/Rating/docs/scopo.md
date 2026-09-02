---
title: "Rating — scopo, confini e come servirlo meglio"
type: concept
module: Rating
status: active
created: 2026-09-02
updated: 2026-09-02
tags: [scopo, confini, valutazioni, schemaless, classi-base, filament, dipendenze]
qmd: "scopo rating valutazioni schemaless classi base foglie tabelle filament confini dipendenze"
---

# Rating — scopo, confini e come servirlo meglio

## Lo scopo, dedotto dal codice

Rating non è una foglia applicativa: è **una piattaforma**, come Xot e Sigma. Non
contiene le valutazioni di nessun dominio — contiene la forma che una valutazione ha in
questo progetto, e le classi Base che i moduli di dominio estendono per averla.

| Fatto | Dove si verifica | Cosa significa |
|---|---|---|
| 10 classi fuori dal modulo estendono `BaseRating` o `BaseRatingMorph` | `Modules/{Ptv,Performance,Progressioni,IndennitaResponsabilita,IndennitaCondizioniLavoro}/app/Models/Rating.php` e `RatingMorph.php` | ogni dominio ha i propri rating, sulla propria connessione, ma la stessa struttura |
| `BaseRatingResource`, `BaseRatingMorphResource`, `BaseRatingsTable`, `BaseRatingForm`, `BaseListRatings` | `app/Filament/Resources/` | il modulo esporta anche l'interfaccia, non solo il modello |
| `Modules/Ptv/app/Models/BaseScheda.php:111` compone `HasRatingsTrait` | `use HasRatingsTrait;` | la Scheda è valutabile perché compone un trait di Rating, non perché Rating conosca la Scheda |
| `$table->schemalessAttributes('extra_attributes')` su `ratings` | `database/migrations/2026_06_16_000003_create_ratings_table.php` | i criteri di valutazione non hanno colonne: cambiano senza migrazione |
| `require`: `spatie/laravel-schemaless-attributes`, `php ^8.3` | `composer.json` | un solo pacchetto terzo, ed è quello che regge lo schemaless |
| Due tabelle: `ratings` (il criterio) e `rating_morph` (la valutazione data, `nullableMorphs('model')` + `nullableUuidMorphs('user')`) | le 2 migrazioni | il criterio è un'entità, il voto è una relazione polimorfa |
| `protected $connection = 'rating'` | `app/Models/BaseModel.php:14`, `BaseMorphPivot.php:30` | default sovrascrivibile dalle foglie, che infatti lo sovrascrivono |

> **Rating è il vocabolario condiviso delle valutazioni: definisce cosa sia un criterio
> (`ratings`, senza schema fisso) e cosa sia una valutazione data (`rating_morph`,
> polimorfa), e offre le classi Base che cinque moduli di dominio estendono. Non
> conosce nessuno di quei domini.**

La direzione delle dipendenze è corretta e va detto: 38 file di Rating toccano
`Modules\Xot`, 2 toccano `Modules\Media`, **zero** toccano un modulo di dominio.
L'unica occorrenza di `Modules\Rating` dentro Xot
(`Modules/Xot/app/Models/XotBaseMorphPivot.php:85,93`) è in due commenti che usano
`RatingMorph` come esempio — non è un import.

La connessione `rating` è dichiarata esplicitamente in `config/localhost/database.php:264`,
ma non nella mappa del tenant di produzione `config/local/ptvx/database.php` (righe
25-46): lì esiste perché `TenantServiceProvider::mergeModuleConnections()`
(`Modules/Tenant/app/Providers/TenantServiceProvider.php:138-159`) la genera copiando la
default. È un giunto disponibile, non ancora usato.

## I confini, e dove oggi sono rotti

### Le colonne stanno nelle `Tables/`, ma sono scritte tre volte

La domanda canonica del progetto — *le colonne sono nelle `Tables/` o ancora nelle
Pages?* — su Rating ha una risposta a due facce.

Nelle Pages il debito è quasi chiuso, ma male: in
`RatingResource/Pages/BaseListRatings.php` il metodo `getTableColumns()` (righe 17-45)
è **dentro un docblock mai chiuso** — la riga 16 apre `/**`, il `*/` arriva solo alla
riga 45. Il metodo non è stato rimosso: è stato spento allargando un commento, e la
classe astratta che ne risulta è un guscio con una sola proprietà. In
`RatingMorphResource/Pages/ListRatingMorphs.php:19` invece `getTableColumns()` è vivo e
compila, ma `XotBaseListRecords` non lo chiama più (riga 64: la dichiarazione astratta è
commentata; righe 23-24: rimando esplicito a `XotBaseResource::table()`). Sei colonne
che nessuna pagina renderizza.

Nelle `Tables/` il debito è invece pieno: 5 classi per 2 Resource.

| Classe | Stato |
|---|---|
| `RatingResource/Tables/BaseRatingsTable.php` | astratta, **è la base vera**: la estendono `Progressioni` e `IndennitaResponsabilita` |
| `RatingResource/Tables/RatingsTable.php` | quella che `getTableClass()` risolve — ma estende `XotBaseResourceTable`, **non** `BaseRatingsTable`: le 9 colonne sono ricopiate identiche |
| `RatingResource/Tables/RatingTable.php` | mai risolta, mai estesa: gli unici riferimenti sono in `tests/Unit/RatingFilamentSchemaTest.php` |
| `RatingMorphResource/Tables/RatingMorphsTable.php` | quella risolta da `getTableClass()` |
| `RatingMorphResource/Tables/RatingMorphTable.php` | mai risolta; stesse colonne di `RatingMorphsTable` ma con modificatori diversi (`->numeric()`, `->limit(50)`, ordine differente). Viva solo nei test |

Due classi morte tenute in piedi da un file di test, e una duplicazione di nove colonne
fra la base condivisa e la classe concreta del modulo che la ospita.

### Le uniche due classi Filament del progetto che estendono Filament direttamente

`app/Filament/RelationManagers/RatingsRelationManager.php:16` e
`app/Filament/Resources/HasRatingResource/RelationManagers/RatingsRelationManager.php:16`
dichiarano entrambe `extends RelationManager` — la classe di Filament, non una
`XotBase*`. Sono due copie dello stesso file: differiscono per il namespace, per un
blocco di sei righe commentate e per un array di filtri scritto su una riga invece che
su due. Nei tre moduli esaminati (Activity, Job, Rating) sono le uniche due violazioni
della regola.

### Un modello senza tabella

`app/Models/Like.php:21` dichiara `protected $table = 'likes'`, ma **nessuna migrazione
in tutto il repository crea `likes`**. La regola "1 modello = 1 migrazione" è rotta dal
lato opposto a quello solito: non una tabella con due migrazioni, ma un modello senza
nessuna. Attorno gli gravitano `Models/Traits/HasLikes.php` e
`Contracts/HasLikeContract.php` — il secondo con zero riferimenti nel repo.

### Lo stesso nome per due contratti diversi

`RatingData` esiste due volte, e non sono due versioni della stessa classe:

| File | Base | Campi |
|---|---|---|
| `app/DataObjects/RatingData.php` | `final readonly class` | `title`, `score` (validato 0-5), `description`, `userId` |
| `app/Datas/RatingData.php` | `extends Spatie\LaravelData\Data` | `title`, `description`, `disabled`, `position`, `locale`, `image_url` |

Il codice applicativo usa solo la seconda (`app/Filament/Blocks/Rating.php:16`); la
prima è importata unicamente da `tests/Unit/RatingDataObjectTest.php`. La cartella
canonica del progetto è `Datas/`.

### Codice morto dichiarato tale e lasciato lì

- `app/Models/Traits/RatingTrait.php` — zero riferimenti nel repo
- `app/Contracts/HasLikeContract.php` — zero riferimenti
- `app/Models/Traits/HasRating.php` — porta in testa (riga 12) un
  `@phpstan-ignore trait.unused` con la nota *«verificato zero consumer reale il
  2026-09-01»*. La verifica è stata fatta, la conseguenza no
- `app/Aggregates/BettableAggregate.to_predict` e `BettableAggregate.to_rating` — due
  file da 296 byte senza estensione `.php`, residui di uno spostamento fra Rating e
  Predict rimasto a metà

### Confine con il dominio: da tenere d'occhio

`app/Filament/Actions/Table/BetTableAction.php`, `app/Enums/RuleEnum.php` e gli
`Aggregates` scommessa (`Bettable`) parlano un linguaggio che non è quello delle
valutazioni. Non è una violazione misurata — non c'è un import di modulo di dominio in
nessuno di questi file — ma è il punto da cui una piattaforma comincia a diventare
un'applicazione.

## Come servire meglio lo scopo

### 1. Una sola definizione di colonne per Resource

Far estendere `BaseRatingsTable` a `RatingsTable` e togliere le nove colonne ricopiate;
cancellare `RatingTable.php` e `RatingMorphTable.php` insieme agli assert che le tengono
vive in `tests/Unit/RatingFilamentSchemaTest.php`. Se una colonna serve solo a Rating e
non alle foglie, si sovrascrive `getTableColumns()` nella concreta: è esattamente il
motivo per cui la base è astratta.

```bash
cd laravel && find Modules/Rating/app/Filament/Resources/*/Tables -name '*.php' | wc -l   # oggi 5, obiettivo 3
```

### 2. Riportare i RelationManager sotto `XotBase*` e tenerne uno

Cancellare `app/Filament/RelationManagers/RatingsRelationManager.php` (fuori dalla
gerarchia delle Resource) e far estendere alla copia superstite la base Xot invece di
`Filament\Resources\RelationManagers\RelationManager`. Sono le uniche due classi
Filament dei tre moduli fuori regola: chiuderle riporta il conteggio a zero.

```bash
cd laravel && grep -rn 'extends RelationManager\b' --include=*.php Modules/Rating/app | wc -l   # oggi 2, obiettivo 0
```

### 3. Decidere su `Like`

Due strade oneste: o `likes` è un concetto del progetto e ha diritto alla sua migrazione
(`{timestamp}_create_likes_table.php`, `XotBaseMigration`, `nullableMorphs('likeable')`),
o non lo è e allora `Like.php`, `HasLikes.php` e `HasLikeContract.php` escono dal repo.
La terza strada — un modello che punta a una tabella inesistente — è quella che rompe
al primo `Like::query()`.

```bash
cd laravel && grep -rl "'likes'" --include=*.php Modules/*/database/migrations database/migrations | wc -l   # oggi 0
```

### 4. Un solo `RatingData`, sotto `Datas/`

Cancellare `app/DataObjects/RatingData.php` e, se la validazione `0 <= score <= 5` ha
valore, portarla come regola dentro `app/Datas/RatingData.php`. Aggiornare
`tests/Unit/RatingDataObjectTest.php`, che è l'unico consumatore della versione da
rimuovere. La cartella `DataObjects/` sparisce.

```bash
cd laravel && ls Modules/Rating/app/DataObjects 2>/dev/null | wc -l   # obiettivo: 0
```

### 5. Rimuovere ciò che è già stato dichiarato morto

`RatingTrait`, `HasLikeContract`, `HasRating` (quest'ultimo con la nota di verifica già
scritta nel file) e i due `BettableAggregate.to_*`. Un `@phpstan-ignore trait.unused` è
una diagnosi, non una cura: se la verifica è stata fatta e ha detto "zero consumer", il
passo successivo è la cancellazione, non l'annotazione.

```bash
cd laravel && ls Modules/Rating/app/Aggregates/*.to_* 2>/dev/null | wc -l   # obiettivo: 0
cd laravel && grep -rn '@phpstan-ignore trait.unused' --include=*.php Modules/Rating/app | wc -l   # obiettivo: 0
```

## Cosa NON è compito di Rating

- **Non** conosce la Scheda, la Performance, la Progressione, l'Indennità. Se una classe
  di Rating nominasse uno di quei concetti, quel codice sarebbe della foglia — vale qui
  la stessa regola di direzione che vale per Sigma verso Ptv.
- **Non** definisce la scala di valutazione. È il senso di `extra_attributes` schemaless:
  chi valuta decide i criteri, il modulo garantisce solo che siano persistiti e
  tracciabili.
- **Non** possiede i modelli concreti delle foglie. `Modules/Performance/app/Models/Rating.php`
  è di Performance: Rating fornisce `BaseRating`, non il figlio.
- **Non** ospita UI di dominio. Una Resource che filtra per anno di scheda o per reparto
  appartiene alla foglia che estende `BaseRatingResource`, non alla base.
- **Non** è il posto delle scommesse. `Bettable`/`BetTableAction` sono lessico di
  Predict: se restano qui, restano come debito dichiarato, non come funzionalità.

## Verifica

```bash
cd laravel

# 1. Rating è una piattaforma: le foglie lo estendono, lui non le conosce
grep -rl 'extends BaseRating\b\|extends BaseRatingMorph\b' --include=*.php Modules/ \
  | grep -v '^Modules/Rating/' | wc -l                                       # oggi 10
for m in Ptv Performance Progressioni Sigma IndennitaResponsabilita IndennitaCondizioniLavoro; do
  echo "$m: $(grep -rl "Modules\\\\$m\\\\" Modules/Rating/app | wc -l)"      # tutti 0
done

# 2. una sola classe Tables per Resource (+ la base astratta condivisa)
find Modules/Rating/app/Filament/Resources/*/Tables -name '*.php' | wc -l    # oggi 5, obiettivo 3

# 3. nessuna estensione diretta di Filament
grep -rn 'extends RelationManager\b' --include=*.php Modules/Rating/app | wc -l   # oggi 2, obiettivo 0

# 4. 1 modello = 1 migrazione, in entrambe le direzioni
ls Modules/Rating/app/Models/*.php | wc -l                                   # oggi 8 (5 astratti/base + 3 concreti)
ls Modules/Rating/database/migrations/*.php | wc -l                          # oggi 2: ratings + rating_morph
grep -rl "'likes'" --include=*.php Modules/*/database/migrations database/migrations | wc -l   # oggi 0: Like non ha tabella

# 5. un solo RatingData, niente DataObjects/
ls Modules/Rating/app/DataObjects 2>/dev/null | wc -l                        # obiettivo 0

# 6. codice dichiarato morto
grep -rn '@phpstan-ignore trait.unused' --include=*.php Modules/Rating/app | wc -l   # obiettivo 0
ls Modules/Rating/app/Aggregates/*.to_* 2>/dev/null | wc -l                  # obiettivo 0

# 7. no-services
ls Modules/Rating/app/Services 2>/dev/null | wc -l                           # oggi 0, deve restare 0

# 8. analisi statica, config di progetto, nuda
./vendor/bin/phpstan analyse Modules/Rating                                  # deve restare a 0 errori
```

## Collegamenti

- [quality-audit.md](quality-audit.md) — i numeri di qualità già misurati
- [Sigma — scopo](../../Sigma/docs/scopo.md) — la stessa disciplina su un altro modulo di piattaforma
- [parental-sti-filament-schemas](../../../../docs/wiki/rules/parental-sti-filament-schemas.md) — perché ogni figlio ha bisogno delle proprie classi Schema
- [migration-filename-from-model-name](../../../../docs/wiki/rules/migration-filename-from-model-name.md) — 1 modello = 1 migrazione
- [architecture.md](architecture.md) — l'architettura già documentata
