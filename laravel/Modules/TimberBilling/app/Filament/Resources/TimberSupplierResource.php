<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Filament\Resources;

use App\Support\AccountFeatures;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\TimberBilling\Filament\Resources\TimberSupplierResource\Pages;
use Modules\TimberBilling\Models\TimberSupplier;

final class TimberSupplierResource extends Resource
{
    protected static ?string $model = TimberSupplier::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationLabel = 'Fornitori';

    protected static ?int $navigationSort = 90;

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

    public static function canEdit(Model $record): bool
    {
        return self::isAvailableInCurrentPanel() && parent::canEdit($record);
    }

    public static function canView(Model $record): bool
    {
        return self::isAvailableInCurrentPanel() && parent::canView($record);
    }

    public static function getNavigationGroup(): string
    {
        return 'Anagrafiche';
    }

    private static function isAvailableInCurrentPanel(): bool
    {
        return AccountFeatures::currentPanelAllowsFeature(
            Auth::user(),
            adminFeature: 'registries',
            operatorFeature: 'registries',
        );
    }

    public static function getModelLabel(): string
    {
        return 'Fornitore';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Fornitori';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()
                ->columnSpanFull()
                ->components([
                    Grid::make(2)->components([
                        TextInput::make('legal_name')
                            ->label('Ragione sociale')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('vat_number')
                            ->label('Partita IVA')
                            ->maxLength(255),

                        TextInput::make('tax_code')
                            ->label('Codice fiscale')
                            ->maxLength(255),

                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->maxLength(255),

                        TextInput::make('phone')
                            ->label('Telefono')
                            ->maxLength(255),

                        TextInput::make('certifications')
                            ->label('Certificazioni')
                            ->placeholder('FSC, PEFC')
                            ->maxLength(255),

                        TextInput::make('city')
                            ->label('Citta')
                            ->maxLength(255),

                        TextInput::make('province')
                            ->label('Provincia')
                            ->maxLength(255),

                        Toggle::make('is_active')
                            ->label('Attivo')
                            ->default(true),
                    ]),

                    Textarea::make('notes')
                        ->label('Note')
                        ->columnSpanFull(),
                ])
                ->columns(2),

            Section::make('Foglio appunti')
                ->description('Annotazioni datate sul fornitore')
                ->columnSpanFull()
                ->components([
                    Repeater::make('contactNotes')
                        ->relationship()
                        ->hiddenLabel()
                        ->schema([
                            Textarea::make('body')
                                ->label('Appunto')
                                ->required()
                                ->rows(2),
                        ])
                        ->addActionLabel('Aggiungi appunto')
                        ->defaultItems(0),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('legal_name')
                    ->label('Ragione sociale')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('vat_number')
                    ->label('Partita IVA')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('city')
                    ->label('Citta')
                    ->toggleable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Attivo')
                    ->boolean(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTimberSuppliers::route('/'),
            'create' => Pages\CreateTimberSupplier::route('/create'),
            'view' => Pages\ViewTimberSupplier::route('/{record}'),
            'edit' => Pages\EditTimberSupplier::route('/{record}/edit'),
        ];
    }
}
