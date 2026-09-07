<?php

declare(strict_types=1);

namespace Modules\Quotation\Filament\Resources\SiteSurveyResource\Pages;

use Filament\Actions\EditAction;
use Modules\Quotation\Filament\Resources\SiteSurveyResource;
use Modules\Quotation\Filament\Resources\SiteSurveyResource\Concerns\RecordsSiteSurvey;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewSiteSurvey extends XotBaseViewRecord
{
    use RecordsSiteSurvey;

    protected static string $resource = SiteSurveyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'recordSurvey' => $this->recordSurveyAction(),
            'edit' => EditAction::make(),
        ];
    }
}
