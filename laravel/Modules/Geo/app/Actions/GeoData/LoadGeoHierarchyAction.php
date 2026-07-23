<?php

declare(strict_types=1);

namespace Modules\Geo\Actions\GeoData;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

use function Safe\json_decode;

use Spatie\QueueableAction\QueueableAction;

/**
 * Carica e espone gerarchia regioni/province/città da JSON comuni.
 *
 * Sostituisce GeoDataService.
 */
final class LoadGeoHierarchyAction
{
    use QueueableAction;

    private const CACHE_KEY_REGIONS = 'geo.regions';

    private const CACHE_KEY_PROVINCES = 'geo.provinces.%s';

    private const CACHE_KEY_CITIES = 'geo.cities.%s';

    private const CACHE_KEY_CAP = 'geo.cap.%s.%s';

    private const CACHE_TTL = 86400;

    private const JSON_PATH = 'Modules/Geo/resources/json/comuni.json';

    /**
     * @return Collection<int, array{name: string, code: string}>
     */
    public function executeRegions(): Collection
    {
        /** @var Collection<int, array{name: string, code: string}> $result */
        $result = Cache::remember(
            self::CACHE_KEY_REGIONS,
            self::CACHE_TTL,
            fn (): Collection => $this->loadData()->pluck('name', 'code'),
        );

        return $result;
    }

    /**
     * @return Collection<int, array{name: string, code: string}>
     */
    public function executeProvinces(string $regionCode): Collection
    {
        $cacheKey = sprintf(self::CACHE_KEY_PROVINCES, $regionCode);

        /** @var Collection<int, array{name: string, code: string}> $result */
        $result = Cache::remember($cacheKey, self::CACHE_TTL, function () use ($regionCode): Collection {
            /** @var array<string, mixed>|null $region */
            $region = $this->loadData()->firstWhere('code', $regionCode);

            if (! $region || ! is_array($region) || ! isset($region['provinces']) || ! is_array($region['provinces'])) {
                return new Collection();
            }

            /** @var array<int, array<string, mixed>> $provinces */
            $provinces = $region['provinces'];

            return (new Collection($provinces))
                ->map(static function (array $province): array {
                    $name = $province['name'] ?? '';
                    $code = $province['code'] ?? '';

                    return [
                        'name' => is_string($name) ? $name : (string) $name,
                        'code' => is_string($code) ? $code : (string) $code,
                    ];
                })
                ->values();
        });

        return $result;
    }

    /**
     * @return Collection<int, array{name: string, code: string}>
     */
    public function executeCities(string $provinceCode): Collection
    {
        $cacheKey = sprintf(self::CACHE_KEY_CITIES, $provinceCode);

        /** @var Collection<int, array{name: string, code: string}> $result */
        $result = Cache::remember($cacheKey, self::CACHE_TTL, function () use ($provinceCode): Collection {
            /** @var array<string, mixed>|null $province */
            $province = $this->loadData()->flatMap(static fn (array $region): array => is_array($region['provinces'] ?? null)
                ? $region['provinces']
                : [])->firstWhere('code', $provinceCode);

            if (! $province || ! is_array($province) || ! isset($province['cities']) || ! is_array($province['cities'])) {
                return new Collection();
            }

            /** @var array<int, array<string, mixed>> $cities */
            $cities = $province['cities'];

            /** @var Collection<string, string> $cityResult */
            $cityResult = (new Collection($cities))->pluck('name', 'code');

            return $cityResult;
        });

        return $result;
    }

    public function executeCap(string $provinceCode, string $cityCode): ?string
    {
        $cacheKey = sprintf(self::CACHE_KEY_CAP, $provinceCode, $cityCode);

        /** @var string|null $result */
        $result = Cache::remember($cacheKey, self::CACHE_TTL, function () use ($provinceCode, $cityCode): ?string {
            /** @var array<string, mixed>|null $province */
            $province = $this->loadData()->flatMap(static fn (array $region): array => is_array($region['provinces'] ?? null)
                ? $region['provinces']
                : [])->firstWhere('code', $provinceCode);

            if (! $province || ! is_array($province) || ! isset($province['cities']) || ! is_array($province['cities'])) {
                return null;
            }

            /** @var array<int, array<string, mixed>> $cities */
            $cities = $province['cities'];

            /** @var array<string, mixed>|null $city */
            $city = (new Collection($cities))->firstWhere('code', $cityCode);

            return is_array($city) && isset($city['cap']) && is_string($city['cap']) ? $city['cap'] : null;
        });

        return $result;
    }

    public function executeClearCache(): void
    {
        Cache::forget(self::CACHE_KEY_REGIONS);
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    private function loadData(): Collection
    {
        if (! File::exists(base_path(self::JSON_PATH))) {
            throw new \RuntimeException('Il file JSON dei comuni non esiste');
        }

        /** @var array<string, mixed> $data */
        $data = json_decode(File::get(base_path(self::JSON_PATH)), true);

        if (! is_array($data)) {
            throw new \RuntimeException('Il file JSON dei comuni non è valido');
        }

        if (! app(ValidateGeoDataIntegrityAction::class)->execute($data)) {
            throw new \RuntimeException('Il file JSON dei comuni non è valido');
        }

        if (! isset($data['regions']) || ! is_array($data['regions'])) {
            throw new \RuntimeException('Regioni mancanti nel file JSON');
        }

        /** @var array<int, array<string, mixed>> $regions */
        $regions = $data['regions'];

        return (new Collection($regions))->values();
    }
}
