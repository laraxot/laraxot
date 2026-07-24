<?php

declare(strict_types=1);

return [
    'navigation' => [
        'name' => 'Email Conversations',
        'plural' => 'Email Conversations',
        'group' => [
            'name' => 'Communications',
            'description' => 'Manage customer email conversations',
        ],
        'label' => 'email conversations',
        'sort' => 30,
        'icon' => 'heroicon-o-envelope',
    ],
    'fields' => [
        'email' => ['label' => 'Email'],
        'display_name' => ['label' => 'Name'],
        'identity_id' => ['label' => 'Identity'],
        'customer_id' => ['label' => 'Customer'],
        'customer_site_id' => ['label' => 'Site'],
        'contact_id' => ['label' => 'Contact'],
        'user_id' => ['label' => 'User'],
        'status' => ['label' => 'Status'],
        'last_message_at' => ['label' => 'Last message at'],
        'meta' => ['label' => 'Metadata'],
        'created_at' => ['label' => 'Created at'],
        'updated_at' => ['label' => 'Updated at'],
    ],
    'label' => 'Email Conversation',
    'plural_label' => 'Email Conversations',
];
