<?php

declare(strict_types=1);

namespace Modules\Platform\Support\Navigation;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Modules\Platform\Models\NavigationPreference;
use ReflectionClass;
use Throwable;

/**
 * Builds the panel -> group -> item tree (plus the flat module list) that backs
 * the "Modules & menu" settings screen.
 *
 * Enumeration is structural — driven by reflection on the declared classes — so
 * items of every panel are listed regardless of the runtime gating that would
 * otherwise hide them in the current request.
 */
final class NavigationCatalog
{
    /**
     * @return array{modules: array<int, array{key: string, module: string, label: string, enabled: bool}>, panels: array<int, array{id: string, label: string, groups: array<int, array{key: ?string, label: string, enabled: bool, items: array<int, array{key: string, label: string, class: string, module: string, protected: bool, enabled: bool}>}>}>}
     */
    public function tree(): array
    {
        $overrides = $this->overrides();
        $modules = [];
        $panels = [];

        foreach (filament()->getPanels() as $panel) {
            $panelId = $panel->getId();

            /** @var array<string, array{key: ?string, label: string, enabled: bool, items: array<int, array{key: string, label: string, class: string, module: string, protected: bool, enabled: bool}>}> $groups */
            $groups = [];

            /** @var array<int, class-string> $classes */
            $classes = [...$panel->getResources(), ...$panel->getPages()];

            foreach ($classes as $class) {
                if (! $this->isStructurallyNavigable($class)) {
                    continue;
                }

                $module = NavigationClassifier::moduleFor($class);
                $modules[$module] = true;

                $groupLabel = NavigationClassifier::groupLabelFor($class);
                $groupKey = $groupLabel === '' ? '' : NavigationClassifier::groupKey($panelId, $groupLabel);

                if (! isset($groups[$groupKey])) {
                    $groups[$groupKey] = [
                        'key' => $groupLabel === '' ? null : $groupKey,
                        'label' => $groupLabel === '' ? '' : $this->displayGroupLabel($class),
                        'enabled' => $groupLabel === '' ? true : ($overrides[$groupKey] ?? true),
                        'items' => [],
                    ];
                }

                $itemKey = NavigationClassifier::itemKey($class);
                $groups[$groupKey]['items'][] = [
                    'key' => $itemKey,
                    'label' => $this->displayItemLabel($class),
                    'class' => $class,
                    'module' => $module,
                    'protected' => NavigationGate::isProtected($class),
                    'enabled' => $overrides[$itemKey] ?? true,
                ];
            }

            if ($groups === []) {
                continue;
            }

            $groups = array_values($groups);
            foreach ($groups as &$group) {
                usort($group['items'], static fn (array $a, array $b): int => strcmp((string) $a['label'], (string) $b['label']));
            }
            unset($group);

            usort($groups, static fn (array $a, array $b): int => strcmp((string) $a['label'], (string) $b['label']));

            $panels[] = [
                'id' => $panelId,
                'label' => $this->displayPanelLabel($panelId),
                'groups' => $groups,
            ];
        }

        usort($panels, static fn (array $a, array $b): int => strcmp($a['label'], $b['label']));

        $moduleRows = [];
        foreach (array_keys($modules) as $module) {
            $moduleRows[] = [
                'key' => NavigationClassifier::moduleKey($module),
                'module' => $module,
                'label' => Str::headline($module),
                'enabled' => $overrides[NavigationClassifier::moduleKey($module)] ?? true,
            ];
        }
        usort($moduleRows, static fn (array $a, array $b): int => strcmp($a['label'], $b['label']));

        return ['modules' => $moduleRows, 'panels' => $panels];
    }

    /**
     * Every node key the tree can address, used to validate incoming toggles.
     *
     * @return array<int, string>
     */
    public function knownKeys(): array
    {
        $keys = [];
        $tree = $this->tree();

        foreach ($tree['modules'] as $module) {
            $keys[] = $module['key'];
        }

        foreach ($tree['panels'] as $panel) {
            foreach ($panel['groups'] as $group) {
                if ($group['key'] !== null) {
                    $keys[] = $group['key'];
                }
                foreach ($group['items'] as $item) {
                    $keys[] = $item['key'];
                }
            }
        }

        return array_values(array_unique($keys));
    }

    /**
     * @return array<string, bool>
     */
    private function overrides(): array
    {
        if (! Schema::hasTable('navigation_preferences')) {
            return [];
        }

        /** @var array<string, bool> $map */
        $map = NavigationPreference::query()
            ->pluck('is_enabled', 'node_key')
            ->map(static fn ($value): bool => (bool) $value)
            ->all();

        return $map;
    }

    /**
     * @param  class-string  $class
     */
    private function isStructurallyNavigable(string $class): bool
    {
        $reflection = new ReflectionClass($class);

        if ($reflection->isAbstract()) {
            return false;
        }

        $defaults = $reflection->getDefaultProperties();

        if (array_key_exists('shouldRegisterNavigation', $defaults) && $defaults['shouldRegisterNavigation'] === false) {
            return false;
        }

        if (array_key_exists('isDiscovered', $defaults) && $defaults['isDiscovered'] === false) {
            return false;
        }

        return true;
    }

    private function displayItemLabel(string $class): string
    {
        foreach (['getNavigationLabel', 'getModelLabel', 'getPluralModelLabel'] as $method) {
            if (method_exists($class, $method)) {
                try {
                    $label = $class::{$method}();
                    if (is_string($label) && $label !== '') {
                        return $label;
                    }
                } catch (Throwable) {
                    // Fall through to the next candidate.
                }
            }
        }

        return Str::headline(class_basename($class));
    }

    private function displayGroupLabel(string $class): string
    {
        if (! method_exists($class, 'getNavigationGroup')) {
            return '';
        }

        try {
            $group = $class::getNavigationGroup();
        } catch (Throwable) {
            return '';
        }

        if (is_string($group) && $group !== '') {
            return $group;
        }

        return NavigationClassifier::groupLabelFor($class);
    }

    private function displayPanelLabel(string $panelId): string
    {
        $key = 'platform.navigation_settings.panels.'.$panelId;
        $translated = __($key);

        if (is_string($translated) && $translated !== $key) {
            return $translated;
        }

        return Str::headline($panelId);
    }
}
