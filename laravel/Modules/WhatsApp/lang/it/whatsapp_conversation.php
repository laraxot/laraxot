<?php

declare(strict_types=1);

return [
    'navigation' => [
        'name' => 'Conversazioni WhatsApp',
        'plural' => 'Conversazioni WhatsApp',
        'group' => [
            'name' => 'Comunicazioni',
            'description' => 'Gestione conversazioni WhatsApp con clienti',
        ],
        'label' => 'conversazioni whatsapp',
        'sort' => 31,
        'icon' => 'heroicon-o-chat-bubble-left-right',
    ],
    'fields' => [
        'phone_e164' => ['label' => 'Telefono'],
        'wa_id' => ['label' => 'WA ID'],
        'display_name' => ['label' => 'Nome'],
        'identity_id' => ['label' => 'Identità'],
        'customer_id' => ['label' => 'Cliente'],
        'customer_site_id' => ['label' => 'Sede'],
        'contact_id' => ['label' => 'Contatto'],
        'user_id' => ['label' => 'Utente'],
        'status' => ['label' => 'Stato'],
        'active_proposal_id' => ['label' => 'Proposta attiva'],
        'approved_up_to_message_id' => ['label' => 'Approvato fino a'],
        'last_message_at' => ['label' => 'Ultimo messaggio'],
        'meta' => ['label' => 'Metadati'],
        'created_at' => ['label' => 'Creato il'],
        'updated_at' => ['label' => 'Aggiornato il'],
    ],
    'label' => 'Conversazione WhatsApp',
    'plural_label' => 'Conversazioni WhatsApp',
];
