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
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\Inventory\Filament\Resources\WarehouseLocationResource\Pages;
use Modules\Inventory\Models\Warehouse;
use Modules\Inventory\Models\WarehouseLocation;
use Modules\Platform\Filament\Resources\GatedXotBaseResource;
use Modules\Platform\Models\PlatformSetting;

final class WarehouseLocationResource extends GatedXotBaseResource
{
    protected static ?string $model = WarehouseLocation::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-squares-2x2';

    public static function shouldRegisterNavigation(): bool
    {
        return self::isAvailableInCurrentPanel() && self::isEnabledByPlatform();
    }

    public static function canViewAny(): bool
    {
        return self::isAvailableInCurrentPanel() && self::isEnabledByPlatform() && parent::canViewAny();
    }

    public static function canCreate(): bool
    {
        return self::isAvailableInCurrentPanel() && self::isEnabledByPlatform() && parent::canCreate();
    }

    public static function canEdit(Model $record): bool
    {
        return self::isAvailableInCurrentPanel() && self::isEnabledByPlatform() && parent::canEdit($record);
    }

    public static function canView(Model $record): bool
    {
        return self::isAvailableInCurrentPanel() && self::isEnabledByPlatform() && parent::canView($record);
    }

    public static function getNavigationGroup(): string
    {
        return __('inventory.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('inventory.location');
    }

    public static function getPluralModelLabel(): string
    {
        return __('inventory.locations');
    }

    /**
     * @return array<int, Component>
     */
    public static function getFormSchema(): array
    {
        return [
            Section::make()
                ->columnSpanFull()
                ->components([
                    Grid::make(2)->components([
                        Select::make('warehouse_id')
                            ->label(__('inventory.fields.warehouse'))
                            ->options(fn (): array => Warehouse::query()->orderBy('name')->pluck('name', 'id')->all())
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('code')
                            ->label(__('inventory.fields.code'))
                            ->required()
                            ->maxLength(64),
                    ]),
                    Grid::make(4)->components([
                        TextInput::make('label')
                            ->label(__('inventory.fields.label'))
                            ->maxLength(255),
                        TextInput::make('row')
                            ->label(__('inventory.fields.row'))
                            ->numeric()
                            ->minValue(0),
                        TextInput::make('column')
                            ->label(__('inventory.fields.column'))
                            ->numeric()
                            ->minValue(0),
                        TextInput::make('level')
                            ->label(__('inventory.fields.level'))
                            ->numeric()
                            ->minValue(0),
                    ]),
                    Toggle::make('is_active')
                        ->label(__('inventory.fields.is_active'))
                        ->default(true),
                ]),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('warehouse.name')
                    ->label(__('inventory.fields.warehouse'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('code')
                    ->label(__('inventory.fields.code'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('label')
                    ->label(__('inventory.fields.label'))
                    ->placeholder('—')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('row')
                    ->label(__('inventory.fields.row'))
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('column')
                    ->label(__('inventory.fields.column'))
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('level')
                    ->label(__('inventory.fields.level'))
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('inventory.fields.is_active'))
                    ->boolean()
                    ->sortable(),
            ])
            ->defaultSort('warehouse_id')
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
            'index' => Pages\ListWarehouseLocations::route('/'),
            'create' => Pages\CreateWarehouseLocation::route('/create'),
            'view' => Pages\ViewWarehouseLocation::route('/{record}'),
            'edit' => Pages\EditWarehouseLocation::route('/{record}/edit'),
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

    private static function isEnabledByPlatform(): bool
    {
        return PlatformSetting::getBool('inventory.enable_storage_zones', false);
    }
}
