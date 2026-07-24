<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Filament\Resources\TimberPriceListResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use Modules\TimberBilling\Actions\SnapshotTimberPriceListAction;
use Modules\TimberBilling\Filament\Resources\TimberPriceListResource;
use Modules\TimberBilling\Models\TimberPriceList;

final class EditTimberPriceList extends EditRecord
{
    protected static string $resource = TimberPriceListResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $record = $this->record;
        if ($record instanceof TimberPriceList) {
            app(SnapshotTimberPriceListAction::class)->execute($record, Auth::id() !== null ? (int) Auth::id() : null, 'Modifica listino');
        }
    }
}
