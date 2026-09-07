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

    /**
     * @return array<string, Component>
     */
    public static function getFormSchema(): array
    {
        return [
            'section' => Section::make()->columnSpanFull()->components([
                'grid' => Grid::make(3)->components([
                    'name' => TextInput::make('name')->required()->maxLength(255),
                    'type' => TextInput::make('type')->maxLength(255),
                    'serial_number' => TextInput::make('serial_number')->maxLength(255),
                    'status' => Select::make('status')->default('available')->options([
                        'available' => 'Disponibile',
                        'assigned' => 'Assegnata',
                        'maintenance' => 'In manutenzione',
                        'blocked' => 'Bloccata',
                    ]),
                    'last_checked_on' => DatePicker::make('last_checked_on'),
                    'next_check_due_on' => DatePicker::make('next_check_due_on'),
                    'maintenance_due_on' => DatePicker::make('maintenance_due_on'),
                    'assigned_work_order_id' => Select::make('assigned_work_order_id')
                        ->options(fn (): array => WorkOrder::query()->orderByDesc('id')->limit(200)->pluck('title', 'id')->all())
                        ->searchable(),
                ]),
                'notes' => Textarea::make('notes')->columnSpanFull(),
            ]),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('type')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('serial_number')->toggleable(),
                Tables\Columns\TextColumn::make('status')->badge()->sortable(),
                Tables\Columns\TextColumn::make('next_check_due_on')->date()->sortable(),
                Tables\Columns\TextColumn::make('assignedWorkOrder.title')->limit(40)->toggleable(),
            ])
            ->recordActions([ViewAction::make(), EditAction::make()])
            ->toolbarActions([DeleteBulkAction::make()]);
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
