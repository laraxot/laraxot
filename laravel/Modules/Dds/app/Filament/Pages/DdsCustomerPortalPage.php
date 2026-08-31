<?php

declare(strict_types=1);

namespace Modules\Dds\Filament\Pages;

final class DdsCustomerPortalPage extends DdsPage
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationLabel = 'UI-05 - Portale clienti';

    protected static ?string $title = 'UI-05 - Portale clienti e documenti';

    protected static ?string $slug = 'customer-portal';

    protected static ?int $navigationSort = 5;

    protected static ?string $ddsFeature = 'dds.customer_portal';

    protected string $view = 'dds::filament.pages.customer-portal';
}
