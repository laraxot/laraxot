<?php

declare(strict_types=1);

namespace Modules\Intervention\Support;

use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;
use Symfony\Component\HtmlSanitizer\Visitor\AttributeSanitizer\AttributeSanitizerInterface;

use function Safe\preg_match;

final class DailyNoteStyleAttributeSanitizer implements AttributeSanitizerInterface
{
    public function getSupportedElements(): array
    {
        return ['span'];
    }

    public function getSupportedAttributes(): array
    {
        return ['style'];
    }

    public function sanitizeAttribute(
        string $element,
        string $attribute,
        string $value,
        HtmlSanitizerConfig $config,
    ): ?string {
        $declarations = array_filter(array_map('trim', explode(';', $value)));

        foreach ($declarations as $declaration) {
            [$property, $rawValue] = array_pad(array_map('trim', explode(':', $declaration, 2)), 2, null);

            if ($property !== 'color' || $rawValue === null) {
                continue;
            }

            if (! $this->isAllowedColor($rawValue)) {
                return null;
            }

            return "color: {$rawValue}";
        }

        return null;
    }

    private function isAllowedColor(string $value): bool
    {
        return preg_match('/^(#[0-9a-fA-F]{3,8}|rgba?\([0-9., %]+\)|hsla?\([0-9., %]+\))$/', $value) === 1;
    }
}
