<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Pages;

use Modules\Xot\Filament\Traits\NavigationLabelTrait;
<<<<<<< HEAD
=======
use Modules\Xot\Filament\Traits\SuperAdminOnlyTrait;
>>>>>>> laraxot/dev

class EnvPage extends XotBasePage
{
    use NavigationLabelTrait;
<<<<<<< HEAD
=======
    use SuperAdminOnlyTrait;
>>>>>>> laraxot/dev

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-home';

    protected string $view = 'xot::filament.pages.dashboard';
}
