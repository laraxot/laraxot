<?php

declare(strict_types=1);

return [
    'company' => [
        'name' => 'Default Company',
        'team' => 'Default Team',
        'webhook_base' => 'https://api.example.com',
        'clinic_name' => 'Default Clinic',
<<<<<<< HEAD
<<<<<<< HEAD
        'repository_url' => 'https://github.com/example/repo'],
=======
        'repository_url' => 'https://github.com/example/repo',
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'repository_url' => 'https://github.com/example/repo'],
>>>>>>> a988596b (first)
    'test_data' => [
        'default_subject' => 'Benvenuto su {{company_name}}',
        'default_content' => 'Grazie per esserti registrato al nostro servizio.',
        'default_welcome_content' => 'Ciao {{user_name}}, benvenuto su {{company_name}}!',
        'default_clinic_name' => '{{clinic_name}}',
        'default_team_name' => '{{team_name}}',
        'default_theme_name' => '{{company_name}} Professional',
        'default_theme_description' => 'Tema professionale per {{company_name}}',
        'default_author' => '{{team_name}}',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
        'default_repository' => '{{repository_url}}'],
    'webhooks' => [
        'notification_delivered' => '{{webhook_base}}/webhooks/notification-delivered',
        'notification_bounced' => '{{webhook_base}}/webhooks/notification-bounced',
        'notification_clicked' => '{{webhook_base}}/webhooks/notification-clicked'],
<<<<<<< HEAD
=======
        'default_repository' => '{{repository_url}}',
    ],
    'webhooks' => [
        'notification_delivered' => '{{webhook_base}}/webhooks/notification-delivered',
        'notification_bounced' => '{{webhook_base}}/webhooks/notification-bounced',
        'notification_clicked' => '{{webhook_base}}/webhooks/notification-clicked',
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
    'email' => [
        'default_from_address' => 'noreply@example.com',
        'default_from_name' => '{{company_name}}',
        'default_admin_email' => 'admin@{{company_name}}.com',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
        'default_developer_email' => 'developer@{{company_name}}.com'],
    'paths' => [
        'default_avatar_path' => '/images/avatars/default.svg',
        'default_image_path' => '/images/default.jpg'],
<<<<<<< HEAD
=======
        'default_developer_email' => 'developer@{{company_name}}.com',
    ],
    'paths' => [
        'default_avatar_path' => '/images/avatars/default.svg',
        'default_image_path' => '/images/default.jpg',
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
    'template_variables' => [
        'company_name' => '{{company_name}}',
        'team_name' => '{{team_name}}',
        'clinic_name' => '{{clinic_name}}',
        'webhook_base' => '{{webhook_base}}',
        'repository_url' => '{{repository_url}}',
        'user_name' => '{{user_name}}',
        'appointment_date' => '{{appointment_date}}',
<<<<<<< HEAD
<<<<<<< HEAD
        'appointment_time' => '{{appointment_time}}']];
=======
        'appointment_time' => '{{appointment_time}}',
    ],
];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'appointment_time' => '{{appointment_time}}']];
>>>>>>> a988596b (first)
