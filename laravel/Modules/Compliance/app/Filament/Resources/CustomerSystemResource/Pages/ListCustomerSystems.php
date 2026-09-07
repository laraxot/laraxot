<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources\CustomerSystemResource\Pages;

use Filament\Actions;
use Modules\Compliance\Filament\Resources\CustomerSystemResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListCustomerSystems extends XotBaseListRecords
{
    protected static string $resource = CustomerSystemResource::class;

    protected function getHeaderActions(): array
    {
        return ['create' => Actions\CreateAction::make()];
    }
}
