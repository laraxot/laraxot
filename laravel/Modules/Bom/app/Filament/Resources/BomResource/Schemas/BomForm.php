<?php

declare(strict_types=1);

namespace Modules\Bom\Filament\Resources\BomResource\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Modules\Bom\Filament\Resources\BomResource;
use Modules\Catalog\Models\Product;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

final class BomForm extends XotBaseResourceForm
{
    protected static string $resource = BomResource::class;

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
            'main' => Section::make()
                ->columnSpanFull()
                ->components([
                    Select::make('product_id')
                        ->label(__('boms.fields.product_id'))
                        ->options(fn () => Product::query()->orderBy('name')->pluck('name', 'id')->all())
                        ->searchable()
                        ->preload()
                        ->required(),
                    TextInput::make('name')
                        ->label(__('boms.fields.name'))
                        ->maxLength(255),
                    Toggle::make('is_active')
                        ->label(__('boms.fields.is_active'))
                        ->default(true),
                ]),
        ];
    }
}
