<?php

declare(strict_types=1);

return [
    'resource' => [
        'name' => 'SMS',
<<<<<<< HEAD
<<<<<<< HEAD
        'plural' => 'SMS'],
=======
        'plural' => 'SMS',
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'plural' => 'SMS'],
>>>>>>> a988596b (first)
    'navigation' => [
        'name' => 'Send SMS',
        'plural' => 'Send SMS',
        'group' => [
            'name' => 'Notifications',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            'description' => 'SMS notification management'],
        'label' => 'Send SMS',
        'icon' => 'heroicon-o-device-phone-mobile',
        'sort' => '10'],
<<<<<<< HEAD
=======
            'description' => 'SMS notification management',
        ],
        'label' => 'Send SMS',
        'icon' => 'heroicon-o-device-phone-mobile',
        'sort' => '10',
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
    'fields' => [
        'to' => [
            'label' => 'Phone Number',
            'placeholder' => 'Enter phone number',
            'helper_text' => 'Enter phone number with international prefix (e.g. +1)',
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
            'placeholder' => 'Enter message',
            'helper_text' => 'Message cannot exceed 160 characters',
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
            'label' => 'SMS Provider',
            'placeholder' => 'Select SMS provider',
            'helper_text' => 'Select the SMS provider to use',
            'tooltip' => '',
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
    'drivers' => [
        'smsfactor' => 'SMSFactor',
        'twilio' => 'Twilio',
        'nexmo' => 'Nexmo',
        'plivo' => 'Plivo',
        'gammu' => 'Gammu',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
        'netfun' => 'Netfun'],
    'actions' => [
        'send' => 'Send SMS',
        'cancel' => 'Cancel'],
    'messages' => [
        'success' => 'SMS sent successfully',
        'error' => 'An error occurred while sending the SMS'],
    'label' => 'Missing Label',
    'plural_label' => 'Missing Plural label'];
<<<<<<< HEAD
=======
        'netfun' => 'Netfun',
    ],
    'actions' => [
        'send' => 'Send SMS',
        'cancel' => 'Cancel',
    ],
    'messages' => [
        'success' => 'SMS sent successfully',
        'error' => 'An error occurred while sending the SMS',
    ],
    'label' => 'Missing Label',
    'plural_label' => 'Missing Plural label',
];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
