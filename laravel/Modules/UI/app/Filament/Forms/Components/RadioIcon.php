<?php

declare(strict_types=1);

namespace Modules\UI\Filament\Forms\Components;

use Modules\Xot\Filament\Forms\Components\XotBaseRadio;

final class RadioIcon extends XotBaseRadio
{
    protected function setUp(): void
    {
        parent::setUp();

        /** @var view-string $view */
        $view = 'ui::filament.forms.components.radio-icon';
        $this->view($view);
    }
}
