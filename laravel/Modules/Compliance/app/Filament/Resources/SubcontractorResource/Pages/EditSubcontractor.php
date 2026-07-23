<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources\SubcontractorResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\Compliance\Filament\Resources\SubcontractorResource;

final class EditSubcontractor extends EditRecord
{
    protected static string $resource = SubcontractorResource::class;
}
