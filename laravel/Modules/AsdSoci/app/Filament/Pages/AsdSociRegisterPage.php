<?php

declare(strict_types=1);

namespace Modules\AsdSoci\Filament\Pages;

final class AsdSociRegisterPage extends AsdSociPage
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationLabel = 'Libro soci';

    protected static ?string $title = 'Backoffice libro soci e delibere';

    protected static ?string $slug = 'member-register';

    protected static ?int $navigationSort = 2;

    protected static ?string $asdFeature = 'asd.member_register';

    protected string $view = 'asdsoci::filament.pages.member-register';
}
