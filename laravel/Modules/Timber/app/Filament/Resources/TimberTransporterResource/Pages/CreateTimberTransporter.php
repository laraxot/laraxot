<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberTransporterResource\Pages;

use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;
use Modules\Timber\Filament\Resources\TimberTransporterResource;

final class CreateTimberTransporter extends XotBaseCreateRecord
{
    protected static string $resource = TimberTransporterResource::class;
}
