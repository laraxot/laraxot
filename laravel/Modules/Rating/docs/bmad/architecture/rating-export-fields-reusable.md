# Architecture — metodo riutilizzabile per getXlsFields dei rating

## Scopo
Fornire un metodo riutilizzabile per generare l logica di preparazione dei campi per l'esportazione XLS/XLSX dei rating collegati a un modello host tramite `extra_attributes`.

## Contesto
Attualmente, la logica per costruire l'array di campi da esportare (vedi `IndennitaResponsabilitaResource::getXlsFields()`) è duplicata o molto simile in altri resource host che hanno bisogno di esportare rating (es. Ptv, Performance, Progressioni). Questa logica include:
- Recupero di parametri (anno, type) dai dati del form
- Risoluzione del modello host e del relativo alias tipo
- Query sui rating con filtri su `extra_attributes`
- Filtraggio dei soli criteri (rating senza figli)
- Eager load delle relazioni necessarie (`children`)
- Costruzione delle colonne di export:
  - Colonna valore: `ratings_by_id.{id}.xls_export_value` (txt del figlio se Select, altrimenti pivot.value)
  - Colonna nota (se applicabile): `ratings_by_id.{id}.pivot.note`
  - Etichette leggibili tramite `HasRatingsTrait::formFieldLabel()`

## Progettazione
### Opzione 1: Metodo in RatingData.php (preferita dall'utente)
```php
namespace Modules\Rating\Datas;

use Illuminate\Support\Arr;
use Modules\Rating\Models\Rating;
use Modules\Rating\Traits\HasRatingsTrait;
use Illuminate\Support\Facades\Lang;

class RatingData extends Data
{
    // ... proprietà esistenti ...

    /**
     * Genera l'array di campi per l'esportazione XLS/XLSX dei rating collegati a un host.
     *
     * @param  array<string, mixed>  $data  Dati del form (deve contenere 'anno' e 'type' o equivalente)
     * @param  class-string<\Modules\Rating\Models\BaseRating>  $ratingClass  Classe rating da usare (default: Rating::getClassName())
     * @return array<int|string, string>  Mappatura path -> label per l'export
     */
    public static function getRatingXlsFields(array $data, ?string $ratingClass = null): array
    {
        $ratingClass ??= Rating::getClassName();

        $anno = Arr::get($data, 'anno/valutatore.anno', null) ?? Arr::get($data, 'anno_valutatore.anno', null);
        if ($anno === null) {
            // Senza anno, restituire solo i campi base (gestito dal chiamante)
            return [];
        }

        // Nella maggior parte dei casi, il tipo è risolto dal modello host
        // Se necessario, può essere passato come parametro aggiuntivo o estratto da $data
        $type = Arr::get($data, 'type') ?? Arr::get($data, 'tipo'); // esempio, da adattare

        // Se il tipo non è fornito, assumiamo che il chiamante lo risolva esternamente
        // e lo passi tramite un parametro aggiuntivo (vedi sotto)
        // Per ora, manteniamo la logica originale: il tipo deve essere disponibile
        if ($type === null) {
            throw new \InvalidArgumentException('Tipo mancante per filtrare i rating');
        }

        $ratings = Rating::withExtraAttributes([
            'anno' => $anno,
            'type' => $type,
        ])->ordered()->get()
            ->reject(fn (Rating $r) => $r->parent_id !== null)
            ->each(fn (Rating $r) => $r->loadMissing('children'));

        $fields = [];

        foreach ($ratings as $rating) {
            $label = HasRatingsTrait::formFieldLabel($rating);
            if ($label === '') {
                $label = 'Rating '.$rating->id;
            }

            $fields[HasRatingsTrait::ratingXlsValuePath($rating)] = $label;

            if ($rating->children->isNotEmpty()) {
                $fields[HasRatingsTrait::ratingValuePath($rating, 'note')] = Lang::get(
                    'rating::fields.note_for',
                    ['label' => $label]
                );
            }
        }

        return $fields;
    }
}
```

