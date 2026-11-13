<?php

declare(strict_types=1);

return [
    'profile' => [
        'page_title' => [
            'title' => 'Profilo',
        ],
        'information' => [
            'title' => 'Informazioni sul profilo',
            'help' => 'Aggiorna le informazioni del profilo e l\'indirizzo email del tuo account.',
        ],
        'photo' => [
            'label' => 'Foto',
        ],
        'photo_select' => [
            'label' => 'Seleziona una nuova foto',
        ],
        'photo_remove' => [
            'label' => 'Rimuovi foto',
        ],
        'email_unverified' => [
            'message' => 'Il tuo indirizzo email non è verificato.',
        ],
        'email_resend' => [
            'label' => 'Clicca qui per inviare di nuovo l\'email di verifica.',
        ],
        'email_resent' => [
            'message' => 'Un nuovo link di verifica è stato inviato al tuo indirizzo email.',
        ],
    ],
    'password' => [
        'update' => [
            'title' => 'Aggiorna password',
            'help' => 'Assicurati che il tuo account utilizzi una password lunga e casuale per rimanere al sicuro.',
        ],
        'current' => [
            'label' => 'Password attuale',
        ],
        'new' => [
            'label' => 'Nuova password',
        ],
        'confirm' => [
            'label' => 'Conferma password',
        ],
    ],
    'common' => [
        'password' => [
            'label' => 'Password',
            'placeholder' => 'Password',
        ],
        'cancel' => [
            'label' => 'Annulla',
        ],
        'save' => [
            'label' => 'Salva',
        ],
        'saved' => [
            'message' => 'Salvato.',
        ],
        'done' => [
            'message' => 'Fatto.',
        ],
        'confirm' => [
            'label' => 'Conferma',
        ],
        'create' => [
            'label' => 'Crea',
        ],
        'email' => [
            'label' => 'Email',
        ],
        'name' => [
            'label' => 'Nome',
        ],
        'update' => [
            'label' => 'Aggiorna',
        ],
    ],
    'delete_account' => [
        'action' => [
            'label' => 'Elimina account',
        ],
        'summary' => [
            'help' => 'Elimina definitivamente il tuo account.',
        ],
        'warning' => [
            'help' => 'Una volta eliminato il tuo account, tutte le sue risorse e i suoi dati verranno eliminati definitivamente. Prima di eliminare il tuo account, scarica i dati o le informazioni che desideri conservare.',
        ],
        'confirm' => [
            'title' => 'Sei sicuro di voler eliminare il tuo account?',
        ],
        'confirm_full' => [
            'message' => 'Sei sicuro di voler eliminare il tuo account? Una volta eliminato il tuo account, tutte le sue risorse e i suoi dati verranno eliminati definitivamente. Inserisci la tua password per confermare che vuoi eliminare definitivamente il tuo account.',
        ],
        'confirm_password' => [
            'message' => 'Una volta eliminato il tuo account, tutte le sue risorse e i suoi dati verranno eliminati definitivamente. Inserisci la tua password per confermare che vuoi eliminare definitivamente il tuo account.',
        ],
        'legacy_warning' => [
            'help' => 'Dopo l\'eliminazione del tuo account, tutti i dati e le risorse vengono rimossi definitivamente. Inserisci la tua password per confermare l\'eliminazione.',
        ],
    ],
    'browser_sessions' => [
        'list' => [
            'title' => 'Sessioni del browser',
            'help' => 'Gestisci e disconnetti le tue sessioni attive su altri browser e dispositivi.',
        ],
        'description' => [
            'help' => 'Se necessario, puoi disconnetterti da tutte le altre sessioni del browser su tutti i tuoi dispositivi. Alcune delle tue sessioni recenti sono elencate di seguito; tuttavia, questo elenco potrebbe non essere esaustivo. Se ritieni che il tuo account sia stato compromesso, dovresti anche aggiornare la tua password.',
        ],
        'unknown' => [
            'label' => 'Sconosciuto',
        ],
        'this_device' => [
            'label' => 'Questo dispositivo',
        ],
        'last_active' => [
            'label' => 'Ultima attività',
        ],
        'log_out_others' => [
            'label' => 'Disconnetti le altre sessioni del browser',
        ],
        'confirm' => [
            'message' => 'Inserisci la tua password per confermare che vuoi disconnetterti dalle altre sessioni del browser su tutti i tuoi dispositivi.',
        ],
    ],
    'two_factor' => [
        'setup' => [
            'title' => 'Autenticazione a due fattori',
            'help' => 'Aggiungi sicurezza extra al tuo account usando l\'autenticazione a due fattori.',
        ],
        'state_finish' => [
            'title' => 'Completa l\'attivazione dell\'autenticazione a due fattori.',
        ],
        'state_enabled' => [
            'title' => 'Hai attivato l\'autenticazione a due fattori.',
        ],
        'state_disabled' => [
            'title' => 'Non hai attivato l\'autenticazione a due fattori.',
        ],
        'intro' => [
            'help' => 'Quando l\'autenticazione a due fattori è attiva, durante l\'accesso ti verrà richiesto un token sicuro e casuale. Puoi ottenere questo token dall\'applicazione Google Authenticator del tuo telefono.',
        ],
        'scan_finish' => [
            'help' => 'Per completare l\'attivazione dell\'autenticazione a due fattori, scansiona il seguente codice QR con l\'applicazione di autenticazione del tuo telefono oppure inserisci la chiave di configurazione e il codice OTP generato.',
        ],
        'scan_enabled' => [
            'help' => 'L\'autenticazione a due fattori è ora attiva. Scansiona il seguente codice QR con l\'applicazione di autenticazione del tuo telefono oppure inserisci la chiave di configurazione.',
        ],
        'setup_key' => [
            'label' => 'Chiave di configurazione',
        ],
        'code' => [
            'label' => 'Codice',
        ],
        'recovery_codes' => [
            'help' => 'Conserva questi codici di recupero in un gestore di password sicuro. Possono essere usati per recuperare l\'accesso al tuo account se il dispositivo per l\'autenticazione a due fattori va perso.',
        ],
        'enable' => [
            'label' => 'Abilita',
        ],
        'recovery_regenerate' => [
            'label' => 'Rigenera i codici di recupero',
        ],
        'recovery_show' => [
            'label' => 'Mostra i codici di recupero',
        ],
        'disable' => [
            'label' => 'Disabilita',
        ],
    ],
    'team' => [
        'settings' => [
            'title' => 'Impostazioni del team',
        ],
        'create' => [
            'title' => 'Crea team',
            'help' => 'Crea un nuovo team per collaborare con altri ai progetti.',
        ],
        'details' => [
            'title' => 'Dettagli del team',
        ],
        'owner' => [
            'label' => 'Proprietario del team',
        ],
        'name' => [
            'label' => 'Nome del team',
            'help' => 'Il nome del team e le informazioni sul proprietario.',
        ],
        'delete' => [
            'label' => 'Elimina team',
        ],
        'delete_summary' => [
            'help' => 'Elimina definitivamente questo team.',
        ],
        'delete_warning' => [
            'help' => 'Una volta eliminato un team, tutte le sue risorse e i suoi dati verranno eliminati definitivamente. Prima di eliminare questo team, scarica i dati o le informazioni su questo team che desideri conservare.',
        ],
        'delete_confirm' => [
            'message' => 'Sei sicuro di voler eliminare questo team? Una volta eliminato un team, tutte le sue risorse e i suoi dati verranno eliminati definitivamente.',
        ],
    ],
    'members' => [
        'add' => [
            'title' => 'Aggiungi membro del team',
            'help' => 'Aggiungi un nuovo membro al tuo team, in modo che possa collaborare con te.',
        ],
        'add_email' => [
            'help' => 'Inserisci l\'indirizzo email della persona che vuoi aggiungere a questo team.',
        ],
        'role' => [
            'label' => 'Ruolo',
        ],
        'added' => [
            'message' => 'Aggiunto.',
        ],
        'add_action' => [
            'label' => 'Aggiungi',
        ],
        'pending' => [
            'title' => 'Inviti al team in sospeso',
            'help' => 'Queste persone sono state invitate al tuo team e hanno ricevuto un\'email di invito. Possono unirsi al team accettando l\'invito via email.',
        ],
        'list' => [
            'title' => 'Membri del team',
            'help' => 'Tutte le persone che fanno parte di questo team.',
        ],
        'leave' => [
            'label' => 'Abbandona',
        ],
        'remove' => [
            'label' => 'Rimuovi',
        ],
        'manage_role' => [
            'title' => 'Gestisci ruolo',
        ],
        'leave_team' => [
            'title' => 'Abbandona il team',
            'message' => 'Sei sicuro di voler abbandonare questo team?',
        ],
        'remove_member' => [
            'title' => 'Rimuovi membro del team',
            'message' => 'Sei sicuro di voler rimuovere questa persona dal team?',
        ],
    ],
];
