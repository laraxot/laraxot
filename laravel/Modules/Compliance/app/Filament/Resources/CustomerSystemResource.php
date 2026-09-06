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
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Modules\Compliance\Filament\Resources\CustomerSystemResource\Pages;
use Modules\Compliance\Filament\Resources\CustomerSystemResource\RelationManagers\SchemasRelationManager;
use Modules\Compliance\Models\CustomerSystem;

final class CustomerSystemResource extends Resource
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

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->columns(2)->components([
                Select::make('system_type')
                    ->options(self::systemTypeOptions())
                    ->required()
                    ->native(false),
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
                TextInput::make('description')->maxLength(255)->columnSpanFull(),
                TextInput::make('brand')->maxLength(255),
                TextInput::make('model')->maxLength(255),
                TextInput::make('serial_number')->maxLength(255),
                TextInput::make('power_kw')->numeric()->suffix('kW'),
                DatePicker::make('installation_date'),
                Toggle::make('is_active')->default(true),
                Textarea::make('notes')->columnSpanFull(),
            ]),
        ]);
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
                    ->formatStateUsing(fn ($state): string => DB::table('customers')->where('id', $state)->value('legal_name') ?? '—')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')->limit(40)->toggleable(),
                Tables\Columns\TextColumn::make('media_count')->counts('media'),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
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
