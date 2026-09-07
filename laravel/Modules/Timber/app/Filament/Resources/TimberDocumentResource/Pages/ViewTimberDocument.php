<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberDocumentResource\Pages;

use Modules\Timber\Filament\Resources\TimberDocumentResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewTimberDocument extends XotBaseViewRecord
{
    protected static string $resource = TimberDocumentResource::class;
}
