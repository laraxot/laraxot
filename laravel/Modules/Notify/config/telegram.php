<?php

declare(strict_types=1);

return [
    'default' => 'official',

    'drivers' => [
        'official' => [
            'token' => null,
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            'api_url' => 'https://api.telegram.org'],
        'botman' => [
            'token' => null,
            'api_url' => 'https://api.telegram.org',
            'webhook_url' => null],
<<<<<<< HEAD
=======
            'api_url' => 'https://api.telegram.org',
        ],
        'botman' => [
            'token' => null,
            'api_url' => 'https://api.telegram.org',
            'webhook_url' => null,
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
        'nutgram' => [
            'token' => null,
            'api_url' => 'https://api.telegram.org',
            'webhook_url' => null,
<<<<<<< HEAD
<<<<<<< HEAD
            'polling' => false]],
=======
            'polling' => false,
        ],
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'polling' => false]],
>>>>>>> a988596b (first)

    'debug' => false,
    'queue' => 'default',
    'timeout' => 30,
    'parse_mode' => 'HTML',
    'retry' => [
        'attempts' => 3,
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
        'delay' => 60],
    'rate_limit' => [
        'enabled' => true,
        'max_attempts' => 30,
        'decay_minutes' => 1]];
<<<<<<< HEAD
=======
        'delay' => 60,
    ],
    'rate_limit' => [
        'enabled' => true,
        'max_attempts' => 30,
        'decay_minutes' => 1,
    ],
];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
