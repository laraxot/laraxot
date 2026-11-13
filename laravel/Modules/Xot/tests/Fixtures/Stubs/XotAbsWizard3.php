<?php

declare(strict_types=1);

namespace Modules\Xot\Tests\Fixtures\Stubs;

use Modules\Xot\Filament\Widgets\XotBaseWizardWidget;

final class XotAbsWizard3 extends XotBaseWizardWidget
{
<<<<<<< HEAD
    protected string $view;
=======
    protected string $view = 'xot::filament.widgets.base';
>>>>>>> laraxot/dev

    public function getSteps(): array
    {
        return [];
    }
}
