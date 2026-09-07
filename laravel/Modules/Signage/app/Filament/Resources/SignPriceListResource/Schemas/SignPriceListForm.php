<?php

declare(strict_types=1);

namespace Modules\Signage\Filament\Resources\SignPriceListResource\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Modules\Customer\Models\Customer;
use Modules\Signage\Filament\Resources\SignPriceListResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

/**
 * Sales price lists, kept per brand/customer the way the customer keeps them.
 * The grouping is organisational: an article has one sale price, discounts live
 * on the quotation line.
 */
final class SignPriceListForm extends XotBaseResourceForm
{
    protected static string $resource = SignPriceListResource::class;

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
                    Grid::make(2)->components([
                        TextInput::make('code')
                            ->required()
                            ->unique(ignoreRecord: true),
                        TextInput::make('name')
                            ->required(),
                    ]),
                    Grid::make(3)->components([
                        TextInput::make('brand'),
                        Select::make('customer_id')
                            ->options(fn (): array => Customer::query()->orderBy('name')->pluck('name', 'id')->all())
                            ->searchable()
                            ->preload(),
                        TextInput::make('default_markup_percent')
                            ->helperText(__('signage::signage.pricing.markup_hint'))
                            ->numeric()
                            ->suffix('%'),
                    ]),
                    Toggle::make('is_active')->default(true),
                ]),
        ];
    }
}
