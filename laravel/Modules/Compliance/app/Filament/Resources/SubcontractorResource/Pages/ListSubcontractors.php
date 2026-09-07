<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources\SubcontractorResource\Pages;

use Filament\Actions;
use Modules\Compliance\Filament\Resources\SubcontractorResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListSubcontractors extends XotBaseListRecords
{
    protected static string $resource = SubcontractorResource::class;

    protected function getHeaderActions(): array
    {
        return ['create' => Actions\CreateAction::make()];
    }
}
