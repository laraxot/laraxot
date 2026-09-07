<?php

declare(strict_types=1);

return [
    'resource' => [
        'name' => 'WhatsApp',
<<<<<<< HEAD
<<<<<<< HEAD
        'plural' => 'WhatsApp'],
=======
        'plural' => 'WhatsApp',
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'plural' => 'WhatsApp'],
>>>>>>> a988596b (first)
    'navigation' => [
        'name' => 'Send WhatsApp',
        'plural' => 'Send WhatsApp',
        'group' => [
            'name' => 'Notifications',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            'description' => 'WhatsApp notification management'],
        'label' => 'Send WhatsApp',
        'icon' => 'heroicon-o-chat-bubble-left-right',
        'sort' => '20'],
<<<<<<< HEAD
=======
            'description' => 'WhatsApp notification management',
        ],
        'label' => 'Send WhatsApp',
        'icon' => 'heroicon-o-chat-bubble-left-right',
        'sort' => '20',
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
            'helper_text' => 'Message cannot exceed 4096 characters',
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
            'label' => 'WhatsApp Provider',
            'placeholder' => 'Select WhatsApp provider',
            'helper_text' => 'Select the WhatsApp provider to use',
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
        'template' => [
            'label' => 'Template',
            'placeholder' => 'Enter template name',
            'helper_text' => 'Template name (optional)',
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
        'parameters' => [
            'label' => 'Parameters',
            'placeholder' => 'Enter parameters',
            'helper_text' => 'Parameters for the template (optional)',
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
        'media_url' => [
            'label' => 'Media URL',
            'placeholder' => 'Enter media URL',
            'helper_text' => 'Media URL (optional)',
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
        'media_type' => [
            'label' => 'Media Type',
            'placeholder' => 'Select media type',
            'helper_text' => 'Select the media type',
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
        'twilio' => 'Twilio',
        'messagebird' => 'MessageBird',
        'vonage' => 'Vonage',
<<<<<<< HEAD
<<<<<<< HEAD
        'infobip' => 'Infobip'],
=======
        'infobip' => 'Infobip',
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'infobip' => 'Infobip'],
>>>>>>> a988596b (first)
    'media_types' => [
        'image' => 'Image',
        'video' => 'Video',
        'document' => 'Document',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
        'audio' => 'Audio'],
    'actions' => [
        'send' => 'Send WhatsApp',
        'cancel' => 'Cancel'],
    'messages' => [
        'success' => 'WhatsApp sent successfully',
        'error' => 'An error occurred while sending the WhatsApp'],
    'label' => 'Missing Label',
    'plural_label' => 'Missing Plural label'];
<<<<<<< HEAD
=======
        'audio' => 'Audio',
    ],
    'actions' => [
        'send' => 'Send WhatsApp',
        'cancel' => 'Cancel',
    ],
    'messages' => [
        'success' => 'WhatsApp sent successfully',
        'error' => 'An error occurred while sending the WhatsApp',
    ],
    'label' => 'Missing Label',
    'plural_label' => 'Missing Plural label',
];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
