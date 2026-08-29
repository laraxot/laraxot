<?php

declare(strict_types=1);

namespace Modules\Platform\Filament\Resources;

use Modules\Platform\Filament\Concerns\GatesResourceNavigation;
use Modules\Xot\Filament\Resources\XotBaseResource;

/**
 * XotBaseResource che rispetta le preferenze di navigazione runtime (NavigationGate):
 * un singolo toggle nasconde la voce dal menu e blocca l'accesso via URL diretto.
 * Equivalente Laraxot del GatedResource del gestionale sorgente; le sottoclassi
 * possono comporre ulteriore autorizzazione (es. HasAccountFeatureVisibility) in AND.
 */
abstract class GatedXotBaseResource extends XotBaseResource
{
    use GatesResourceNavigation;
}
