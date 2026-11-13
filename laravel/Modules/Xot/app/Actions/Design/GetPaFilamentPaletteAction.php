<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Design;

<<<<<<< HEAD
use Modules\Xot\Actions\PaDesignColorsAction;
use Modules\Xot\Enums\PaDesignColorEnum;
=======
use Filament\Support\Colors\Color;
>>>>>>> 77b1e2748 (first)
use Spatie\QueueableAction\QueueableAction;

/**
 * Palette Design Comuni / PA per Filament (FO widget + pannelli admin).
 *
 * @see laravel/Themes/Sixteen/tailwind.config.js (primary verde, italia-blue)
 */
final class GetPaFilamentPaletteAction
{
    use QueueableAction;

    /** Verde PA — azioni primarie, CTA istituzionali */
<<<<<<< HEAD
    public const string PRIMARY_HEX = PaDesignColorEnum::Primary->value;

    /** Blu istituzionale — info, link header */
    public const string INSTITUTIONAL_BLUE_HEX = PaDesignColorEnum::InstitutionalBlue->value;
=======
    public const string PRIMARY_HEX = '#007A52';

    /** Blu istituzionale — info, link header */
    public const string INSTITUTIONAL_BLUE_HEX = '#0066CC';
>>>>>>> 77b1e2748 (first)

    /**
     * @return array<string, array<int, string>|string>
     */
    public function execute(): array
    {
<<<<<<< HEAD
        return app(PaDesignColorsAction::class)->filamentPalette();
=======
        return [
            'danger' => Color::Red,
            'gray' => Color::Zinc,
            'info' => Color::hex(self::INSTITUTIONAL_BLUE_HEX),
            'primary' => Color::hex(self::PRIMARY_HEX),
            'success' => Color::Green,
            'warning' => Color::Orange,
        ];
>>>>>>> 77b1e2748 (first)
    }
}
