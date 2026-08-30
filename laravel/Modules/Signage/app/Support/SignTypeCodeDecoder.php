<?php

declare(strict_types=1);

namespace Modules\Signage\Support;

use function Safe\preg_match;

/**
 * Best-effort decoder for the Signage "TYPE OF SIGN" code found in the drawing
 * title block, e.g. MM_BLOCK25_8019_800900:
 *
 *   MM        brand prefix (MaxMara)
 *   BLOCK25   family/material token + integer thickness in mm (BLOCK, 25mm)
 *   8019      RAL colour code
 *   800900    overall dimensions in mm, two groups (800 x 900)
 *
 * The scheme is a convention, not a strict grammar, so every field is optional
 * and the raw code is always preserved.
 */
final class SignTypeCodeDecoder
{
    /**
     * @return array{brand_prefix: ?string, family: ?string, thickness_mm: ?float, color_ral: ?string, dimensions_mm: ?string}
     */
    public static function decode(?string $code): array
    {
        $result = [
            'brand_prefix' => null,
            'family' => null,
            'thickness_mm' => null,
            'color_ral' => null,
            'dimensions_mm' => null,
        ];

        $code = trim((string) $code);
        if ($code === '') {
            return $result;
        }

        $tokens = array_values(array_filter(explode('_', $code), static fn (string $t): bool => $t !== ''));
        if ($tokens === []) {
            return $result;
        }

        $result['brand_prefix'] = $tokens[0];

        foreach (array_slice($tokens, 1) as $token) {
            // Family token: leading letters, optional trailing thickness digits (e.g. BLOCK25, SIDE, PLXT).
            if (preg_match('/^([A-Za-z][A-Za-z]+)(\d+)?$/', $token, $m) === 1) {
                if ($result['family'] === null) {
                    $result['family'] = $m[1] ?? '';
                    if (isset($m[2])) {
                        $result['thickness_mm'] = (float) $m[2];
                    }
                }

                continue;
            }

            // Pure numeric token: 4 digits => RAL colour, 6 digits => two dimension groups.
            if (preg_match('/^\d+$/', $token) === 1) {
                if (strlen($token) === 6 && $result['dimensions_mm'] === null) {
                    $result['dimensions_mm'] = substr($token, 0, 3).'x'.substr($token, 3, 3);
                } elseif ($result['color_ral'] === null) {
                    $result['color_ral'] = $token;
                }
            }
        }

        return $result;
    }
}
