<?php

declare(strict_types=1);

namespace Modules\Giveback\Filament\Pages;

final class GivebackLotteryCompliancePage extends GivebackPlatformPage
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-scale';

    protected static ?string $navigationLabel = 'UI-07 - Lotteria';

    protected static ?string $title = 'UI-07 - Lotteria, compliance e note legali';

    protected static ?string $slug = 'lottery-compliance';

    protected static ?int $navigationSort = 7;

    protected static ?string $givebackFeature = 'giveback.lottery_compliance';

    protected string $view = 'giveback::filament.pages.lottery-compliance';
}
