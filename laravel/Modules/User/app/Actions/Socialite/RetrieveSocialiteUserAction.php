<?php

/**
 * @see https://github.com/DutchCodingCompany/filament-socialite
 */

declare(strict_types=1);

namespace Modules\User\Actions\Socialite;

<<<<<<< HEAD
<<<<<<< HEAD
use InvalidArgumentException;
use Laravel\Socialite\Contracts\User as SocialiteUserContract;
use Modules\User\Models\SocialiteUser;
use ReflectionClass;
use ReflectionException;
use RuntimeException;
=======
=======
>>>>>>> 87273113 (.)
// use DutchCodingCompany\FilamentSocialite\FilamentSocialite;
use InvalidArgumentException;
use RuntimeException;
use ReflectionClass;
use ReflectionException;
=======
>>>>>>> 2024e2e7 (.)
use Laravel\Socialite\Contracts\User as SocialiteUserContract;
use Modules\User\Models\SocialiteUser;
>>>>>>> f548be94 (.)
use Spatie\QueueableAction\QueueableAction;

class RetrieveSocialiteUserAction
{
    use QueueableAction;

    /**
     * Execute the action.
     */
<<<<<<< HEAD
<<<<<<< HEAD
    public function execute(string $provider, SocialiteUserContract $user): ?SocialiteUser
=======
=======
>>>>>>> 87273113 (.)
    public function execute(string $provider, SocialiteUserContract $user): null|SocialiteUser
>>>>>>> f548be94 (.)
    {
        if (empty($provider)) {
            throw new InvalidArgumentException('Il provider non può essere vuoto');
        }

        $providerId = $user->getId();
<<<<<<< HEAD
        if (! is_string($providerId) && ! is_int($providerId)) {
=======
        if (!is_string($providerId) && !is_int($providerId)) {
>>>>>>> f548be94 (.)
            throw new RuntimeException('L\'ID del provider deve essere una stringa o un intero');
=======
    public function execute(string $provider, SocialiteUserContract $user): ?SocialiteUser
    {
        if (empty($provider)) {
            throw new \InvalidArgumentException('Il provider non può essere vuoto');
        }

        $providerId = $user->getId();
        if (! is_string($providerId) && ! is_int($providerId)) {
            throw new \RuntimeException('L\'ID del provider deve essere una stringa o un intero');
>>>>>>> 2024e2e7 (.)
        }

        $res = SocialiteUser::query()
            ->with(['user'])
            ->where('provider', $provider)
            ->where('provider_id', $providerId)
            ->first();

<<<<<<< HEAD
<<<<<<< HEAD
        if (null === $res) {
            return null;
        }

        $res->update([
            'token' => $this->extractToken($user),
        ]);

        return $res;
    }

    /**
     * Estrae in modo type-safe il token dall'utente Socialite, provando in ordine
     * getToken(), token() e la proprietà `token`, con fallback su un valore
     * placeholder se nessuna di queste sorgenti restituisce una stringa valida.
     */
    private function extractToken(SocialiteUserContract $user): string
    {
        $token = '';

=======
=======
>>>>>>> 87273113 (.)
        if ($res === null) {
=======
        if (null === $res) {
>>>>>>> 2024e2e7 (.)
            return null;
        }

        // Accesso sicuro alla proprietà token in modo type-safe
        $token = '';

        // Utilizzo ReflectionClass per accedere in modo sicuro alle proprietà/metodi
>>>>>>> f548be94 (.)
        try {
<<<<<<< HEAD
            $reflection = new ReflectionClass($user);
=======
            $reflection = new \ReflectionClass($user);
>>>>>>> 2024e2e7 (.)

            // Prova prima i metodi standard
            if ($reflection->hasMethod('getToken')) {
                $method = $reflection->getMethod('getToken');
                $method->setAccessible(true);
                $tokenValue = $method->invoke($user);
                if (is_string($tokenValue)) {
                    $token = $tokenValue;
                }
            } elseif ($reflection->hasMethod('token')) {
                $method = $reflection->getMethod('token');
                $method->setAccessible(true);
                $tokenValue = $method->invoke($user);
                if (is_string($tokenValue)) {
                    $token = $tokenValue;
                }
            } elseif ($reflection->hasProperty('token')) { // Prova poi ad accedere alla proprietà
                $property = $reflection->getProperty('token');
                $property->setAccessible(true);
                $tokenValue = $property->getValue($user);
                if (is_string($tokenValue)) {
                    $token = $tokenValue;
                }
            } elseif (isset($user->token) && is_string($user->token)) { // Fallback su accesso diretto con var_export
                $token = $user->token;
            }
<<<<<<< HEAD
        } catch (ReflectionException $e) {
=======
        } catch (\ReflectionException $e) {
>>>>>>> 2024e2e7 (.)
            // Fallback silenzioso
        }

        if (empty($token)) {
            // Se non riusciamo a ottenere un token valido, utilizziamo un valore predefinito
<<<<<<< HEAD
<<<<<<< HEAD
            $token = 'no_token_'.time();
        }

        return $token;
=======
=======
>>>>>>> 87273113 (.)
            $token = 'no_token_' . time();
=======
            $token = 'no_token_'.time();
>>>>>>> 2024e2e7 (.)
        }

        $res->update([
            'token' => $token,
        ]);

        return $res;
>>>>>>> f548be94 (.)
    }
}
