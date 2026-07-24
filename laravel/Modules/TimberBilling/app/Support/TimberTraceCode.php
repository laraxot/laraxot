<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Support;

use DateTimeInterface;

/**
 * Costruisce il "codice tracciabile" del semilavorato, che parte dal codice del
 * lotto di ingresso e si arricchisce di un segmento ad ogni tappa
 * (es. LOT-2026-0001·TAG-0703·ESS·CNF-0715-ART123).
 */
final class TimberTraceCode
{
    public const SEPARATOR = '·';

    /**
     * Segmento per una tappa: PREFISSO-mmgg (+ eventuale suffisso, es. codice articolo).
     */
    public static function segment(string $stageTypeCode, DateTimeInterface $date, ?string $suffix = null): string
    {
        $prefix = self::prefixFor($stageTypeCode);
        $segment = $prefix.'-'.$date->format('md');

        $trimmedSuffix = trim((string) $suffix);
        if ($trimmedSuffix !== '') {
            $segment .= '-'.$trimmedSuffix;
        }

        return $segment;
    }

    /**
     * Segmento senza data (es. essiccazione/stagionatura come marcatore di stato).
     */
    public static function marker(string $stageTypeCode): string
    {
        return self::prefixFor($stageTypeCode);
    }

    public static function append(string $baseCode, string $segment): string
    {
        $base = trim($baseCode);
        $seg = trim($segment);

        if ($base === '') {
            return $seg;
        }

        return $seg === '' ? $base : $base.self::SEPARATOR.$seg;
    }

    private static function prefixFor(string $stageTypeCode): string
    {
        $prefixes = config('timberbilling.timber.stage_code_prefixes', []);

        if (is_array($prefixes) && isset($prefixes[$stageTypeCode]) && is_string($prefixes[$stageTypeCode])) {
            return $prefixes[$stageTypeCode];
        }

        return strtoupper(substr($stageTypeCode, 0, 3));
    }
}
