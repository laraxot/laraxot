<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Filament\Resources\TimberDocumentResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use Modules\TimberBilling\Filament\Resources\TimberDocumentResource;

final class ViewTimberDocument extends ViewRecord
{
    protected static string $resource = TimberDocumentResource::class;
}
