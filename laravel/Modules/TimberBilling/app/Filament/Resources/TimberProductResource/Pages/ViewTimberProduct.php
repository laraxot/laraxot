<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Filament\Resources\TimberProductResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use Modules\TimberBilling\Filament\Resources\TimberProductResource;

final class ViewTimberProduct extends ViewRecord
{
    protected static string $resource = TimberProductResource::class;
}
