<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Filament\Resources\TimberAgentResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use Modules\TimberBilling\Filament\Resources\TimberAgentResource;

final class ViewTimberAgent extends ViewRecord
{
    protected static string $resource = TimberAgentResource::class;
}
