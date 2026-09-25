<?php

declare(strict_types=1);

namespace Themes\Sixteen\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Themes\Sixteen\Models\User;

/**
 * Evento lanciato quando un utente si autentica con successo tramite SPID
 *
 * Questo evento permette di reagire all'autenticazione SPID
 * per logging, analytics, integrazione con sistemi esterni, etc.
 */
class SpidAuthenticated
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
        return $this->spidAttributes['provider'] ?? null;
    }

    /**
     * Ottiene il livello SPID utilizzato
     */
    public function getAuthLevel(): ?int
    {
        return $this->spidAttributes['auth_level'] ?? null;
    }

    /**
     * Ottiene il codice fiscale dall'autenticazione SPID
     */
    public function getFiscalCode(): ?string
    {
        return $this->spidAttributes['fiscal_code'] ?? null;
    }

    /**
     * Verifica se è la prima autenticazione dell'utente
     */
    public function isFirstLogin(): bool
    {
        return $this->user->wasRecentlyCreated;
    }

    /**
     * Ottiene tutti gli attributi SPID ricevuti
     */
    public function getSpidAttributes(): array
    {
        return $this->spidAttributes;
    }

    /**
     * Ottiene attributi specifici per logging sicuro
     */
    public function getLoggingData(): array
    {
        return [
            'user_id' => $this->user->id,
            'provider' => $this->getProvider(),
            'auth_level' => $this->getAuthLevel(),
            'fiscal_code' => $this->getFiscalCode(),
            'is_first_login' => $this->isFirstLogin(),
            'timestamp' => now()->toISOString(),
        ];
    }
}


