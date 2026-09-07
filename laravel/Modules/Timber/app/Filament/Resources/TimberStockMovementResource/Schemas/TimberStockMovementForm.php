<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberStockMovementResource\Schemas;

use Closure;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Modules\Timber\Filament\Resources\TimberStockMovementResource;
use Modules\Timber\Models\TimberProduct;
use Modules\Timber\Support\TimberConfigMap;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

final class TimberStockMovementForm extends XotBaseResourceForm
{
    protected static string $resource = TimberStockMovementResource::class;

    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            'section' => Section::make()->columns(2)->components([
                'movement_date' => DatePicker::make('movement_date')->default(now())->required(),
                'direction_code' => Select::make('direction_code')->options(TimberConfigMap::get('timber.stock_movement_directions'))->default('load')->required(),
                'product_id' => Select::make('product_id')
                    ->options(fn (): array => TimberProduct::query()->where('is_active', true)->orderBy('description')->pluck('description', 'id')->all())
                    ->searchable()->preload()->required()->live()
                    ->afterStateUpdated(function (Set $set, mixed $state): void {
                        $product = is_numeric($state) ? TimberProduct::query()->find($state) : null;
                        if ($product !== null) {
                            $set('unit_of_measure', $product->unit_of_measure);
                        }
                    }),
                'grid' => Grid::make(2)->components([
                    'quantity' => TextInput::make('quantity')
                        ->numeric()
                        ->required()
                        ->rule(static function (Get $get): Closure {
                            return static function (string $attribute, mixed $value, Closure $fail) use ($get): void {
                                $quantity = is_numeric($value) ? (float) $value : 0.0;
                                $isAdjustment = $get('direction_code') === 'adjustment';

                                if ($isAdjustment && abs($quantity) < 0.0001) {
                                    $fail('La rettifica non puo essere nulla.');

                                    return;
                                }

                                if (! $isAdjustment && $quantity <= 0) {
                                    $fail('La quantita deve essere positiva.');
                                }
                            };
                        }),
                    'unit_of_measure' => Select::make('unit_of_measure')
                        ->options(TimberConfigMap::get('timber.units_of_measure'))
                        ->default('quintali')
                        ->required(),
                ]),
                'customer_id' => Select::make('customer_id')->relationship('customer', 'business_name')->searchable()->preload(),
                'document_type_code' => Select::make('document_type_code')->options(TimberConfigMap::get('timber.document_types')),
                'document_reference' => TextInput::make('document_reference')->maxLength(255),
                'lot_code' => TextInput::make('lot_code')->maxLength(255),
                'unit_cost' => TextInput::make('unit_cost')->numeric()->minValue(0)
                    ->helperText('Aggiorna costo medio e ultimo costo del prodotto sui carichi'),
                'notes' => Textarea::make('notes')->rows(2)->columnSpanFull(),
            ]),
        ];
    }
}
