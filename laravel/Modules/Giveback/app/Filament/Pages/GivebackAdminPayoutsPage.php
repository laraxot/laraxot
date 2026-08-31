<?php

declare(strict_types=1);

namespace Modules\Giveback\Filament\Pages;

final class GivebackAdminPayoutsPage extends GivebackPlatformPage
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'UI-06 - Bonifici';

    protected static ?string $title = 'UI-06 - Admin bonifici e controlli';

    protected static ?string $slug = 'admin-payouts';

    protected static ?int $navigationSort = 6;

    protected static ?string $givebackFeature = 'giveback.admin_payouts';

    protected string $view = 'giveback::filament.pages.admin-payouts';
}
