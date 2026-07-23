<?php

declare(strict_types=1);

namespace Modules\Geo\Actions\GeoData;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Spatie\QueueableAction\QueueableAction;

/**
 * Ottiene le città di una provincia (cache 24h).
 */
class GetCitiesAction
{
    use QueueableAction;

    public const CACHE_KEY = 'geo.cities.%s';

    public const CACHE_TTL = 86400;

    /**
     * @param string $provinceCode Codice della provincia
     *
     * @return Collection<int, array{name: string, code: string}>
     */
    public function execute(string $provinceCode): Collection
    {
        $cacheKey = \sprintf(self::CACHE_KEY, $provinceCode);

        /** @var Collection<int, array{name: string, code: string}> $result */
        $result = Cache::remember($cacheKey, self::CACHE_TTL, function () use ($provinceCode): Collection {
            /** @var array<string, mixed>|null $province */
            $province = app(LoadGeoDataAction::class)->execute()->flatMap(static fn (array $region): array => \is_array($region['provinces'] ?? null)
                ? $region['provinces']
                : [])->firstWhere('code', $provinceCode);

            if (! $province || ! \is_array($province) || ! isset($province['cities']) || ! \is_array($province['cities'])) {
                return new Collection();
            }

            /** @var array<int, array<string, mixed>> $cities */
            $cities = $province['cities'];

            /** @var Collection<int, array<string, mixed>> $citiesCollection */
            $citiesCollection = new Collection($cities);

            /** @var Collection<string, string> $cityResult */
            $cityResult = $citiesCollection->pluck('name', 'code');

            return $cityResult;
        });

        return $result;
    }
}
