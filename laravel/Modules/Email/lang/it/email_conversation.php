<?php

declare(strict_types=1);

return [
    'navigation' => [
        'name' => 'Conversazioni Email',
        'plural' => 'Conversazioni Email',
        'group' => [
            'name' => 'Comunicazioni',
            'description' => 'Gestione conversazioni email con clienti',
        ],
        'label' => 'conversazioni email',
        'sort' => 30,
        'icon' => 'heroicon-o-envelope',
    ],
    'fields' => [
        'email' => ['label' => 'Email'],
        'display_name' => ['label' => 'Nome'],
        'identity_id' => ['label' => 'Identità'],
        'customer_id' => ['label' => 'Cliente'],
        'customer_site_id' => ['label' => 'Sede'],
        'contact_id' => ['label' => 'Contatto'],
        'user_id' => ['label' => 'Utente'],
        'status' => ['label' => 'Stato'],
        'last_message_at' => ['label' => 'Ultimo messaggio'],
        'meta' => ['label' => 'Metadati'],
        'created_at' => ['label' => 'Creato il'],
        'updated_at' => ['label' => 'Aggiornato il'],
    ],
    'label' => 'Conversazione Email',
    'plural_label' => 'Conversazioni Email',
];
