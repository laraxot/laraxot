<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources;

use App\Support\Concerns\HasAccountFeatureVisibility;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Catalog\Filament\Resources\CatalogPartTypeResource\Pages;
use Modules\Catalog\Models\CatalogPartType;
use Modules\Platform\Filament\Resources\GatedXotBaseResource;

final class CatalogPartTypeResource extends GatedXotBaseResource
{
    use HasAccountFeatureVisibility;

    protected static ?string $model = CatalogPartType::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-tag';

    protected const ACCOUNT_FEATURE_ADMIN = 'admin.catalog';

    protected const ACCOUNT_FEATURE_OPERATOR = 'operator.catalog';

    public static function getNavigationGroup(): string
    {
        return __('catalog.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('catalog.part_type');
    }

    public static function getPluralModelLabel(): string
    {
        return __('catalog.part_types');
    }

    /**
     * @return array<int, Component>
     */
    public static function getFormSchema(): array
    {
        return [
            Section::make()->columnSpanFull()->components([
                TextInput::make('name')
                    ->label(__('catalog.fields.name'))
                    ->required()
                    ->maxLength(255),
            ]),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('catalog.fields.name'))
                    ->searchable()
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
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCatalogPartTypes::route('/'),
            'create' => Pages\CreateCatalogPartType::route('/create'),
            'view' => Pages\ViewCatalogPartType::route('/{record}'),
            'edit' => Pages\EditCatalogPartType::route('/{record}/edit'),
        ];
    }
}
