<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Filament\Resources\EnergyBrokerDocumentResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\EnergyBroker\Filament\Resources\EnergyBrokerDocumentResource;

final class EditEnergyBrokerDocument extends EditRecord
{
    protected static string $resource = EnergyBrokerDocumentResource::class;
}
