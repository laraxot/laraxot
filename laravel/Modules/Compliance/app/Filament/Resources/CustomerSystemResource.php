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

    private const ACCOUNT_FEATURE_ADMIN = 'admin.customer_systems';

    private const ACCOUNT_FEATURE_ADMIN_DEFAULT = false;

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

    public static function getNavigationGroup(): ?string
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
                    ->label('Tipo impianto')
                    ->options(self::systemTypeOptions())
                    ->required()
                    ->native(false),
                TextInput::make('code')->label('Codice')->maxLength(255),
                Select::make('customer_id')
                    ->label('Cliente')
                    ->options(fn (): array => DB::table('customers')->orderBy('legal_name')->pluck('legal_name', 'id')->all())
                    ->searchable()->preload()->live()
                    ->afterStateUpdated(fn (callable $set) => $set('customer_site_id', null))
                    ->required(),
                Select::make('customer_site_id')
                    ->label('Sede')
                    ->options(function (callable $get): array {
                        $customerId = $get('customer_id');

                        return blank($customerId)
                            ? []
                            : DB::table('customer_sites')->where('customer_id', $customerId)->pluck('name', 'id')->all();
                    })
                    ->searchable()->preload(),
                TextInput::make('description')->label('Descrizione')->maxLength(255)->columnSpanFull(),
                TextInput::make('brand')->label('Marca')->maxLength(255),
                TextInput::make('model')->label('Modello')->maxLength(255),
                TextInput::make('serial_number')->label('Matricola')->maxLength(255),
                TextInput::make('power_kw')->label('Potenza (kW)')->numeric()->suffix('kW'),
                DatePicker::make('installation_date')->label('Data installazione'),
                Toggle::make('is_active')->label('Attivo')->default(true),
                Textarea::make('notes')->label('Note')->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('system_type')
                    ->label('Tipo')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => self::systemTypeOptions()[$state] ?? (string) $state)
                    ->sortable(),
                Tables\Columns\TextColumn::make('code')->label('Codice')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('customer_id')
                    ->label('Cliente')
                    ->formatStateUsing(fn ($state): string => (string) (DB::table('customers')->where('id', $state)->value('legal_name') ?? '—'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')->label('Descrizione')->limit(40)->toggleable(),
                Tables\Columns\TextColumn::make('media_count')->label('Schemi')->counts('media'),
                Tables\Columns\IconColumn::make('is_active')->label('Attivo')->boolean(),
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
