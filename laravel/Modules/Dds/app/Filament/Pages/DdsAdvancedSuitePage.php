<?php

declare(strict_types=1);

namespace Modules\Dds\Filament\Pages;

final class DdsAdvancedSuitePage extends DdsPage
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cpu-chip';

    protected static ?string $navigationLabel = 'UI-07 - Full suite';

    protected static ?string $title = 'UI-07 - Full suite: magazzino, RENTRI, AI e multi-tenant';

    protected static ?string $slug = 'advanced-suite';

    protected static ?int $navigationSort = 7;

    protected static ?string $ddsFeature = 'dds.full_suite';

    protected string $view = 'dds::filament.pages.advanced-suite';
}
