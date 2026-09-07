<?php

<<<<<<< HEAD
<<<<<<< HEAD
declare(strict_types=1);

return [
    'navigation' => ['label' => 'Tenant', 'plural_label' => 'Tenant', 'group' => 'Gestione Utenti', 'icon' => 'heroicon-o-building-office', 'sort' => 30],
    'label' => 'Tenant',
    'plural_label' => 'Tenant',
    'table' => [
        'heading' => ['label' => 'Tenant', 'tooltip' => 'Elenco dei tenant', 'helper_text' => 'Visualizza tutti i tenant', 'description' => 'Titolo della tabella tenant'],
    ],
    'fields' => [
        'first_name' => ['label' => 'Nome', 'tooltip' => 'Nome del tenant', 'placeholder' => 'Inserisci il nome', 'helper_text' => 'Nome del tenant o dell\'organizzazione', 'description' => 'Nome di battesimo del tenant'],
        'last_name' => ['label' => 'Cognome', 'tooltip' => 'Cognome del tenant', 'placeholder' => 'Inserisci il cognome', 'helper_text' => 'Cognome o ragione sociale', 'description' => 'Cognome di famiglia del tenant'],
        'secondary_color' => ['label' => 'Colore Secondario', 'tooltip' => 'Colore secondario del tema', 'placeholder' => 'Seleziona colore secondario', 'helper_text' => 'Colore utilizzato come tono secondario nell\'interfaccia', 'description' => 'Colore secondario del tema'],
        'slug' => ['label' => 'Slug', 'tooltip' => 'Identificatore URL-friendly', 'placeholder' => 'inserisci-slug-univoco', 'helper_text' => 'Identificatore univoco utilizzato negli URL', 'description' => 'Slug del tenant'],
        'name' => ['label' => 'Nome Tenant', 'tooltip' => 'Nome identificativo del tenant', 'placeholder' => 'Inserisci nome del tenant', 'helper_text' => 'Nome completo o ragione sociale del tenant', 'description' => 'Nome del tenant'],
        'id' => ['label' => 'ID', 'tooltip' => 'Identificatore univoco', 'placeholder' => 'ID univoco', 'helper_text' => 'Chiave primaria del tenant nel database', 'description' => 'Identificatore univoco'],
        'message' => ['label' => 'Messaggio', 'tooltip' => 'Messaggio informativo', 'placeholder' => 'Inserisci un messaggio', 'helper_text' => 'Messaggio di comunicazione per il tenant', 'description' => 'Messaggio per il tenant'],
        'resetFilters' => ['label' => 'Azzera Filtri', 'tooltip' => 'Rimuove tutti i filtri applicati', 'placeholder' => 'Clicca per azzerare', 'helper_text' => 'Azione per rimuovere tutti i filtri attivi', 'description' => 'Reimposta i filtri'],
        'applyFilters' => ['label' => 'Applica Filtri', 'tooltip' => 'Applica i filtri selezionati', 'placeholder' => 'Clicca per applicare', 'helper_text' => 'Azione per applicare i filtri configurati', 'description' => 'Applica i filtri'],
        'recordId' => ['label' => 'ID Record', 'tooltip' => 'Identificatore del record', 'placeholder' => 'ID del record', 'helper_text' => 'Identificatore univoco del record', 'description' => 'ID del record'],
        'primary_color' => ['label' => 'Colore Primario', 'tooltip' => 'Colore principale del tema', 'placeholder' => 'Seleziona colore primario', 'helper_text' => 'Colore principale utilizzato nell\'interfaccia', 'description' => 'Colore primario del tema'],
        'domain' => ['label' => 'domain', 'placeholder' => 'domain', 'helper_text' => 'domain', 'description' => 'domain'],
        'email_address' => ['label' => 'email_address', 'placeholder' => 'email_address', 'helper_text' => 'email_address', 'description' => 'email_address'],
        'phone' => ['label' => 'phone', 'placeholder' => 'phone', 'helper_text' => 'phone', 'description' => 'phone'],
        'mobile' => ['label' => 'mobile', 'placeholder' => 'mobile', 'helper_text' => 'mobile', 'description' => 'mobile'],
        'address' => ['label' => 'address', 'placeholder' => 'address', 'helper_text' => 'address', 'description' => 'address'],
        'created_at' => ['label' => 'created_at'],
        'updated_at' => ['label' => 'updated_at'],
    ],
    'actions' => [
        'import' => [
            'label' => 'Importa',
            'tooltip' => 'Importa dati',
            'helper_text' => 'Importa dati da file esterno',
            'description' => 'Azione per importare',
            'fields' => [
                'import_file' => ['label' => 'File Import', 'tooltip' => 'Seleziona un file', 'placeholder' => 'Seleziona un file XLS o CSV da caricare', 'helper_text' => 'Seleziona un file XLS o CSV da caricare', 'description' => 'File contenente i dati'],
            ],
        ],
        'export' => [
            'label' => 'Esporta',
            'tooltip' => 'Esporta dati',
            'helper_text' => 'Esporta i dati in formato CSV/Excel',
            'description' => 'Azione per esportare',
            'filename_prefix' => ['label' => 'Prefisso Nome File', 'tooltip' => 'Prefisso per il nome', 'placeholder' => 'Inserisci il prefisso', 'helper_text' => 'Prefisso per il nome del file', 'description' => 'Prefisso del file'],
            'columns' => [
                'name' => ['label' => 'Nome Colonna', 'tooltip' => 'Nome della colonna', 'helper_text' => 'Nome della colonna', 'description' => 'Nome della colonna'],
                'parent_name' => ['label' => 'Nome Padre', 'tooltip' => 'Nome del livello superiore', 'helper_text' => 'Nome del parent', 'description' => 'Nome del parent'],
            ],
        ],
        'change_password' => ['label' => 'Cambia Password', 'tooltip' => 'Cambia la password', 'helper_text' => 'Modifica la password del tenant', 'description' => 'Azione per cambiare password'],
        'create' => ['label' => 'Crea', 'tooltip' => 'Crea nuovo elemento', 'helper_text' => 'Crea un nuovo tenant', 'description' => 'Azione per creare', 'icon' => 'heroicon-o-plus'],
        'edit' => ['label' => 'Modifica', 'tooltip' => 'Modifica elemento', 'helper_text' => 'Modifica il tenant', 'description' => 'Azione per modificare', 'icon' => 'heroicon-o-pencil'],
        'delete' => ['label' => 'Elimina', 'tooltip' => 'Elimina elemento', 'helper_text' => 'Elimina il tenant', 'description' => 'Azione per eliminare', 'icon' => 'heroicon-o-trash'],
        'view' => ['label' => 'Visualizza', 'tooltip' => 'Visualizza dettagli', 'helper_text' => 'Visualizza i dettagli del tenant', 'description' => 'Azione per visualizzare', 'icon' => 'heroicon-o-eye'],
        'save' => ['label' => 'Salva', 'tooltip' => 'Salva modifiche', 'helper_text' => 'Salva le modifiche', 'description' => 'Azione per salvare', 'icon' => 'heroicon-o-check'],
        'cancel' => ['label' => 'Annulla', 'tooltip' => 'Annulla operazione', 'helper_text' => 'Annulla e torna indietro', 'description' => 'Azione per annullare', 'icon' => 'heroicon-o-x-mark'],
        'createAnother' => ['label' => 'createAnother', 'icon' => 'createAnother', 'tooltip' => 'createAnother'],
        'detach' => ['label' => 'detach', 'icon' => 'detach', 'tooltip' => 'detach'],
    ],
    'messages' => ['created' => 'Tenant creato con successo', 'updated' => 'Tenant aggiornato con successo', 'deleted' => 'Tenant eliminato con successo'],
    'sections' => [
        'empty' => ['label' => '', 'heading' => ''],
    ],
=======
=======
>>>>>>> 87273113 (.)
return [
    'navigation' => [
        'name' => 'Tenant',
        'plural' => 'Tenants',
        'group' => [
            'name' => 'Gestione Utenti',
            'description' => 'Gestione dei tenant e delle loro configurazioni',
        ],
        'label' => 'tenant',
        'sort' => 30,
        'icon' => 'ui-user-user-tenant',
    ],
    'table' => [
        'heading' => 'Tenant',
    ],
    'fields' => [
        'first_name' => [
            'label' => 'Nome',
            'placeholder' => 'Inserisci il nome',
            'tooltip' => 'Nome del tenant',
            'description' => 'Nome di battesimo del tenant',
            'helper_text' => '',
        ],
        'last_name' => [
            'label' => 'Cognome',
            'placeholder' => 'Inserisci il cognome',
            'tooltip' => 'Cognome del tenant',
            'description' => 'Cognome di famiglia del tenant',
            'helper_text' => '',
        ],
        'secondary_color' => [
            'label' => 'Colore Secondario',
            'placeholder' => 'Seleziona colore secondario',
            'tooltip' => 'Colore secondario del tema',
            'description' => 'Colore utilizzato come tono secondario nell\'interfaccia',
            'helper_text' => '',
        ],
        'slug' => [
            'label' => 'Slug',
            'placeholder' => 'inserisci-slug-univoco',
            'tooltip' => 'Identificatore URL-friendly',
            'description' => 'Identificatore univoco utilizzato negli URL',
            'helper_text' => '',
        ],
        'name' => [
            'label' => 'Nome Tenant',
            'placeholder' => 'Inserisci nome del tenant',
            'tooltip' => 'Nome identificativo del tenant',
            'description' => 'Nome completo o ragione sociale del tenant',
            'helper_text' => '',
        ],
        'id' => [
            'label' => 'ID',
            'placeholder' => 'ID univoco',
            'tooltip' => 'Identificatore univoco',
            'description' => 'Chiave primaria del tenant nel database',
            'helper_text' => '',
        ],
        'message' => [
            'label' => 'Messaggio',
            'placeholder' => 'Inserisci un messaggio',
            'tooltip' => 'Messaggio informativo',
            'description' => 'Messaggio di comunicazione per il tenant',
            'helper_text' => '',
        ],
        'resetFilters' => [
            'label' => 'Azzera Filtri',
            'placeholder' => 'Clicca per azzerare',
            'tooltip' => 'Rimuove tutti i filtri applicati',
            'description' => 'Azione per rimuovere tutti i filtri attivi',
            'helper_text' => '',
        ],
        'applyFilters' => [
            'label' => 'Applica Filtri',
            'placeholder' => 'Clicca per applicare',
            'tooltip' => 'Applica i filtri selezionati',
            'description' => 'Azione per applicare i filtri configurati',
            'helper_text' => '',
        ],
        'recordId' => [
            'description' => 'recordId',
            'helper_text' => 'recordId',
            'placeholder' => 'recordId',
            'label' => 'recordId',
        ],
    ],
    'actions' => [
        'import' => [
            'fields' => [
                'import_file' => 'Seleziona un file XLS o CSV da caricare',
            ],
        ],
        'export' => [
            'filename_prefix' => 'Aree al',
            'columns' => [
                'name' => 'Nome area',
                'parent_name' => 'Nome area livello superiore',
            ],
        ],
        'change_password' => 'Cambio password',
        'openColumnManager' => [
            'icon' => 'openColumnManager',
            'label' => 'openColumnManager',
        ],
        'reorderRecords' => [
            'icon' => 'reorderRecords',
            'label' => 'reorderRecords',
        ],
        'cancel' => [
            'icon' => 'ui-cancel',
            'label' => 'cancel',
        ],
        'attachAnother' => [
            'icon' => 'attachAnother',
            'label' => 'attachAnother',
        ],
        'submit' => [
            'icon' => 'submit',
            'label' => 'submit',
        ],
        'detach' => [
            'icon' => 'detach',
            'label' => 'detach',
        ],
        'edit' => [
            'label' => 'edit',
            'icon' => 'edit',
        ],
        'attach' => [
            'label' => 'attach',
            'icon' => 'attach',
        ],
        'create' => [
            'label' => 'create',
            'icon' => 'ui-create',
        ],
        'applyFilters' => [
            'label' => 'applyFilters',
            'icon' => 'applyFilters',
        ],
        'openFilters' => [
            'label' => 'openFilters',
            'icon' => 'openFilters',
        ],
        'resetFilters' => [
            'label' => 'resetFilters',
            'icon' => 'resetFilters',
        ],
        'applyTableColumnManager' => [
            'label' => 'applyTableColumnManager',
            'icon' => 'applyTableColumnManager',
        ],
        'layout' => [
            'icon' => 'layout',
            'label' => 'layout',
        ],
        'delete' => [
            'icon' => 'delete',
            'label' => 'delete',
        ],
        'logout' => [
            'icon' => 'logout',
            'label' => 'logout',
        ],
        'profile' => [
            'icon' => 'profile',
            'label' => 'profile',
        ],
        'view' => [
            'icon' => 'view',
        ],
    ],
    'label' => 'tenant',
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
declare(strict_types=1);

return [
    'navigation' => ['label' => 'Tenant', 'plural_label' => 'Tenant', 'group' => 'Gestione Utenti', 'icon' => 'heroicon-o-building-office', 'sort' => 30],
    'label' => 'Tenant',
    'plural_label' => 'Tenant',
    'table' => [
        'heading' => ['label' => 'Tenant', 'tooltip' => 'Elenco dei tenant', 'helper_text' => 'Visualizza tutti i tenant', 'description' => 'Titolo della tabella tenant'],
    ],
    'fields' => [
        'first_name' => ['label' => 'Nome', 'tooltip' => 'Nome del tenant', 'placeholder' => 'Inserisci il nome', 'helper_text' => 'Nome del tenant o dell\'organizzazione', 'description' => 'Nome di battesimo del tenant'],
        'last_name' => ['label' => 'Cognome', 'tooltip' => 'Cognome del tenant', 'placeholder' => 'Inserisci il cognome', 'helper_text' => 'Cognome o ragione sociale', 'description' => 'Cognome di famiglia del tenant'],
        'secondary_color' => ['label' => 'Colore Secondario', 'tooltip' => 'Colore secondario del tema', 'placeholder' => 'Seleziona colore secondario', 'helper_text' => 'Colore utilizzato come tono secondario nell\'interfaccia', 'description' => 'Colore secondario del tema'],
        'slug' => ['label' => 'Slug', 'tooltip' => 'Identificatore URL-friendly', 'placeholder' => 'inserisci-slug-univoco', 'helper_text' => 'Identificatore univoco utilizzato negli URL', 'description' => 'Slug del tenant'],
        'name' => ['label' => 'Nome Tenant', 'tooltip' => 'Nome identificativo del tenant', 'placeholder' => 'Inserisci nome del tenant', 'helper_text' => 'Nome completo o ragione sociale del tenant', 'description' => 'Nome del tenant'],
        'id' => ['label' => 'ID', 'tooltip' => 'Identificatore univoco', 'placeholder' => 'ID univoco', 'helper_text' => 'Chiave primaria del tenant nel database', 'description' => 'Identificatore univoco'],
        'message' => ['label' => 'Messaggio', 'tooltip' => 'Messaggio informativo', 'placeholder' => 'Inserisci un messaggio', 'helper_text' => 'Messaggio di comunicazione per il tenant', 'description' => 'Messaggio per il tenant'],
        'resetFilters' => ['label' => 'Azzera Filtri', 'tooltip' => 'Rimuove tutti i filtri applicati', 'placeholder' => 'Clicca per azzerare', 'helper_text' => 'Azione per rimuovere tutti i filtri attivi', 'description' => 'Reimposta i filtri'],
        'applyFilters' => ['label' => 'Applica Filtri', 'tooltip' => 'Applica i filtri selezionati', 'placeholder' => 'Clicca per applicare', 'helper_text' => 'Azione per applicare i filtri configurati', 'description' => 'Applica i filtri'],
        'recordId' => ['label' => 'ID Record', 'tooltip' => 'Identificatore del record', 'placeholder' => 'ID del record', 'helper_text' => 'Identificatore univoco del record', 'description' => 'ID del record'],
        'primary_color' => ['label' => 'Colore Primario', 'tooltip' => 'Colore principale del tema', 'placeholder' => 'Seleziona colore primario', 'helper_text' => 'Colore principale utilizzato nell\'interfaccia', 'description' => 'Colore primario del tema'],
    ],
    'actions' => [
        'import' => [
            'label' => 'Importa',
            'tooltip' => 'Importa dati',
            'helper_text' => 'Importa dati da file esterno',
            'description' => 'Azione per importare',
            'fields' => [
                'import_file' => ['label' => 'File Import', 'tooltip' => 'Seleziona un file', 'placeholder' => 'Seleziona un file XLS o CSV da caricare', 'helper_text' => 'Seleziona un file XLS o CSV da caricare', 'description' => 'File contenente i dati'],
            ],
        ],
        'export' => [
            'label' => 'Esporta',
            'tooltip' => 'Esporta dati',
            'helper_text' => 'Esporta i dati in formato CSV/Excel',
            'description' => 'Azione per esportare',
            'filename_prefix' => ['label' => 'Prefisso Nome File', 'tooltip' => 'Prefisso per il nome', 'placeholder' => 'Inserisci il prefisso', 'helper_text' => 'Prefisso per il nome del file', 'description' => 'Prefisso del file'],
            'columns' => [
                'name' => ['label' => 'Nome Colonna', 'tooltip' => 'Nome della colonna', 'helper_text' => 'Nome della colonna', 'description' => 'Nome della colonna'],
                'parent_name' => ['label' => 'Nome Padre', 'tooltip' => 'Nome del livello superiore', 'helper_text' => 'Nome del parent', 'description' => 'Nome del parent'],
            ],
        ],
        'change_password' => ['label' => 'Cambia Password', 'tooltip' => 'Cambia la password', 'helper_text' => 'Modifica la password del tenant', 'description' => 'Azione per cambiare password'],
        'create' => ['label' => 'Crea', 'tooltip' => 'Crea nuovo elemento', 'helper_text' => 'Crea un nuovo tenant', 'description' => 'Azione per creare', 'icon' => 'heroicon-o-plus'],
        'edit' => ['label' => 'Modifica', 'tooltip' => 'Modifica elemento', 'helper_text' => 'Modifica il tenant', 'description' => 'Azione per modificare', 'icon' => 'heroicon-o-pencil'],
        'delete' => ['label' => 'Elimina', 'tooltip' => 'Elimina elemento', 'helper_text' => 'Elimina il tenant', 'description' => 'Azione per eliminare', 'icon' => 'heroicon-o-trash'],
        'view' => ['label' => 'Visualizza', 'tooltip' => 'Visualizza dettagli', 'helper_text' => 'Visualizza i dettagli del tenant', 'description' => 'Azione per visualizzare', 'icon' => 'heroicon-o-eye'],
        'save' => ['label' => 'Salva', 'tooltip' => 'Salva modifiche', 'helper_text' => 'Salva le modifiche', 'description' => 'Azione per salvare', 'icon' => 'heroicon-o-check'],
        'cancel' => ['label' => 'Annulla', 'tooltip' => 'Annulla operazione', 'helper_text' => 'Annulla e torna indietro', 'description' => 'Azione per annullare', 'icon' => 'heroicon-o-x-mark'],
        'createAnother' => ['label' => 'createAnother', 'icon' => 'createAnother', 'tooltip' => 'createAnother'],
        'detach' => ['label' => 'detach', 'icon' => 'detach', 'tooltip' => 'detach'],
    ],
    'messages' => ['created' => 'Tenant creato con successo', 'updated' => 'Tenant aggiornato con successo', 'deleted' => 'Tenant eliminato con successo'],
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
];
