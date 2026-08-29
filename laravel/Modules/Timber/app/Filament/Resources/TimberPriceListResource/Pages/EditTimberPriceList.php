<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberPriceListResource\Pages;

use Filament\Actions\DeleteAction;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;
use Illuminate\Support\Facades\Auth;
use Modules\Timber\Actions\SnapshotTimberPriceListAction;
use Modules\Timber\Filament\Resources\TimberPriceListResource;
use Modules\Timber\Models\TimberPriceList;

final class EditTimberPriceList extends XotBaseEditRecord
{
    protected static string $resource = TimberPriceListResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'delete' => DeleteAction::make(),
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
