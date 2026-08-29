<?php

declare(strict_types=1);

namespace Modules\Timber\Support;

/**
 * Mappe chiave=>etichetta lette dalla config del modulo (config('timber.*')).
 * Punto unico (DRY) usato da Resources, List pages e pagine operative.
 */
final class TimberConfigMap
{
    /**
     * @return array<string, string>
     */
    public static function get(string $key): array
    {
        $value = config($key, []);

        // Il file config/timber.php del modulo è registrato sotto config('timber.timber.*'):
        // le chiavi brevi ereditate dal sorgente ("timber.product_types") vanno rimappate.
        if ((! is_array($value) || $value === []) && str_starts_with($key, 'timber.') && ! str_starts_with($key, 'timber.timber.')) {
            $short = substr($key, 7);
            $value = config('timber.timber.'.$short, []);
            if (! is_array($value) || $value === []) {
                $value = config('timber.config.'.$short, []);
            }
        }

        if (! is_array($value)) {
            return [];
        }

        /** @var array<string, string> $map */
        $map = [];
        foreach ($value as $code => $label) {
            $map[(string) $code] = is_string($label) ? $label : (string) $code;
        }

        return $map;
    }
}
