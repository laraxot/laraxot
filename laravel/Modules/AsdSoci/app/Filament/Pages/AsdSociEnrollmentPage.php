<?php

declare(strict_types=1);

namespace Modules\AsdSoci\Filament\Pages;

final class AsdSociEnrollmentPage extends AsdSociPage
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-plus';

    protected static ?string $navigationLabel = 'Iscrizione e rinnovo';

    protected static ?string $title = 'Landing iscrizione e rinnovo';

    protected static ?string $slug = 'enrollment';

    protected static ?int $navigationSort = 1;

    protected static ?string $asdFeature = 'asd.enrollment';

    protected string $view = 'asdsoci::filament.pages.enrollment';
}
