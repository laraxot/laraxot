<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberDocumentResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use Modules\Timber\Filament\Resources\TimberDocumentResource;

final class ViewTimberDocument extends ViewRecord
{
    protected static string $resource = TimberDocumentResource::class;
}
