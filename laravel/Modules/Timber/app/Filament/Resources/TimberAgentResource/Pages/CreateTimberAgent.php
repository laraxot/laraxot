<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberAgentResource\Pages;

use Modules\Timber\Filament\Resources\TimberAgentResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;

final class CreateTimberAgent extends XotBaseCreateRecord
{
    protected static string $resource = TimberAgentResource::class;
}
