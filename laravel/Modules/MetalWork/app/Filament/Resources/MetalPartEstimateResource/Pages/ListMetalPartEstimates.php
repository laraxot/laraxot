<?php

declare(strict_types=1);

namespace Modules\MetalWork\Filament\Resources\MetalPartEstimateResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\MetalWork\Filament\Resources\MetalPartEstimateResource;

final class ListMetalPartEstimates extends ListRecords
{
    protected static string $resource = MetalPartEstimateResource::class;
}
