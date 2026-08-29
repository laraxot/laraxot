<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberProductResource\Pages;

use Modules\Timber\Filament\Resources\TimberProductResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;

final class CreateTimberProduct extends XotBaseCreateRecord
{
    protected static string $resource = TimberProductResource::class;
}
