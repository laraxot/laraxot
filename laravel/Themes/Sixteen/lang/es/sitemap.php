<?php

declare(strict_types=1);

return [
    'meta' => ['title' => 'Mapa del sitio', 'description' => 'Explora las páginas y los servicios públicos de FixCity.', 'breadcrumb' => 'Mapa del sitio'],
    'intro' => 'Encuentra rápidamente las páginas públicas y las tareas disponibles.',
    'groups' => [
        ['title' => 'Avisos', 'links' => [
            ['label' => 'Avisar un problema', 'path' => '/tickets/create'],
            ['label' => 'Lista y mapa de avisos', 'path' => '/tickets'],
            ['label' => 'Seguir un aviso', 'path' => '/tickets/track'],
            ['label' => 'Mis trámites', 'path' => '/area-personale/pratiche'],
        ]],
        ['title' => 'Información', 'links' => [
            ['label' => 'Preguntas frecuentes', 'path' => '/domande-frequenti'],
            ['label' => 'Privacidad', 'path' => '/privacy'],
            ['label' => 'Avisos legales', 'path' => '/note-legali'],
            ['label' => 'Política de cookies', 'path' => '/cookie-policy'],
            ['label' => 'Accesibilidad', 'path' => '/accessibilita'],
        ]],
        ['title' => 'Cuenta', 'links' => [
            ['label' => 'Iniciar sesión', 'path' => '/auth/login'],
            ['label' => 'Crear una cuenta', 'path' => '/auth/register'],
        ]],
    ],
];
