<?php

declare(strict_types=1);

namespace Modules\Geo\Actions\GeoData;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Spatie\QueueableAction\QueueableAction;

/**
 * Ottiene le province di una regione (cache 24h).
 */
class GetProvincesAction
{
    use QueueableAction;

    public const CACHE_KEY = 'geo.provinces.%s';

    public const CACHE_TTL = 86400;

    /**
     * @param string $regionCode Codice della regione
     *
     * @return Collection<int, array{name: string, code: string}>
     */
    public function execute(string $regionCode): Collection
    {
        $cacheKey = \sprintf(self::CACHE_KEY, $regionCode);

        /** @var Collection<int, array{name: string, code: string}> $result */
        $result = Cache::remember($cacheKey, self::CACHE_TTL, function () use ($regionCode): Collection {
            /** @var array<string, mixed>|null $region */
            $region = app(LoadGeoDataAction::class)->execute()->firstWhere('code', $regionCode);

            if (! $region || ! \is_array($region) || ! isset($region['provinces']) || ! \is_array($region['provinces'])) {
                /** @var Collection<int, array{name: string, code: string}> $empty */
                $empty = new Collection();

                return $empty;
            }

            /** @var array<int, array<string, mixed>> $provinces */
            $provinces = $region['provinces'];

            /** @var Collection<int, array<string, mixed>> $provincesCollection */
            $provincesCollection = new Collection($provinces);

            /** @var Collection<int, array{name: string, code: string}> $provinceResult */
            $provinceResult = $provincesCollection
                ->map(static function (array $province): array {
                    $name = $province['name'] ?? '';
                    $code = $province['code'] ?? '';

                    return [
                        'name' => \is_string($name) ? $name : (string) $name,
                        'code' => \is_string($code) ? $code : (string) $code,
                    ];
                })
                ->values();

            return $provinceResult;
        });

        return $result;
    }
}
