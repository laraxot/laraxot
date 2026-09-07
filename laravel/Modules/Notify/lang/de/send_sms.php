<?php

declare(strict_types=1);

return [
    'resource' => [
        'name' => 'Invio SMS',
<<<<<<< HEAD
<<<<<<< HEAD
        'plural' => 'Invio SMS'],
=======
        'plural' => 'Invio SMS',
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'plural' => 'Invio SMS'],
>>>>>>> a988596b (first)
    'navigation' => [
        'name' => 'Invio SMS',
        'plural' => 'Invio SMS',
        'group' => [
            'name' => 'Notifiche',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            'description' => 'Gestione dell\'invio di notifiche SMS'],
        'label' => 'Invio SMS',
        'icon' => 'heroicon-o-chat-bubble-left-right',
        'sort' => '15'],
<<<<<<< HEAD
=======
            'description' => 'Gestione dell\'invio di notifiche SMS',
        ],
        'label' => 'Invio SMS',
        'icon' => 'heroicon-o-chat-bubble-left-right',
        'sort' => '15',
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
    'fields' => [
        'driver' => [
            'description' => 'Driver SMS',
            'helper_text' => 'Seleziona il provider per l\'invio SMS',
            'placeholder' => 'Seleziona un driver',
            'label' => 'Driver',
<<<<<<< HEAD
<<<<<<< HEAD
            'tooltip' => ''],
=======
            'tooltip' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'tooltip' => ''],
>>>>>>> a988596b (first)
        'message' => [
            'description' => 'Contenuto del messaggio',
            'helper_text' => 'Testo del messaggio da inviare',
            'placeholder' => 'Scrivi il messaggio',
            'label' => 'Messaggio',
<<<<<<< HEAD
<<<<<<< HEAD
            'tooltip' => ''],
=======
            'tooltip' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'tooltip' => ''],
>>>>>>> a988596b (first)
        'phone' => [
            'description' => 'Numero di telefono',
            'helper_text' => 'Numero del destinatario',
            'placeholder' => 'Inserisci il numero',
            'label' => 'Numero telefono',
<<<<<<< HEAD
<<<<<<< HEAD
            'tooltip' => ''],
=======
            'tooltip' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'tooltip' => ''],
>>>>>>> a988596b (first)
        'gateway' => [
            'description' => 'Gateway SMS',
            'helper_text' => 'Gateway utilizzato per l\'invio',
            'placeholder' => 'Gateway SMS',
            'label' => 'Gateway',
<<<<<<< HEAD
<<<<<<< HEAD
            'tooltip' => '']],
=======
            'tooltip' => '',
        ],
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'tooltip' => '']],
>>>>>>> a988596b (first)
    'actions' => [
        'send' => [
            'label' => 'Invia SMS',
            'tooltip' => 'Invia un messaggio SMS al destinatario',
            'success_message' => 'SMS inviato con successo',
<<<<<<< HEAD
<<<<<<< HEAD
            'error_message' => 'Errore nell\'invio dell\'SMS'],
=======
            'error_message' => 'Errore nell\'invio dell\'SMS',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'error_message' => 'Errore nell\'invio dell\'SMS'],
>>>>>>> a988596b (first)
        'test' => [
            'label' => 'Test connessione',
            'tooltip' => 'Verifica la connessione con il provider',
            'success_message' => 'Connessione verificata con successo',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            'error_message' => 'Errore nella verifica della connessione']],
    'messages' => [
        'success' => 'SMS inviato con successo',
        'error' => 'Si è verificato un errore durante l\'invio dell\'SMS',
        'confirmation' => 'Sei sicuro di voler inviare questo SMS?'],
    'label' => 'Missing Label',
    'plural_label' => 'Missing Plural label'];
<<<<<<< HEAD
=======
            'error_message' => 'Errore nella verifica della connessione',
        ],
    ],
    'messages' => [
        'success' => 'SMS inviato con successo',
        'error' => 'Si è verificato un errore durante l\'invio dell\'SMS',
        'confirmation' => 'Sei sicuro di voler inviare questo SMS?',
    ],
    'label' => 'Missing Label',
    'plural_label' => 'Missing Plural label',
];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
