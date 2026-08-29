<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberTransporterResource\Pages;

use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;
use Modules\Timber\Filament\Resources\TimberTransporterResource;

final class EditTimberTransporter extends XotBaseEditRecord
{
    protected static string $resource = TimberTransporterResource::class;
}
