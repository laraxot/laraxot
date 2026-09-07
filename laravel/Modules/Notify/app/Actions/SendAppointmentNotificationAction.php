<?php

declare(strict_types=1);

// This file references <nome progetto> models that do not exist in this project

namespace Modules\Notify\Actions;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
// use Modules\<nome progetto>\Models\Appointment;
// use Modules\<nome progetto>\Models\Patient;
use Spatie\QueueableAction\QueueableAction;

class SendAppointmentNotificationAction
{
    use QueueableAction;

    /**
     * Numero massimo di tentativi per l'invio della notifica.
     */
    public int $tries = 3;

    /**
     * Invia una notifica relativa a un appuntamento.
     *
     * @param  Model  $appointment  L'appuntamento a cui si riferisce la notifica
     * @param  string  $type  Il tipo di notifica (confermato, annullato, promemoria, ecc.)
     * @param  array<string, mixed>  $additionalData  Dati aggiuntivi per la notifica
     */
    public function execute(
        Model $appointment,
        string $type,
        array $additionalData = []
    ): bool {
        try {
            // Patient::with('user')->find($appointment->patient_id); — modello non disponibile in questo progetto

            // Since patient models are not available in this project,
            // we return early with logging
            Log::debug('Notification service not fully implemented - missing Patient models', [
                'type' => $type,
<<<<<<< HEAD
<<<<<<< HEAD
                'additional_data' => $additionalData]);
=======
                'additional_data' => $additionalData,
            ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'additional_data' => $additionalData]);
>>>>>>> a988596b (first)

            return false;
        } catch (Exception $e) {
            Log::error('Errore nell\'invio della notifica di appuntamento', [
                'type' => $type,
                'error' => $e->getMessage(),
<<<<<<< HEAD
<<<<<<< HEAD
                'trace' => $e->getTraceAsString()]);
=======
                'trace' => $e->getTraceAsString(),
            ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'trace' => $e->getTraceAsString()]);
>>>>>>> a988596b (first)

            return false;
        }
    }
}
