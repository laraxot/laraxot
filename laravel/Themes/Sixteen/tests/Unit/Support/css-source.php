<?php

declare(strict_types=1);

use function Safe\preg_replace;

/**
 * Lettura minima dei fogli CSS SORGENTE del tema (resources/css), per i test di contratto che non possono
 * misurare il browser: un test sul sorgente dice solo che la regola e' scritta, non che il tema compilato la
 * applichi. La prova vera e' la misura nel browser dopo `npm run build` (vedi la story che usa l'helper).
 *
 * Legge regole piatte e dentro un solo livello di @media/@supports; ignora commenti. Niente specificita' calcolata.
 */
if (! function_exists('sixteenCssRules')) {
    /**
     * @return list<array{media: string, selector: string, declarations: array<string, string>}>
     */
    function sixteenCssRules(string $css, string $media = ''): array
    {
        $css = preg_replace('#/\*.*?\*/#s', '', $css);
        $rules = [];
        $length = strlen($css);
        $offset = 0;

        while ($offset < $length) {
            $open = strpos($css, '{', $offset);
            if ($open === false) {
                break;
            }
            $prelude = trim(substr($css, $offset, $open - $offset));
            $depth = 1;
            $cursor = $open + 1;
            while ($cursor < $length && $depth > 0) {
                $depth += match ($css[$cursor]) {
                    '{' => 1,
                    '}' => -1,
                    default => 0,
                };
                $cursor++;
            }
            $body = substr($css, $open + 1, $cursor - $open - 2);

            if (str_starts_with($prelude, '@media') || str_starts_with($prelude, '@supports')) {
                array_push($rules, ...sixteenCssRules($body, trim($media.' '.$prelude)));
            } elseif ($prelude !== '' && ! str_starts_with($prelude, '@')) {
                $declarations = [];
                foreach (explode(';', $body) as $declaration) {
                    if (str_contains($declaration, ':')) {
                        [$property, $value] = explode(':', $declaration, 2);
                        $declarations[strtolower(trim($property))] = trim($value);
                    }
                }
                $rules[] = ['media' => $media, 'selector' => preg_replace('/\s+/', ' ', $prelude), 'declarations' => $declarations];
            }
            $offset = $cursor;
        }

        return $rules;
    }
}

if (! function_exists('sixteenCssDeclaration')) {
    /**
     * Valore dichiarato dall'ULTIMA regola il cui selettore contiene $selector (testo letterale) e il cui @media contiene $media.
     */
    function sixteenCssDeclaration(string $css, string $selector, string $property, string $media = ''): ?string
    {
        $found = null;
        foreach (sixteenCssRules($css) as $rule) {
            if (str_contains($rule['selector'], $selector) && str_contains($rule['media'], $media) && isset($rule['declarations'][$property])) {
                $found = $rule['declarations'][$property];
            }
        }

        return $found;
    }
}

if (! function_exists('sixteenContrastRatio')) {
    /** Rapporto di contrasto WCAG 2.x tra due colori #rrggbb. */
    function sixteenContrastRatio(string $foreground, string $background): float
    {
        $luminance = static function (string $hex): float {
            $hex = ltrim($hex, '#');
            if (strlen($hex) === 3) {
                $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
            }
            $channels = array_map(static function (int|float $value): float {
                $unit = $value / 255;

                return $unit <= 0.03928 ? $unit / 12.92 : (($unit + 0.055) / 1.055) ** 2.4;
            }, array_map('hexdec', str_split($hex, 2)));

            return 0.2126 * $channels[0] + 0.7152 * $channels[1] + 0.0722 * $channels[2];
        };
        $light = max($luminance($foreground), $luminance($background));
        $dark = min($luminance($foreground), $luminance($background));

        return ($light + 0.05) / ($dark + 0.05);
    }
}
