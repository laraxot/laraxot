<?php

declare(strict_types=1);

namespace Modules\Customer\Filament\Resources\ResellerResource\Pages;

use Filament\Actions\CreateAction;
use Modules\Customer\Filament\Resources\ResellerResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListResellers extends XotBaseListRecords
{
    protected static string $resource = ResellerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
