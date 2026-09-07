<?php

declare(strict_types=1);

return [
    'navigation' => [
        'group' => 'Sistema',
        'label' => 'Temi Notifiche',
        'icon' => 'notify-theme-animated',
        'sort' => '50',
<<<<<<< HEAD
<<<<<<< HEAD
        'description' => 'Gestione dei temi per le notifiche'],
=======
        'description' => 'Gestione dei temi per le notifiche',
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'description' => 'Gestione dei temi per le notifiche'],
>>>>>>> a988596b (first)
    'fields' => [
        'name' => [
            'label' => 'Nome',
            'tooltip' => 'Nome del tema',
            'placeholder' => 'es: Tema Aziendale',
            'help' => 'Inserisci un nome descrittivo per il tema',
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
        'description' => [
            'label' => 'Descrizione',
            'tooltip' => 'Descrizione del tema',
            'placeholder' => 'es: Tema standard per le comunicazioni aziendali',
            'help' => 'Breve descrizione dello scopo del tema',
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
        'colors' => [
            'label' => 'Colori',
            'tooltip' => 'Schema colori del tema',
            'help' => 'Definisci i colori principali del tema',
            'options' => [
                'primary' => [
                    'label' => 'Primario',
                    'tooltip' => 'Colore principale del tema',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
                    'placeholder' => 'es: #4A90E2'],
                'secondary' => [
                    'label' => 'Secondario',
                    'tooltip' => 'Colore secondario del tema',
                    'placeholder' => 'es: #5C6AC4'],
                'accent' => [
                    'label' => 'Accento',
                    'tooltip' => 'Colore di accento per elementi in evidenza',
                    'placeholder' => 'es: #F5A623'],
                'success' => [
                    'label' => 'Successo',
                    'tooltip' => 'Colore per messaggi di successo',
                    'placeholder' => 'es: #7CBD5C'],
                'warning' => [
                    'label' => 'Attenzione',
                    'tooltip' => 'Colore per messaggi di avviso',
                    'placeholder' => 'es: #F5A623'],
                'error' => [
                    'label' => 'Errore',
                    'tooltip' => 'Colore per messaggi di errore',
                    'placeholder' => 'es: #D0021B']],
            'helper_text' => '',
            'description' => ''],
<<<<<<< HEAD
=======
                    'placeholder' => 'es: #4A90E2',
                ],
                'secondary' => [
                    'label' => 'Secondario',
                    'tooltip' => 'Colore secondario del tema',
                    'placeholder' => 'es: #5C6AC4',
                ],
                'accent' => [
                    'label' => 'Accento',
                    'tooltip' => 'Colore di accento per elementi in evidenza',
                    'placeholder' => 'es: #F5A623',
                ],
                'success' => [
                    'label' => 'Successo',
                    'tooltip' => 'Colore per messaggi di successo',
                    'placeholder' => 'es: #7CBD5C',
                ],
                'warning' => [
                    'label' => 'Attenzione',
                    'tooltip' => 'Colore per messaggi di avviso',
                    'placeholder' => 'es: #F5A623',
                ],
                'error' => [
                    'label' => 'Errore',
                    'tooltip' => 'Colore per messaggi di errore',
                    'placeholder' => 'es: #D0021B',
                ],
            ],
            'helper_text' => '',
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
        'typography' => [
            'label' => 'Tipografia',
            'tooltip' => 'Impostazioni tipografiche',
            'help' => 'Configura i font e le dimensioni del testo',
            'options' => [
                'font_family' => [
                    'label' => 'Font principale',
                    'tooltip' => 'Font utilizzato per il testo principale',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
                    'placeholder' => 'es: Arial, sans-serif'],
                'heading_font' => [
                    'label' => 'Font titoli',
                    'tooltip' => 'Font utilizzato per i titoli',
                    'placeholder' => 'es: Helvetica, Arial, sans-serif'],
<<<<<<< HEAD
=======
                    'placeholder' => 'es: Arial, sans-serif',
                ],
                'heading_font' => [
                    'label' => 'Font titoli',
                    'tooltip' => 'Font utilizzato per i titoli',
                    'placeholder' => 'es: Helvetica, Arial, sans-serif',
                ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
                'font_sizes' => [
                    'label' => 'Dimensioni font',
                    'tooltip' => 'Dimensioni dei caratteri',
                    'options' => [
                        'small' => [
                            'label' => 'Piccolo',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
                            'tooltip' => 'Testo piccolo (es: note)'],
                        'normal' => [
                            'label' => 'Normale',
                            'tooltip' => 'Testo normale'],
                        'large' => [
                            'label' => 'Grande',
                            'tooltip' => 'Testo grande (es: titoli)']]]],
            'helper_text' => '',
            'description' => ''],
<<<<<<< HEAD
=======
                            'tooltip' => 'Testo piccolo (es: note)',
                        ],
                        'normal' => [
                            'label' => 'Normale',
                            'tooltip' => 'Testo normale',
                        ],
                        'large' => [
                            'label' => 'Grande',
                            'tooltip' => 'Testo grande (es: titoli)',
                        ],
                    ],
                ],
            ],
            'helper_text' => '',
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
        'layout' => [
            'label' => 'Layout',
            'tooltip' => 'Impostazioni del layout',
            'help' => 'Configura la struttura del template',
            'options' => [
                'header' => [
                    'label' => 'Intestazione',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
                    'tooltip' => 'Stile dell\'intestazione'],
                'footer' => [
                    'label' => 'Piè di pagina',
                    'tooltip' => 'Stile del piè di pagina'],
                'spacing' => [
                    'label' => 'Spaziatura',
                    'tooltip' => 'Spaziatura tra gli elementi']],
            'helper_text' => '',
            'description' => ''],
