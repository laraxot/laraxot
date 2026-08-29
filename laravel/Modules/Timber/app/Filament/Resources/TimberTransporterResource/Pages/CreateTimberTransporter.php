<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberTransporterResource\Pages;

use Modules\Timber\Filament\Resources\TimberTransporterResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;

final class CreateTimberTransporter extends XotBaseCreateRecord
{
    protected static string $resource = TimberTransporterResource::class;
}
