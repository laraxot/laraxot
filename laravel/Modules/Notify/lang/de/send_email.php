<?php

declare(strict_types=1);

return [
    'navigation' => [
        'label' => 'Invio Email',
        'group' => [
            'label' => 'Sistema',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => 'Funzionalità per l\'invio di email attraverso il sistema di notifiche'],
        'icon' => 'heroicon-o-envelope',
        'sort' => '49'],
=======
            'description' => 'Funzionalità per l\'invio di email attraverso il sistema di notifiche',
        ],
        'icon' => 'heroicon-o-envelope',
        'sort' => '49',
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => 'Funzionalità per l\'invio di email attraverso il sistema di notifiche'],
        'icon' => 'heroicon-o-envelope',
        'sort' => '49'],
>>>>>>> a988596b (first)
    'fields' => [
        'subject' => [
            'label' => 'Oggetto',
            'placeholder' => 'Inserisci l\'oggetto dell\'email',
            'help' => 'Oggetto che apparirà nell\'intestazione dell\'email',
            'tooltip' => '',
            'helper_text' => '',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => ''],
=======
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => ''],
>>>>>>> a988596b (first)
        'template_id' => [
            'label' => 'Template Email',
            'placeholder' => 'Seleziona il template email da utilizzare',
            'help' => 'Template predefinito per l\'email (opzionale)',
            'tooltip' => '',
            'helper_text' => '',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => ''],
=======
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => ''],
>>>>>>> a988596b (first)
        'to' => [
            'label' => 'Destinatario',
            'placeholder' => 'destinatario@dominio.com',
            'help' => 'Indirizzo email del destinatario',
            'tooltip' => '',
            'helper_text' => '',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => ''],
=======
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => ''],
>>>>>>> a988596b (first)
        'cc' => [
            'label' => 'Copia Conoscenza (CC)',
            'placeholder' => 'cc@dominio.com (opzionale)',
            'help' => 'Indirizzi email in copia conoscenza, separati da virgole',
            'tooltip' => '',
            'helper_text' => '',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => ''],
=======
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => ''],
>>>>>>> a988596b (first)
        'bcc' => [
            'label' => 'Copia Nascosta (BCC)',
            'placeholder' => 'bcc@dominio.com (opzionale)',
            'help' => 'Indirizzi email in copia nascosta, separati da virgole',
            'tooltip' => '',
            'helper_text' => '',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => ''],
=======
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => ''],
>>>>>>> a988596b (first)
        'content' => [
            'label' => 'Contenuto Testo',
            'placeholder' => 'Inserisci il contenuto testuale dell\'email',
            'help' => 'Contenuto testuale dell\'email (versione plain text)',
            'tooltip' => '',
            'helper_text' => '',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => ''],
=======
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => ''],
>>>>>>> a988596b (first)
        'body_html' => [
            'label' => 'Contenuto HTML',
            'placeholder' => '<h1>Titolo</h1><p>Contenuto dell\'email in formato HTML</p>',
            'help' => 'Contenuto HTML dell\'email da inviare (opzionale)',
            'tooltip' => '',
            'helper_text' => '',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => ''],
=======
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => ''],
>>>>>>> a988596b (first)
        'parameters' => [
            'label' => 'Parametri Template',
            'placeholder' => '{\\"nome\\": \\"Mario\\", \\"cognome\\": \\"Rossi\\"}',
            'help' => 'Parametri JSON per personalizzare il template selezionato',
            'tooltip' => '',
            'helper_text' => '',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => ''],
=======
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => ''],
>>>>>>> a988596b (first)
        'attachments' => [
            'label' => 'Allegati',
            'placeholder' => 'Seleziona i file da allegare',
            'help' => 'File da allegare all\'email (opzionale)',
            'tooltip' => '',
            'helper_text' => '',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => ''],
=======
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => ''],
>>>>>>> a988596b (first)
        'priority' => [
            'label' => 'Priorità',
            'placeholder' => 'Seleziona la priorità dell\'email',
            'help' => 'Priorità dell\'email (normale, alta, urgente)',
            'options' => [
                'normal' => 'Normale',
                'high' => 'Alta',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
                'urgent' => 'Urgente'],
            'tooltip' => '',
            'helper_text' => '',
            'description' => '']],
