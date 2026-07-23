<?php

declare(strict_types=1);

namespace Themes\Sixteen\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\User\Models\User;

/**
 * Evento lanciato quando un utente effettua il logout da CIE
 *
 * Questo evento permette di reagire al logout CIE per cleanup,
 * logging, sincronizzazione con sistemi esterni, etc.
 */
class CieLoggedOut
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public User $user,
        public array $cieAttributes
    ) {}

    /**
     * Ottiene il metodo di autenticazione CIE utilizzato
     */
    public function getAuthMethod(): ?string
    {
        $value = $this->cieAttributes['auth_method'] ?? null;

        return is_string($value) ? $value : null;
    }

    /**
     * Ottiene il codice fiscale dall'autenticazione CIE
     */
    public function getFiscalCode(): ?string
    {
        $value = $this->cieAttributes['fiscal_code'] ?? null;

        return is_string($value) ? $value : null;
    }

    /**
     * Ottiene l'ID CIE dell'utente
     */
    public function getCieId(): ?string
    {
        $value = $this->cieAttributes['cie_id'] ?? null;

        return is_string($value) ? $value : null;
    }

    /**
     * Ottiene attributi specifici per logging sicuro
     */
    public function getLoggingData(): array
    {
        return [
            'user_id' => $this->user->id,
            'auth_method' => $this->getAuthMethod(),
            'fiscal_code' => $this->getFiscalCode(),
            'logout_timestamp' => now()->toISOString(),
        ];
    }
}
