<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Providers\Filament;

use Modules\Xot\Providers\Filament\XotBasePanelProvider;

class AdminPanelProvider extends XotBasePanelProvider
{
    protected string $module = 'TimberBilling';
}
