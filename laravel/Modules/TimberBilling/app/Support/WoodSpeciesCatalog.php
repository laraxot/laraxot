<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Support;

use Illuminate\Support\Facades\Schema;
use Modules\TimberBilling\Models\TimberWoodSpecies;

/**
 * Single source of truth for wood species: managed from the DB (editable page),
 * with the config list as fallback when the table is empty or not migrated yet.
 */
final class WoodSpeciesCatalog
{
    /**
     * Request-scoped memo for the full code => name map, so rendering a giacenza
     * table does not run one query per row. Reset with flushCache() in tests.
     *
     * @var array<string, string>|null
     */
    private static ?array $nameCache = null;

    /**
     * Human-readable name for a species code (falls back to the capitalized
     * code for unknown/legacy values). Used to show names, not codes, in stock.
     */
    public static function name(string $code): string
    {
        $code = trim($code);
        if ($code === '') {
            return '';
        }

        self::$nameCache ??= self::names();

        return self::$nameCache[$code] ?? ucfirst($code);
    }

    /**
     * Full code => name map for display: every DB species (also inactive/legacy)
     * plus config codes, so historical species always resolve to a name.
     *
     * @return array<string, string>
     */
    public static function names(): array
    {
        $names = self::configMap();

        if (Schema::hasTable('timber_wood_species')) {
            /** @var array<string, string> $fromDb */
            $fromDb = TimberWoodSpecies::query()->pluck('name', 'code')->all();
            $names = [...$names, ...$fromDb];
        }

        return $names;
    }

    public static function flushCache(): void
    {
        self::$nameCache = null;
    }

    /**
     * Species available for selection (active), code => name.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        if (! Schema::hasTable('timber_wood_species')) {
            return self::configMap();
        }

        /** @var array<string, string> $fromDb */
        $fromDb = TimberWoodSpecies::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->pluck('name', 'code')
            ->all();

        return $fromDb !== [] ? $fromDb : self::configMap();
    }

    /**
     * Codes accepted on validation: every DB code (also inactive/legacy) plus the
     * config codes, so historical species are never rejected.
     *
     * @return array<int, string>
     */
    public static function validCodes(): array
    {
        $codes = array_keys(self::configMap());

        if (Schema::hasTable('timber_wood_species')) {
            /** @var array<int, string> $dbCodes */
            $dbCodes = TimberWoodSpecies::query()->pluck('code')->all();
            $codes = [...$codes, ...$dbCodes];
        }

        return array_values(array_unique($codes));
    }

    /**
     * @return array<string, string>
     */
    private static function configMap(): array
    {
        $species = config('timberbilling.timber.wood_species', []);

        if (! is_array($species)) {
            return [];
        }

        /** @var array<string, string> $map */
        $map = [];
        foreach ($species as $code => $name) {
            $map[(string) $code] = is_string($name) ? $name : (string) $code;
        }

        return $map;
    }
}
