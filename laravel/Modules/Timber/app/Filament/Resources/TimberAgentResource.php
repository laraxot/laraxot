<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources;

use App\Support\AccountFeatures;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\Timber\Filament\Resources\TimberAgentResource\Pages;
use Modules\Timber\Models\TimberAgent;
use Modules\Timber\Models\TimberProduct;

final class TimberAgentResource extends Resource
{
    protected static ?string $model = TimberAgent::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-identification';

    protected static ?string $navigationLabel = 'Agenti';

    protected static ?int $navigationSort = 92;

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
        return 'Agente';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Agenti';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()
                ->columnSpanFull()
                ->components([
                    Grid::make(2)->components([
                        TextInput::make('full_name')
                            ->label('Nome e cognome')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->maxLength(255),

                        TextInput::make('phone')
                            ->label('Telefono')
                            ->maxLength(255),

                        TextInput::make('zone')
                            ->label('Zona')
                            ->maxLength(255),

                        TextInput::make('commission_percent')
                            ->label('Provvigione base %')
                            ->helperText('Usata quando non c\'e una regola piu specifica')
                            ->numeric()
                            ->step(0.01)
                            ->suffix('%'),

                        Toggle::make('is_active')
                            ->label('Attivo')
                            ->default(true),
                    ]),

                    Textarea::make('notes')
                        ->label('Note')
                        ->columnSpanFull(),
                ])
                ->columns(2),

            Section::make('Provvigioni per categoria / prodotto')
                ->components([
                    Repeater::make('commissions')
                        ->relationship()
                        ->hiddenLabel()
                        ->schema([
                            Select::make('scope_type')
                                ->label('Ambito')
                                ->options([
                                    'default' => 'Predefinita',
                                    'category' => 'Per categoria',
                                    'product' => 'Per prodotto',
                                ])
                                ->default('default')
                                ->live()
                                ->required(),
                            TextInput::make('category')
                                ->label('Categoria')
                                ->maxLength(255)
                                ->visible(fn (Get $get): bool => $get('scope_type') === 'category')
                                ->required(fn (Get $get): bool => $get('scope_type') === 'category'),
                            Select::make('product_id')
                                ->label('Prodotto')
                                ->options(fn (): array => TimberProduct::query()
                                    ->orderBy('description')
                                    ->pluck('description', 'id')
                                    ->all())
                                ->searchable()
                                ->preload()
                                ->visible(fn (Get $get): bool => $get('scope_type') === 'product')
                                ->required(fn (Get $get): bool => $get('scope_type') === 'product'),
                            TextInput::make('rate_percent')
                                ->label('Provvigione %')
                                ->numeric()
                                ->step(0.01)
                                ->suffix('%')
                                ->required(),
                        ])
                        ->columns(2)
                        ->addActionLabel('Aggiungi regola')
                        ->defaultItems(0),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Nome e cognome')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('zone')
                    ->label('Zona')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('commission_percent')
                    ->label('Provvigione %')
                    ->suffix('%'),
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
            'index' => Pages\ListTimberAgents::route('/'),
            'create' => Pages\CreateTimberAgent::route('/create'),
            'view' => Pages\ViewTimberAgent::route('/{record}'),
            'edit' => Pages\EditTimberAgent::route('/{record}/edit'),
        ];
    }
}
