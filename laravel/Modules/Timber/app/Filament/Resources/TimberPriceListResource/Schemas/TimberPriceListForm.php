<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberPriceListResource\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Modules\Timber\Filament\Resources\TimberPriceListResource;
use Modules\Timber\Models\TimberProduct;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

final class TimberPriceListForm extends XotBaseResourceForm
{
    protected static string $resource = TimberPriceListResource::class;

    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            'section' => Section::make()->components([
                Grid::make(3)->components([
                    'code' => TextInput::make('code')->required()->unique(ignoreRecord: true)->maxLength(255),
                    'name' => TextInput::make('name')->required()->maxLength(255),
                    'is_active' => Toggle::make('is_active')->default(true),
                ]),
            ]),
            'items' => Section::make('Prezzi prodotti')->components([
                Repeater::make('items')->relationship()->hiddenLabel()->schema([
                    Select::make('product_id')
                        ->options(fn (): array => TimberProduct::query()->orderBy('description')->pluck('description', 'id')->all())
                        ->searchable()->preload()->required()->columnSpan(2),
                    TextInput::make('net_price')->numeric()->minValue(0)->required(),
                ])->columns(3)->addActionLabel('Aggiungi prezzo')->defaultItems(0),
            ]),
        ];
    }
}
