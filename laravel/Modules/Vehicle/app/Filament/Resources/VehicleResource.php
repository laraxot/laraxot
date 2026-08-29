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
use Modules\Platform\Filament\Resources\GatedXotBaseResource;
use Modules\Vehicle\Filament\Resources\VehicleResource\Pages;
use Modules\Vehicle\Filament\Resources\VehicleResource\RelationManagers\DocumentsRelationManager;
use Modules\Vehicle\Models\Vehicle;

final class VehicleResource extends GatedXotBaseResource
{
    use HasAccountFeatureVisibility;

    protected static ?string $model = Vehicle::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-truck';

    /** Read reflectively by HasAccountFeatureVisibility via defined()/constant(). */
    private const ACCOUNT_FEATURE_ADMIN = 'admin.vehicles';

    /** Read reflectively by HasAccountFeatureVisibility via defined()/constant(). */
    private const ACCOUNT_FEATURE_OPERATOR = 'operator.vehicles';

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

    public static function getFormSchema(): array
    {
        return [
            Section::make()->columnSpanFull()->components([
                Grid::make(2)->components([
                    TextInput::make('plate_number')
                        ->label(__('vehicles.fields.plate_number'))
                        ->required()
                        ->maxLength(50),
                    TextInput::make('year')
                        ->label(__('vehicles.fields.year'))
                        ->numeric()
                        ->minValue(1900)
                        ->maxValue((int) now()->format('Y') + 1),
                ]),
                Grid::make(2)->components([
                    TextInput::make('make')->label(__('vehicles.fields.make'))->maxLength(255),
                    TextInput::make('model')->label(__('vehicles.fields.model'))->maxLength(255),
                ]),
                Grid::make(3)->components([
                    TextInput::make('odometer_km')
                        ->label(__('vehicles.fields.odometer_km'))
                        ->numeric()
                        ->minValue(0),
                    DatePicker::make('last_service_on')->label(__('vehicles.fields.last_service_on')),
                    DatePicker::make('insurance_expires_on')->label(__('vehicles.fields.insurance_expires_on')),
                ]),
                Textarea::make('notes')->label(__('vehicles.fields.notes'))->columnSpanFull(),
                Toggle::make('is_active')->label(__('vehicles.fields.is_active'))->default(true),
            ])->columns(2),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('plate_number')
                    ->label(__('vehicles.fields.plate_number'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('make')
                    ->label(__('vehicles.fields.make'))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('model')
                    ->label(__('vehicles.fields.model'))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('odometer_km')
                    ->label(__('vehicles.fields.odometer_km'))
                    ->numeric()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('insurance_expires_on')
                    ->label(__('vehicles.fields.insurance_expires_on'))
                    ->date()
                    ->toggleable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('vehicles.fields.is_active'))
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
                RestoreBulkAction::make(),
                ForceDeleteBulkAction::make(),
            ]);
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
