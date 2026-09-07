<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources\SubcontractorResource\Pages;

use Modules\Compliance\Filament\Resources\SubcontractorResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;

final class EditSubcontractor extends XotBaseEditRecord
{
    protected static string $resource = SubcontractorResource::class;
}
