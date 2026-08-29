<?php

declare(strict_types=1);

namespace Modules\Platform\Filament\Concerns;

use Modules\Platform\Support\Navigation\NavigationGate;

/**
 * Applies the runtime navigation gate to a Filament page, covering both the
 * sidebar entry and direct-URL access.
 *
 * Direct-URL access is enforced in {@see mountCanAuthorizeAccess()} rather than
 * only in canAccess(): many pages override canAccess() with their own logic and
 * do not call parent, so gating there would be bypassed. The mount hook lives on
 * the base page, is not overridden by subclasses, and therefore always runs.
 */
trait GatesPageNavigation
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

    public static function canAccess(): bool
    {
        return NavigationGate::isItemEnabled(static::class) && parent::canAccess();
    }

    public function mountCanAuthorizeAccess(): void
    {
        abort_unless(NavigationGate::isItemEnabled(static::class), 403);

        parent::mountCanAuthorizeAccess();
    }
}
