<?php

declare(strict_types=1);

namespace Modules\Platform\Support\Navigation;

use Illuminate\Support\Facades\Schema;
use Modules\Platform\Filament\Pages\NavigationSettingsPage;
use Modules\Platform\Filament\Resources\PlatformSettingResource;
use Modules\Platform\Models\NavigationPreference;

/**
 * Runtime authority deciding whether a Filament resource/page is enabled.
 *
 * An item is visible only when its item node, its owning module node and its
 * navigation-group node are all enabled. State is stored as explicit overrides
 * in {@see NavigationPreference}; anything absent defaults to enabled, so new
 * resources are visible without any configuration.
 */
final class NavigationGate
{
    /**
     * Cache of node keys explicitly turned off. Null means "not loaded yet".
     *
     * @var array<string, true>|null
     */
    private static ?array $disabled = null;

    /**
     * Node keys that must never be disabled, to avoid locking an admin out of
     * the very screen used to re-enable things.
     *
     * @var array<int, string>
     */
    private const PROTECTED_ITEM_CLASSES = [
        NavigationSettingsPage::class,
        PlatformSettingResource::class,
    ];

    public static function isItemEnabled(string $class): bool
    {
        if (self::isProtected($class)) {
            return true;
        }

        $disabled = self::disabledKeys();

        if ($disabled === []) {
            return true;
        }

        if (isset($disabled[NavigationClassifier::itemKey($class)])) {
            return false;
        }

        $module = NavigationClassifier::moduleFor($class);
        if (isset($disabled[NavigationClassifier::moduleKey($module)])) {
            return false;
        }

        return ! self::isGroupDisabledFor($class, $disabled);
    }

    public static function isModuleEnabled(string $module): bool
    {
        return ! isset(self::disabledKeys()[NavigationClassifier::moduleKey($module)]);
    }

    public static function isNodeEnabled(string $nodeKey): bool
    {
        return ! isset(self::disabledKeys()[$nodeKey]);
    }

    public static function isProtected(string $class): bool
    {
        return in_array($class, self::PROTECTED_ITEM_CLASSES, true);
    }

    /**
     * @param  array<string, true>  $disabled
     */
    private static function isGroupDisabledFor(string $class, array $disabled): bool
    {
        $groupLabel = NavigationClassifier::groupLabelFor($class);
        if ($groupLabel === '') {
            return false;
        }

        $panelId = filament()->getCurrentPanel()?->getId();
        if ($panelId === null) {
            return false;
        }

        return isset($disabled[NavigationClassifier::groupKey($panelId, $groupLabel)]);
    }

    /**
     * @return array<string, true>
     */
    private static function disabledKeys(): array
    {
        if (self::$disabled !== null) {
            return self::$disabled;
        }

        if (! Schema::hasTable('navigation_preferences')) {
            return self::$disabled = [];
        }

        /** @var array<int, string> $keys */
        $keys = NavigationPreference::query()
            ->where('is_enabled', false)
            ->pluck('node_key')
            ->all();

        $map = [];
        foreach ($keys as $key) {
            $map[$key] = true;
        }

        return self::$disabled = $map;
    }

    /**
     * Drop the in-memory cache. Call after persisting changes or between tests.
     */
    public static function flush(): void
    {
        self::$disabled = null;
    }
}
