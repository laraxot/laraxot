<?php

declare(strict_types=1);

namespace Modules\Vehicle\Filament\Resources\VehicleResource\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Modules\Vehicle\Filament\Resources\VehicleResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

final class VehicleForm extends XotBaseResourceForm
{
    protected static string $resource = VehicleResource::class;

    public function getFormColumns(): int
    {
        return 2;
    }

    /**
     * @return array<int|string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            Section::make()->columnSpanFull()->components([
                Grid::make(2)->components([
                    TextInput::make('plate_number')
                        ->required()
                        ->maxLength(50),
                    TextInput::make('year')
                        ->numeric()
                        ->minValue(1900)
                        ->maxValue((int) now()->format('Y') + 1),
                ]),
                Grid::make(2)->components([
                    TextInput::make('make')->maxLength(255),
                    TextInput::make('model')->maxLength(255),
                ]),
                Grid::make(3)->components([
                    TextInput::make('odometer_km')
                        ->numeric()
                        ->minValue(0),
                    DatePicker::make('last_service_on'),
                    DatePicker::make('insurance_expires_on'),
                ]),
                Textarea::make('notes')->columnSpanFull(),
                Toggle::make('is_active')->default(true),
            ])->columns(2),
        ];
    }
}
