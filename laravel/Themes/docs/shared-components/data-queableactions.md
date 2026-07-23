# Convenzioni per Data Objects e QueueableActions

Questo documento definisce le convenzioni per l'utilizzo dei Data Objects e QueueableActions nel framework Laraxot <nome progetto>, con una chiara preferenza per le librerie Spatie rispetto agli approcci tradizionali.

## Data Objects con Spatie Laravel Data

### Posizione e Namespace corretti

I Data Objects devono essere collocati nella cartella `app/Datas` del modulo e **NON** in `app/DataObjects`.

#### ✅ CORRETTO
```
Modules/Rating/app/Datas/RatingData.php
```

#### ❌ ERRATO
```
Modules/Rating/app/DataObjects/RatingData.php
```

### Namespace corretto

Il namespace deve seguire la convenzione del modulo **senza** includere il segmento `App`:

#### ✅ CORRETTO
```php
namespace Modules\Rating\Datas;
```

#### ❌ ERRATO
```php
namespace Modules\Rating\App\Datas;
// o
namespace Modules\Rating\DataObjects;
```

### Implementazione di un Data Object

Utilizzare Spatie Laravel Data per implementare Data Objects:

```php
<?php

declare(strict_types=1);

namespace Modules\Rating\Datas;

use Spatie\LaravelData\Data;

class RatingData extends Data
{
    public function __construct(
        public int $value,
        public string $comment,
        public ?string $user_id = null,
        public ?string $rated_type = null,
        public ?string $rated_id = null,
    ) {
    }

    /**
     * Esempio di metodo factory per creare una nuova istanza
     */
    public static function fromRequest(array $data): self
    {
        return new self(
            value: $data['value'] ?? 0,
            comment: $data['comment'] ?? '',
            user_id: $data['user_id'] ?? null,
            rated_type: $data['rated_type'] ?? null,
            rated_id: $data['rated_id'] ?? null,
        );
    }
}
```

### Vantaggi dei Data Objects

1. **Type Safety**: Tipi di dati espliciti per tutti i campi
2. **Validazione Integrata**: Possibilità di integrare regole di validazione
3. **Serializzazione**: Facile conversione da/a JSON
4. **Immutabilità**: I dati non possono essere modificati dopo la creazione
5. **API Resource**: Facile integrazione con le API Resource di Laravel

## QueueableActions invece di Services

### Posizione e Namespace corretti

Le QueueableActions devono essere collocate nella cartella `app/Actions` del modulo:

#### ✅ CORRETTO
```
Modules/Rating/app/Actions/CreateRatingAction.php
```

### Namespace corretto

Il namespace deve seguire la convenzione del modulo **senza** includere il segmento `App`:

#### ✅ CORRETTO
```php
namespace Modules\Rating\Actions;
```

#### ❌ ERRATO
```php
namespace Modules\Rating\App\Actions;
```

### Implementazione di una QueueableAction

Utilizzare Spatie QueueableAction per implementare azioni:

```php
<?php

declare(strict_types=1);

namespace Modules\Rating\Actions;

use Modules\Rating\Datas\RatingData;
use Modules\Rating\Models\Rating;
use Spatie\QueueableAction\QueueableAction;

class CreateRatingAction
{
    use QueueableAction;

    /**
     * Crea una nuova valutazione
     */
    public function execute(RatingData $data): Rating
    {
        $rating = new Rating();
        $rating->value = $data->value;
        $rating->comment = $data->comment;
        $rating->user_id = $data->user_id;
        $rating->rated_type = $data->rated_type;
        $rating->rated_id = $data->rated_id;
        $rating->save();

        return $rating;
    }
}
```

### Uso con Folio/Volt (NON Controller)

Utilizza le Actions direttamente nei file Volt Folio invece dei Controller:

