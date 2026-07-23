<?php

declare(strict_types=1);

namespace Modules\Geo\Actions\GeoData;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Spatie\QueueableAction\QueueableAction;

/**
 * Ottiene tutte le regioni italiane (cache 24h).
 */
class GetRegionsAction
{
    use QueueableAction;

    public const CACHE_KEY = 'geo.regions';

    public const CACHE_TTL = 86400;

    /**
     * @return Collection<int, array{name: string, code: string}>
     */
    public function execute(): Collection
    {
        /** @var Collection<int, array{name: string, code: string}> $result */
        $result = Cache::remember(
            self::CACHE_KEY,
            self::CACHE_TTL,
            fn (): Collection => app(LoadGeoDataAction::class)->execute()->pluck('name', 'code'),
        );

        return $result;
    }
}
