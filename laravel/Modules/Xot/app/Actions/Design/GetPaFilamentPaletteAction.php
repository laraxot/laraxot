<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Design;

<<<<<<< HEAD
use Filament\Support\Colors\Color;
=======
use Modules\Xot\Actions\PaDesignColorsAction;
use Modules\Xot\Enums\PaDesignColorEnum;
>>>>>>> laraxot/dev
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
    public const string PRIMARY_HEX = '#007A52';

    /** Blu istituzionale — info, link header */
    public const string INSTITUTIONAL_BLUE_HEX = '#0066CC';
=======
    public const string PRIMARY_HEX = PaDesignColorEnum::Primary->value;

    /** Blu istituzionale — info, link header */
    public const string INSTITUTIONAL_BLUE_HEX = PaDesignColorEnum::InstitutionalBlue->value;
>>>>>>> laraxot/dev

    /**
     * @return array<string, array<int, string>|string>
     */
    public function execute(): array
    {
<<<<<<< HEAD
        return [
            'danger' => Color::Red,
            'gray' => Color::Zinc,
            'info' => Color::hex(self::INSTITUTIONAL_BLUE_HEX),
            'primary' => Color::hex(self::PRIMARY_HEX),
            'success' => Color::Green,
            'warning' => Color::Orange,
        ];
=======
        return app(PaDesignColorsAction::class)->filamentPalette();
>>>>>>> laraxot/dev
    }
}
