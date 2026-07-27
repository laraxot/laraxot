<?php

declare(strict_types=1);

use Modules\User\Models\Profile;
use Modules\User\Models\User;

return [
    'adm_theme' => 'AdminLTE',
    'enable_ads' => false,
    'model' => [
        'profile' => Profile::class,
        'user' => User::class,
    ],
];
