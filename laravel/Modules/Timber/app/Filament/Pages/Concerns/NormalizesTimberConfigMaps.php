<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Pages\Concerns;

trait NormalizesTimberConfigMaps
{
    /**
     * @return array<string, string>
     */
    private function configMap(string $key): array
    {
        $values = config($key, []);
        if (! is_array($values)) {
            return [];
        }

        $normalized = [];
        foreach ($values as $code => $label) {
            if (! is_string($code)) {
                continue;
            }

            $normalized[$code] = is_scalar($label) ? (string) $label : '';
        }

        return $normalized;
    }
}
