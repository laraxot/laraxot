<?php

declare(strict_types=1);

namespace Modules\Catalog\Providers\Filament;

use Filament\Panel;
use Modules\Xot\Providers\Filament\XotBasePanelProvider;

class AdminPanelProvider extends XotBasePanelProvider
{
    protected string $module = 'Catalog';

    public function panel(Panel $panel): Panel
    {
        return parent::panel($panel);
    }
}
