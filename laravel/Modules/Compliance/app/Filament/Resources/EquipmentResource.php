<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Compliance\Filament\Resources\EquipmentResource\Pages;
use Modules\Compliance\Models\Equipment;
use Modules\Platform\Filament\Concerns\GatesResourceNavigation;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\Xot\Filament\Resources\XotBaseResource;

final class EquipmentResource extends XotBaseResource
{
    use GatesResourceNavigation;

    protected static ?string $model = Equipment::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-wrench-screwdriver';

    public static function getNavigationGroup(): string
    {
        return __('compliance.navigation');
    }

    public static function getNavigationLabel(): string
    {
        return __('compliance.equipment');
    }

    public static function getModelLabel(): string
    {
        return __('compliance.equipment_item');
    }

    public static function getPluralModelLabel(): string
    {
        return __('compliance.equipment');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEquipment::route('/'),
            'create' => Pages\CreateEquipment::route('/create'),
            'view' => Pages\ViewEquipment::route('/{record}'),
            'edit' => Pages\EditEquipment::route('/{record}/edit'),
        ];
    }
}
