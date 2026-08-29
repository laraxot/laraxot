<?php

declare(strict_types=1);

namespace Modules\Platform\Filament\Concerns;

use Modules\Platform\Support\Navigation\NavigationGate;

/**
 * Applies the runtime navigation gate to a Filament resource, covering both the
 * sidebar entry and direct-URL access. Composes with any other authorization by
 * AND-ing the parent implementation.
 */
trait GatesResourceNavigation
{
    public static function registerNavigationItems(): void
    {
        if (! NavigationGate::isItemEnabled(static::class)) {
            return;
        }

        parent::registerNavigationItems();
    }

    public static function shouldRegisterNavigation(): bool
    {
        return NavigationGate::isItemEnabled(static::class) && parent::shouldRegisterNavigation();
    }

    public static function canViewAny(): bool
    {
        return NavigationGate::isItemEnabled(static::class) && parent::canViewAny();
    }
}
