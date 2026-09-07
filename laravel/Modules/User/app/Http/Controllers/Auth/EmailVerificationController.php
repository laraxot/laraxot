<?php

/**
 * Handles the email verification process for authenticated users.
 *
 * This controller method is responsible for verifying a user's email address
 * when they click on a verification link. It checks that the user is
 * authenticated, that the provided ID and hash match the user's information,
 * and that the email has not already been verified. If the verification is
 * successful, it marks the email as verified and dispatches a Verified event.
 *
<<<<<<< HEAD
<<<<<<< HEAD
 * @param string $id   the ID of the user to be verified
 * @param string $hash the hash of the user's email address
 *
 * @throws AuthorizationException if the verification fails
 *
 * @return RedirectResponse a redirect response to the home page
 */

=======
=======
>>>>>>> 87273113 (.)
 * @param  string $id  the ID of the user to be verified
 * @param  string $hash  the hash of the user's email address
 * @return RedirectResponse a redirect response to the home page
 *
 * @throws AuthorizationException if the verification fails
 */
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
 * @param string $id   the ID of the user to be verified
 * @param string $hash the hash of the user's email address
 *
 * @throws AuthorizationException if the verification fails
 *
 * @return RedirectResponse a redirect response to the home page
 */

>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
declare(strict_types=1);

namespace Modules\User\Http\Controllers\Auth;

<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Auth\MustVerifyEmail;
=======
=======
>>>>>>> 87273113 (.)
use Illuminate\Contracts\Auth\MustVerifyEmail;
use InvalidArgumentException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Auth\MustVerifyEmail;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Modules\User\Http\Controllers\Controller;

class EmailVerificationController extends Controller
{
    public function __invoke(string $id, string $hash): RedirectResponse
    {
        $user = Auth::user();
<<<<<<< HEAD
<<<<<<< HEAD
        if (null === $user) {
            throw new AuthorizationException();
        }

        if (! hash_equals($id, (string) Auth::id())) {
            throw new AuthorizationException();
        }

        if (! hash_equals($hash, sha1($user->getEmailForVerification()))) {
=======
=======
>>>>>>> 87273113 (.)
        if ($user === null) {
            throw new AuthorizationException();
        }

        if (!hash_equals($id, (string) Auth::id())) {
            throw new AuthorizationException();
        }

        if (!hash_equals($hash, sha1($user->getEmailForVerification()))) {
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
        if (null === $user) {
            throw new AuthorizationException();
        }

        if (! hash_equals($id, (string) Auth::id())) {
            throw new AuthorizationException();
        }

        if (! hash_equals($hash, sha1($user->getEmailForVerification()))) {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            throw new AuthorizationException();
        }

        if ($user->hasVerifiedEmail()) {
            return redirect(route('home'));
        }

        $user->markEmailAsVerified();

        // Verificare che l'utente implementi l'interfaccia MustVerifyEmail
<<<<<<< HEAD
<<<<<<< HEAD
        if (! $user instanceof MustVerifyEmail) {
            throw new \InvalidArgumentException('L\'utente deve implementare l\'interfaccia MustVerifyEmail');
=======
        if (!($user instanceof MustVerifyEmail)) {
            throw new InvalidArgumentException('L\'utente deve implementare l\'interfaccia MustVerifyEmail');
>>>>>>> f548be94 (.)
=======
        if (!($user instanceof MustVerifyEmail)) {
            throw new InvalidArgumentException('L\'utente deve implementare l\'interfaccia MustVerifyEmail');
=======
        if (! $user instanceof MustVerifyEmail) {
            throw new \InvalidArgumentException('L\'utente deve implementare l\'interfaccia MustVerifyEmail');
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        }

        event(new Verified($user));

        return redirect(route('home'));
    }
}
