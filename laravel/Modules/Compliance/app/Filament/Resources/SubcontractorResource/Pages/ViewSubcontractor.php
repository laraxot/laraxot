<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources\SubcontractorResource\Pages;

use Modules\Compliance\Filament\Resources\SubcontractorResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewSubcontractor extends XotBaseViewRecord
{
    protected static string $resource = SubcontractorResource::class;
}
