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
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Modules\Compliance\Filament\Resources\HeatingSystemResource\Pages;
use Modules\Compliance\Filament\Resources\HeatingSystemResource\RelationManagers\BoilersRelationManager;
use Modules\Compliance\Filament\Resources\HeatingSystemResource\RelationManagers\ControlsRelationManager;
use Modules\Compliance\Models\HeatingSystem;

final class HeatingSystemResource extends Resource
{
    use HasAccountFeatureVisibility;

    protected static ?string $model = HeatingSystem::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-fire';

    private const ACCOUNT_FEATURE_ADMIN = 'admin.heating_maintenance';

    private const ACCOUNT_FEATURE_OPERATOR = 'operator.heating_maintenance';

    public static function getNavigationGroup(): ?string
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

    public static function form(Schema $schema): Schema
    {
        /** @var array<int|string, string> $fuelOptions */
        $fuelOptions = collect((array) config('heating.fuel_types', []))
            ->mapWithKeys(fn ($f): array => [(string) $f => __("heating.fuels.{$f}")])
            ->all();

        return $schema->components([
            Section::make()->columns(2)->components([
                TextInput::make('code')->label(__('heating.fields.code'))->maxLength(255),
                Select::make('customer_id')
                    ->label(__('heating.fields.customer'))
                    ->options(fn (): array => DB::table('customers')->orderBy('legal_name')->pluck('legal_name', 'id')->all())
                    ->searchable()->preload()->live()
                    ->afterStateUpdated(fn (callable $set) => $set('customer_site_id', null))
                    ->required(),
                Select::make('customer_site_id')
                    ->label(__('heating.fields.site'))
                    ->options(function (callable $get): array {
                        $customerId = $get('customer_id');

                        return blank($customerId)
                            ? []
                            : DB::table('customer_sites')->where('customer_id', $customerId)->pluck('name', 'id')->all();
                    })
                    ->searchable()->preload(),
                TextInput::make('description')->label(__('heating.fields.description'))->maxLength(255),
                Select::make('fuel_type')->label(__('heating.fields.fuel_type'))->options($fuelOptions),
                TextInput::make('total_power_kw')->label(__('heating.fields.total_power_kw'))->numeric()->suffix('kW'),
                TextInput::make('plant_registry_code')->label(__('heating.fields.plant_registry_code'))->maxLength(255),
                DatePicker::make('installation_date')->label(__('heating.fields.installation_date')),
                Toggle::make('is_active')->label(__('heating.fields.is_active'))->default(true),
                Grid::make(1)->components([
                    Textarea::make('notes')->label(__('heating.fields.notes'))->columnSpanFull(),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')->label(__('heating.fields.code'))->searchable()->sortable(),
                Tables\Columns\TextColumn::make('customer_id')
                    ->label(__('heating.fields.customer'))
                    ->formatStateUsing(fn ($state): string => (string) (DB::table('customers')->where('id', $state)->value('legal_name') ?? '—'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')->label(__('heating.fields.description'))->limit(40)->toggleable(),
                Tables\Columns\TextColumn::make('boilers_count')->label(__('heating.fields.boilers'))->counts('boilers'),
                Tables\Columns\IconColumn::make('is_active')->label(__('heating.fields.is_active'))->boolean(),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
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
