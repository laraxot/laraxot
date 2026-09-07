<?php

declare(strict_types=1);

return [
    'fields' => [
        'recipient' => [
            'label' => 'Recipient',
            'helper_text' => 'Enter the phone number in international format (e.g. +393401234567).',
            'tooltip' => '',
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
        'to' => [
            'label' => 'Recipient',
            'helper_text' => 'Enter the phone number in international format (e.g. +393401234567).',
            'tooltip' => '',
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
        'message' => [
            'label' => 'Message',
            'helper_text' => 'Enter the message content (max 160 characters for a single SMS).',
            'tooltip' => '',
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
        'driver' => [
            'label' => 'SMS Driver',
            'helper_text' => 'Select the provider for sending the SMS.',
            'tooltip' => '',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            'description' => '']],
    'actions' => [
        'send' => 'Send SMS'],
    'notifications' => [
        'sent' => [
            'title' => 'SMS Sent',
            'body' => 'The message has been accepted by the provider.'],
        'error' => [
            'title' => 'Sending Error',
            'body' => 'An error occurred while sending the SMS.']],
    'form' => [
        'to' => [
            'label' => 'Recipient',
            'helper' => 'Phone number with international prefix.'],
        'from' => [
            'label' => 'Sender',
            'helper' => 'Sender name or number (max 11 characters).'],
        'body' => [
            'label' => 'Message Text',
            'helper' => 'Content of the SMS to send.'],
        'provider' => [
            'label' => 'Provider']],
<<<<<<< HEAD
=======
            'description' => '',
        ],
    ],
    'actions' => [
        'send' => 'Send SMS',
    ],
    'notifications' => [
        'sent' => [
            'title' => 'SMS Sent',
            'body' => 'The message has been accepted by the provider.',
        ],
        'error' => [
            'title' => 'Sending Error',
            'body' => 'An error occurred while sending the SMS.',
        ],
    ],
    'form' => [
        'to' => [
            'label' => 'Recipient',
            'helper' => 'Phone number with international prefix.',
        ],
        'from' => [
            'label' => 'Sender',
            'helper' => 'Sender name or number (max 11 characters).',
        ],
        'body' => [
            'label' => 'Message Text',
            'helper' => 'Content of the SMS to send.',
        ],
        'provider' => [
            'label' => 'Provider',
        ],
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
    'navigation' => [
        'label' => 'Missing Navigation Label',
        'plural_label' => 'Missing Navigation Plural Label',
        'group' => 'Missing Group',
        'icon' => 'heroicon-o-puzzle-piece',
<<<<<<< HEAD
<<<<<<< HEAD
        'sort' => 100],
    'label' => 'Missing Label',
    'plural_label' => 'Missing Plural label'];
=======
        'sort' => 100,
    ],
    'label' => 'Missing Label',
    'plural_label' => 'Missing Plural label',
];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'sort' => 100],
    'label' => 'Missing Label',
    'plural_label' => 'Missing Plural label'];
>>>>>>> a988596b (first)
