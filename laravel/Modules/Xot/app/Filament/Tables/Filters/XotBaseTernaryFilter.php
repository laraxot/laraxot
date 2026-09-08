<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Tables\Filters;

use Filament\Tables\Filters\TernaryFilter as FilamentTernaryFilter;

/**
 * Base class for TernaryFilter.
 *
 * Following Laraxot architectural pattern: never extend Filament classes directly.
 * This class wraps Filament's TernaryFilter to provide a XotBase layer.
 */
abstract class XotBaseTernaryFilter extends FilamentTernaryFilter
{
}
