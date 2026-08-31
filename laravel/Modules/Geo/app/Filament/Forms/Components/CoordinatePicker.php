<?php

declare(strict_types=1);

namespace Modules\Geo\Filament\Forms\Components;

use Modules\Geo\Filament\Forms\Components\Traits\HasCoordinatePicker;
use Modules\Xot\Filament\Forms\Components\XotBaseField;

class CoordinatePicker extends XotBaseField
{
    use HasCoordinatePicker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpCoordinatePicker();
        $this->dehydrated();
    }
}
