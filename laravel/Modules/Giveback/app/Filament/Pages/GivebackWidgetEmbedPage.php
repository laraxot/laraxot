<?php

declare(strict_types=1);

namespace Modules\Giveback\Filament\Pages;

final class GivebackWidgetEmbedPage extends GivebackPlatformPage
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-code-bracket-square';

    protected static ?string $navigationLabel = 'UI-03 - Widget';

    protected static ?string $title = 'UI-03 - Widget embed e installazione';

    protected static ?string $slug = 'widget-embed';

    protected static ?int $navigationSort = 3;

    protected static ?string $givebackFeature = 'giveback.widget';

    protected string $view = 'giveback::filament.pages.widget-embed';
}
