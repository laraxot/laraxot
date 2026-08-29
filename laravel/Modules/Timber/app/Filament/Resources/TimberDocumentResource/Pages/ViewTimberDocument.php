<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberDocumentResource\Pages;

use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;
use Modules\Timber\Filament\Resources\TimberDocumentResource;

final class ViewTimberDocument extends XotBaseViewRecord
{
    protected static string $resource = TimberDocumentResource::class;

    /** @return array<string, \Filament\Schemas\Components\Component> */
    protected function getInfolistSchema(): array
    {
        return [];
    }
}
