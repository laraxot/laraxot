<?php

declare(strict_types=1);

namespace Modules\Dds\Filament\Pages;

final class DdsAdministrationPage extends DdsPage
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'UI-06 - Amministrazione';

    protected static ?string $title = 'UI-06 - Amministrazione, fatture e presenze';

    protected static ?string $slug = 'administration';

    protected static ?int $navigationSort = 6;

    protected static ?string $ddsFeature = 'dds.administration';

    protected string $view = 'dds::filament.pages.administration';
}
