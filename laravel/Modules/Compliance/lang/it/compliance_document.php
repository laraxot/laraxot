<?php

declare(strict_types=1);

return [
    'fields' => [
        'type' => ['label' => 'Type'],
        'title' => ['label' => 'Title'],
        'status' => ['label' => 'Status'],
        'is_required' => ['label' => 'Is required'],
        'issued_on' => ['label' => 'Issued on'],
        'expires_on' => ['label' => 'Expires on'],
        'work_order_id' => ['label' => 'Work order id'],
        'file_path' => ['label' => 'File path'],
        'file_preview' => ['label' => 'File preview'],
        'notes' => ['label' => 'Notes'],
        'workOrder.title' => ['label' => 'WorkOrder.title'],
    ],
    'actions' => [
        'analyzeWithAi' => ['label' => 'AnalyzeWithAi'],
    ],
    'navigation' => ['label' => 'compliance document.navigation'],
];
