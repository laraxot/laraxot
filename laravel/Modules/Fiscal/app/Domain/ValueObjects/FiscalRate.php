<?php

declare(strict_types=1);

namespace Modules\Fiscal\Domain\ValueObjects;

/**
 * Normalises a VAT rate percentage to a stable string key used to group
 * taxable/VAT amounts by rate in the fiscal summary and VAT registers
 * (e.g. 22.00 -> "22", 4.50 -> "4.5", 0.00 -> "0").
 */
final class FiscalRate
{
    public static function key(float $ratePercent): string
    {
        return rtrim(rtrim(number_format($ratePercent, 2, '.', ''), '0'), '.');
    }
}
