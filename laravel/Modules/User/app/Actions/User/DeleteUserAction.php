<?php

declare(strict_types=1);

namespace Modules\User\Actions\User;

<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Hashing\Hasher;
=======
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
>>>>>>> f548be94 (.)
=======
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
=======
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Hashing\Hasher;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Modules\User\Models\User;
use Spatie\QueueableAction\QueueableAction;

class DeleteUserAction
{
    use QueueableAction;

<<<<<<< HEAD
<<<<<<< HEAD
    public function __construct(
        private readonly Hasher $hasher,
        private readonly Guard $authGuard,
    ) {
    }

    /**
     * Elimina l'utente dopo aver verificato la password.
     *
     * @param User   $user            L'utente da eliminare
=======
=======
>>>>>>> 87273113 (.)
    /**
     * Elimina l'utente dopo aver verificato la password.
     *
     * @param User $user L'utente da eliminare
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    public function __construct(
        private readonly Hasher $hasher,
        private readonly Guard $authGuard,
    ) {
    }

    /**
     * Elimina l'utente dopo aver verificato la password.
     *
     * @param User   $user            L'utente da eliminare
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     * @param string $confirmPassword La password di conferma
     *
     * @return array{success: bool, message: string} Risultato dell'operazione
     */
    public function execute(User $user, string $confirmPassword): array
    {
<<<<<<< HEAD
<<<<<<< HEAD
        if (! $this->hasher->check($confirmPassword, $user->password)) {
=======
        if (!Hash::check($confirmPassword, $user->password)) {
>>>>>>> f548be94 (.)
=======
        if (!Hash::check($confirmPassword, $user->password)) {
=======
        if (! $this->hasher->check($confirmPassword, $user->password)) {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            return [
                'success' => false,
                'message' => 'La password inserita non è corretta',
            ];
        }

        try {
<<<<<<< HEAD
<<<<<<< HEAD
            $this->authGuard->logout();
=======
            Auth::logout();
>>>>>>> f548be94 (.)
=======
            Auth::logout();
=======
            $this->authGuard->logout();
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            $user->delete();

            return [
                'success' => true,
                'message' => 'Account eliminato con successo',
            ];
<<<<<<< HEAD
<<<<<<< HEAD
        } catch (\Exception $e) {
=======
        } catch (Exception $e) {
>>>>>>> f548be94 (.)
=======
        } catch (Exception $e) {
=======
        } catch (\Exception $e) {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            return [
                'success' => false,
                'message' => 'Si è verificato un errore durante l\'eliminazione dell\'account',
            ];
        }
    }
}
