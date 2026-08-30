<?php

declare(strict_types=1);

namespace Modules\Signage\Providers\Filament;

use Modules\Xot\Providers\Filament\XotBasePanelProvider;

class AdminPanelProvider extends XotBasePanelProvider
{
    protected string $module = 'Signage';

    protected ?string $panelId = 'signage';

    protected ?string $panelPath = 'signage';
}
