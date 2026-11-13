<?php

declare(strict_types=1);

/**
 * Caller: Themes/Sixteen/resources/views/pages/auth/logout.blade.php
 * (__('pub_theme::auth.logout.*')). Nessun lang/es/auth.php esisteva
 * (it/en/de sì). Schema: array Laravel lang sotto namespace pub_theme.
 * User: «ricordati che il sito e' in multilingua e le chiavi delle
 * traduzioni seguono delle convenzioni».
 */
/** @var array<string, mixed> $base */
$base = require __DIR__.'/../en/auth.php';

return array_replace_recursive($base, [
    'logout' => [
        'title' => 'Cerrar sesión',
        'confirm_message' => '¿Seguro que quieres cerrar la sesión?',
        'confirm_button' => 'Confirmar cierre de sesión',
        'cancel_button' => 'Cancelar',
        'success_title' => 'Sesión cerrada',
        'success_message' => 'Has cerrado la sesión correctamente.',
        'error_title' => 'Error al cerrar sesión',
        'error_message' => 'Se ha producido un error al cerrar la sesión.',
        'try_again' => 'Reintentar',
        'back_to_home' => 'Volver al inicio',
    ],
]);