<<<<<<< HEAD
=======
                    'tooltip' => 'Stile dell\'intestazione',
                ],
                'footer' => [
                    'label' => 'Piè di pagina',
                    'tooltip' => 'Stile del piè di pagina',
                ],
                'spacing' => [
                    'label' => 'Spaziatura',
                    'tooltip' => 'Spaziatura tra gli elementi',
                ],
            ],
            'helper_text' => '',
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
        'assets' => [
            'label' => 'Risorse',
            'tooltip' => 'Risorse del tema',
            'help' => 'Gestisci le risorse associate al tema',
            'options' => [
                'logo' => [
                    'label' => 'Logo',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
                    'tooltip' => 'Logo da utilizzare nelle notifiche'],
                'background' => [
                    'label' => 'Sfondo',
                    'tooltip' => 'Immagine di sfondo'],
                'icons' => [
                    'label' => 'Icone',
                    'tooltip' => 'Set di icone del tema']],
            'helper_text' => '',
            'description' => ''],
<<<<<<< HEAD
=======
                    'tooltip' => 'Logo da utilizzare nelle notifiche',
                ],
                'background' => [
                    'label' => 'Sfondo',
                    'tooltip' => 'Immagine di sfondo',
                ],
                'icons' => [
                    'label' => 'Icone',
                    'tooltip' => 'Set di icone del tema',
                ],
            ],
            'helper_text' => '',
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
        'is_default' => [
            'label' => 'Predefinito',
            'tooltip' => 'Imposta come tema predefinito',
            'help' => 'Il tema predefinito verrà utilizzato per tutte le notifiche senza tema specifico',
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
        'is_active' => [
            'label' => 'Attivo',
            'tooltip' => 'Stato di attivazione del tema',
            'help' => 'Solo i temi attivi possono essere utilizzati',
            'helper_text' => '',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => '']],
=======
            'description' => '',
        ],
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => '']],
>>>>>>> a988596b (first)
    'actions' => [
        'preview' => [
            'label' => 'Anteprima',
            'tooltip' => 'Visualizza anteprima del tema',
            'icon' => 'heroicon-o-eye',
<<<<<<< HEAD
<<<<<<< HEAD
            'color' => 'primary'],
=======
            'color' => 'primary',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'color' => 'primary'],
