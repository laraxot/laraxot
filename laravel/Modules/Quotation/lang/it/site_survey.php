<?php

declare(strict_types=1);

return [
    'navigation' => [
        'label' => 'Sopralluoghi',
        'group' => 'Preventivi',
        'icon' => 'heroicon-o-map-pin',
        'sort' => 40,
    ],
    'fields' => [
        'code' => [
            'label' => 'Code',
        ],
        'status_code' => [
            'label' => 'Status code',
        ],
        'title' => [
            'label' => 'Title',
        ],
        'scheduled_at' => [
            'label' => 'Scheduled at',
        ],
        'performed_at' => [
            'label' => 'Performed at',
        ],
        'customer_id' => [
            'label' => 'Customer id',
        ],
        'customer_site_id' => [
            'label' => 'Customer site id',
        ],
        'work_order_id' => [
            'label' => 'Work order id',
        ],
        'quotation_id' => [
            'label' => 'Quotation id',
        ],
        'performed_by_user_id' => [
            'label' => 'Performed by user id',
        ],
        'notes' => [
            'label' => 'Notes',
        ],
        'customer.legal_name' => [
            'label' => 'Customer.legal name',
        ],
        'media_count' => [
            'label' => 'Media count',
        ],
    ],
];
