<?php

declare(strict_types=1);

namespace Modules\Dds\Filament\Pages;

final class DdsSurveysContractsPage extends DdsPage
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'UI-02 - Sopralluoghi e contratti';

    protected static ?string $title = 'UI-02 - Sopralluoghi, offerte e contratti';

    protected static ?string $slug = 'surveys-contracts';

    protected static ?int $navigationSort = 2;

    protected static ?string $ddsFeature = 'dds.surveys_contracts';

    protected string $view = 'dds::filament.pages.surveys-contracts';
}
