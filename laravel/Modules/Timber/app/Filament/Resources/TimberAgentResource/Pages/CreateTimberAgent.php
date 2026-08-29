<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberAgentResource\Pages;

use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;
use Modules\Timber\Filament\Resources\TimberAgentResource;

final class CreateTimberAgent extends XotBaseCreateRecord
{
    protected static string $resource = TimberAgentResource::class;
}
