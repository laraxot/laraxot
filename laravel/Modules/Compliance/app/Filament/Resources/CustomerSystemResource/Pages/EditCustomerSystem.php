<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources\CustomerSystemResource\Pages;

use Filament\Actions\DeleteAction;
use Modules\Compliance\Filament\Resources\CustomerSystemResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;

final class EditCustomerSystem extends XotBaseEditRecord
{
    protected static string $resource = CustomerSystemResource::class;

    protected function getHeaderActions(): array
    {
        return ['delete' => DeleteAction::make()];
    }
}
