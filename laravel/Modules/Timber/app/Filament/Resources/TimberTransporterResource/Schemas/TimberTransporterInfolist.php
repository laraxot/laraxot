<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberTransporterResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Timber\Filament\Resources\TimberTransporterResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

final class TimberTransporterInfolist extends XotBaseResourceInfolist
{
    protected static string $resource = TimberTransporterResource::class;

    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'legal_name' => TextEntry::make('legal_name'),
            'vat_number' => TextEntry::make('vat_number'),
            'phone' => TextEntry::make('phone'),
            'email' => TextEntry::make('email'),
            'vehicle_plate' => TextEntry::make('vehicle_plate'),
            'is_active' => TextEntry::make('is_active'),
            'notes' => TextEntry::make('notes'),
        ];
    }
}
