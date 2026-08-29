<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberAgentResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Timber\Filament\Resources\TimberAgentResource;

final class CreateTimberAgent extends CreateRecord
{
    protected static string $resource = TimberAgentResource::class;
}
