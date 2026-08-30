<?php

declare(strict_types=1);

namespace Modules\Signage\Filament\Resources\SignEstimationRuleResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\Signage\Filament\Resources\SignEstimationRuleResource;

final class ListSignEstimationRules extends ListRecords
{
    protected static string $resource = SignEstimationRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
