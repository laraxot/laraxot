<?php

declare(strict_types=1);

namespace Modules\Inventory\Filament\Resources\WarehouseResource\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Facades\DB;
use Modules\Inventory\Filament\Resources\WarehouseResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

final class WarehouseForm extends XotBaseResourceForm
{
    protected static string $resource = WarehouseResource::class;

    /**
     * @return array<string, \Filament\Schemas\Components\Component>
     */
    public function getFormSchema(): array
    {
        return [
            'section' => Section::make()
                ->columnSpanFull()
                ->components([
                    Grid::make(2)->components([
                        TextInput::make('name')
                            ->label(__('inventory.fields.name'))
                            ->required()
                            ->maxLength(255),
                        TextInput::make('code')
                            ->label(__('inventory.fields.code'))
                            ->maxLength(64)
                            ->unique(ignoreRecord: true),
                    ]),
                    Grid::make(2)->components([
                        Select::make('vehicle_id')
                            ->label(__('inventory.fields.vehicle'))
                            ->options(fn (): array => DB::table('vehicles')
                                ->orderBy('plate_number')
                                ->pluck('plate_number', 'id')
                                ->map(fn ($plate, $id) => ($plate ?: ('#'.$id)))
                                ->all()
                            )
                            ->searchable()
                            ->preload()
                            ->nullable(),
                    ]),
                    Grid::make(2)->components([
                        Toggle::make('is_active')
                            ->label(__('inventory.fields.is_active'))
                            ->default(true),
                        Toggle::make('is_final_destination')
                            ->label(__('inventory.fields.is_final_destination'))
                            ->helperText(__('inventory.help.is_final_destination'))
                            ->default(false),
                    ]),
                ]),
        ];
    }
}
