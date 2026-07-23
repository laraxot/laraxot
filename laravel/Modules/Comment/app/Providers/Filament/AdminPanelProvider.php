<?php

declare(strict_types=1);

namespace Modules\Comment\Providers\Filament;

use Filament\Panel;
use Modules\Xot\Providers\Filament\XotBasePanelProvider;

class AdminPanelProvider extends XotBasePanelProvider
{
    protected string $module = 'Comment';

    public function panel(Panel $panel): Panel
    {
        return parent::panel($panel);
    }
}
