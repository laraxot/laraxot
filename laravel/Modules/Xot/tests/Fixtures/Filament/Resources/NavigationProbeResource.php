<?php

declare(strict_types=1);

namespace Modules\Xot\Tests\Fixtures\Filament\Resources;

use Modules\Xot\Filament\Resources\XotBaseResource;

<<<<<<< HEAD
class NavigationProbeResource extends XotBaseResource
{
    protected static string $module = 'Xot';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string|\UnitEnum|null $navigationGroup = 'Test Group';

    protected static ?int $navigationSort = 1;
=======
/**
 * Probe per il contratto di navigazione: icona, gruppo e ordinamento arrivano dal
 * trait NavigationLabelTrait (traduzioni), non da property statiche.
 */
class NavigationProbeResource extends XotBaseResource
{
    protected static string $module = 'Xot';
>>>>>>> laraxot/dev
}
