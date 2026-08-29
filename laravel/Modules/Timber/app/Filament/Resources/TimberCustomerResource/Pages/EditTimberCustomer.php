<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberCustomerResource\Pages;

use Filament\Actions\DeleteAction;
use Modules\Timber\Filament\Resources\TimberCustomerResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;

final class EditTimberCustomer extends XotBaseEditRecord
{
    protected static string $resource = TimberCustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'delete' => DeleteAction::make(),
        ];
    }
}
