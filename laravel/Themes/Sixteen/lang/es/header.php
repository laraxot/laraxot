<?php

declare(strict_types=1);

/** @var array<string, mixed> $base */
$base = require __DIR__.'/../en/header.php';

return array_replace_recursive($base, [
    'guest' => ['login' => ['label' => 'Acceder'], 'register' => ['label' => 'Registrarse'], 'cta' => ['label' => 'Área personal', 'help' => 'Acceder al área personal.']],
    'center' => ['brand' => ['title' => ['label' => 'Mi Ayuntamiento'], 'tagline' => ['label' => 'Una ciudad para vivir']], 'search' => ['label' => 'Buscar', 'toggle_aria' => ['label' => 'Buscar en el sitio']], 'nav' => ['toggle_aria' => ['label' => 'Mostrar u ocultar la navegación'], 'close_aria' => ['label' => 'Cerrar navegación'], 'primary_aria' => ['label' => 'Navegación principal'], 'secondary_aria' => ['label' => 'Navegación secundaria'], 'amministrazione' => ['label' => 'Administración'], 'novita' => ['label' => 'Noticias'], 'servizi' => ['label' => 'Servicios'], 'argomenti' => ['label' => 'Todos los temas']]],
    'language' => ['active_prefix' => ['label' => 'Idioma actual:'], 'code_it' => ['label' => 'ITA'], 'code_en' => ['label' => 'ENG'], 'selected_suffix' => ['label' => 'seleccionado']],
    // DCP-106: menu utente dell'header (area personale): senza questa sezione le lingue de/es ricadevano sull'inglese.
    'user' => [
        'unread_notifications' => 'notificaciones no leídas',
        'personal_area' => 'Área personal',
        'aria' => ['toggle_menu' => ['label' => 'Abrir menú de usuario']],
        'dropdown' => [
            // chiavi lette da partials/user-dropdown.blade.php: senza di loro il menu ricade sull'inglese
            'my_services' => ['label' => 'Mis servicios'],
            'my_practices' => ['label' => 'Mis trámites'],
            'notifications' => ['label' => 'Notificaciones'],
            'settings' => ['label' => 'Ajustes'],
            'my_reports' => ['label' => 'Mis incidencias'],
            'followed_reports' => ['label' => 'Incidencias que sigo'],
            'notification_center' => ['label' => 'Notificaciones'],
            'settings_privacy' => ['label' => 'Ajustes y privacidad'],
            'logout' => ['label' => 'Cerrar sesión'],
        ],
    ],
]);
