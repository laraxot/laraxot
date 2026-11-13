<?php

declare(strict_types=1);

return [
    'pending_requests' => [
        'title' => 'Mis solicitudes pendientes',
        'days_pending' => 'días en espera',
        'view' => 'Ver',
        'cancel' => 'Cancelar',
        'view_all' => 'Ver todas las solicitudes',
        'empty_state' => [
            'title' => '¡Todo gestionado!',
            'description' => 'El administrador ha gestionado todas tus solicitudes.',
        ],
        'new_request' => 'Nueva solicitud',
    ],
    'time_off_balance' => [
        'title' => 'Mis saldos',
        'description' => 'Saldos de vacaciones y permisos de :month',
        'view_period' => 'Periodo',
        'monthly' => 'Mensual',
        'annual' => 'Anual',
        'current_balance' => 'Saldo actual',
        'used' => 'Utilizado',
        'total' => 'Total',
        'no_limit' => 'Sin límite',
        'negative_balance' => 'Saldo negativo',
        'exhausted' => 'Agotado',
        'last_updated' => 'Última actualización',
        'view_details' => 'Ver detalles',
    ],
    'today_presence' => [
        'title' => 'Quién está hoy',
        'description' => 'Presencias en tiempo real',
        'present' => 'Presentes',
        'absent' => 'Ausentes',
        'present_employees' => 'Empleados presentes',
        'absent_employees' => 'Empleados ausentes',
        'return_date' => 'Regreso',
        'last_updated' => 'Actualizado a las',
        'view_details' => 'Ver detalle',
    ],
    'todo' => [
        'title' => 'Tareas pendientes',
        'description' => 'Actividades de RR. HH. que requieren tu atención',
        'due_date' => 'Vencimiento',
        'action_button' => 'Ir',
        'empty_state' => [
            'title' => '¡Todo completado!',
            'description' => 'No tienes actividades pendientes en este momento.',
        ],
        'view_all' => 'Ver todas las actividades',
    ],
    'upcoming_schedule' => [
        'title' => 'Próximos 7 días',
        'description' => 'Eventos y citas programados',
        'tabs' => [
            'all' => 'Todos',
            'absences' => 'Ausencias',
            'smart_working' => 'Smart Working',
            'transfers' => 'Desplazamientos',
        ],
        'days' => 'días',
        'empty_state' => [
            'title' => 'Ningún evento programado',
            'description' => 'No hay eventos en los próximos 7 días.',
        ],
        'view_all' => 'Ver presencias',
    ],
];
