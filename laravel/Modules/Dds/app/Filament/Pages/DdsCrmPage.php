<?php

declare(strict_types=1);

namespace Modules\Dds\Filament\Pages;

final class DdsCrmPage extends DdsPage
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'UI-01 - CRM e import';

    protected static ?string $title = 'UI-01 - CRM, importazione e anagrafiche';

    protected static ?string $slug = 'crm-import';

    protected static ?int $navigationSort = 1;

    protected static ?string $ddsFeature = 'dds.crm';

    protected string $view = 'dds::filament.pages.crm-import';
}
