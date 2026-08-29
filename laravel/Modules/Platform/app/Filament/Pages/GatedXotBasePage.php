<?php

declare(strict_types=1);

namespace Modules\Platform\Filament\Pages;

use Modules\Platform\Filament\Concerns\GatesPageNavigation;
use Modules\Xot\Filament\Pages\XotBasePage;

/**
 * XotBasePage che rispetta le preferenze di navigazione runtime (NavigationGate):
 * un toggle nasconde la voce dal menu e blocca l'accesso via URL diretto.
 * Equivalente Laraxot del GatedPage del gestionale sorgente.
 */
abstract class GatedXotBasePage extends XotBasePage
{
    use GatesPageNavigation;
}
