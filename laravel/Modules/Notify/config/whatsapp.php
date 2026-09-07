<?php

declare(strict_types=1);

return [
    'default' => 'twilio',

    'drivers' => [
        'twilio' => [
            'account_sid' => null,
            'auth_token' => null,
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            'from' => null],
        'vonage' => [
            'api_key' => null,
            'api_secret' => null,
            'from' => null],
<<<<<<< HEAD
=======
            'from' => null,
        ],
        'vonage' => [
            'api_key' => null,
            'api_secret' => null,
            'from' => null,
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
        'facebook' => [
            'app_id' => null,
            'app_secret' => null,
            'access_token' => null,
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            'phone_number_id' => null],
        '360dialog' => [
            'api_key' => null,
            'phone_number_id' => null]],
<<<<<<< HEAD
=======
            'phone_number_id' => null,
        ],
        '360dialog' => [
            'api_key' => null,
            'phone_number_id' => null,
        ],
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)

    'debug' => false,
    'queue' => 'default',
    'timeout' => 30,
    'from' => null,
    'retry' => [
        'attempts' => 3,
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
        'delay' => 60],
    'rate_limit' => [
        'enabled' => true,
        'max_attempts' => 60,
        'decay_minutes' => 1]];
<<<<<<< HEAD
=======
        'delay' => 60,
    ],
    'rate_limit' => [
        'enabled' => true,
        'max_attempts' => 60,
        'decay_minutes' => 1,
    ],
];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
