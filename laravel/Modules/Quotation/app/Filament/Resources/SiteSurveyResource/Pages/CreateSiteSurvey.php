<?php

declare(strict_types=1);

namespace Modules\Quotation\Filament\Resources\SiteSurveyResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Quotation\Filament\Resources\SiteSurveyResource;

final class CreateSiteSurvey extends CreateRecord
{
    protected static string $resource = SiteSurveyResource::class;
}
