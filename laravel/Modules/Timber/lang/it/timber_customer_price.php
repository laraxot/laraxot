<?php

declare(strict_types=1);

return [
    'sections' => [
        'empty' => ['label' => 'empty', 'heading' => 'empty'],
    ],
    'fields' => [
        'customer_id' => ['label' => 'customer_id', 'placeholder' => 'customer_id', 'helper_text' => 'customer_id', 'description' => 'customer_id'],
        'product_id' => ['label' => 'product_id', 'placeholder' => 'product_id', 'helper_text' => 'product_id', 'description' => 'product_id'],
        'net_price' => ['label' => 'net_price', 'placeholder' => 'net_price', 'helper_text' => 'net_price', 'description' => 'net_price'],
    ],
    'actions' => [
        'create' => ['label' => 'create', 'icon' => 'create', 'tooltip' => 'create'],
        'createAnother' => ['label' => 'createAnother', 'icon' => 'createAnother', 'tooltip' => 'createAnother'],
        'cancel' => ['label' => 'cancel', 'icon' => 'cancel', 'tooltip' => 'cancel'],
    ],
    'navigation' => ['label' => 'timber customer price.navigation'],
];
