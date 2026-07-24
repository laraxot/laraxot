<?php

declare(strict_types=1);

return [
    'navigation' => [
        'name' => 'Documents',
        'plural' => 'Documents',
        'group' => [
            'name' => 'System',
            'description' => 'Document and attachment management',
        ],
        'label' => 'documents',
        'sort' => 20,
        'icon' => 'heroicon-o-document',
    ],
    'fields' => [
        'mediable_type' => ['label' => 'Owner type'],
        'mediable_id' => ['label' => 'Owner ID'],
        'uploaded_by_user_id' => ['label' => 'Uploaded by'],
        'kind' => ['label' => 'Kind'],
        'title' => ['label' => 'Title'],
        'disk' => ['label' => 'Disk'],
        'path' => ['label' => 'Path'],
        'mime_type' => ['label' => 'Mime type'],
        'size_bytes' => ['label' => 'Size (bytes)'],
        'meta' => ['label' => 'Metadata'],
        'created_at' => ['label' => 'Created at'],
        'updated_at' => ['label' => 'Updated at'],
    ],
    'label' => 'Document',
    'plural_label' => 'Documents',
];
