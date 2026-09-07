<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources;

use App\Support\Concerns\HasAccountFeatureVisibility;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Modules\Compliance\Filament\Resources\HeatingSystemResource\Pages;
use Modules\Compliance\Filament\Resources\HeatingSystemResource\RelationManagers\BoilersRelationManager;
use Modules\Compliance\Filament\Resources\HeatingSystemResource\RelationManagers\ControlsRelationManager;
use Modules\Compliance\Models\HeatingSystem;
use Modules\Xot\Filament\Resources\XotBaseResource;

final class HeatingSystemResource extends XotBaseResource
{
    use HasAccountFeatureVisibility;

    protected static ?string $model = HeatingSystem::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-fire';

    /** @var string */
    protected const ACCOUNT_FEATURE_ADMIN = 'admin.heating_maintenance';

    /** @var string */
    protected const ACCOUNT_FEATURE_OPERATOR = 'operator.heating_maintenance';

    public static function getNavigationGroup(): string
    {
        return __('heating.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('heating.system');
    }

    public static function getPluralModelLabel(): string
    {
        return __('heating.systems');
    }

    public static function getRelations(): array
    {
        return [
            BoilersRelationManager::class,
            ControlsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHeatingSystems::route('/'),
            'create' => Pages\CreateHeatingSystem::route('/create'),
            'view' => Pages\ViewHeatingSystem::route('/{record}'),
            'edit' => Pages\EditHeatingSystem::route('/{record}/edit'),
        ];
    }
}
