<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberPriceListResource\Pages;

use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;
use Illuminate\Support\Facades\Auth;
use Modules\Timber\Actions\SnapshotTimberPriceListAction;
use Modules\Timber\Filament\Resources\TimberPriceListResource;
use Modules\Timber\Models\TimberPriceList;

final class CreateTimberPriceList extends XotBaseCreateRecord
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
