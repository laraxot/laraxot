<?php

declare(strict_types=1);

namespace Modules\Inventory\Filament\Resources;

use App\Support\AccountFeatures;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Modules\Inventory\Filament\Resources\WarehouseResource\Pages;
use Modules\Inventory\Models\Warehouse;

final class WarehouseResource extends Resource
{
    protected static ?string $model = Warehouse::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-building-storefront';

    public static function shouldRegisterNavigation(): bool
    {
        return self::isAvailableInCurrentPanel();
    }

    public static function canViewAny(): bool
    {
        return self::isAvailableInCurrentPanel() && parent::canViewAny();
    }

    public static function canCreate(): bool
    {
        return self::isAvailableInCurrentPanel() && parent::canCreate();
    }

    public static function canEdit($record): bool
    {
        return self::isAvailableInCurrentPanel() && parent::canEdit($record);
    }

    public static function canView($record): bool
    {
        return self::isAvailableInCurrentPanel() && parent::canView($record);
    }

    public static function getNavigationGroup(): ?string
    {
        return __('inventory.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('inventory.warehouse');
    }

    public static function getPluralModelLabel(): string
    {
        return __('inventory.warehouses');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()
                ->columnSpanFull()
                ->components([
                    Grid::make(2)->components([
                        TextInput::make('name')
                            ->label(__('inventory.fields.name'))
                            ->required()
                            ->maxLength(255),
                        TextInput::make('code')
                            ->label(__('inventory.fields.code'))
                            ->maxLength(64)
                            ->unique(ignoreRecord: true),
                    ]),
                    Grid::make(2)->components([
                        Select::make('vehicle_id')
                            ->label(__('inventory.fields.vehicle'))
                            ->options(fn (): array => DB::table('vehicles')
                                ->orderBy('plate_number')
                                ->pluck('plate_number', 'id')
                                ->map(fn ($plate, $id) => ($plate ?: ('#' . $id)))
                                ->all()
                            )
                            ->searchable()
                            ->preload()
                            ->nullable(),
                    ]),
                    Grid::make(2)->components([
                        Toggle::make('is_active')
                            ->label(__('inventory.fields.is_active'))
                            ->default(true),
                        Toggle::make('is_final_destination')
                            ->label(__('inventory.fields.is_final_destination'))
                            ->helperText(__('inventory.help.is_final_destination'))
                            ->default(false),
                    ]),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label(__('inventory.fields.name'))->searchable()->sortable(),
                Tables\Columns\TextColumn::make('code')->label(__('inventory.fields.code'))->toggleable()->sortable(),
                Tables\Columns\TextColumn::make('vehicle.plate_number')
                    ->label(__('inventory.fields.vehicle'))
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('is_active')->label(__('inventory.fields.is_active'))->boolean()->sortable(),
                Tables\Columns\IconColumn::make('is_final_destination')->label(__('inventory.fields.final'))->boolean()->toggleable(),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWarehouses::route('/'),
            'create' => Pages\CreateWarehouse::route('/create'),
            'view' => Pages\ViewWarehouse::route('/{record}'),
            'edit' => Pages\EditWarehouse::route('/{record}/edit'),
        ];
    }

    private static function isAvailableInCurrentPanel(): bool
    {
        return AccountFeatures::currentPanelAllowsFeature(
            Auth::user(),
            adminFeature: 'admin.inventory',
            operatorFeature: null,
            adminDefault: true,
            operatorDefault: false,
            fallback: false,
        );
    }
}

