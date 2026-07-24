<?php

declare(strict_types=1);

namespace Modules\WhatsApp\Support;

use function Safe\preg_replace;

final class PhoneNumberNormalizer
{
    public function normalize(?string $value, string $defaultCountryPrefix = '+39'): ?string
    {
        $raw = trim((string) $value);
        if ($raw === '') {
            return null;
        }

        $hasPlus = str_starts_with($raw, '+');
        $digits = preg_replace('/\D+/', '', $raw);
        if ($digits === '') {
            return null;
        }

        if ($hasPlus) {
            return '+'.$digits;
        }

        $defaultDigits = preg_replace('/\D+/', '', $defaultCountryPrefix);
        $defaultDigits = $defaultDigits !== '' ? $defaultDigits : '39';

        if (str_starts_with($digits, '00')) {
            return '+'.substr($digits, 2);
        }

        if (str_starts_with($digits, $defaultDigits)) {
            return '+'.$digits;
        }

        return '+'.$defaultDigits.$digits;
    }
}
