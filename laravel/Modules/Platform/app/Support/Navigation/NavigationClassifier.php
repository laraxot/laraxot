<?php

declare(strict_types=1);

namespace Modules\Platform\Support\Navigation;

use Illuminate\Support\Str;
use UnitEnum;

use function Safe\preg_match;

/**
 * Derives locale-stable identity for a Filament resource/page: which module it
 * belongs to and which navigation group label it declares.
 *
 * Node keys must never depend on the request locale, otherwise a toggle set in
 * Italian would stop matching once the UI is viewed in another language. Group
 * labels are therefore always resolved under the application's default locale.
 */
final class NavigationClassifier
{
    /**
     * App\Filament sub-namespaces that are standalone subsystems rather than the
     * core admin/operator surface.
     *
     * @var array<int, string>
     */
    /** @var list<string> */
    private const SUBSYSTEM_SEGMENTS = [
        'Wts', 'Timber', 'Dds', 'Thermo', 'EnergyBroker', 'AsdSoci', 'GivebackPlatform',
    ];

    public static function moduleFor(string $class): string
    {
        if (preg_match('/^Modules\\\\([^\\\\]+)\\\\/', $class, $m) === 1 && isset($m[1])) {
            return $m[1];
        }

        if (preg_match('/^App\\\\Filament\\\\([^\\\\]+)\\\\/', $class, $m) === 1 && isset($m[1])) {
            return in_array($m[1], self::SUBSYSTEM_SEGMENTS, true) ? $m[1] : 'Core';
        }

        return 'Core';
    }

    /**
     * Resolve the navigation group label declared by a resource/page, under the
     * default locale, so the derived key is stable across languages. Returns an
     * empty string for ungrouped items.
     */
    public static function groupLabelFor(string $class): string
    {
        if (! method_exists($class, 'getNavigationGroup')) {
            return '';
        }

        $previous = app()->getLocale();
        $default = config('app.locale');
        app()->setLocale(is_string($default) ? $default : 'en');

        try {
            /** @var string|UnitEnum|null $group */
            $group = $class::getNavigationGroup();
        } catch (\Throwable) {
            return '';
        } finally {
            app()->setLocale($previous);
        }

        if ($group instanceof UnitEnum) {
            return $group->name;
        }

        return is_string($group) ? $group : '';
    }

    public static function moduleKey(string $module): string
    {
        return 'module:'.$module;
    }

    public static function groupKey(string $panelId, string $groupLabel): string
    {
        return 'group:'.$panelId.':'.Str::slug($groupLabel);
    }

    public static function itemKey(string $class): string
    {
        return 'item:'.$class;
    }
}
