<?php

declare(strict_types=1);

return [
    'meta' => ['title' => 'Site map', 'description' => 'Explore FixCity public pages and services.', 'breadcrumb' => 'Site map'],
    'intro' => 'Find public pages and available tasks quickly.',
    'groups' => [
        ['title' => 'Reports', 'links' => [
            ['label' => 'Report a problem', 'path' => '/tickets/create'],
            ['label' => 'Report list and map', 'path' => '/tickets'],
            ['label' => 'Track a report', 'path' => '/tickets/track'],
            ['label' => 'My cases', 'path' => '/area-personale/pratiche'],
        ]],
        ['title' => 'Information', 'links' => [
            ['label' => 'Frequently asked questions', 'path' => '/domande-frequenti'],
            ['label' => 'Privacy', 'path' => '/privacy'],
            ['label' => 'Legal notices', 'path' => '/note-legali'],
            ['label' => 'Cookie policy', 'path' => '/cookie-policy'],
            ['label' => 'Accessibility', 'path' => '/accessibilita'],
        ]],
        ['title' => 'Account', 'links' => [
            ['label' => 'Sign in', 'path' => '/auth/login'],
            ['label' => 'Create an account', 'path' => '/auth/register'],
        ]],
    ],
];
