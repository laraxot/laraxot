<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Filament\Resources\EnergyBrokerDocumentResource\Pages;

use Modules\EnergyBroker\Filament\Resources\EnergyBrokerDocumentResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;

final class EditEnergyBrokerDocument extends XotBaseEditRecord
{
    protected static string $resource = EnergyBrokerDocumentResource::class;
}
