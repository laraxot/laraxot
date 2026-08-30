<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Filament\Resources;

use App\Support\AccountFeatures;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Illuminate\Support\Facades\Auth;
use Modules\Platform\Filament\Resources\GatedXotBaseResource;
use Modules\WorkOrder\Filament\Resources\WorkOrderStatusResource\Pages;
use Modules\WorkOrder\Models\WorkOrderStatus;

/**
 * Le fasi di una commessa sono diverse in ogni officina (preventivo →
 * approvazione → produzione → spedizione → chiusa per un insegnista, un'altra
 * lista per un idraulico). Modificabili qui invece che in un seeder
 * irraggiungibile.
 */
final class WorkOrderStatusResource extends GatedXotBaseResource
{
    protected static ?string $model = WorkOrderStatus::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-flag';

    protected static ?int $navigationSort = 90;

    public static function getNavigationGroup(): string
    {
        return (string) __('workorder::work_orders.navigation');
    }

    public static function getModelLabel(): string
    {
        return (string) __('workorder::work_orders.status');
    }

    public static function getPluralModelLabel(): string
    {
        return (string) __('workorder::work_orders.statuses');
    }

    public static function canAccess(): bool
    {
        return AccountFeatures::currentPanelAllowsFeature(
            Auth::user(),
            adminFeature: 'admin.work_orders',
            operatorFeature: null,
            adminDefault: true,
            operatorDefault: false,
            fallback: false,
        );
    }

    /** @return array<int|string, Component|Field> */
    public static function getFormSchema(): array
    {
        return [
            TextInput::make('code')
                ->label(__('workorder::work_orders.fields.code'))
                ->helperText(__('workorder::work_orders.status_help.code'))
                ->required()
                ->maxLength(50)
                ->unique(ignoreRecord: true),
            TextInput::make('name')
                ->label(__('workorder::work_orders.fields.title'))
                ->required()
                ->maxLength(255),
            Select::make('color')
                ->label(__('workorder::work_orders.status_help.color'))
                ->options([
                    'gray' => 'gray',
                    'blue' => 'blue',
                    'amber' => 'amber',
                    'green' => 'green',
                    'red' => 'red',
                ]),
            TextInput::make('sort_order')
                ->label(__('workorder::work_orders.status_help.sort_order'))
                ->numeric()
                ->default(0),
            Toggle::make('is_terminal')
                ->label(__('workorder::work_orders.status_help.is_terminal'))
                ->helperText(__('workorder::work_orders.status_help.is_terminal_hint')),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWorkOrderStatuses::route('/'),
            'create' => Pages\CreateWorkOrderStatus::route('/create'),
            'edit' => Pages\EditWorkOrderStatus::route('/{record}/edit'),
        ];
    }
}
