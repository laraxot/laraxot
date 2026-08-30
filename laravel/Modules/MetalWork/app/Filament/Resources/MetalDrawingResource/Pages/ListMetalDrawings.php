<?php

declare(strict_types=1);

namespace Modules\MetalWork\Filament\Resources\MetalDrawingResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\MetalWork\Filament\Resources\MetalDrawingResource;

final class ListMetalDrawings extends ListRecords
{
    protected static string $resource = MetalDrawingResource::class;
}
