<?php

declare(strict_types=1);

namespace Modules\AsdSoci\Filament\Pages;

final class AsdSociMemberAreaPage extends AsdSociPage
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-identification';

    protected static ?string $navigationLabel = 'Area socio';

    protected static ?string $title = 'Area socio e rinnovo da mobile';

    protected static ?string $slug = 'member-area';

    protected static ?int $navigationSort = 5;

    protected static ?string $asdFeature = 'asd.member_area';

    protected string $view = 'asdsoci::filament.pages.member-area';
}
