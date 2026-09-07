<?php

declare(strict_types=1);

namespace Modules\Signage\Filament\Resources\SignPriceListItemResource\Pages;

use Filament\Actions\EditAction;
use Modules\Signage\Filament\Resources\SignPriceListItemResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewSignPriceListItem extends XotBaseViewRecord
{
    protected static string $resource = SignPriceListItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'edit' => EditAction::make(),
        ];
    }
}
