<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberCustomerPriceResource\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Modules\Timber\Filament\Resources\TimberCustomerPriceResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

final class TimberCustomerPriceForm extends XotBaseResourceForm
{
    protected static string $resource = TimberCustomerPriceResource::class;

    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            'section' => Section::make()->columns(3)->components([
                'customer_id' => Select::make('customer_id')->relationship('customer', 'business_name')->searchable()->preload()->required(),
                'product_id' => Select::make('product_id')->relationship('product', 'description')->searchable()->preload()->required(),
                'net_price' => TextInput::make('net_price')->numeric()->minValue(0)->required(),
            ]),
        ];
    }
}
