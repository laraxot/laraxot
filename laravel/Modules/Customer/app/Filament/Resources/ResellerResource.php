<?php

declare(strict_types=1);

namespace Modules\Customer\Filament\Resources;

use App\Support\AccountFeatures;
use App\Support\Concerns\HasAccountFeatureVisibility;
use Illuminate\Support\Facades\Auth;
use Modules\Customer\Filament\Resources\ResellerResource\Pages;
use Modules\Customer\Models\Reseller;
use Modules\Xot\Filament\Resources\XotBaseResource;

final class ResellerResource extends XotBaseResource
{
    use HasAccountFeatureVisibility;

    protected static ?string $model = Reseller::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    private const string ACCOUNT_FEATURE_ADMIN = 'admin.resellers';

    private const ?string ACCOUNT_FEATURE_OPERATOR = null;

    private const bool ACCOUNT_FEATURE_OPERATOR_DEFAULT = false;

    public static function getNavigationGroup(): string
    {
        return (string) __('customers.navigation');
    }

    /**
     * Explicit feature wiring (constants referenced for PHPStan + AccountFeatures).
     */
    protected static function isAvailableInCurrentPanel(): bool
    {
        return AccountFeatures::currentPanelAllowsFeature(
            Auth::user(),
            adminFeature: self::ACCOUNT_FEATURE_ADMIN,
            operatorFeature: self::ACCOUNT_FEATURE_OPERATOR,
            operatorDefault: self::ACCOUNT_FEATURE_OPERATOR_DEFAULT,
            fallback: false,
        );
    }

    public static function getModelLabel(): string
    {
        return __('resellers.reseller');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resellers.resellers');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListResellers::route('/'),
            'create' => Pages\CreateReseller::route('/create'),
            'view' => Pages\ViewReseller::route('/{record}'),
            'edit' => Pages\EditReseller::route('/{record}/edit'),
        ];
    }
}
