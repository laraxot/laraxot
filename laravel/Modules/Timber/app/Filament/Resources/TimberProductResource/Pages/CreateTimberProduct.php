<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberProductResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Timber\Filament\Resources\TimberProductResource;

final class CreateTimberProduct extends CreateRecord
{
    protected static string $resource = TimberProductResource::class;
}
