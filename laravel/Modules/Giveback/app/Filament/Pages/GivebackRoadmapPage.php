<?php

declare(strict_types=1);

namespace Modules\Giveback\Filament\Pages;

final class GivebackRoadmapPage extends GivebackPlatformPage
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-map';

    protected static ?string $navigationLabel = 'UI-08 - Roadmap';

    protected static ?string $title = 'UI-08 - Pricing, timeline e roadmap';

    protected static ?string $slug = 'roadmap';

    protected static ?int $navigationSort = 8;

    protected static ?string $givebackFeature = 'giveback.roadmap';

    protected string $view = 'giveback::filament.pages.roadmap';
}
