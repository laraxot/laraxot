<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources\CatalogMeasureUnitResource\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Modules\Catalog\Filament\Resources\CatalogMeasureUnitResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

final class CatalogMeasureUnitForm extends XotBaseResourceForm
{
    protected static string $resource = CatalogMeasureUnitResource::class;

    public function getFormColumns(): int
    {
        return 1;
    }

    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            'main' => Section::make()->columnSpanFull()->components([
                Grid::make(2)->components([
                    TextInput::make('name')
                        ->label(__('catalog.fields.name'))
                        ->required()
                        ->maxLength(255),
                    TextInput::make('symbol')
                        ->label(__('catalog.fields.symbol'))
                        ->maxLength(50),
                ]),
            ]),
        ];
    }
}
