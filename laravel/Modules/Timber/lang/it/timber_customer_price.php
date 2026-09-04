<?php

declare(strict_types=1);

return [
    'sections' => [
        'empty' => [
            'label' => 'empty',
            'heading' => 'empty',
        ],
    ],
    'fields' => [
        'customer_id' => [
            'label' => 'Cliente',
            'placeholder' => 'customer_id',
            'helper_text' => 'customer_id',
            'description' => 'customer_id',
        ],
        'product_id' => [
            'label' => 'Prodotto',
            'placeholder' => 'product_id',
            'helper_text' => 'product_id',
            'description' => 'product_id',
        ],
        'net_price' => [
            'label' => 'Prezzo netto',
            'placeholder' => 'net_price',
            'helper_text' => 'net_price',
            'description' => 'net_price',
        ],
        'customer.business_name' => [
            'label' => 'Cliente',
        ],
        'product.description' => [
            'label' => 'Prodotto',
        ],
    ],
    'actions' => [
        'create' => [
            'label' => 'create',
            'icon' => 'create',
            'tooltip' => 'create',
        ],
        'createAnother' => [
            'label' => 'createAnother',
            'icon' => 'createAnother',
            'tooltip' => 'createAnother',
        ],
        'cancel' => [
            'label' => 'cancel',
            'icon' => 'cancel',
            'tooltip' => 'cancel',
        ],
    ],
    'navigation' => [
        'label' => 'timber customer price.navigation',
    ],
];
