<?php

declare(strict_types=1);

return [
    'actions' => [
        'create' => ['label' => 'create', 'icon' => 'create', 'tooltip' => 'create'],
        'view' => ['label' => 'view', 'icon' => 'view', 'tooltip' => 'view'],
        'view_attachment' => ['label' => 'view_attachment', 'icon' => 'view_attachment', 'tooltip' => 'view_attachment'],
        'delete' => ['label' => 'delete', 'icon' => 'delete', 'tooltip' => 'delete'],
        'download_attachment' => ['label' => 'download_attachment', 'icon' => 'download_attachment', 'tooltip' => 'download_attachment'],
        'convert' => ['label' => 'convert', 'icon' => 'convert', 'tooltip' => 'convert'],
        'edit' => ['label' => 'edit', 'icon' => 'edit', 'tooltip' => 'edit'],
        'detach' => ['label' => 'detach', 'icon' => 'detach', 'tooltip' => 'detach'],
        'add_attachment' => ['label' => 'add_attachment', 'icon' => 'add_attachment', 'tooltip' => 'add_attachment'],
    ],
    'fields' => [
        'collection_name' => ['label' => 'collection_name', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'name' => ['label' => 'name', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'mime_type' => ['label' => 'mime_type', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'preview' => ['label' => 'preview', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'human_readable_size' => ['label' => 'human_readable_size', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'creator' => [
            'name' => ['label' => 'creator.name'],
            'label' => '',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'created_at' => ['label' => 'created_at', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'applyFilters' => ['label' => 'applyFilters', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'toggleColumns' => ['label' => 'toggleColumns', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'reorderRecords' => ['label' => 'reorderRecords', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'id' => ['label' => 'id'],
        'model_type' => ['label' => 'model_type'],
        'model_id' => ['label' => 'model_id'],
        'file_name' => ['label' => 'file_name'],
        'disk' => ['label' => 'disk'],
        'size' => ['label' => 'size'],
    ],
    'label' => 'Medium',
    'plural_label' => 'Medium (Plurale)',
    'navigation' => [
        'name' => 'Medium',
        'plural' => 'Medium',
        'group' => ['name' => 'General', 'description' => 'General Settings'],
        'label' => 'Medium',
        'sort' => 1,
        'icon' => 'heroicon-o-rectangle-stack',
    ],
];
