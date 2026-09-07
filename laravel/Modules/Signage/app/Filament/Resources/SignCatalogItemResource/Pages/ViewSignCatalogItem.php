<?php

declare(strict_types=1);

namespace Modules\Signage\Filament\Resources\SignCatalogItemResource\Pages;

use Filament\Actions\EditAction;
use Modules\Signage\Filament\Resources\SignCatalogItemResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

/**
 * The catalog item with its price broken down line by line — the customer asked
 * to always have the calculation of every single row in sight, not just the
 * total.
 */
final class ViewSignCatalogItem extends XotBaseViewRecord
{
    protected static string $resource = SignCatalogItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'edit' => EditAction::make(),
        ];
    }
}
