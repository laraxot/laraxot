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

    /**
     * @return array<string, Component>
     */
    public static function getFormSchema(): array
    {
        return [
            'section' => Section::make()->columns(2)->components([
                'system_type' => Select::make('system_type')
                    ->options(self::systemTypeOptions())
                    ->required()
                    ->native(false),
                'code' => TextInput::make('code')->maxLength(255),
                'customer_id' => Select::make('customer_id')
                    ->options(fn (): array => DB::table('customers')->orderBy('legal_name')->pluck('legal_name', 'id')->all())
                    ->searchable()->preload()->live()
                    ->afterStateUpdated(fn (callable $set) => $set('customer_site_id', null))
                    ->required(),
                'customer_site_id' => Select::make('customer_site_id')
                    ->options(function (callable $get): array {
                        $customerId = $get('customer_id');

                        return blank($customerId)
                            ? []
                            : DB::table('customer_sites')->where('customer_id', $customerId)->pluck('name', 'id')->all();
                    })
                    ->searchable()->preload(),
                'description' => TextInput::make('description')->maxLength(255)->columnSpanFull(),
                'brand' => TextInput::make('brand')->maxLength(255),
                'model' => TextInput::make('model')->maxLength(255),
                'serial_number' => TextInput::make('serial_number')->maxLength(255),
                'power_kw' => TextInput::make('power_kw')->numeric()->suffix('kW'),
                'installation_date' => DatePicker::make('installation_date'),
                'is_active' => Toggle::make('is_active')->default(true),
                'notes' => Textarea::make('notes')->columnSpanFull(),
            ]),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('system_type')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => self::systemTypeOptions()[$state] ?? (string) $state)
                    ->sortable(),
                Tables\Columns\TextColumn::make('code')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('customer_id')
                    ->formatStateUsing(function ($state): string {
                        $name = DB::table('customers')->where('id', $state)->value('legal_name');

                        return is_string($name) ? $name : '—';
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')->limit(40)->toggleable(),
                Tables\Columns\TextColumn::make('media_count')->counts('media'),
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
