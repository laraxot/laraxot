<?php

// use Modules\Tenant\Services\TenantService;
use Nwidart\Modules\Activators\FileActivator;

return [
    'activators' => [
        'file' => [
            'class' => FileActivator::class,
            'statuses-file' => base_path('config/local/workorder/modules_statuses.json'),
        ],
    ],
];
