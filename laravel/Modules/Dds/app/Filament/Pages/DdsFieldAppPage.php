<?php

declare(strict_types=1);

namespace Modules\Dds\Filament\Pages;

final class DdsFieldAppPage extends DdsPage
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-device-phone-mobile';

    protected static ?string $navigationLabel = 'UI-04 - App tecnico';

    protected static ?string $title = 'UI-04 - App tecnico e operativita sul campo';

    protected static ?string $slug = 'field-app';

    protected static ?int $navigationSort = 4;

    protected static ?string $ddsFeature = 'dds.field_app';

    protected string $view = 'dds::filament.pages.field-app';
}
