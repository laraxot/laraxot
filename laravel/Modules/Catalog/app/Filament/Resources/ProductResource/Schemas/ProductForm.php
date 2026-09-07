<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources\ProductResource\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Modules\Catalog\Filament\Resources\ProductResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

final class ProductForm extends XotBaseResourceForm
{
    protected static string $resource = ProductResource::class;

    public function getFormColumns(): int
    {
        return 2;
    }

    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            'main' => Section::make()
                ->columnSpanFull()
                ->columns(2)
                ->components([
                    TextInput::make('name')
                        ->label(__('catalog.fields.name'))
                        ->required()
                        ->maxLength(255),
                    TextInput::make('sku')
                        ->label(__('catalog.fields.sku'))
                        ->maxLength(255),
                    Textarea::make('description')
                        ->label(__('catalog.fields.description'))
                        ->columnSpanFull(),
                    Grid::make(2)->components([
                        TextInput::make('unit_price')
                            ->label(__('catalog.fields.unit_price'))
                            ->numeric()
                            ->minValue(0),
                        Toggle::make('is_active')
                            ->label(__('catalog.fields.is_active'))
                            ->default(true),
                    ]),
                    Grid::make(2)->components([
                        Select::make('catalog_model_id')
                            ->label(__('catalog.model'))
                            ->relationship('model', 'name')
                            ->searchable()
                            ->preload(),
                        Select::make('catalog_part_type_id')
                            ->label(__('catalog.part_type'))
                            ->relationship('partType', 'name')
                            ->searchable()
                            ->preload(),
                    ]),
                    Grid::make(2)->components([
                        Select::make('catalog_measure_unit_id')
                            ->label(__('catalog.measure_unit'))
                            ->relationship('measureUnit', 'name')
                            ->searchable()
                            ->preload(),
                        Select::make('catalog_frequency_id')
                            ->label(__('catalog.frequency'))
                            ->relationship('frequency', 'name')
                            ->searchable()
                            ->preload(),
                    ]),
                ]),
        ];
    }
}
