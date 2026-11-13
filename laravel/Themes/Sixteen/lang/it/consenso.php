<?php

declare(strict_types=1);

/**
 * Consenso al trattamento dei dati personali — step 1 del flusso di segnalazione.
 * Design Comuni `segnalazione-01-privacy.html`: il consenso e' lo step 1 in tutti e
 * sette i flussi di servizio, senza eccezioni.
 */
return [
    'title' => 'Informativa e consenso',
    'intro' => 'Per inviare una segnalazione trattiamo i dati che ci invii. Puoi continuare solo se acconsenti. I dati sono trattati per rispondere alla segnalazione e per il tempo necessario a ottemperare agli obblighi di legge.',
    'points' => [
        'Dati trattati: nome, cognome, recapito elettronico e posizione del disservizio.',
        'Finalità: rispondere alla segnalazione e inviare aggiornamenti sullo stato.',
        'Base giuridica: il tuo consenso. Puoi revocarlo in qualsiasi momento.',
        'Destinatari: personale dell’ufficio che gestisce la segnalazione. Non cediamo i dati a terzi.',
        'Conservazione: per la durata necessaria agli obblighi di legge.',
    ],
    'link' => '/it/privacy',
    'link_label' => "Leggi l'informativa completa",
    'opens_new_window' => 'si apre in una nuova finestra',
    'label' => 'Ho letto l’informativa privacy e acconsento al trattamento dei miei dati personali per la gestione di questa segnalazione.',
    'error' => 'Per inviare la segnalazione devi acconsentire al trattamento dei dati.',
    'action' => 'Continua',
];
