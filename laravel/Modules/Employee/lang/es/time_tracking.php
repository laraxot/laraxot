<?php

declare(strict_types=1);

return [
    'tracker' => [
        'status' => [
            'active' => 'Sesión activa',
            'completed' => 'Jornada completada',
            'waiting' => 'A la espera de fichaje',
        ],
        'no_entries_today' => [
            'message' => 'Ningún fichaje hoy',
        ],
        'end_session' => [
            'label' => 'Finalizar jornada',
        ],
        'start_session' => [
            'label' => 'Iniciar jornada',
        ],
        'entries_today' => [
            'description' => '{1} :count fichaje hoy|[2,*] :count fichajes hoy',
        ],
    ],
    'card' => [
        'hours_worked_today' => [
            'label' => 'Horas trabajadas hoy',
        ],
        'last_entry' => [
            'label' => 'Último fichaje',
        ],
        'hours_today' => [
            'label' => 'Horas de hoy',
        ],
        'week' => [
            'label' => 'Semana',
        ],
        'month' => [
            'label' => 'Mes',
        ],
        'auto_refresh' => [
            'label' => 'Se actualiza automáticamente cada 30 segundos',
        ],
    ],
];
