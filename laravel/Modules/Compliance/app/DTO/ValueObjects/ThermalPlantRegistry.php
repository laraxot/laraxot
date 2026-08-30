<?php

declare(strict_types=1);

namespace Modules\Compliance\DTO\ValueObjects;

/**
 * Italy has no single register of thermal plants: each region runs its own, with
 * its own name, its own portal and its own maintenance sticker. An installer
 * working across a regional border deals with two of them, so the register is a
 * property of the plant's location, never of the company.
 *
 * The codes below identify which register a plant belongs to. Filing itself
 * stays manual — each portal needs its own accredited credentials, and none of
 * that is automated here.
 */
final class ThermalPlantRegistry
{
    /**
     * Region code => [register code, register name].
     *
     * @var array<string, array{0: string, 1: string}>
     */
    private const BY_REGION = [
        'lombardia' => ['curit', 'CURIT'],
        'emilia-romagna' => ['criter', 'CRITER'],
        'piemonte' => ['catasto-piemonte', 'Catasto impianti termici Piemonte'],
        'veneto' => ['carit', 'CARIT'],
        'toscana' => ['siert', 'SIERT'],
        'lazio' => ['cirie', 'CIRIE'],
        'liguria' => ['catasto-liguria', 'Catasto impianti termici Liguria'],
        'marche' => ['catasto-marche', 'Catasto impianti termici Marche'],
        'campania' => ['citec', 'CITEC'],
        'puglia' => ['catasto-puglia', 'Catasto impianti termici Puglia'],
    ];

    /**
     * @return list<string>
     */
    public static function regions(): array
    {
        return array_keys(self::BY_REGION);
    }

    /**
     * Null where no register is configured for the region. The plant is still
     * valid — it just has nowhere to be filed from here, and saying so is
     * better than filing it into an invented register.
     */
    public static function forRegion(string $region): ?string
    {
        return self::BY_REGION[self::normalise($region)][0] ?? null;
    }

    public static function nameForRegion(string $region): ?string
    {
        return self::BY_REGION[self::normalise($region)][1] ?? null;
    }

    public static function nameForCode(string $registryCode): ?string
    {
        foreach (self::BY_REGION as [$code, $name]) {
            if (strcasecmp($code, trim($registryCode)) === 0) {
                return $name;
            }
        }

        return null;
    }

    private static function normalise(string $region): string
    {
        return strtolower(trim($region));
    }
}
