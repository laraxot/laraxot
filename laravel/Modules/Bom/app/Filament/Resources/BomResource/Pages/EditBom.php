<?php

declare(strict_types=1);

namespace Modules\Bom\Filament\Resources\BomResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\Bom\Filament\Resources\BomResource;

final class EditBom extends EditRecord
{
    protected static string $resource = BomResource::class;
}

