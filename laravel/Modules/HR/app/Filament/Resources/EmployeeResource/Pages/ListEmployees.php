<?php

declare(strict_types=1);

namespace Modules\HR\Filament\Resources\EmployeeResource\Pages;

use Filament\Actions;
use Modules\HR\Filament\Resources\EmployeeResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListEmployees extends XotBaseListRecords
{
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'create' => Actions\CreateAction::make(),
        ];
    }
}
