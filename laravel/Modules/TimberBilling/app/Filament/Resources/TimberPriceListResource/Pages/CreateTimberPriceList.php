<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Filament\Resources\TimberPriceListResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Modules\TimberBilling\Actions\SnapshotTimberPriceListAction;
use Modules\TimberBilling\Filament\Resources\TimberPriceListResource;
use Modules\TimberBilling\Models\TimberPriceList;

final class CreateTimberPriceList extends CreateRecord
{
    protected static string $resource = TimberPriceListResource::class;

    protected function afterCreate(): void
    {
        $record = $this->record;
        if ($record instanceof TimberPriceList) {
            app(SnapshotTimberPriceListAction::class)->execute($record, Auth::id() !== null ? (int) Auth::id() : null, 'Creazione listino');
        }
    }
}
