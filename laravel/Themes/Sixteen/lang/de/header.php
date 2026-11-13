<?php

declare(strict_types=1);

/** @var array<string, mixed> $base */
$base = require __DIR__.'/../en/header.php';

return array_replace_recursive($base, [
    'guest' => ['login' => ['label' => 'Anmelden'], 'register' => ['label' => 'Registrieren'], 'cta' => ['label' => 'Persönlicher Bereich', 'help' => 'Zum persönlichen Bereich.']],
    'center' => ['brand' => ['title' => ['label' => 'Meine Gemeinde'], 'tagline' => ['label' => 'Eine Gemeinde zum Leben']], 'search' => ['label' => 'Suchen', 'toggle_aria' => ['label' => 'Website durchsuchen']], 'nav' => ['toggle_aria' => ['label' => 'Navigation anzeigen oder ausblenden'], 'close_aria' => ['label' => 'Navigation schließen'], 'primary_aria' => ['label' => 'Hauptnavigation'], 'secondary_aria' => ['label' => 'Sekundärnavigation'], 'amministrazione' => ['label' => 'Verwaltung'], 'novita' => ['label' => 'Nachrichten'], 'servizi' => ['label' => 'Dienste'], 'argomenti' => ['label' => 'Alle Themen']]],
    'language' => ['active_prefix' => ['label' => 'Aktuelle Sprache:'], 'code_it' => ['label' => 'ITA'], 'code_en' => ['label' => 'ENG'], 'selected_suffix' => ['label' => 'ausgewählt']],
    // DCP-106: menu utente dell'header (area personale): senza questa sezione le lingue de/es ricadevano sull'inglese.
    'user' => [
        'unread_notifications' => 'ungelesene Benachrichtigungen',
        'personal_area' => 'Persönlicher Bereich',
        'aria' => ['toggle_menu' => ['label' => 'Benutzermenü öffnen']],
        'dropdown' => [
            // chiavi lette da partials/user-dropdown.blade.php: senza di loro il menu ricade sull'inglese
            'my_services' => ['label' => 'Meine Dienste'],
            'my_practices' => ['label' => 'Meine Vorgänge'],
            'notifications' => ['label' => 'Benachrichtigungen'],
            'settings' => ['label' => 'Einstellungen'],
            'my_reports' => ['label' => 'Meine Meldungen'],
            'followed_reports' => ['label' => 'Meldungen, denen ich folge'],
            'notification_center' => ['label' => 'Benachrichtigungen'],
            'settings_privacy' => ['label' => 'Einstellungen und Datenschutz'],
            'logout' => ['label' => 'Abmelden'],
        ],
    ],
]);
