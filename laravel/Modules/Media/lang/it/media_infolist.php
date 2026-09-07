<?php

declare(strict_types=1);

return [
    'fields' => [
        'id' => ['label' => 'id'],
        'model_type' => ['label' => 'model_type'],
        'model_id' => ['label' => 'model_id'],
        'uuid' => ['label' => 'uuid'],
        'collection_name' => ['label' => 'collection_name'],
        'name' => ['label' => 'name'],
        'file_name' => ['label' => 'file_name'],
        'mime_type' => ['label' => 'mime_type'],
        'disk' => ['label' => 'disk'],
        'size' => ['label' => 'size'],
        'url' => ['label' => 'url'],
        'human_readable_size' => ['label' => 'human_readable_size'],
        'created_at' => ['label' => 'created_at'],
        'entry_conversions' => ['label' => 'entry_conversions'],
        'src' => ['label' => 'src'],
    ],
    'sections' => [
        'empty' => ['label' => '', 'heading' => ''],
    ],
    'actions' => [
        'convert' => ['label' => 'convert', 'icon' => 'convert', 'tooltip' => 'convert'],
    ],
];
