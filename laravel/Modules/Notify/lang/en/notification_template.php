<?php

declare(strict_types=1);

return [
    'navigation' => [
        'icon' => 'heroicon-o-document-text',
        'label' => 'Notification Templates',
        'group' => 'System',
<<<<<<< HEAD
<<<<<<< HEAD
        'sort' => '52'],
=======
        'sort' => '52',
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'sort' => '52'],
>>>>>>> a988596b (first)
    'fields' => [
        'name' => [
            'label' => 'Name',
            'helper' => 'Unique template name',
            'tooltip' => '',
            'helper_text' => '',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => ''],
=======
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => ''],
>>>>>>> a988596b (first)
        'subject' => [
            'label' => 'Subject',
            'helper' => 'Notification subject',
            'tooltip' => '',
            'helper_text' => '',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => ''],
=======
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => ''],
>>>>>>> a988596b (first)
        'type' => [
            'label' => 'Type',
            'helper' => 'Notification type',
            'tooltip' => '',
            'helper_text' => '',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => ''],
=======
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => ''],
>>>>>>> a988596b (first)
        'body_text' => [
            'label' => 'Plain Text',
            'helper' => 'Plain text version of the notification',
            'tooltip' => '',
            'helper_text' => '',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => ''],
=======
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => ''],
>>>>>>> a988596b (first)
        'body_html' => [
            'label' => 'HTML',
            'helper' => 'HTML version of the notification',
            'tooltip' => '',
            'helper_text' => '',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => ''],
=======
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => ''],
>>>>>>> a988596b (first)
        'preview_data' => [
            'label' => 'Preview Data',
            'helper' => 'JSON data for preview',
            'tooltip' => '',
            'helper_text' => '',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => '']],
=======
            'description' => '',
        ],
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => '']],
>>>>>>> a988596b (first)
    'columns' => [
        'name' => 'Name',
        'subject' => 'Subject',
        'type' => 'Type',
        'created_at' => 'Created At',
<<<<<<< HEAD
<<<<<<< HEAD
        'updated_at' => 'Updated At'],
    'actions' => [
        'preview' => 'Preview'],
=======
        'updated_at' => 'Updated At',
    ],
    'actions' => [
        'preview' => 'Preview',
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'updated_at' => 'Updated At'],
    'actions' => [
        'preview' => 'Preview'],
>>>>>>> a988596b (first)
    'enums' => [
        'notification_type' => [
            'email' => 'Email',
            'sms' => 'SMS',
<<<<<<< HEAD
<<<<<<< HEAD
            'push' => 'Push Notification']],
    'label' => 'Missing Label',
    'plural_label' => 'Missing Plural label'];
=======
            'push' => 'Push Notification',
        ],
    ],
    'label' => 'Missing Label',
    'plural_label' => 'Missing Plural label',
];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'push' => 'Push Notification']],
    'label' => 'Missing Label',
    'plural_label' => 'Missing Plural label'];
>>>>>>> a988596b (first)
