<?php

declare(strict_types=1);

namespace Modules\Signage\Filament\Resources\SignCatalogItemResource\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Modules\Signage\Filament\Resources\SignCatalogItemResource;
use Modules\Signage\Models\SignPriceList;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

/**
 * Schema form per {@see SignCatalogItemResource}.
 *
 * A finished product in a sales price list, with its own distinta. Items are
 * exploded rather than parametric — one row per letter-height band, LED
 * temperature and colour — so "Duplica" is how the next variant is born, the
 * same as in the customer's gestionale.
 */
final class SignCatalogItemForm extends XotBaseResourceForm
{
    protected static string $resource = SignCatalogItemResource::class;

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
}
