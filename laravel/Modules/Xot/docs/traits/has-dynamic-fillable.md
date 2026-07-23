# HasDynamicFillable

`Modules\Xot\Models\Traits\HasDynamicFillable`

Estende la lista `$fillable` di un modello con i valori di uno o più enum backed, senza dover elencare manualmente ogni campo.

## Uso

```php
use Modules\Xot\Models\Traits\HasDynamicFillable;

class Client extends BaseModel
{
    use HasDynamicFillable;

    protected array $dynamicFillableEnums = [
        AddressItemEnum::class,
    ];

    protected $fillable = [
        'name',
        // ...campi espliciti
    ];
}
```

`getFillable()` viene sovrascritto per unire `$fillable` con il valore scalare (`->value`) di ogni case di ogni enum dichiarato in `getDynamicFillableEnums()`. Il default del trait ritorna un array vuoto: un consumer deve sovrascrivere `getDynamicFillableEnums()` (o dichiarare `$dynamicFillableEnums` e un override del metodo, come sopra) per attivare l'estensione.

## Contesto (2026-07-06)

Il trait è stato creato durante una sessione di pulizia PHPStan perché `Modules/TechPlanner/app/Models/Client.php` lo referenziava (`use Modules\Xot\Models\Traits\HasDynamicFillable;`) senza che esistesse mai — introdotto in un refactor passato mai completato (verificato con `git log -p`, il file non è mai stato committato). L'intento era ricostruibile in modo univoco dal codice già presente in `Client.php` (property `$dynamicFillableEnums`, metodo `getDynamicFillableEnums()`, enum `Modules\Geo\Enums\AddressItemEnum` con i valori attesi), quindi il trait è stato creato invece di rimuovere la funzionalità. Verificato funzionalmente via `php artisan tinker` (campo `phone` da `AddressItemEnum` risulta fillable su `Client`).

Vedi anche `Modules/Employee/docs/phpstan-compliance-status.md` e `docs/wiki/second-brain/phpstan-journey.md` per il criterio generale: creare codice di produzione mancante solo quando l'intento è univoco e ricostruibile dal codice esistente, mai per far passare un test che testa un dominio inventato.
