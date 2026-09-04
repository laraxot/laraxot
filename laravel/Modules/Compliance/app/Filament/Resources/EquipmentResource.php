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
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Compliance\Filament\Resources\EquipmentResource\Pages;
use Modules\Compliance\Models\Equipment;
use Modules\Platform\Filament\Concerns\GatesResourceNavigation;
use Modules\WorkOrder\Models\WorkOrder;

final class EquipmentResource extends Resource
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

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->columnSpanFull()->components([
                Grid::make(3)->components([
                    TextInput::make('name')->required()->maxLength(255),
                    TextInput::make('type')->maxLength(255),
                    TextInput::make('serial_number')->maxLength(255),
                    Select::make('status')->default('available')->options([
                        'available' => 'Disponibile',
                        'assigned' => 'Assegnata',
                        'maintenance' => 'In manutenzione',
                        'blocked' => 'Bloccata',
                    ]),
                    DatePicker::make('last_checked_on'),
                    DatePicker::make('next_check_due_on'),
                    DatePicker::make('maintenance_due_on'),
                    Select::make('assigned_work_order_id')
                        ->options(fn (): array => WorkOrder::query()->orderByDesc('id')->limit(200)->pluck('title', 'id')->all())
                        ->searchable(),
                ]),
                Textarea::make('notes')->columnSpanFull(),
            ]),
        ]);
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
            ->actions([ViewAction::make(), EditAction::make()])
            ->bulkActions([DeleteBulkAction::make()]);
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
