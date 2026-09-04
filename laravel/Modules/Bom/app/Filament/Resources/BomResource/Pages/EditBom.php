<?php

declare(strict_types=1);

namespace Modules\Bom\Filament\Resources\BomResource\Pages;

use Modules\Bom\Filament\Resources\BomResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;

final class EditBom extends XotBaseEditRecord
{
    protected static string $resource = BomResource::class;
}
