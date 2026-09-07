<?php

declare(strict_types=1);

namespace Modules\MetalWork\Filament\Resources\MetalPartEstimateResource\Schemas;

use Filament\Forms\Components\Field;
use Filament\Schemas\Components\Component;
use Modules\MetalWork\Filament\Resources\MetalPartEstimateResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

/**
 * I preventivi nascono dal calcolo sul disegno: qui si consultano e si
 * trasformano in offerta, non si scrivono a mano (canCreate() = false).
 */
final class MetalPartEstimateForm extends XotBaseResourceForm
{
    protected static string $resource = MetalPartEstimateResource::class;

    /**
     * @return array<string, Component|Field>
     */
    public function getFormSchema(): array
    {
        return [];
    }
}
