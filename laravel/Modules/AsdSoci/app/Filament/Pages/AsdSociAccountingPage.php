<?php

declare(strict_types=1);

namespace Modules\AsdSoci\Filament\Pages;

final class AsdSociAccountingPage extends AsdSociPage
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'Tesoreria e movimenti';

    protected static ?string $title = 'Quote, ricevute, entrate e uscite';

    protected static ?string $slug = 'accounting';

    protected static ?int $navigationSort = 3;

    protected static ?string $asdFeature = 'asd.accounting';

    protected string $view = 'asdsoci::filament.pages.accounting';
}
