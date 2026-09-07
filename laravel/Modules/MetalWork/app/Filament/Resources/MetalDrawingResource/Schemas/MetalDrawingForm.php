<?php

declare(strict_types=1);

namespace Modules\MetalWork\Filament\Resources\MetalDrawingResource\Schemas;

use Filament\Forms\Components\Field;
use Filament\Schemas\Components\Component;
use Modules\MetalWork\Filament\Resources\MetalDrawingResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

/**
 * I disegni entrano dall'import STEP, non si compilano a mano: la risorsa
 * serve a consultarli e a lanciare la preventivazione (canCreate() = false).
 */
final class MetalDrawingForm extends XotBaseResourceForm
{
    protected static string $resource = MetalDrawingResource::class;

    /**
     * @return array<string, Component|Field>
     */
    public function getFormSchema(): array
    {
        return [];
    }
}