>>>>>>> a988596b (first)
        'duplicate' => [
            'label' => 'Duplica',
            'tooltip' => 'Crea una copia del tema',
            'icon' => 'heroicon-o-document-duplicate',
            'color' => 'info',
            'confirmation' => [
                'title' => 'Conferma duplicazione',
                'message' => 'Vuoi creare una copia di questo tema?',
                'confirm' => 'Sì, duplica',
<<<<<<< HEAD
<<<<<<< HEAD
                'cancel' => 'No, annulla']],
=======
                'cancel' => 'No, annulla',
            ],
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'cancel' => 'No, annulla']],
>>>>>>> a988596b (first)
        'export' => [
            'label' => 'Esporta',
            'tooltip' => 'Esporta configurazione del tema',
            'icon' => 'heroicon-o-arrow-down-tray',
<<<<<<< HEAD
<<<<<<< HEAD
            'color' => 'success'],
=======
            'color' => 'success',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'color' => 'success'],
>>>>>>> a988596b (first)
        'import' => [
            'label' => 'Importa',
            'tooltip' => 'Importa configurazione del tema',
            'icon' => 'heroicon-o-arrow-up-tray',
<<<<<<< HEAD
<<<<<<< HEAD
            'color' => 'warning'],
=======
            'color' => 'warning',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'color' => 'warning'],
>>>>>>> a988596b (first)
        'set_default' => [
            'label' => 'Imposta predefinito',
            'tooltip' => 'Imposta questo tema come predefinito',
            'icon' => 'heroicon-o-star',
            'color' => 'primary',
            'confirmation' => [
                'title' => 'Conferma impostazione predefinito',
                'message' => 'Vuoi impostare questo tema come predefinito?',
                'confirm' => 'Sì, imposta',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
                'cancel' => 'No, annulla']]],
    'messages' => [
        'preview_title' => [
            'title' => 'Anteprima Tema',
            'message' => 'Questa è un\'anteprima di come apparirà il tema'],
        'duplicated' => [
            'title' => 'Tema Duplicato',
            'message' => 'Il tema è stato duplicato con successo'],
        'export_success' => [
            'title' => 'Esportazione Completata',
            'message' => 'La configurazione del tema è stata esportata con successo'],
        'import_success' => [
            'title' => 'Importazione Completata',
            'message' => 'La configurazione del tema è stata importata con successo'],
        'set_default_success' => [
            'title' => 'Tema Predefinito',
            'message' => 'Il tema è stato impostato come predefinito']],
    'filters' => [
        'status' => [
            'label' => 'Stato',
            'tooltip' => 'Filtra per stato di attivazione'],
        'type' => [
            'label' => 'Tipo',
            'tooltip' => 'Filtra per tipo di tema']],
    'label' => 'Missing Label',
    'plural_label' => 'Missing Plural label'];
<<<<<<< HEAD
=======
                'cancel' => 'No, annulla',
            ],
        ],
    ],
    'messages' => [
        'preview_title' => [
            'title' => 'Anteprima Tema',
            'message' => 'Questa è un\'anteprima di come apparirà il tema',
        ],
        'duplicated' => [
            'title' => 'Tema Duplicato',
            'message' => 'Il tema è stato duplicato con successo',
        ],
        'export_success' => [
            'title' => 'Esportazione Completata',
            'message' => 'La configurazione del tema è stata esportata con successo',
        ],
        'import_success' => [
            'title' => 'Importazione Completata',
            'message' => 'La configurazione del tema è stata importata con successo',
        ],
        'set_default_success' => [
            'title' => 'Tema Predefinito',
            'message' => 'Il tema è stato impostato come predefinito',
        ],
    ],
    'filters' => [
        'status' => [
            'label' => 'Stato',
            'tooltip' => 'Filtra per stato di attivazione',
        ],
        'type' => [
            'label' => 'Tipo',
            'tooltip' => 'Filtra per tipo di tema',
        ],
    ],
    'label' => 'Missing Label',
    'plural_label' => 'Missing Plural label',
];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
