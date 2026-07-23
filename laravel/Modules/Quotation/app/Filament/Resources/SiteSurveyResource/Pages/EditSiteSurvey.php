<?php

declare(strict_types=1);

namespace Modules\Quotation\Filament\Resources\SiteSurveyResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\Quotation\Filament\Resources\SiteSurveyResource;
use Modules\Quotation\Filament\Resources\SiteSurveyResource\Concerns\RecordsSiteSurvey;

final class EditSiteSurvey extends EditRecord
{
    use RecordsSiteSurvey;

    protected static string $resource = SiteSurveyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->recordSurveyAction(),
        ];
    }
}
