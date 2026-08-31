<?php

declare(strict_types=1);

namespace Modules\Thermo\Filament\Pages;

final class ThermoSurveyQuotePage extends ThermoPage
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationLabel = 'UI-04 - Sopralluogo e preventivo';

    protected static ?string $title = 'UI-04 - Sopralluoghi, preventivi e accettazione';

    protected static ?string $slug = 'survey-quote';

    protected static ?int $navigationSort = 4;

    protected static ?string $thermoFeature = 'thermo.survey_quote';

    protected string $view = 'thermo::filament.pages.survey-quote';
}
