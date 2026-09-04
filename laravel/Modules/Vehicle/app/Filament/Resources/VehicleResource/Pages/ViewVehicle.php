<?php

declare(strict_types=1);

namespace Modules\Vehicle\Filament\Resources\VehicleResource\Pages;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Vehicle\Filament\Resources\VehicleResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewVehicle extends XotBaseViewRecord
{
    protected static string $resource = VehicleResource::class;

    /**
     * @return array<string, Component>
     */
    protected function getInfolistSchema(): array
    {
        return [
            'plate_number' => TextEntry::make('plate_number')->label(__('vehicles.fields.plate_number')),
            'make' => TextEntry::make('make')->label(__('vehicles.fields.make')),
            'model' => TextEntry::make('model')->label(__('vehicles.fields.model')),
            'year' => TextEntry::make('year')->label(__('vehicles.fields.year')),
            'odometer_km' => TextEntry::make('odometer_km')->label(__('vehicles.fields.odometer_km'))->numeric(),
            'insurance_expires_on' => TextEntry::make('insurance_expires_on')->label(__('vehicles.fields.insurance_expires_on'))->date(),
        ];
    }
}
