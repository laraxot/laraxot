<?php

declare(strict_types=1);

return [
    'meta' => ['title' => 'Preguntas frecuentes', 'description' => 'Respuestas sobre el servicio de avisos de FixCity.', 'breadcrumb' => 'Preguntas frecuentes'],
    'intro' => 'Información práctica para enviar un aviso, consultar las novedades y proteger tus datos.',
    'items' => [
        ['question' => '¿Cómo envío un aviso?', 'answer' => 'Inicia sesión en tu cuenta, abre el servicio de avisos y completa los pasos requeridos. Puedes revisar el resumen antes de enviarlo.'],
        ['question' => '¿Necesito una cuenta para enviar un aviso?', 'answer' => 'Sí. Si no has iniciado sesión, el sitio te lleva a la página de acceso antes de continuar.'],
        ['question' => '¿Cómo puedo consultar los avisos?', 'answer' => 'Puedes explorar la lista y el mapa de los avisos publicados por el municipio. Algunos detalles solo están disponibles para la persona titular o el personal autorizado.'],
        ['question' => '¿Cómo puedo hacer seguimiento de un trámite?', 'answer' => 'Introduce en la página de seguimiento el código recibido después del envío. También encontrarás los trámites vinculados a tu cuenta en el área personal.'],
        ['question' => '¿Dónde encuentro información sobre el tratamiento de datos?', 'answer' => 'Consulta la política de privacidad publicada para este servicio. Si no está disponible, el formulario no permite enviar el aviso.'],
    ],
    'links' => ['service' => 'Abrir el servicio de avisos', 'reports' => 'Consultar los avisos', 'tracking' => 'Abrir seguimiento', 'privacy' => 'Leer la política de privacidad'],
    'navigation' => ['label' => 'Páginas relacionadas', 'home' => 'Volver al inicio'],
    'search' => ['label' => 'Buscar preguntas', 'placeholder' => 'Buscar', 'submit' => 'Buscar', 'empty' => 'No hay preguntas que coincidan con la búsqueda.', 'results' => 'Preguntas encontradas: :count', 'load_more' => 'Cargar más preguntas'],
    'feedback' => ['title' => '¿Qué claridad tienen las informaciones de esta página?', 'rating_label' => 'Valora la claridad de esta página de una a cinco estrellas', 'rate' => 'Valora', 'out_of' => 'estrellas de 5', 'next' => 'Siguiente', 'back' => 'Atrás', 'positive_title' => '¿Qué aspectos te han resultado más útiles?', 'negative_title' => '¿Dónde has encontrado más dificultades?', 'positive_options' => ['Las indicaciones eran claras', 'Las indicaciones eran completas', 'Siempre sabía cómo continuar', 'Otro'], 'negative_options' => ['Las indicaciones no eran claras', 'Las indicaciones no eran completas', 'No siempre sabía cómo continuar', 'Otro'], 'details_title' => '¿Quieres añadir más detalles?', 'details_label' => 'Detalle', 'thanks' => 'Gracias. Tu opinión nos ayudará a mejorar el servicio.'],
    'contacts' => ['aria_label' => 'Opciones de contacto', 'title' => 'Contacta con el municipio', 'city_title' => 'Problemas en la ciudad', 'report' => 'Enviar un aviso', 'track' => 'Seguir un aviso', 'login' => 'Acceder al área personal', 'city_report' => 'Comunicar un problema'],
];
