<?php

declare(strict_types=1);

namespace Modules\Vehicle\Filament\Resources;

use App\Support\AccountFeatures;
use App\Support\Concerns\HasAccountFeatureVisibility;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Modules\Vehicle\Filament\Resources\VehicleResource\Pages;
use Modules\Vehicle\Filament\Resources\VehicleResource\RelationManagers\DocumentsRelationManager;
use Modules\Vehicle\Models\Vehicle;
use Modules\Xot\Filament\Resources\XotBaseResource;

final class VehicleResource extends XotBaseResource
{
    use HasAccountFeatureVisibility;

    protected static ?string $model = Vehicle::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-truck';

    /** Read reflectively by HasAccountFeatureVisibility via defined()/constant(). */
    private const string ACCOUNT_FEATURE_ADMIN = 'admin.vehicles';

    /** Read reflectively by HasAccountFeatureVisibility via defined()/constant(). */
    private const string ACCOUNT_FEATURE_OPERATOR = 'operator.vehicles';

    public static function getNavigationGroup(): string
    {
        return (string) __('vehicles.navigation');
    }

    protected static function isAvailableInCurrentPanel(): bool
    {
        return AccountFeatures::currentPanelAllowsFeature(
            Auth::user(),
            adminFeature: self::ACCOUNT_FEATURE_ADMIN,
            operatorFeature: self::ACCOUNT_FEATURE_OPERATOR,
            fallback: false,
        );
    }

    public static function getModelLabel(): string
    {
        return __('vehicles.vehicle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('vehicles.vehicles');
    }

    public static function getRelations(): array
    {
        return [
            DocumentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVehicles::route('/'),
            'create' => Pages\CreateVehicle::route('/create'),
            'view' => Pages\ViewVehicle::route('/{record}'),
            'edit' => Pages\EditVehicle::route('/{record}/edit'),
        ];
    }
}
