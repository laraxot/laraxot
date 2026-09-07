<?php

declare(strict_types=1);

namespace Modules\Quotation\Filament\Resources\SiteSurveyResource\Pages;

use Modules\Quotation\Filament\Resources\SiteSurveyResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;

final class CreateSiteSurvey extends XotBaseCreateRecord
{
    protected static string $resource = SiteSurveyResource::class;
}
