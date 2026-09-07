<?php

declare(strict_types=1);

namespace Modules\Signage\Filament\Resources\SignPriceListItemResource\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Modules\Costing\Models\WorkCenter;
use Modules\Signage\Filament\Resources\SignPriceListItemResource;
use Modules\Signage\Support\SignageCosting;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

/**
 * The supplier price list: what every material, machining step and outsourced
 * job costs, grouped by the work centre it belongs to. It is the cost side —
 * the sales side lives in SignPriceListResource.
 */
final class SignPriceListItemForm extends XotBaseResourceForm
{
    protected static string $resource = SignPriceListItemResource::class;

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
                        Select::make('category_code')
                            ->options(__('signage::signage.categories'))
                            ->required(),
                    ]),
                    Select::make('work_center_code')
                        ->options(fn (): array => WorkCenter::query()
                            ->forContext(SignageCosting::CONTEXT)
                            ->where('is_active', true)
                            ->orderBy('sort_order')
                            ->pluck('name', 'code')
                            ->all())
                        ->searchable()
                        ->preload(),
                    TextInput::make('name')
                        ->required()
                        ->columnSpanFull(),
                    Grid::make(3)->components([
                        Select::make('unit_code')
                            ->options(__('signage::signage.units'))
                            ->required(),
                        TextInput::make('unit_cost')
                            ->numeric()
                            ->required()
                            ->default(0),
                        TextInput::make('currency')
                            ->default('EUR')
                            ->maxLength(3),
                    ]),
                    Grid::make(2)->components([
                        TextInput::make('supplier_name')->nullable(),
                        TextInput::make('supplier_code')->nullable(),
                    ]),
                    Textarea::make('notes')->rows(2),
                    Toggle::make('is_active')->default(true),
                ]),
        ];
    }
}
