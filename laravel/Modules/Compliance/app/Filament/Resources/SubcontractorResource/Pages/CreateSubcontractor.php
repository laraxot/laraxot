<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources\SubcontractorResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Compliance\Filament\Resources\SubcontractorResource;

final class CreateSubcontractor extends CreateRecord
{
    protected static string $resource = SubcontractorResource::class;
}