<<<<<<< HEAD
=======
                'urgent' => 'Urgente',
            ],
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
    'actions' => [
        'send' => [
            'label' => 'Invia Email',
            'success' => 'Email inviata con successo al destinatario',
            'error' => 'Errore nell\'invio dell\'email. Verifica la configurazione.',
            'confirmation' => 'Sei sicuro di voler inviare questa email?',
<<<<<<< HEAD
<<<<<<< HEAD
            'tooltip' => 'Invia l\'email al destinatario specificato'],
=======
            'tooltip' => 'Invia l\'email al destinatario specificato',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'tooltip' => 'Invia l\'email al destinatario specificato'],
>>>>>>> a988596b (first)
        'preview' => [
            'label' => 'Anteprima',
            'success' => 'Anteprima dell\'email generata correttamente',
            'error' => 'Errore nella generazione dell\'anteprima',
<<<<<<< HEAD
<<<<<<< HEAD
            'tooltip' => 'Visualizza l\'anteprima dell\'email prima dell\'invio'],
=======
            'tooltip' => 'Visualizza l\'anteprima dell\'email prima dell\'invio',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'tooltip' => 'Visualizza l\'anteprima dell\'email prima dell\'invio'],
>>>>>>> a988596b (first)
        'save_draft' => [
            'label' => 'Salva Bozza',
            'success' => 'Bozza salvata correttamente',
            'error' => 'Errore nel salvataggio della bozza',
<<<<<<< HEAD
<<<<<<< HEAD
            'tooltip' => 'Salva l\'email come bozza per inviarla successivamente'],
=======
            'tooltip' => 'Salva l\'email come bozza per inviarla successivamente',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'tooltip' => 'Salva l\'email come bozza per inviarla successivamente'],
>>>>>>> a988596b (first)
        'schedule' => [
            'label' => 'Programma Invio',
            'success' => 'Email programmata per l\'invio',
            'error' => 'Errore nella programmazione dell\'invio',
<<<<<<< HEAD
<<<<<<< HEAD
            'tooltip' => 'Programma l\'invio dell\'email per una data e ora specifiche']],
=======
            'tooltip' => 'Programma l\'invio dell\'email per una data e ora specifiche',
        ],
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'tooltip' => 'Programma l\'invio dell\'email per una data e ora specifiche']],
>>>>>>> a988596b (first)
    'messages' => [
        'success' => 'Email inviata con successo! Controlla la casella email del destinatario.',
        'error' => 'Si è verificato un errore durante l\'invio dell\'email. Verifica la configurazione SMTP.',
        'draft_saved' => 'Bozza salvata correttamente. Puoi recuperarla dalla sezione Bozze.',
        'scheduled' => 'Email programmata per l\'invio. Riceverai una notifica quando verrà inviata.',
        'preview_generated' => 'Anteprima generata correttamente. Controlla l\'aspetto dell\'email.',
        'invalid_template' => 'Template email non valido o non trovato.',
        'invalid_parameters' => 'Parametri del template non validi. Verifica il formato JSON.',
        'no_recipients' => 'Nessun destinatario specificato. Inserisci almeno un indirizzo email.',
<<<<<<< HEAD
<<<<<<< HEAD
        'smtp_error' => 'Errore di configurazione SMTP. Verifica le impostazioni del server.'],
=======
        'smtp_error' => 'Errore di configurazione SMTP. Verifica le impostazioni del server.',
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'smtp_error' => 'Errore di configurazione SMTP. Verifica le impostazioni del server.'],
>>>>>>> a988596b (first)
    'validation' => [
        'subject_required' => 'Der Betreff ist erforderlich',
        'to_required' => 'Der Empfänger ist erforderlich',
        'to_valid' => 'Il destinatario deve essere un indirizzo email valido',
        'cc_valid' => 'Gli indirizzi in CC devono essere email valide',
        'bcc_valid' => 'Gli indirizzi in BCC devono essere email valide',
        'content_required' => 'Der Inhalt ist erforderlich',
        'template_exists' => 'Il template selezionato non esiste',
        'parameters_json' => 'I parametri devono essere in formato JSON valido',
<<<<<<< HEAD
<<<<<<< HEAD
        'priority_valid' => 'La priorità deve essere una delle opzioni disponibili'],
    'label' => 'Missing Label',
    'plural_label' => 'Missing Plural label'];
=======
        'priority_valid' => 'La priorità deve essere una delle opzioni disponibili',
    ],
    'label' => 'Missing Label',
    'plural_label' => 'Missing Plural label',
];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'priority_valid' => 'La priorità deve essere una delle opzioni disponibili'],
    'label' => 'Missing Label',
    'plural_label' => 'Missing Plural label'];
>>>>>>> a988596b (first)
