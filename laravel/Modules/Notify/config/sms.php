<?php

declare(strict_types=1);

return [
    'default' => 'smsfactor',

    'drivers' => [
        'smsfactor' => [
            'token' => null,
<<<<<<< HEAD
<<<<<<< HEAD
            'base_url' => 'https://api.smsfactor.com']],
=======
            'base_url' => 'https://api.smsfactor.com',
        ],
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'base_url' => 'https://api.smsfactor.com']],
>>>>>>> a988596b (first)

    'debug' => false,
    'queue' => 'default',
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
        'decay_minutes' => 1],
    'circuit_breaker' => [
        'enabled' => true,
        'threshold' => 5,
        'timeout' => 60],
    'timeout' => 30,
    'logging' => [
        'enabled' => true,
        'channel' => 'stack'],
    'validation' => [
        'enabled' => true,
        'pattern' => '/^\+[1-9]\d{1,14}$/']];
<<<<<<< HEAD
=======
        'delay' => 60,
    ],
    'rate_limit' => [
        'enabled' => true,
        'max_attempts' => 60,
        'decay_minutes' => 1,
    ],
    'circuit_breaker' => [
        'enabled' => true,
        'threshold' => 5,
        'timeout' => 60,
    ],
    'timeout' => 30,
    'logging' => [
        'enabled' => true,
        'channel' => 'stack',
    ],
    'validation' => [
        'enabled' => true,
        'pattern' => '/^\+[1-9]\d{1,14}$/',
    ],
];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
