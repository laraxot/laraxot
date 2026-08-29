<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberAgentResource\Pages;

use Modules\Timber\Filament\Resources\TimberAgentResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;

final class EditTimberAgent extends XotBaseEditRecord
{
    protected static string $resource = TimberAgentResource::class;
}
