<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Widgets;

/**
 * Widget di test per verificare la registrazione Livewire.
 */
class TestWidget extends XotBaseWidget
{
<<<<<<< HEAD
    protected string $view = 'xot::filament.widgets.base';
=======
    /** @var view-string */
    /** @var view-string */
    protected string $view;
>>>>>>> 77b1e2748 (first)

    protected int|string|array $columnSpan = 'full';

    /**
     * Determina se il widget deve essere visibile.
     */
    public static function canView(): bool
    {
        return true;
    }
}
