<?php

declare(strict_types=1);

namespace Modules\Giveback\Filament\Pages;

final class GivebackMerchantDashboardPage extends GivebackPlatformPage
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $navigationLabel = 'UI-05 - Merchant';

    protected static ?string $title = 'UI-05 - Merchant dashboard';

    protected static ?string $slug = 'merchant-dashboard';

    protected static ?int $navigationSort = 5;

    protected static ?string $givebackFeature = 'giveback.merchant_dashboard';

    protected string $view = 'giveback::filament.pages.merchant-dashboard';
}