```php
<?php
// ❌ VIETATO: Modules/Rating/app/Http/Controllers/RatingController.php
// ❌ VIETATO: laravel/app/Http/Controllers/RatingController.php
// Canon memoria: docs/wiki/memories/no-http-controllers-folio-actions.md

// ✅ CORRETTO: resources/views/pages/api/rating/store.blade.php
use Modules\Rating\Actions\CreateRatingAction;
use Modules\Rating\Datas\RatingData;
use function Laravel\Folio\{middleware};

middleware(['auth']);

$validated = validator(request()->all(), [
    'value' => 'required|integer|min:1|max:5',
    'comment' => 'required|string',
    'rated_type' => 'required|string',
    'rated_id' => 'required|string',
])->validate();

$ratingData = new RatingData(
    value: $validated['value'],
    comment: $validated['comment'],
    user_id: auth()->id(),
    rated_type: $validated['rated_type'],
    rated_id: $validated['rated_id'],
);

$rating = CreateRatingAction::run($ratingData);

return response()->json(['success' => true]);
```

**Regola architetturale:** `Http/Controllers` è vietato per route applicative. Usare:
- **Folio pages** (`resources/views/pages/`) per frontoffice
- **Filament widgets** per admin
- **Actions** per logica business
```

### Esecuzione in background

Un grande vantaggio delle QueueableActions è la possibilità di eseguirle in background senza modificare il codice:

```php
// Esecuzione sincrona
$rating = $createRatingAction->execute($ratingData);

// Esecuzione in background (coda)
$createRatingAction->onQueue('ratings')->execute($ratingData);
```

### Vantaggi delle QueueableActions rispetto ai Services

1. **Responsabilità Singola**: Ogni action ha un unico scopo
2. **Facile da Testare**: Le actions sono facilmente testabili in isolamento
3. **Esecuzione in Background**: Facile passaggio da esecuzione sincrona ad asincrona
4. **Tipo di Ritorno Esplicito**: Dichiarazione chiara di ciò che l'action restituisce
5. **Forte Tipizzazione**: Utilizzo di Data Objects per parametri di input
6. **Riusabilità**: Possibilità di riutilizzare le actions in vari punti dell'applicazione

## Migrazione da Services a QueueableActions

### Approccio per la migrazione

1. **Identificare i Services**: Individuare i services esistenti nel codebase
2. **Scomporre in Azioni**: Dividere i metodi dei services in actions singole
3. **Creare Data Objects**: Convertire i parametri di input in Data Objects
4. **Aggiornare i Consumer**: Folio/Volt o Filament widgets per utilizzare le nuove actions
5. **Rimuovere Controller**: Nessun `app/Http/Controllers` - solo Actions + Folio/Volt

### Esempio di migrazione

#### Service originale
```php
// DEPRECATO
class RatingService
{
    public function createRating(array $data)
    {
        // Logica di creazione
    }

    public function updateRating(Rating $rating, array $data)
    {
        // Logica di aggiornamento
    }
}
```

#### Convertito in QueueableActions
```php
// CORRETTO
// Modules/Rating/Actions/CreateRatingAction.php
class CreateRatingAction
{
    use QueueableAction;

    public function execute(RatingData $data): Rating
    {
        // Logica di creazione
    }
}

// Modules/Rating/Actions/UpdateRatingAction.php
class UpdateRatingAction
{
    use QueueableAction;

    public function execute(Rating $rating, RatingData $data): Rating
    {
        // Logica di aggiornamento
    }
}
```

## Vantaggi complessivi dell'approccio

1. **Migliore Organizzazione del Codice**: Struttura più chiara e organizzata
2. **Tipo di Ritorno Esplicito**: Facile comprensione di ciò che ogni componente restituisce
3. **Forte Tipizzazione**: Riduzione degli errori a runtime
4. **Facile Testabilità**: Componenti isolati e facili da testare
5. **Scalabilità**: Facile aggiunta di nuove funzionalità
6. **Manutenibilità**: Codice più leggibile e facile da mantenere
7. **Compatibilità con PHPStan**: Struttura adatta per analisi PHPStan di livello 9
