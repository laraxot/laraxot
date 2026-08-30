<?php

declare(strict_types=1);

return [
    'navigation' => ['label' => 'invoice.navigation', 'icon' => 'invoice.navigation', 'sort' => 14],
    'actions' => [
        'create' => ['label' => 'create', 'icon' => 'create', 'tooltip' => 'create'],
        'view' => ['label' => 'view', 'icon' => 'view', 'tooltip' => 'view'],
        'edit' => ['label' => 'edit', 'icon' => 'edit', 'tooltip' => 'edit'],
        'delete' => ['label' => 'delete', 'icon' => 'delete', 'tooltip' => 'delete'],
        'restore' => ['label' => 'restore', 'icon' => 'restore', 'tooltip' => 'restore'],
        'forceDelete' => ['label' => 'forceDelete', 'icon' => 'forceDelete', 'tooltip' => 'forceDelete'],
        'applyFilters' => ['label' => 'applyFilters', 'icon' => 'applyFilters', 'tooltip' => 'applyFilters'],
        'openFilters' => ['label' => 'openFilters', 'icon' => 'openFilters', 'tooltip' => 'openFilters'],
        'resetFilters' => ['label' => 'resetFilters', 'icon' => 'resetFilters', 'tooltip' => 'resetFilters'],
        'applyTableColumnManager' => ['label' => 'applyTableColumnManager', 'icon' => 'applyTableColumnManager', 'tooltip' => 'applyTableColumnManager'],
        'openColumnManager' => ['label' => 'openColumnManager', 'icon' => 'openColumnManager', 'tooltip' => 'openColumnManager'],
        'resetColumnManager' => ['label' => 'resetColumnManager', 'icon' => 'resetColumnManager', 'tooltip' => 'resetColumnManager'],
        'reorderRecords' => ['label' => 'reorderRecords', 'icon' => 'reorderRecords', 'tooltip' => 'reorderRecords'],
        'il-mio-profilo' => ['label' => 'il-mio-profilo', 'icon' => 'il-mio-profilo', 'tooltip' => 'il-mio-profilo'],
        'profile' => ['label' => 'profile', 'icon' => 'profile', 'tooltip' => 'profile'],
        'logout' => ['label' => 'logout', 'icon' => 'logout', 'tooltip' => 'logout'],
    ],
    'fields' => [
        'number' => ['label' => 'number'],
        'customer_id' => ['label' => 'customer_id'],
        'issued_on' => ['label' => 'issued_on'],
        'total' => ['label' => 'total'],
        'electronic_invoice_sent_at' => ['label' => 'electronic_invoice_sent_at'],
        'electronic_invoice_external_id' => ['label' => 'electronic_invoice_external_id'],
        'updated_at' => ['label' => 'updated_at'],
    ],
];
