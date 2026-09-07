<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources\CatalogPartTypeResource\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Modules\Catalog\Filament\Resources\CatalogPartTypeResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

final class CatalogPartTypeForm extends XotBaseResourceForm
{
    protected static string $resource = CatalogPartTypeResource::class;

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
                TextInput::make('name')
                    ->label(__('catalog.fields.name'))
                    ->required()
                    ->maxLength(255),
            ]),
        ];
    }
}
