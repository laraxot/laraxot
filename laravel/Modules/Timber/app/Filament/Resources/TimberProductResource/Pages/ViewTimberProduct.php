<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberProductResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use Modules\Timber\Filament\Resources\TimberProductResource;

final class ViewTimberProduct extends ViewRecord
{
    protected static string $resource = TimberProductResource::class;
}
