<?php

declare(strict_types=1);

namespace Modules\Xot\Tests\Fixtures\Stubs;

use Modules\Xot\Filament\Widgets\XotBaseWizardWidget;

final class XotAbsWizard3 extends XotBaseWizardWidget
{
<<<<<<< HEAD
    protected string $view = 'xot::filament.widgets.base';
=======
    protected string $view;
>>>>>>> 77b1e2748 (first)

    public function getSteps(): array
    {
        return [];
    }
}
