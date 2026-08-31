<?php

declare(strict_types=1);

namespace Modules\Giveback\Filament\Pages;

final class GivebackMerchantOnboardingPage extends GivebackPlatformPage
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rocket-launch';

    protected static ?string $navigationLabel = 'UI-04 - Onboarding';

    protected static ?string $title = 'UI-04 - Onboarding merchant';

    protected static ?string $slug = 'merchant-onboarding';

    protected static ?int $navigationSort = 4;

    protected static ?string $givebackFeature = 'giveback.onboarding';

    protected string $view = 'giveback::filament.pages.merchant-onboarding';
}
