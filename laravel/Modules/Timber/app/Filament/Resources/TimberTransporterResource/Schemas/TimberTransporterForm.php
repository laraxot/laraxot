<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberTransporterResource\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Modules\Timber\Filament\Resources\TimberTransporterResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

final class TimberTransporterForm extends XotBaseResourceForm
{
    protected static string $resource = TimberTransporterResource::class;

    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            'section' => Section::make()->columnSpanFull()->components([
                Grid::make(2)->components([
                    'legal_name' => TextInput::make('legal_name')->required()->maxLength(255),
                    'vat_number' => TextInput::make('vat_number')->maxLength(255),
                    'phone' => TextInput::make('phone')->maxLength(255),
                    'email' => TextInput::make('email')->email()->maxLength(255),
                    'vehicle_plate' => TextInput::make('vehicle_plate')->maxLength(255),
                    'is_active' => Toggle::make('is_active')->default(true),
                ]),
                'notes' => Textarea::make('notes')->columnSpanFull(),
            ])->columns(2),
        ];
    }
}
