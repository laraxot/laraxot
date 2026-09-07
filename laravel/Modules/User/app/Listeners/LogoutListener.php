<?php

declare(strict_types=1);

/**
 * @see https://github.com/rappasoft/laravel-authentication-log/blob/main/src/Listeners/LogoutListener.php
 */

namespace Modules\User\Listeners;

<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Auth\Events\Logout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\User\Actions\Authentication\GetAuthenticationLogQueryForAuthenticatableAction;
use Modules\User\Actions\GetCurrentDeviceAction;
use Modules\User\Models\BaseUser;
use Modules\User\Models\DeviceUser;
=======
=======
>>>>>>> 87273113 (.)
use Exception;
use Illuminate\Auth\Events\Logout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\User\Actions\GetCurrentDeviceAction;
use Modules\User\Contracts\HasAuthentications;
use Modules\User\Models\AuthenticationLog;
use Modules\User\Models\DeviceUser;
use Modules\User\Traits\HasAuthentications as HasAuthenticationsTrait;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Illuminate\Auth\Events\Logout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\User\Actions\Authentication\GetAuthenticationLogQueryForAuthenticatableAction;
use Modules\User\Actions\GetCurrentDeviceAction;
use Modules\User\Models\BaseUser;
use Modules\User\Models\DeviceUser;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

class LogoutListener
{
    protected Request $request;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Handle the event.
     */
    public function handle(Logout $event): void
    {
        try {
            // Verifica se l'utente esiste prima di procedere
<<<<<<< HEAD
<<<<<<< HEAD
            if (! $event->user) {
                Log::warning('Tentativo di logout per un utente non autenticato');

=======
            if (!$event->user) {
                Log::warning('Tentativo di logout per un utente non autenticato');
>>>>>>> f548be94 (.)
=======
            if (!$event->user) {
                Log::warning('Tentativo di logout per un utente non autenticato');
=======
            if (! $event->user) {
                Log::warning('Tentativo di logout per un utente non autenticato');

>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
                return;
            }

            $device = app(GetCurrentDeviceAction::class)->execute();

            // Aggiorna il pivot solo se abbiamo sia l'utente che il device
            if ($device) {
                try {
                    $pivot = DeviceUser::firstOrCreate([
                        'user_id' => $event->user->getAuthIdentifier(),
                        'device_id' => $device->id,
                    ]);
                    $pivot->update(['logout_at' => now()]);
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
                    Log::error('Errore durante l\'aggiornamento del pivot device-user', [
                        'error' => $e->getMessage(),
                        'user_id' => $event->user->getAuthIdentifier(),
                        'device_id' => $device->id,
                    ]);
                }
            }

            // Gestione delle autenticazioni
<<<<<<< HEAD
<<<<<<< HEAD
            if ($event->user instanceof BaseUser) {
=======
            if ($event->user instanceof HasAuthentications) {
>>>>>>> f548be94 (.)
=======
            if ($event->user instanceof HasAuthentications) {
=======
            if ($event->user instanceof BaseUser) {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
                try {
                    $event
                        ->user
                        ->authentications()
                        ->create([
                            'type' => 'logout',
                            'ip_address' => request()->ip(),
                            'user_agent' => request()->userAgent(),
                        ]);
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
                    Log::error('Errore durante la creazione del log di autenticazione', [
                        'error' => $e->getMessage(),
                        'user_id' => $event->user->getAuthIdentifier(),
                    ]);
                }
            }

            // Log dell'evento
<<<<<<< HEAD
<<<<<<< HEAD
            Log::debug('Logout effettuato', [
=======
            Log::info('Logout effettuato', [
>>>>>>> f548be94 (.)
=======
            Log::info('Logout effettuato', [
=======
            Log::debug('Logout effettuato', [
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
                'user_id' => $event->user->getAuthIdentifier(),
                'device_id' => $device->id,
                'timestamp' => now(),
            ]);
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
            Log::error('Errore durante il logout', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $event->user->getAuthIdentifier(),
            ]);
        }
    }

    /**
     * Rimuove i remember tokens.
     */
    public function forgetRememberTokens(Logout $event): void
    {
<<<<<<< HEAD
<<<<<<< HEAD
        if ($event->user instanceof BaseUser) {
            try {
                app(GetAuthenticationLogQueryForAuthenticatableAction::class)->execute($event->user)
=======
=======
>>>>>>> 87273113 (.)
        if ($event->user && $event->user instanceof HasAuthentications) {
            try {
                $event
                    ->user
                    ->authentications()
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
        if ($event->user instanceof BaseUser) {
            try {
                app(GetAuthenticationLogQueryForAuthenticatableAction::class)->execute($event->user)
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
                    ->whereNotNull('remember_token')
                    ->update([
                        'remember_token' => null,
                    ]);
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
                Log::error('Errore durante la rimozione dei remember tokens', [
                    'error' => $e->getMessage(),
                    'user_id' => $event->user->getAuthIdentifier(),
                ]);
            }
        }
    }
}
