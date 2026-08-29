<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberProductResource\Pages;

use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;
use Modules\Timber\Filament\Resources\TimberProductResource;

final class CreateTimberProduct extends XotBaseCreateRecord
{
    protected static string $resource = TimberProductResource::class;
}
