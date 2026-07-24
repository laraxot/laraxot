<?php

declare(strict_types=1);

namespace Modules\Intervention\Providers\Filament;

use Filament\Panel;
use Modules\Xot\Providers\Filament\XotBasePanelProvider;

class AdminPanelProvider extends XotBasePanelProvider
{
    protected string $module = 'Intervention';

    public function panel(Panel $panel): Panel
    {
        return parent::panel($panel);
    }
}
