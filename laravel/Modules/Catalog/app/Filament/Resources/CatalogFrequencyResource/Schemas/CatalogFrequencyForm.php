<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources\CatalogFrequencyResource\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Modules\Catalog\Filament\Resources\CatalogFrequencyResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

final class CatalogFrequencyForm extends XotBaseResourceForm
{
    protected static string $resource = CatalogFrequencyResource::class;

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
                    TextInput::make('sort_order')
                        ->label(__('catalog.fields.sort_order'))
                        ->numeric()
                        ->minValue(0)
                        ->default(0),
                ]),
            ]),
        ];
    }
}
