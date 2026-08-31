<?php

declare(strict_types=1);

namespace Modules\Giveback\Filament\Pages;

final class GivebackValueFlowPage extends GivebackPlatformPage
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-sparkles';

    protected static ?string $navigationLabel = 'UI-02 - Valore';

    protected static ?string $title = 'UI-02 - Meccanismo donazione e valore';

    protected static ?string $slug = 'value-flow';

    protected static ?int $navigationSort = 2;

    protected static ?string $givebackFeature = 'giveback.value_flow';

    protected string $view = 'giveback::filament.pages.value-flow';
}
