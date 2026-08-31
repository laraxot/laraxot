<?php

declare(strict_types=1);

namespace Modules\Geo\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;
use Modules\Geo\Actions\FilterCoordinatesInRadiusAction;

/**
 * Regola di validazione per filtrare le coordinate all'interno di un raggio.
 */
class FilterCoordinatesInRadius implements ValidationRule
{
    public function __construct(
        private readonly FilterCoordinatesInRadiusAction $filterAction,
        private readonly float $centerLatitude,
        private readonly float $centerLongitude,
        private readonly int $radius,
    ) {}

    /**
     * Determina se le coordinate passate sono all'interno del raggio specificato.
     *
     * @param  string  $attribute  Nome dell'attributo
     * @param  mixed  $value  Valore da validare
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! \is_array($value)) {
            $fail('Il valore deve essere un array di coordinate');

            return;
        }

        /** @var array<array{latitude: string, longitude: string}> $coordinates */
        $coordinates = array_map(static function (mixed $coordinate): array {
            if (! \is_array($coordinate)) {
                return ['latitude' => '', 'longitude' => ''];
            }

            $latitude = $coordinate['latitude'] ?? null;
            $longitude = $coordinate['longitude'] ?? null;

            return [
                'latitude' => \is_scalar($latitude) ? ((string) $latitude) : '',
                'longitude' => \is_scalar($longitude) ? ((string) $longitude) : '',
            ];
        }, $value);

        $filteredCoordinates = $this->filterAction->execute(
            $this->centerLatitude,
            $this->centerLongitude,
            $coordinates,
            $this->radius,
        );

        if ($filteredCoordinates === []) {
            $fail($this->message());
        }
    }

    /**
     * Ottiene il messaggio di errore per la validazione fallita.
     */
    public function message(): string
    {
        return 'Nessuna coordinata trovata nel raggio specificato';
    }
}
