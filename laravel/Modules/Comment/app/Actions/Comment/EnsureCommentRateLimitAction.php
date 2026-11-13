<?php

declare(strict_types=1);

namespace Modules\Comment\Actions\Comment;

use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Spatie\QueueableAction\QueueableAction;

/**
 * Freno ai commenti inviati dal front office (CMT-022): nuovo commento, risposta e modifica passano da qui.
 *
 * Il limite e' per utente (o per IP se non c'e' un utente) e conta ogni invio, valido o no: chi prova cento volte un testo
 * rifiutato e' lo stesso che riempirebbe la pagina di commenti. Il messaggio dice quanto aspettare, come il login (G-07).
 */
class EnsureCommentRateLimitAction
{
    use QueueableAction;

    /** Invii consentiti nella finestra. */
    public const int MAX_ATTEMPTS = 5;

    /** Durata della finestra e del blocco, in secondi. */
    public const int DECAY_SECONDS = 60;

    /**
     * @param  string  $statePath  Campo a cui agganciare l'errore (di norma `data.text`).
     *
     * @throws ValidationException quando il limite e' superato
     */
    public function execute(string $statePath = 'data.text'): void
    {
        $key = 'comment-submit:'.(string) (auth()->id() ?? request()->ip());

        if (RateLimiter::tooManyAttempts($key, self::MAX_ATTEMPTS)) {
            throw ValidationException::withMessages([
                $statePath => __('comment::txt.throttle', ['seconds' => RateLimiter::availableIn($key)]),
            ]);
        }

        RateLimiter::hit($key, self::DECAY_SECONDS);
    }
}