### Opzione 2: Nuova classe servizio (consigliato)
```php
namespace Modules\Rating\Services;

use Illuminate\Support\Arr;
use Modules\Rating\Models\Rating;
use Modules\Rating\Traits\HasRatingsTrait;
use Illuminate\Support\Facades\Lang;

class RatingExportService
{
    /**
     * Genera l'array di campi per l'esportazione XLS/XLSX dei rating collegati a un host.
     *
     * @param  array<string, mixed>  $data  Dati del form (deve contenere 'anno' e 'type')
     * @param  class-string<\Modules\Rating\Models\BaseRating>  $hostModelClass  Classe del modello host (per risolvere l'alias tipo)
     * @param  class-string<\Modules\Rating\Models\BaseRating>  $ratingClass  Classe rating da usare (default: Rating::getClassName())
     * @return array<int|string, string>  Mappatura path -> label per l'export
     */
    public static function getXlsFieldsForHost(
        array $data,
        string $hostModelClass,
        ?string $ratingClass = null
    ): array {
        $ratingClass ??= Rating::getClassName();

        $anno = Arr::get($data, 'anno/valutatore.anno', null) ?? Arr::get($data, 'anno_valutatore.anno', null);
        if ($anno === null) {
            return []; // nessun rating da esportare
        }

        // Risolviamo l'alias tipo dal modello host (es. SchedaDip -> 'dip')
        $type = (new $hostModelClass)->classToAlias($hostModelClass);

        $ratings = Rating::withExtraAttributes([
            'anno' => $anno,
            'type' => $type,
        ])->ordered()->get()
            ->reject(fn (Rating $r) => $r->parent_id !== null)
            ->each(fn (Rating $r) => $r->loadMissing('children'));

        $fields = [];

        foreach ($ratings as $rating) {
            $label = HasRatingsTrait::formFieldLabel($rating);
            if ($label === '') {
                $label = 'Rating '.$rating->id;
            }

            $fields[HasRatingsTrait::ratingXlsValuePath($rating)] = $label;

            if ($rating->children->isNotEmpty()) {
                $fields[HasRatingsTrait::ratingValuePath($rating, 'note')] = Lang::get(
                    'rating::fields.note_for',
                    ['label' => $label]
                );
            }
        }

        return $fields;
    }
}
```

### Opzione 3: Metodo statico su HasRatingsTrait (alternativa)
```php
namespace Modules\Rating\Traits;

use Illuminate\Support\Arr;
use Modules\Rating\Models\Rating;
use Illuminate\Support\Facades\Lang;

trait HasRatingsTrait
{
    // ... metodi esistenti ...

    /**
     * Genera l'array di campi per l'esportazione XLS/XLSX dei rating collegati a un host.
     *
     * @param  array<string, mixed>  $data  Dati del form (deve contenere 'anno' e 'type')
     * @param  class-string  $hostModelClass  Classe del modello host (es. IndennitaResponsabilita::class)
     * @return array<int|string, string>  Mappatura path -> label per l'export
     */
    public static function getRatingXlsFields(array $data, string $hostModelClass): array
    {
        $anno = Arr::get($data, 'anno/valutatore.anno', null) ?? Arr::get($data, 'anno_valutatore.anno', null);
        if ($anno === null) {
            return [];
        }

        // In contesto di trait usato sul modello host, $this è l'istanza del host
        // Ma essendo statico, passiamo la classe esplicitamente
        $type = (new $hostModelClass)->classToAlias($hostModelClass);

        $ratings = Rating::withExtraAttributes([
            'anno' => $anno,
            'type' => $type,
        ])->ordered()->get()
            ->reject(fn (Rating $r) => $r->parent_id !== null)
            ->each(fn (Rating $r) => $r->loadMissing('children'));

        $fields = [];

        foreach ($ratings as $rating) {
            $label = self::formFieldLabel($rating);
            if ($label === '') {
                $label = 'Rating '.$rating->id;
            }

            $fields[self::ratingXlsValuePath($rating)] = $label;

            if ($rating->children->isNotEmpty()) {
                $fields[self::ratingValuePath($rating, 'note')] = Lang::get(
                    'rating::fields.note_for',
                    ['label' => $label]
                );
            }
        }

        return $fields;
    }
}
```

## Flussi di dati
1. Resource host (es. IndennitaResponsabilitaResource) chiama il metodo riutilizzabile
2. Passa i dati del form (`$data`) e, a seconda dell'opzione:
   - Opzione 1: nessun altro parametro (tipo risolto internamente se possibile)
   - Opzione 2: classe host e classe rating (opzionale)
   - Opzione 3: classe host
3. Il metodo restituisce un array `['ratings_by_id.{id}.xls_export_value' => 'Etichetta', ...]`
4. Il resource host lo restituisce da `getXlsFields()` così com'è

## Dipendenze
- `Modules\Rating\Models\Rating` (con `withExtraAttributes` scope)
- `Modules\Rating\Traits\HasRatingsTrait` (helper: `formFieldLabel`, `ratingXlsValuePath`, `ratingValuePath`)
- `Illuminate\Support\Arr` (per accesso sicuro ai dati)
- `Illuminate\Support\Facades\Lang` (per traduzione delle etichette nota)

## Sicurezza
- Nessuna query SQL dinamica: si usa `withExtraAttributes` che è già parametrizzato e sicuro
- I dati in input (`$data`) sono attenduti provenire da form validati (dal resource Filament)
- Le etichette vengono tradotte tramite Laravel Lang (safe)

## Testing
- Unit test sul metodo (opzione servizio o trait) con mock dei dati e dei rating
- Test di integrazione su almeno un resource host (IndennitaResponsabilita)
- Verificare che l'array restituito sia compatibile con `CollectionExport::map`

## Nota sull'opzione scelta dall'utente
L'utente ha espresso preferenza per `laravel/Modules/Rating/app/Datas/RatingData.php`. Se si sceglie questa opzione:
- Il metodo sarà statico e pubblico in `RatingData`
- Si manterrà la stessa firma e logica dell'opzione 1 sopra
- Si dovrà valutare l'impatto sulla coesione della classe (DTO UI + logica di export)
- Si consiglia di aggiungere un commento chiarificatore sul doppio ruolo della classe