<?php

declare(strict_types=1);

namespace Modules\User\Actions\Passport;

use Modules\User\Models\OauthRefreshToken;
use Spatie\QueueableAction\QueueableAction;

/**
 * RevokeRefreshTokenAction: Revoca un refresh token OAuth2.
 */
class RevokeRefreshTokenAction
{
    use QueueableAction;

    public function __construct(
        private readonly OauthRefreshToken $refreshTokenModel,
    ) {
    }

    /**
     * Revoca un refresh token OAuth2.
     *
     * @param OauthRefreshToken|string $token Il token da revocare (istanza o ID)
     *
     * @return bool True se il token è stato revocato con successo
     */
    public function execute(OauthRefreshToken|string $token): bool
    {
        if (is_string($token)) {
            $token = $this->refreshTokenModel->find($token);
        }

        if (! $token instanceof OauthRefreshToken) {
            return false;
        }

<<<<<<< HEAD
<<<<<<< HEAD
        $token->setAttribute('revoked', true);
=======
        $token->revoked = true;
>>>>>>> 60a2c9a9 (.)
=======
        $token->revoked = true;
=======
        $token->setAttribute('revoked', true);
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        $token->save();

        return true;
    }
}
