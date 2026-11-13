<?php

declare(strict_types=1);

namespace Themes\Sixteen\Presentation\Accessibility;

use function Safe\preg_match;
use function Safe\preg_replace;

/**
 * Risolve title e aria-label del link «portale regione» nel header slim (template CMS con graffe).
 */
final class RegionPortalLinkLabels
{
    /**
     * @param  array<string, mixed>  $region  `region` di header.json: `name` (es. "Demo"), `title`, `aria_label`
     * @param  string|null  $enteName  nome dell'ente (DCP-289): se valorizzato vince su `name`
     * @return array{name: string, title: ?string, aria_label: ?string}
     */
    public static function resolve(array $region, ?string $enteName = null): array
    {
        // DCP-289: nessun ripiego su `comune.regione`, il cui default `Regione` e' un segnaposto.
        $name = self::stringOrEmpty($region['name'] ?? null);
        $enteName = self::stringOrEmpty($enteName);
        if ($enteName !== '' && $enteName !== $name) {
            // Le etichette del CMS sono scritte attorno al nome del CMS (D35): se vince l'ente, il nome accessibile lo segue (WCAG 2.5.3).
            foreach (['title', 'aria_label'] as $key) {
                if ($name !== '' && is_string($region[$key] ?? null)) {
                    $region[$key] = str_replace($name, $enteName, $region[$key]);
                }
            }
            $name = $enteName;
        }

        return [
            'name' => $name,
            'title' => self::interpolateLabel($region['title'] ?? null, $name),
            'aria_label' => self::interpolateLabel($region['aria_label'] ?? null, $name),
        ];
    }

    private static function interpolateLabel(mixed $template, string $regionName): ?string
    {
        if (! is_string($template)) {
            return null;
        }

        $template = trim($template);
        if ($template === '') {
            return null;
        }

        if ($regionName === '') {
            if (preg_match('/\{[^}]+\}/', $template) === 1) {
                return null;
            }

            return $template;
        }

        $resolved = trim(preg_replace('/\{[^}]+\}/', $regionName, $template));
        if ($resolved === '' || preg_match('/\{[^}]+\}/', $resolved) === 1) {
            return null;
        }

        return $resolved;
    }

    private static function stringOrEmpty(mixed $value): string
    {
        return is_string($value) ? trim($value) : '';
    }
}
