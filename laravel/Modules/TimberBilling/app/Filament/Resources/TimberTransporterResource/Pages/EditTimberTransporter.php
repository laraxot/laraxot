<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Filament\Resources\TimberTransporterResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\TimberBilling\Filament\Resources\TimberTransporterResource;

final class EditTimberTransporter extends EditRecord
{
    protected static string $resource = TimberTransporterResource::class;
}
