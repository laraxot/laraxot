<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources\HeatingSystemResource\Pages;

use Filament\Actions;
use Modules\Compliance\Filament\Resources\HeatingSystemResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListHeatingSystems extends XotBaseListRecords
{
    protected static string $resource = HeatingSystemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'create' => Actions\CreateAction::make(),
        ];
    }
}
