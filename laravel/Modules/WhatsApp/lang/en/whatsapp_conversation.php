<?php

declare(strict_types=1);

return [
    'navigation' => [
        'name' => 'WhatsApp Conversations',
        'plural' => 'WhatsApp Conversations',
        'group' => [
            'name' => 'Communications',
            'description' => 'Manage customer WhatsApp conversations',
        ],
        'label' => 'whatsapp conversations',
        'sort' => 31,
        'icon' => 'heroicon-o-chat-bubble-left-right',
    ],
    'fields' => [
        'phone_e164' => ['label' => 'Phone'],
        'wa_id' => ['label' => 'WA ID'],
        'display_name' => ['label' => 'Name'],
        'identity_id' => ['label' => 'Identity'],
        'customer_id' => ['label' => 'Customer'],
        'customer_site_id' => ['label' => 'Site'],
        'contact_id' => ['label' => 'Contact'],
        'user_id' => ['label' => 'User'],
        'status' => ['label' => 'Status'],
        'active_proposal_id' => ['label' => 'Active proposal'],
        'approved_up_to_message_id' => ['label' => 'Approved up to'],
        'last_message_at' => ['label' => 'Last message at'],
        'meta' => ['label' => 'Metadata'],
        'created_at' => ['label' => 'Created at'],
        'updated_at' => ['label' => 'Updated at'],
    ],
    'label' => 'WhatsApp Conversation',
    'plural_label' => 'WhatsApp Conversations',
];
