<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Support;

/**
 * Costruisce un link wa.me con messaggio precompilato (invio WhatsApp "manuale":
 * apre WhatsApp gia' pronto, l'invio lo fa l'operatore). Nessuna API richiesta.
 */
final class WhatsAppMessageLink
{
    public static function build(?string $phone, string $message, string $defaultCountryPrefix = '39'): ?string
    {
        $digits = preg_replace('/\D/', '', (string) $phone) ?? '';
        if ($digits === '') {
            return null;
        }

        // Numero italiano senza prefisso internazionale: anteponi il default.
        if (! str_starts_with($digits, '00') && strlen($digits) <= 10) {
            $digits = $defaultCountryPrefix.$digits;
        }
        $digits = ltrim($digits, '0');

        return 'https://wa.me/'.$digits.'?text='.rawurlencode($message);
    }
}
