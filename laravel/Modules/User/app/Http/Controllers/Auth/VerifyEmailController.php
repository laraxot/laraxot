<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers\Auth;

<<<<<<< HEAD
<<<<<<< HEAD
=======
use InvalidArgumentException;
use Illuminate\Contracts\Auth\MustVerifyEmail;
>>>>>>> f548be94 (.)
=======
use InvalidArgumentException;
use Illuminate\Contracts\Auth\MustVerifyEmail;
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use App\Http\Controllers\Controller;
use Filament\Facades\Filament;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Contracts\Auth\MustVerifyEmail;
=======
>>>>>>> f548be94 (.)
=======
=======
use Illuminate\Contracts\Auth\MustVerifyEmail;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        $user = Auth::user();
<<<<<<< HEAD
<<<<<<< HEAD
        if (null === $user) {
=======
        if ($user === null) {
>>>>>>> f548be94 (.)
=======
        if ($user === null) {
=======
        if (null === $user) {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            return redirect()->route('filament.user.auth.login');
        }

        // Ottieni il valore hash in modo sicuro
        $routeHash = $request->route('hash');
<<<<<<< HEAD
<<<<<<< HEAD
        if (null === $routeHash) {
            throw new \InvalidArgumentException('Hash di verifica mancante');
=======
        if ($routeHash === null) {
            throw new InvalidArgumentException('Hash di verifica mancante');
>>>>>>> f548be94 (.)
=======
        if ($routeHash === null) {
            throw new InvalidArgumentException('Hash di verifica mancante');
=======
        if (null === $routeHash) {
            throw new \InvalidArgumentException('Hash di verifica mancante');
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        }

        $stringRouteHash = is_string($routeHash) ? $routeHash : '';

        // Utilizziamo getEmailForVerification() solo se disponibile
        $userEmail = method_exists($user, 'getEmailForVerification')
            ? $user->getEmailForVerification()
            : ($user->email ?? '');

<<<<<<< HEAD
<<<<<<< HEAD
        if (! hash_equals(sha1($userEmail), $stringRouteHash)) {
=======
        if (!hash_equals(sha1($userEmail), $stringRouteHash)) {
>>>>>>> f548be94 (.)
=======
        if (!hash_equals(sha1($userEmail), $stringRouteHash)) {
=======
        if (! hash_equals(sha1($userEmail), $stringRouteHash)) {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            throw new AuthorizationException();
        }

        // Verifichiamo l'email solo se il metodo esiste
        if (method_exists($user, 'hasVerifiedEmail') && $user->hasVerifiedEmail()) {
            return redirect()->intended(Filament::getUrl());
        }

        // Contrassegna l'email come verificata solo se il metodo esiste
        if (method_exists($user, 'markEmailAsVerified')) {
            $user->markEmailAsVerified();
        }

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

<<<<<<< HEAD
<<<<<<< HEAD
        return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
=======
        return redirect()->intended(route('dashboard', absolute: false) . '?verified=1');
>>>>>>> f548be94 (.)
=======
        return redirect()->intended(route('dashboard', absolute: false) . '?verified=1');
=======
        return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }
}
