<?php

declare(strict_types=1);

return [
    'meta' => ['title' => 'Noticias y comunicados', 'description' => 'Noticias, comunicados y avisos del Ayuntamiento.', 'breadcrumb' => 'Noticias'],
    'hero' => ['title' => 'Noticias y comunicados', 'subtitle' => 'Actualizaciones oficiales e información útil de la ciudad.'],
    'filters' => ['aria' => 'Filtros de noticias', 'all' => 'Todas las noticias', 'press' => 'Comunicados', 'notices' => 'Avisos'],
    'latest' => ['title' => 'Últimas noticias', 'subtitle' => 'Mantente al día de las novedades municipales.'],
    'search' => ['label' => 'Buscar noticias', 'placeholder' => 'Buscar noticias'],
    'read_more' => 'Leer más',
    'index' => [
        'default_category' => 'Noticias',
        'default_event_category' => 'Eventos',
        'search_label' => 'Buscar',
        'search_placeholder' => 'Buscar por palabra clave',
        'search_submit' => 'Enviar',
        'result_one' => '1 noticia encontrada',
        'result_many' => ':count noticias encontradas',
        'feedback' => [
            'title' => '¿Qué tan clara es la información de esta página?',
            'legend' => 'Valora esta página de 1 a 5 estrellas',
            'star_label' => 'Valora con %d de 5 estrellas',
            'thanks' => 'Gracias. Tu opinión nos ayudará a mejorar este servicio.',
            'positive' => [
                'question' => '¿Qué aspectos te han gustado más?',
                'answers' => [
                    'clear' => 'Las indicaciones eran claras',
                    'complete' => 'Las indicaciones eran completas',
                    'progress' => 'Siempre sabía si estaba siguiendo los pasos correctamente',
                    'technical' => 'No tuve problemas técnicos',
                    'other' => 'Otro',
                ],
            ],
            'negative' => [
                'question' => '¿Dónde encontraste más dificultades?',
                'answers' => [
                    'clear' => 'A veces las indicaciones no eran claras',
                    'complete' => 'A veces las indicaciones estaban incompletas',
                    'progress' => 'A veces no sabía si estaba siguiendo los pasos correctamente',
                    'technical' => 'Tuve problemas técnicos',
                    'other' => 'Otro',
                ],
            ],
            'detail' => [
                'question' => '¿Quieres añadir más detalles?',
                'label' => 'Detalles',
                'help' => 'Introduce un máximo de 200 caracteres',
            ],
            'back' => 'Volver',
            'next' => 'Siguiente',
        ],
    ],
    'items' => [
        'one' => ['category' => 'Comunicado', 'date_iso' => '2026-09-01', 'date' => '1 de septiembre de 2026', 'title' => 'Nuevos servicios digitales para la ciudadanía', 'body' => 'Ya están disponibles nuevos trámites digitales municipales.'],
        'two' => ['category' => 'Aviso', 'date_iso' => '2026-08-20', 'date' => '20 de agosto de 2026', 'title' => 'Actualización de los servicios de la ciudad', 'body' => 'Consulta las novedades operativas para la comunidad.'],
        'three' => ['category' => 'Evento', 'date_iso' => '2026-08-05', 'date' => '5 de agosto de 2026', 'title' => 'Actividades abiertas a la ciudadanía', 'body' => 'Descubre las próximas iniciativas de la ciudad.'],
    ],
];
