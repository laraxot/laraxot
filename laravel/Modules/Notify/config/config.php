<?php

declare(strict_types=1);

return [
    'name' => 'Notify',
    'description' => 'Modulo per la gestione delle notifiche e comunicazioni',
    'icon' => 'heroicon-o-bell',
    'navigation' => [
        'enabled' => true,
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
        'sort' => 70],
    'routes' => [
        'enabled' => true,
        'middleware' => ['web', 'auth']],
    'providers' => [
        'Modules\\Notify\\Providers\\NotifyServiceProvider'],
<<<<<<< HEAD
=======
        'sort' => 70,
    ],
    'routes' => [
        'enabled' => true,
        'middleware' => ['web', 'auth'],
    ],
    'providers' => [
        'Modules\\Notify\\Providers\\NotifyServiceProvider',
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
    'logo_url' => null,
    'social_links' => [
        'facebook' => null,
        'twitter' => null,
        'instagram' => null,
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
        'linkedin' => null],
    'unsubscribe_url' => null,
    'default_layout' => 'notify::mail-layouts.base.default',
    'layouts' => [
        'default' => 'notify::mail-layouts.base.default'],
    'templates' => [
        'welcome' => 'notify::mail-layouts.templates.welcome']];
<<<<<<< HEAD
=======
        'linkedin' => null,
    ],
    'unsubscribe_url' => null,
    'default_layout' => 'notify::mail-layouts.base.default',
    'layouts' => [
        'default' => 'notify::mail-layouts.base.default',
    ],
    'templates' => [
        'welcome' => 'notify::mail-layouts.templates.welcome',
    ],
];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
