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
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Modules\Compliance\Filament\Resources\CustomerSystemResource\Pages;
use Modules\Compliance\Filament\Resources\CustomerSystemResource\RelationManagers\SchemasRelationManager;
use Modules\Compliance\Models\CustomerSystem;
use Modules\Xot\Filament\Resources\XotBaseResource;

final class CustomerSystemResource extends XotBaseResource
{
    use HasAccountFeatureVisibility;

    protected static ?string $model = CustomerSystem::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cpu-chip';

    protected const ACCOUNT_FEATURE_ADMIN = 'admin.customer_systems';

    protected const ACCOUNT_FEATURE_ADMIN_DEFAULT = false;

    /**
     * Tipi di impianto gestiti (videosorveglianza, quadro elettrico, fotovoltaico).
     *
     * @return array<string, string>
     */
    public static function systemTypeOptions(): array
    {
        return [
            'video_surveillance' => 'Videosorveglianza',
            'electrical_panel' => 'Quadro elettrico',
            'photovoltaic' => 'Fotovoltaico',
        ];
    }

    public static function getNavigationGroup(): string
    {
        return 'Impianti';
    }

    public static function getNavigationLabel(): string
    {
        return 'Impianti cliente';
    }

    public static function getModelLabel(): string
    {
        return 'Impianto';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Impianti cliente';
    }

    public static function getRelations(): array
    {
        return [
            SchemasRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomerSystems::route('/'),
            'create' => Pages\CreateCustomerSystem::route('/create'),
            'view' => Pages\ViewCustomerSystem::route('/{record}'),
            'edit' => Pages\EditCustomerSystem::route('/{record}/edit'),
        ];
    }
}
