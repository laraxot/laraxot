<?php

declare(strict_types=1);

return [
    'navigation' => [
        'label' => 'Send Email',
        'group' => [
            'label' => 'System',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => 'Functionality for sending emails through the notification system'],
        'icon' => 'heroicon-o-envelope',
        'sort' => '49'],
=======
            'description' => 'Functionality for sending emails through the notification system',
        ],
        'icon' => 'heroicon-o-envelope',
        'sort' => '49',
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => 'Functionality for sending emails through the notification system'],
        'icon' => 'heroicon-o-envelope',
        'sort' => '49'],
>>>>>>> a988596b (first)
    'fields' => [
        'subject' => [
            'label' => 'Subject',
            'placeholder' => 'Enter email subject',
            'help' => 'Subject that will appear in the email header',
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
        'template_id' => [
            'label' => 'Email Template',
            'placeholder' => 'Select the email template to use',
            'help' => 'Default template for the email (optional)',
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
        'to' => [
            'label' => 'Recipient',
            'placeholder' => 'recipient@domain.com',
            'help' => 'Email address of the recipient',
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
        'cc' => [
            'label' => 'Carbon Copy (CC)',
            'placeholder' => 'cc@domain.com (optional)',
            'help' => 'Email addresses in carbon copy, separated by commas',
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
        'bcc' => [
            'label' => 'Blind Carbon Copy (BCC)',
            'placeholder' => 'bcc@domain.com (optional)',
            'help' => 'Email addresses in blind carbon copy, separated by commas',
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
        'content' => [
            'label' => 'Text Content',
            'placeholder' => 'Enter the text content of the email',
            'help' => 'Text content of the email (plain text version)',
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
            'label' => 'HTML Content',
            'placeholder' => '<h1>Title</h1><p>Email content in HTML format</p>',
            'help' => 'HTML content of the email to send (optional)',
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
        'parameters' => [
            'label' => 'Template Parameters',
            'placeholder' => '{\\"name\\": \\"John\\", \\"surname\\": \\"Doe\\"}',
            'help' => 'JSON parameters to customize the selected template',
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
        'attachments' => [
            'label' => 'Attachments',
            'placeholder' => 'Select files to attach',
            'help' => 'Files to attach to the email (optional)',
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
        'priority' => [
            'label' => 'Priority',
            'placeholder' => 'Select email priority',
            'help' => 'Email priority (normal, high, urgent)',
            'options' => [
                'normal' => 'Normal',
                'high' => 'High',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
                'urgent' => 'Urgent'],
            'tooltip' => '',
            'helper_text' => '',
            'description' => '']],
<<<<<<< HEAD
=======
                'urgent' => 'Urgent',
            ],
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
    'actions' => [
        'send' => [
            'label' => 'Send Email',
            'success' => 'Email sent successfully to the recipient',
            'error' => 'Error sending email. Check the configuration.',
            'confirmation' => 'Are you sure you want to send this email?',
<<<<<<< HEAD
<<<<<<< HEAD
            'tooltip' => 'Send the email to the specified recipient'],
=======
            'tooltip' => 'Send the email to the specified recipient',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'tooltip' => 'Send the email to the specified recipient'],
>>>>>>> a988596b (first)
        'preview' => [
            'label' => 'Preview',
            'success' => 'Email preview generated correctly',
            'error' => 'Error generating preview',
<<<<<<< HEAD
<<<<<<< HEAD
            'tooltip' => 'View email preview before sending'],
=======
            'tooltip' => 'View email preview before sending',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'tooltip' => 'View email preview before sending'],
>>>>>>> a988596b (first)
        'save_draft' => [
            'label' => 'Save Draft',
            'success' => 'Draft saved correctly',
            'error' => 'Error saving draft',
<<<<<<< HEAD
<<<<<<< HEAD
            'tooltip' => 'Save email as draft to send later'],
=======
            'tooltip' => 'Save email as draft to send later',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'tooltip' => 'Save email as draft to send later'],
>>>>>>> a988596b (first)
        'schedule' => [
            'label' => 'Schedule Send',
            'success' => 'Email scheduled for sending',
            'error' => 'Error scheduling send',
<<<<<<< HEAD
<<<<<<< HEAD
            'tooltip' => 'Schedule email sending for a specific date and time']],
=======
            'tooltip' => 'Schedule email sending for a specific date and time',
        ],
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'tooltip' => 'Schedule email sending for a specific date and time']],
>>>>>>> a988596b (first)
    'messages' => [
        'success' => 'Email sent successfully! Check the recipient\'s email inbox.',
        'error' => 'An error occurred while sending the email. Check the SMTP configuration.',
        'draft_saved' => 'Draft saved correctly. You can retrieve it from the Drafts section.',
        'scheduled' => 'Email scheduled for sending. You will receive a notification when it is sent.',
        'preview_generated' => 'Preview generated correctly. Check the email appearance.',
        'invalid_template' => 'Invalid or not found email template.',
        'invalid_parameters' => 'Invalid template parameters. Check the JSON format.',
        'no_recipients' => 'No recipient specified. Enter at least one email address.',
<<<<<<< HEAD
<<<<<<< HEAD
        'smtp_error' => 'SMTP configuration error. Check server settings.'],
=======
        'smtp_error' => 'SMTP configuration error. Check server settings.',
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'smtp_error' => 'SMTP configuration error. Check server settings.'],
>>>>>>> a988596b (first)
    'validation' => [
        'subject_required' => 'Email subject is required',
        'to_required' => 'Recipient is required',
        'to_valid' => 'Recipient must be a valid email address',
        'cc_valid' => 'CC addresses must be valid emails',
        'bcc_valid' => 'BCC addresses must be valid emails',
        'content_required' => 'Email content is required',
        'template_exists' => 'Selected template does not exist',
        'parameters_json' => 'Parameters must be in valid JSON format',
<<<<<<< HEAD
<<<<<<< HEAD
        'priority_valid' => 'Priority must be one of the available options'],
    'label' => 'Missing Label',
    'plural_label' => 'Missing Plural label'];
=======
        'priority_valid' => 'Priority must be one of the available options',
    ],
    'label' => 'Missing Label',
    'plural_label' => 'Missing Plural label',
];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'priority_valid' => 'Priority must be one of the available options'],
    'label' => 'Missing Label',
    'plural_label' => 'Missing Plural label'];
>>>>>>> a988596b (first)
