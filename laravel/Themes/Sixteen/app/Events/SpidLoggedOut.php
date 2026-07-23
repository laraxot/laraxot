<?php

declare(strict_types=1);

namespace Themes\Sixteen\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\User\Models\User;

/**
 * Evento lanciato quando un utente effettua il logout da SPID
 *
 * Questo evento permette di reagire al logout SPID per cleanup,
 * logging, sincronizzazione con sistemi esterni, etc.
 */
class SpidLoggedOut
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public User $user,
        public array $spidAttributes
    ) {}

    /**
     * Ottiene il provider SPID utilizzato
     */
    public function getProvider(): ?string
    {
        $value = $this->spidAttributes['provider'] ?? null;

        return is_string($value) ? $value : null;
    }

    /**
     * Ottiene il codice fiscale dall'autenticazione SPID
     */
    public function getFiscalCode(): ?string
    {
        $value = $this->spidAttributes['fiscal_code'] ?? null;

        return is_string($value) ? $value : null;
    }

    /**
     * Ottiene attributi specifici per logging sicuro
     */
    public function getLoggingData(): array
    {
        return [
            'user_id' => $this->user->id,
            'provider' => $this->getProvider(),
            'fiscal_code' => $this->getFiscalCode(),
            'logout_timestamp' => now()->toISOString(),
        ];
    }
}
