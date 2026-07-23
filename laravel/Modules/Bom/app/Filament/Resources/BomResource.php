<?php

declare(strict_types=1);

namespace Modules\Bom\Filament\Resources;

use App\Support\Concerns\HasAccountFeatureVisibility;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Bom\Filament\Resources\BomResource\Pages\CreateBom;
use Modules\Bom\Filament\Resources\BomResource\Pages\EditBom;
use Modules\Bom\Filament\Resources\BomResource\Pages\ListBoms;
use Modules\Bom\Filament\Resources\BomResource\Pages\ViewBom;
use Modules\Bom\Filament\Resources\BomResource\RelationManagers\BomItemsRelationManager;
use Modules\Bom\Models\Bom;
use Modules\Catalog\Models\Product;

final class BomResource extends Resource
{
    use HasAccountFeatureVisibility;

    protected static ?string $model = Bom::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-list-bullet';

    private const ACCOUNT_FEATURE_ADMIN = 'admin.bom';

    private const ACCOUNT_FEATURE_OPERATOR = null;

    private const ACCOUNT_FEATURE_OPERATOR_DEFAULT = false;

    public static function getNavigationGroup(): ?string
    {
        return 'Produzione';
    }

    public static function getModelLabel(): string
    {
        return 'Distinta base';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Distinte base';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()
                ->columnSpanFull()
                ->components([
                    Select::make('product_id')
                        ->label('Prodotto padre')
                        ->options(fn () => Product::query()->orderBy('name')->pluck('name', 'id')->all())
                        ->searchable()
                        ->preload()
                        ->required(),
                    TextInput::make('name')
                        ->label('Nome/versione')
                        ->maxLength(255),
                    Toggle::make('is_active')
                        ->label('Attiva')
                        ->default(true),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('#')->sortable(),
                Tables\Columns\TextColumn::make('product.name')->label('Prodotto')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('name')->label('Nome')->toggleable(),
                Tables\Columns\IconColumn::make('is_active')->label('Attiva')->boolean()->sortable(),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('id', 'desc')
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
            BomItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBoms::route('/'),
            'create' => CreateBom::route('/create'),
            'view' => ViewBom::route('/{record}'),
            'edit' => EditBom::route('/{record}/edit'),
        ];
    }
}

