<?php

declare(strict_types=1);

namespace App\Support\Concerns;

use App\Support\AccountFeatures;
use Illuminate\Support\Facades\Auth;

/**
 * Shim da gestionale_commesse — visibility Filament via AccountFeatures.
 */
trait HasAccountFeatureVisibility
{
    public static function shouldRegisterNavigation(): bool
    {
        return static::isAvailableInCurrentPanel();
    }

    public static function canViewAny(): bool
    {
        return static::isAvailableInCurrentPanel() && parent::canViewAny();
    }

    public static function canCreate(): bool
    {
        return static::isAvailableInCurrentPanel() && parent::canCreate();
    }

    public static function canEdit(mixed $record): bool
    {
        return static::isAvailableInCurrentPanel() && parent::canEdit($record);
    }

    public static function canView(mixed $record): bool
    {
        return static::isAvailableInCurrentPanel() && parent::canView($record);
    }

    protected static function isAvailableInCurrentPanel(): bool
    {
        $class = static::class;

        return AccountFeatures::currentPanelAllowsFeature(
            Auth::user(),
            adminFeature: defined($class.'::ACCOUNT_FEATURE_ADMIN')
                ? constant($class.'::ACCOUNT_FEATURE_ADMIN')
                : null,
            operatorFeature: defined($class.'::ACCOUNT_FEATURE_OPERATOR')
                ? constant($class.'::ACCOUNT_FEATURE_OPERATOR')
                : null,
            adminDefault: defined($class.'::ACCOUNT_FEATURE_ADMIN_DEFAULT')
                ? (bool) constant($class.'::ACCOUNT_FEATURE_ADMIN_DEFAULT')
                : true,
            operatorDefault: defined($class.'::ACCOUNT_FEATURE_OPERATOR_DEFAULT')
                ? (bool) constant($class.'::ACCOUNT_FEATURE_OPERATOR_DEFAULT')
                : true,
            fallback: false,
        );
    }
}
