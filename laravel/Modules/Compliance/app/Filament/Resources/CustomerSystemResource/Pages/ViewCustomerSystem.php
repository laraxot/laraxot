<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources\CustomerSystemResource\Pages;

use Filament\Actions\EditAction;
use Modules\Compliance\Filament\Resources\CustomerSystemResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewCustomerSystem extends XotBaseViewRecord
{
    protected static string $resource = CustomerSystemResource::class;

    protected function getHeaderActions(): array
    {
        return ['edit' => EditAction::make()];
    }
}
