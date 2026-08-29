<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Pages\Concerns;

use Modules\Timber\Support\TimberConfigMap;

trait NormalizesTimberConfigMaps
{
    /**
     * @return array<string, string>
     */
    private function configMap(string $key): array
    {
        return TimberConfigMap::get($key);
    }
}
