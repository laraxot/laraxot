<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources;

use App\Support\Concerns\HasAccountFeatureVisibility;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Catalog\Filament\Resources\CatalogFrequencyResource\Pages;
use Modules\Catalog\Models\CatalogFrequency;
use Modules\Xot\Filament\Resources\XotBaseResource;

final class CatalogFrequencyResource extends XotBaseResource
{
    use HasAccountFeatureVisibility;

    protected static ?string $model = CatalogFrequency::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clock';

    /** @var string */
    protected const ACCOUNT_FEATURE_ADMIN = 'admin.catalog';

    /** @var string */
    protected const ACCOUNT_FEATURE_OPERATOR = 'operator.catalog';

    public static function getNavigationGroup(): string
    {
        return __('catalog.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('catalog.frequency');
    }

    public static function getPluralModelLabel(): string
    {
        return __('catalog.frequencies');
    }

    /**
     * @return array<int, Component>
     */
    public static function getFormSchema(): array
    {
        return [
            Section::make()->columnSpanFull()->components([
                Grid::make(2)->components([
                    TextInput::make('name')
                        ->label(__('catalog.fields.name'))
                        ->required()
                        ->maxLength(255),
                    TextInput::make('sort_order')
                        ->label(__('catalog.fields.sort_order'))
                        ->numeric()
                        ->minValue(0)
                        ->default(0),
                ]),
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
                Tables\Columns\TextColumn::make('sort_order')
                    ->label(__('catalog.fields.sort_order'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCatalogFrequencies::route('/'),
            'create' => Pages\CreateCatalogFrequency::route('/create'),
            'view' => Pages\ViewCatalogFrequency::route('/{record}'),
            'edit' => Pages\EditCatalogFrequency::route('/{record}/edit'),
        ];
    }
}
