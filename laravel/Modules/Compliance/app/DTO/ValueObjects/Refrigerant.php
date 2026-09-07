<?php

declare(strict_types=1);

namespace Modules\Compliance\DTO\ValueObjects;

/**
 * Fluorinated gases and their global warming potential.
 *
 * Values are the ones Regulation (EU) 517/2014 lists in Annexes I and II, which
 * are AR4 figures. Later IPCC assessments revised several of them, but the
 * Regulation pins these, and it is the Regulation an inspector checks against —
 * so the table follows the law rather than the latest science.
 */
final class Refrigerant
{
    /**
     * @var array<string, int>
     */
    private const array GWP = [
        'R23' => 14800,
        'R32' => 675,
        'R125' => 3500,
        'R134a' => 1430,
        'R143a' => 4470,
        'R404A' => 3922,
        'R407C' => 1774,
        'R410A' => 2088,
        'R448A' => 1387,
        'R449A' => 1397,
        'R452A' => 2140,
        'R513A' => 631,
        // Natural refrigerants: in scope for the register, effectively not for CO2 equivalence.
        'R290' => 3,
        'R600a' => 3,
        'R717' => 0,
        'R744' => 1,
        'R1234yf' => 4,
    ];

    /**
     * @return list<string>
     */
    public static function all(): array
    {
        return array_keys(self::GWP);
    }

    public static function isKnown(string $code): bool
    {
        return array_key_exists(self::normalise($code), self::GWP);
    }

    /**
     * Null for a refrigerant not in the table: an unknown gas cannot be costed
     * into CO2 equivalent, and guessing a GWP would produce a check schedule
     * that looks authoritative and is not.
     */
    public static function globalWarmingPotential(string $code): ?int
    {
        return self::GWP[self::normalise($code)] ?? null;
    }

    /**
     * Tonnes of CO2 equivalent for a charge, which is what every F-Gas
     * threshold is expressed in.
     */
    public static function co2EquivalentTonnes(string $code, float $chargeKg): ?float
    {
        $gwp = self::globalWarmingPotential($code);

        if ($gwp === null) {
            return null;
        }

        return round(($chargeKg * $gwp) / 1000, 3);
    }

    private static function normalise(string $code): string
    {
        $trimmed = trim($code);

        foreach (array_keys(self::GWP) as $known) {
            if (strcasecmp($trimmed, $known) === 0) {
                return $known;
            }
        }

        return $trimmed;
    }
}
