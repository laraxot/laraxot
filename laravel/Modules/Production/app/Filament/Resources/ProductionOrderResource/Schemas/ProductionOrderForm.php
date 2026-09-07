<?php

declare(strict_types=1);

namespace Modules\Production\Filament\Resources\ProductionOrderResource\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Modules\Catalog\Models\Product;
use Modules\Production\Filament\Resources\ProductionOrderResource;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

final class ProductionOrderForm extends XotBaseResourceForm
{
    protected static string $resource = ProductionOrderResource::class;

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
            'section' => Section::make()
                ->columnSpanFull()
                ->components([
                    'code_status' => Grid::make(2)->components([
                        'code' => TextInput::make('code')
                            ->label(__('production.fields.code'))
                            ->maxLength(255)
                            ->disabled(),
                        'status' => Select::make('status')
                            ->label(__('production.fields.status'))
                            ->options([
                                'planned' => __('production.statuses.planned'),
                                'in_progress' => __('production.statuses.in_progress'),
                                'completed' => __('production.statuses.completed'),
                                'cancelled' => __('production.statuses.cancelled'),
                            ])
                            ->required(),
                    ]),
                    'product_qty' => Grid::make(2)->components([
                        'product_id' => Select::make('product_id')
                            ->label(__('production.fields.product'))
                            ->options(fn () => Product::query()->orderBy('name')->pluck('name', 'id')->all())
                            ->searchable()
                            ->preload()
                            ->required(),
                        'quantity' => TextInput::make('quantity')
                            ->label(__('production.fields.quantity'))
                            ->numeric()
                            ->required(),
                    ]),
                    'work_order_id' => Select::make('work_order_id')
                        ->label(__('production.fields.work_order'))
                        ->options(fn () => WorkOrder::query()->orderByDesc('id')->limit(200)->pluck('code', 'id')->all())
                        ->searchable(),
                    'due_at' => DateTimePicker::make('due_at')
                        ->label(__('production.fields.due_at')),
                    'notes' => Textarea::make('notes')
                        ->label(__('production.fields.notes'))
                        ->columnSpanFull(),
                ]),
        ];
    }
}
