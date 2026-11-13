<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Filament;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;
use Spatie\QueueableAction\QueueableAction;

/**
 * Plain text for Filament grid labels — Htmlable, enum HasLabel, scalar.
 */
class PlainTextFromFilamentValueAction
{
    use QueueableAction;

    /**
<<<<<<< HEAD
     * @param  mixed  $value  Valore Filament eterogeneo (Htmlable|string|scalar|\Stringable|HasLabel|null)
     * @param  string|int|float|bool|\Stringable|null  $fallback  Valore di riserva se $value non è testo
=======
<<<<<<< .merge_file_1vkH2y
     * @param  mixed  $value  Valore Filament eterogeneo (Htmlable|string|scalar|\Stringable|HasLabel|null)
     * @param  string|int|float|bool|\Stringable|null  $fallback  Valore di riserva se $value non è testo
=======
     * <<<<<<< HEAD.
     *
     * @param mixed                                  $value    Valore Filament eterogeneo (Htmlable|string|scalar|\Stringable|HasLabel|null)
     * @param string|int|float|bool|\Stringable|null $fallback valore di riserva se $value non è testo
     *                                                         =======
     *                                                         <<<<<<< HEAD
     * @param mixed                                  $value    Valore Filament eterogeneo (Htmlable|string|scalar|\Stringable|HasLabel|null)
     * @param string|int|float|bool|\Stringable|null $fallback valore di riserva se $value non è testo
     *                                                         =======
     *                                                         <<<<<<< HEAD
     *                                                         <<<<<<< HEAD
     * @param mixed                                  $value    Valore Filament eterogeneo (Htmlable|string|scalar|\Stringable|HasLabel|null)
     * @param string|int|float|bool|\Stringable|null $fallback Valore di riserva se $value non è testo
     *                                                         =======
     * @param mixed                                  $value    Valore Filament eterogeneo (Htmlable|string|scalar|\Stringable|HasLabel|null)
     * @param string|int|float|bool|\Stringable|null $fallback Valore di riserva se $value non è testo
     *                                                         >>>>>>> laraxot/dev
     *                                                         =======
     * @param mixed                                  $value    Valore Filament eterogeneo (Htmlable|string|scalar|\Stringable|HasLabel|null)
     * @param string|int|float|bool|\Stringable|null $fallback Valore di riserva se $value non è testo
     *                                                         >>>>>>> laraxot/dev
     *                                                         >>>>>>> laraxot/dev
     *                                                         >>>>>>> laraxot/dev
>>>>>>> .merge_file_wsjSCJ
>>>>>>> 77b1e2748 (first)
     */
    public function execute(mixed $value, string|int|float|bool|\Stringable|null $fallback = ''): string
    {
        if ($value instanceof Htmlable) {
            return strip_tags($value->toHtml());
        }

        if (is_string($value)) {
            return $value;
        }

        if (is_scalar($value) || $value instanceof \Stringable) {
            return (string) $value;
        }

        if ($value instanceof HasLabel) {
            return $this->execute($value->getLabel(), $fallback);
        }

        if (is_scalar($fallback) || $fallback instanceof \Stringable) {
            return (string) $fallback;
        }

        return '';
    }

    /**
<<<<<<< HEAD
     * @param  mixed  $value  Valore Filament eterogeneo (Htmlable|string|scalar|\Stringable|HasLabel|null)
     * @param  string|int|float|bool|\Stringable|null  $fallback  Valore di riserva se $value non è testo
=======
<<<<<<< .merge_file_1vkH2y
     * @param  mixed  $value  Valore Filament eterogeneo (Htmlable|string|scalar|\Stringable|HasLabel|null)
     * @param  string|int|float|bool|\Stringable|null  $fallback  Valore di riserva se $value non è testo
=======
     * <<<<<<< HEAD.
     *
     * @param mixed                                  $value    Valore Filament eterogeneo (Htmlable|string|scalar|\Stringable|HasLabel|null)
     * @param string|int|float|bool|\Stringable|null $fallback valore di riserva se $value non è testo
     *                                                         =======
     *                                                         <<<<<<< HEAD
     * @param mixed                                  $value    Valore Filament eterogeneo (Htmlable|string|scalar|\Stringable|HasLabel|null)
     * @param string|int|float|bool|\Stringable|null $fallback valore di riserva se $value non è testo
     *                                                         =======
     *                                                         <<<<<<< HEAD
     *                                                         <<<<<<< HEAD
     * @param mixed                                  $value    Valore Filament eterogeneo (Htmlable|string|scalar|\Stringable|HasLabel|null)
     * @param string|int|float|bool|\Stringable|null $fallback Valore di riserva se $value non è testo
     *                                                         =======
     * @param mixed                                  $value    Valore Filament eterogeneo (Htmlable|string|scalar|\Stringable|HasLabel|null)
     * @param string|int|float|bool|\Stringable|null $fallback Valore di riserva se $value non è testo
     *                                                         >>>>>>> laraxot/dev
     *                                                         =======
     * @param mixed                                  $value    Valore Filament eterogeneo (Htmlable|string|scalar|\Stringable|HasLabel|null)
     * @param string|int|float|bool|\Stringable|null $fallback Valore di riserva se $value non è testo
     *                                                         >>>>>>> laraxot/dev
     *                                                         >>>>>>> laraxot/dev
     *                                                         >>>>>>> laraxot/dev
>>>>>>> .merge_file_wsjSCJ
>>>>>>> 77b1e2748 (first)
     */
    public static function cast(mixed $value, string|int|float|bool|\Stringable|null $fallback = ''): string
    {
        return app(self::class)->execute($value, $fallback);
    }
}
