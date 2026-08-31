<?php

declare(strict_types=1);

return [
    'navigation' => ['label' => 'media convert', 'group' => 'media convert', 'icon' => 'media convert', 'sort' => 20],
    'fields' => [
        'applyFilters' => ['label' => 'applyFilters', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'toggleColumns' => ['label' => 'toggleColumns', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'format' => ['label' => 'format', 'placeholder' => 'format', 'helper_text' => 'format', 'description' => 'format', 'tooltip' => ''],
        'codec_video' => ['label' => 'codec_video', 'placeholder' => 'codec_video', 'helper_text' => 'codec_video', 'description' => 'codec_video', 'tooltip' => ''],
        'codec_audio' => ['label' => 'codec_audio', 'placeholder' => 'codec_audio', 'helper_text' => 'codec_audio', 'description' => 'codec_audio', 'tooltip' => ''],
        'preset' => ['label' => 'preset', 'placeholder' => 'preset', 'helper_text' => 'preset', 'description' => 'preset', 'tooltip' => ''],
        'bitrate' => ['label' => 'bitrate', 'placeholder' => 'bitrate', 'helper_text' => 'bitrate', 'description' => 'bitrate', 'tooltip' => ''],
        'width' => ['label' => 'width', 'placeholder' => 'width', 'helper_text' => 'width', 'description' => 'width', 'tooltip' => ''],
        'height' => ['label' => 'height', 'placeholder' => 'height', 'helper_text' => 'height', 'description' => 'height', 'tooltip' => ''],
        'threads' => ['label' => 'threads', 'placeholder' => 'threads', 'helper_text' => 'threads', 'description' => 'threads', 'tooltip' => ''],
        'speed' => ['label' => 'speed', 'placeholder' => 'speed', 'helper_text' => 'speed', 'description' => 'speed', 'tooltip' => ''],
        'id' => ['label' => 'id'],
        'media' => [
            'file_name' => ['label' => 'media.file_name'],
        ],
        'percentage' => ['label' => 'percentage'],
        'remaining' => ['label' => 'remaining'],
        'rate' => ['label' => 'rate'],
        'execution_time' => ['label' => 'execution_time'],
    ],
    'label' => 'Media Convert',
    'plural_label' => 'Media Convert (Plurale)',
    'actions' => [
        'create' => ['label' => 'Crea Media Convert', 'icon' => 'create', 'tooltip' => 'create'],
        'edit' => ['label' => 'Modifica Media Convert', 'icon' => 'edit', 'tooltip' => 'edit'],
        'delete' => ['label' => 'Elimina Media Convert', 'icon' => 'delete', 'tooltip' => 'delete'],
        'createAnother' => ['label' => 'createAnother', 'icon' => 'createAnother', 'tooltip' => 'createAnother'],
        'save' => ['label' => 'save', 'icon' => 'save', 'tooltip' => 'save'],
        'view' => ['label' => 'view', 'icon' => 'view', 'tooltip' => 'view'],
        'convert' => ['label' => 'convert', 'icon' => 'convert', 'tooltip' => 'convert'],
    ],
];
