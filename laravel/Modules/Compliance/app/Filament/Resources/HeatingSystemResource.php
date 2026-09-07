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

    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        /** @var array<int|string, string> $fuelOptions */
        $fuelOptions = collect((array) config('heating.fuel_types', []))
            ->mapWithKeys(function ($f): array {
                $key = is_string($f) ? $f : '';

                return [$key => __('heating.fuels.'.$key)];
            })
            ->all();

        return [
            'main' => Section::make()->columns(2)->components([
                TextInput::make('code')->maxLength(255),
                Select::make('customer_id')

                    ->options(fn (): array => DB::table('customers')->orderBy('legal_name')->pluck('legal_name', 'id')->all())
                    ->searchable()->preload()->live()
                    ->afterStateUpdated(fn (callable $set) => $set('customer_site_id', null))
                    ->required(),
                Select::make('customer_site_id')

                    ->options(function (callable $get): array {
                        $customerId = $get('customer_id');

                        return blank($customerId)
                            ? []
                            : DB::table('customer_sites')->where('customer_id', $customerId)->pluck('name', 'id')->all();
                    })
                    ->searchable()->preload(),
                TextInput::make('description')->maxLength(255),
                Select::make('fuel_type')->options($fuelOptions),
                TextInput::make('total_power_kw')->numeric()->suffix('kW'),
                TextInput::make('plant_registry_code')->maxLength(255),
                DatePicker::make('installation_date'),
                Toggle::make('is_active')->default(true),
                Grid::make(1)->components([
                    Textarea::make('notes')->columnSpanFull(),
                ]),
            ]),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('customer_id')

                    ->formatStateUsing(function ($state): string {
                        $name = DB::table('customers')->where('id', $state)->value('legal_name');

                        return is_string($name) ? $name : '—';
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')->limit(40)->toggleable(),
                Tables\Columns\TextColumn::make('boilers_count')->counts('boilers'),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
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
