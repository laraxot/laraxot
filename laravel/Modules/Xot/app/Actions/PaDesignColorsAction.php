<?php

declare(strict_types=1);

namespace Modules\Xot\Actions;

use Filament\Support\Colors\Color;
use Spatie\QueueableAction\QueueableAction;

/**
 * Colori del design system PA (verde istituzionale, blu istituzionale) e
 * palette Filament corrispondente.
 *
 * @example
 * $palette = app(PaDesignColorsAction::class)->execute();
 * $filamentColors = app(PaDesignColorsAction::class)->filamentPalette();
 */
final class PaDesignColorsAction
{
    use QueueableAction;

    public const PRIMARY_HEX = '#007A52';

    public const INSTITUTIONAL_BLUE_HEX = '#0066CC';

    /**
     * @return array{primary: string, institutional_blue: string, danger: string, gray: string, info: string, success: string, warning: string}
     */
    public function execute(): array
    {
        return [
            'primary' => self::PRIMARY_HEX,
            'institutional_blue' => self::INSTITUTIONAL_BLUE_HEX,
            'danger' => 'red',
            'gray' => 'zinc',
            'info' => 'blue',
            'success' => 'green',
            'warning' => 'orange',
        ];
    }

    /**
     * Colori Filament per il panel UI.
     *
     * @return array<string, array<int, string>>
     */
    public function filamentPalette(): array
    {
        return [
            'danger' => Color::Red,
            'gray' => Color::Zinc,
            'info' => Color::hex(self::INSTITUTIONAL_BLUE_HEX),
            'primary' => Color::hex(self::PRIMARY_HEX),
            'success' => Color::Green,
            'warning' => Color::Orange,
        ];
    }
}
