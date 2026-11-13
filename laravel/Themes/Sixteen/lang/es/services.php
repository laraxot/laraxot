<?php

declare(strict_types=1);

return [
    'meta' => ['title' => 'Servicios para tu ciudad', 'description' => 'Informa de un problema, consulta avisos públicos o sigue una solicitud.', 'breadcrumb' => 'Servicios'],
    'hero' => ['title' => 'Servicios para tu ciudad', 'subtitle' => 'Envía una incidencia, consulta las incidencias públicas o sigue una solicitud con su código de seguimiento.', 'search_label' => 'Buscar tareas disponibles', 'search_placeholder' => 'Buscar tareas…'],
    'nav' => ['aria' => 'Tareas disponibles', 'report' => 'Enviar una incidencia', 'browse' => 'Consultar incidencias', 'track' => 'Seguir una solicitud'],
    'featured' => ['title' => 'Qué puedes hacer', 'subtitle' => 'Elige una tarea para abrir un recorrido disponible en Fixcity.'],
    'card' => [
        'featured' => 'Destacado',
        'access' => 'Abrir servicio',
        'access_aria' => 'Abrir servicio: :title',
        'status' => ['active' => 'Disponible', 'inactive' => 'No disponible', 'maintenance' => 'En mantenimiento'],
    ],
    'tasks' => [
        'report_title' => 'Informar de un problema', 'report_body' => 'Describe un problema en un espacio público y envíalo a la oficina responsable.', 'report_auth_note' => 'Inicia sesión para enviar una incidencia.', 'report_action' => 'Enviar una incidencia',
        'browse_title' => 'Explorar el mapa y la lista', 'browse_body' => 'Consulta las incidencias públicas y las intervenciones en curso en la ciudad.', 'browse_action' => 'Abrir incidencias',
        'track_title' => 'Seguir una incidencia', 'track_body' => 'Consulta las actualizaciones con el código de seguimiento recibido tras el envío.', 'track_action' => 'Abrir seguimiento',
    ],
    'results' => ['singular' => 'tarea disponible', 'plural' => 'tareas disponibles', 'empty' => 'Ninguna tarea coincide con la búsqueda. Prueba con otras palabras.'],
    'categories' => ['title' => 'Categorías de incidencias', 'subtitle' => 'Elige el tipo de problema que quieres comunicar o consulta las incidencias publicadas.', 'actions' => ['aria' => 'Acciones sobre incidencias', 'report' => 'Comunicar un problema', 'browse' => 'Consultar incidencias'], 'directory' => ['title' => 'Explora por categoría', 'subcategories' => 'Subcategorías', 'empty' => 'Las categorías aún no están disponibles. Puedes consultar las incidencias públicas.'], 'items' => ['reports' => ['title' => 'Incidencias públicas', 'description' => 'Consulta las incidencias publicadas por el municipio y las actualizaciones de las intervenciones.']]],
];
