<?php

declare(strict_types=1);

namespace Modules\MetalWork\Filament\Resources\MetalRawMaterialResource\Schemas;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Modules\MetalWork\Filament\Resources\MetalRawMaterialResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

final class MetalRawMaterialForm extends XotBaseResourceForm
{
    protected static string $resource = MetalRawMaterialResource::class;

    /**
     * @return array<string, Component|Field>
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
                        TextInput::make('description')
                            ->required(),
                    ]),
                    Grid::make(4)->components([
                        Select::make('family_code')
                            ->options(MetalRawMaterialResource::familyOptions())
                            ->default('other')
                            ->required(),
                        Select::make('unit_code')
                            ->options(['kg' => 'kg', 'ml' => 'ml', 'mq' => 'mq', 'nr' => 'nr'])
                            ->default('kg')
                            ->required(),
                        TextInput::make('steel_grade'),
                        TextInput::make('supplier_name'),
                    ]),
                    Grid::make(4)->components([
                        TextInput::make('thickness_mm')->numeric(),
                        TextInput::make('width_mm')->numeric(),
                        TextInput::make('height_mm')->numeric(),
                        TextInput::make('length_mm')->numeric(),
                    ]),
                    Grid::make(3)->components([
                        TextInput::make('density_kg_dm3')
                            ->numeric()
                            ->placeholder('7,85'),
                        TextInput::make('weight_per_meter_kg')
                            ->numeric(),
                        Toggle::make('is_active')->default(true),
                    ]),
                ]),
        ];
    }
}
