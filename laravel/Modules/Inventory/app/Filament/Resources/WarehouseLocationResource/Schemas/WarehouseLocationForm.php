<?php

declare(strict_types=1);

namespace Modules\Inventory\Filament\Resources\WarehouseLocationResource\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Modules\Inventory\Filament\Resources\WarehouseLocationResource;
use Modules\Inventory\Models\Warehouse;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

final class WarehouseLocationForm extends XotBaseResourceForm
{
    protected static string $resource = WarehouseLocationResource::class;

    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            'section' => Section::make()
                ->columnSpanFull()
                ->components([
                    Grid::make(2)->components([
                        Select::make('warehouse_id')
                            ->label(__('inventory.fields.warehouse'))
                            ->options(fn (): array => Warehouse::query()->orderBy('name')->pluck('name', 'id')->all())
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('code')
                            ->label(__('inventory.fields.code'))
                            ->required()
                            ->maxLength(64),
                    ]),
                    Grid::make(4)->components([
                        TextInput::make('label')
                            ->label(__('inventory.fields.label'))
                            ->maxLength(255),
                        TextInput::make('row')
                            ->label(__('inventory.fields.row'))
                            ->numeric()
                            ->minValue(0),
                        TextInput::make('column')
                            ->label(__('inventory.fields.column'))
                            ->numeric()
                            ->minValue(0),
                        TextInput::make('level')
                            ->label(__('inventory.fields.level'))
                            ->numeric()
                            ->minValue(0),
                    ]),
                    Toggle::make('is_active')
                        ->label(__('inventory.fields.is_active'))
                        ->default(true),
                ]),
        ];
    }
}
