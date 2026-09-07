<?php

declare(strict_types=1);

namespace Modules\Vehicle\Filament\Resources\VehicleResource\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Vehicle\Filament\Resources\VehicleResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

final class VehicleInfolist extends XotBaseResourceInfolist
{
    protected static string $resource = VehicleResource::class;

    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'plate_number' => TextEntry::make('plate_number'),
            'make' => TextEntry::make('make'),
            'model' => TextEntry::make('model'),
            'year' => TextEntry::make('year'),
            'odometer_km' => TextEntry::make('odometer_km'),
            'last_service_on' => TextEntry::make('last_service_on')->date(),
            'insurance_expires_on' => TextEntry::make('insurance_expires_on')->date(),
            'notes' => TextEntry::make('notes'),
            'is_active' => IconEntry::make('is_active')->boolean(),
            'created_at' => TextEntry::make('created_at')->dateTime(),
            'updated_at' => TextEntry::make('updated_at')->dateTime(),
        ];
    }
}
