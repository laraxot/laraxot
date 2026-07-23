<?php

declare(strict_types=1);

namespace Modules\Bom\Filament\Resources\BomResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Bom\Filament\Resources\BomResource;

final class CreateBom extends CreateRecord
{
    protected static string $resource = BomResource::class;
}

