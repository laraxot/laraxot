<?php

declare(strict_types=1);

return [
    'fields' => [
        'employee_id' => [
            'label' => 'Empleado',
        ],
    ],
    'pages' => [
        'timeclock' => [
            'heading' => 'Control de fichaje',
        ],
        'dashboard' => [
            'title' => 'Panel de horas de trabajo',
        ],
    ],
    'timeclock' => [
        'notes' => [
            'label' => 'Notas (opcional)',
            'placeholder' => 'Añade una nota a este registro...',
        ],
    ],
    'summary' => [
        'last_entry' => 'Último registro: :type a las :time',
        'todays_summary' => 'Resumen de hoy',
        'total_hours_worked' => 'Total de horas trabajadas',
        'current_status' => 'Estado actual',
        'total_entries' => 'Total de registros',
        'todays_entries' => 'Registros de hoy',
    ],
    'messages' => [
        'empty_states' => [
            'no_entries_today' => 'Hoy no hay registros',
        ],
    ],
    'quick_actions' => [
        'title' => 'Acciones rápidas',
        'view_all' => 'Ver todos los registros',
        'refresh_data' => 'Actualizar datos',
        'time_clock' => 'Control de fichaje',
    ],
    'actions' => [
        'refresh' => [
            'label' => 'Actualizar',
        ],
    ],
    'dashboard' => [
        'overview' => [
            'label' => 'Resumen',
        ],
        'period_month' => [
            'label' => 'Este mes',
        ],
        'progress_of_target' => [
            'label' => ':percentage% del objetivo de :target',
        ],
        'week_number' => [
            'label' => 'Semana :number',
        ],
    ],
    'tabs' => [
        'this_week' => 'Esta semana',
    ],
    'widgets' => [
        'stats' => [
            'today' => [
                'label' => 'Hoy',
            ],
            'this_week' => [
                'label' => 'Esta semana',
            ],
            'avg_day' => [
                'label' => 'Prom./día',
            ],
            'work_days' => [
                'label' => 'Días laborables',
            ],
        ],
        'progress' => [
            'weekly_progress' => 'Progreso semanal',
        ],
        'breakdown' => [
            'daily_breakdown' => 'Desglose diario',
            'weekly_breakdown' => 'Desglose semanal',
        ],
        'recent_entries' => [
            'title' => 'Registros recientes',
            'empty_state' => 'Sin registros recientes',
        ],
    ],
];
