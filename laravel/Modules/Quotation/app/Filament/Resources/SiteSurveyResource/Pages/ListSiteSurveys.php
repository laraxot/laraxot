<?php

declare(strict_types=1);

namespace Modules\Quotation\Filament\Resources\SiteSurveyResource\Pages;

use Filament\Actions\CreateAction;
use Modules\Quotation\Filament\Resources\SiteSurveyResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListSiteSurveys extends XotBaseListRecords
{
    protected static string $resource = SiteSurveyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'create' => CreateAction::make(),
        ];
    }
}
