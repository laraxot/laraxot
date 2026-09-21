<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Tables\Filters;

use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\StateCasts\BooleanStateCast;
use Filament\Tables\Filters\TernaryFilter as FilamentTernaryFilter;

/**
 * Ternary sì/no/tutti con ToggleButtons raggruppati (non Select full-width).
 *
 * Filament TernaryFilter estende SelectFilter: semanticamente ok, UI pesante per 3 stati.
 * Qui si sostituisce il field con ToggleButtons grouped; le query boolean del parent restano.
 *
 * Deselezionare = stato blank («tutti»), come il placeholder del Select precedente.
 */
abstract class XotBaseTernaryFilter extends FilamentTernaryFilter
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->schema(function (): array {
            return [
                ToggleButtons::make('value')
                    ->hiddenLabel()
                    ->grouped()
                    ->options([
                        1 => $this->getTrueLabel() ?? __('filament-forms::components.select.boolean.true'),
                        0 => $this->getFalseLabel() ?? __('filament-forms::components.select.boolean.false'),
                    ])
                    ->colors([
                        1 => 'success',
                        0 => 'danger',
                    ])
                    ->stateCast(app(BooleanStateCast::class, ['isStoredAsInt' => true])),
            ];
        });
    }
}
