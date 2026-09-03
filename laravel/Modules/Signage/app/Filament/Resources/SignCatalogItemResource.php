<?php

declare(strict_types=1);

namespace Modules\Signage\Filament\Resources;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ReplicateAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Platform\Filament\Resources\GatedXotBaseResource;
use Modules\Signage\Filament\Resources\SignCatalogItemResource\Pages;
use Modules\Signage\Models\SignCatalogItem;
use Modules\Signage\Models\SignPriceList;

/**
 * A finished product in a sales price list, with its own distinta. Items are
 * exploded rather than parametric — one row per letter-height band, LED
 * temperature and colour — so "Duplica" is how the next variant is born, the
 * same as in the customer's gestionale.
 */
final class SignCatalogItemResource extends GatedXotBaseResource
{
    protected static ?string $model = SignCatalogItem::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-tag';

    protected static ?int $navigationSort = 40;

    public static function getNavigationGroup(): string
    {
        return (string) __('signage::signage.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('signage::signage.catalog_item');
    }

    public static function getPluralModelLabel(): string
    {
        return __('signage::signage.catalog_items');
    }

    /**
     * @return array<string, Component|Field>
     */
    public static function getFormSchema(): array
    {
        return [
            'main' => Section::make()
                ->columnSpanFull()
                ->components([
                    Grid::make(2)->components([
                        Select::make('sign_price_list_id')
                            ->options(fn (): array => SignPriceList::query()->orderBy('code')->pluck('name', 'id')->all())
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('code')
                            ->required()
                            ->unique(ignoreRecord: true),
                    ]),
                    TextInput::make('name')
                        ->required()
                        ->columnSpanFull(),
                    Textarea::make('description')
                        ->rows(4)
                        ->columnSpanFull(),
                    Grid::make(4)->components([
                        TextInput::make('family_code'),
                        TextInput::make('color_code'),
                        TextInput::make('thickness_mm')->numeric(),
                        TextInput::make('led_temperature_code'),
                    ]),
                    Grid::make(4)->components([
                        TextInput::make('letter_height_min_mm')->numeric(),
                        TextInput::make('letter_height_max_mm')->numeric(),
                        Select::make('unit_code')
                            ->options(__('signage::signage.units'))
                            ->default('nr')
                            ->required(),
                        TextInput::make('sort_order')->numeric()->default(0),
                    ]),
                    Grid::make(2)->components([
                        TextInput::make('markup_percent')
                            ->helperText(__('signage::signage.pricing.markup_hint'))
                            ->numeric()
                            ->suffix('%'),
                        TextInput::make('manual_sale_price')
                            ->helperText(__('signage::signage.pricing.manual_notice'))
                            ->numeric()
                            ->prefix('€'),
                    ]),
                    Textarea::make('notes')->rows(2)->columnSpanFull(),
                    Toggle::make('is_active')->default(true),
                ]),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                Tables\Columns\TextColumn::make('code')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('priceList.name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('name')->searchable()->limit(50)->wrap(),
                Tables\Columns\TextColumn::make('letter_height_min_mm')
                    ->formatStateUsing(fn (SignCatalogItem $record): string => $record->letter_height_min_mm === null
                        ? '—'
                        : $record->letter_height_min_mm.'–'.$record->letter_height_max_mm.' mm')
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('led_temperature_code')->placeholder('—'),
                Tables\Columns\TextColumn::make('components_count')->counts('components'),
                Tables\Columns\TextColumn::make('manual_sale_price')
                    ->money('EUR')
                    ->placeholder('—'),
                Tables\Columns\IconColumn::make('is_active')->boolean()->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('sign_price_list_id')
                    ->options(fn (): array => SignPriceList::query()->orderBy('code')->pluck('name', 'id')->all()),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                ReplicateAction::make()
                    // components_count rides along from the table's counts()
                    // aggregate and is not a column, so it must not be copied.
                    ->excludeAttributes(['code', 'components_count'])
                    ->beforeReplicaSaved(function (SignCatalogItem $replica, SignCatalogItem $record): void {
                        $replica->code = $record->code.'-COPY';
                    })
                    ->after(function (SignCatalogItem $replica, SignCatalogItem $record): void {
                        foreach ($record->components as $component) {
                            $copy = $component->replicate();
                            $copy->sign_catalog_item_id = $replica->id;
                            $copy->save();
                        }
                    }),
                DeleteAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            SignCatalogItemResource\RelationManagers\ComponentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSignCatalogItems::route('/'),
            'view' => Pages\ViewSignCatalogItem::route('/{record}'),
        ];
    }
}
