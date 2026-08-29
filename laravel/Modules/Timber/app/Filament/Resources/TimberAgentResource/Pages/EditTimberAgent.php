<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberAgentResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\Timber\Filament\Resources\TimberAgentResource;

final class EditTimberAgent extends EditRecord
{
    protected static string $resource = TimberAgentResource::class;
}
