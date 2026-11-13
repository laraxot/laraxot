<?php

declare(strict_types=1);

return [
    'profile' => [
        'page_title' => [
            'title' => 'Perfil',
        ],
        'information' => [
            'title' => 'Información del perfil',
            'help' => 'Actualiza la información de perfil y la dirección de correo electrónico de tu cuenta.',
        ],
        'photo' => [
            'label' => 'Foto',
        ],
        'photo_select' => [
            'label' => 'Seleccionar una nueva foto',
        ],
        'photo_remove' => [
            'label' => 'Eliminar foto',
        ],
        'email_unverified' => [
            'message' => 'Tu dirección de correo electrónico no está verificada.',
        ],
        'email_resend' => [
            'label' => 'Haz clic aquí para reenviar el correo de verificación.',
        ],
        'email_resent' => [
            'message' => 'Se ha enviado un nuevo enlace de verificación a tu dirección de correo electrónico.',
        ],
    ],
    'password' => [
        'update' => [
            'title' => 'Actualizar contraseña',
            'help' => 'Asegúrate de que tu cuenta utilice una contraseña larga y aleatoria para mantenerse segura.',
        ],
        'current' => [
            'label' => 'Contraseña actual',
        ],
        'new' => [
            'label' => 'Nueva contraseña',
        ],
        'confirm' => [
            'label' => 'Confirmar contraseña',
        ],
    ],
    'common' => [
        'password' => [
            'label' => 'Contraseña',
            'placeholder' => 'Contraseña',
        ],
        'cancel' => [
            'label' => 'Cancelar',
        ],
        'save' => [
            'label' => 'Guardar',
        ],
        'saved' => [
            'message' => 'Guardado.',
        ],
        'done' => [
            'message' => 'Hecho.',
        ],
        'confirm' => [
            'label' => 'Confirmar',
        ],
        'create' => [
            'label' => 'Crear',
        ],
        'email' => [
            'label' => 'Correo electrónico',
        ],
        'name' => [
            'label' => 'Nombre',
        ],
        'update' => [
            'label' => 'Actualizar',
        ],
    ],
    'delete_account' => [
        'action' => [
            'label' => 'Eliminar cuenta',
        ],
        'summary' => [
            'help' => 'Elimina tu cuenta de forma permanente.',
        ],
        'warning' => [
            'help' => 'Una vez que se elimina tu cuenta, todos sus recursos y datos se eliminan de forma permanente. Antes de eliminar tu cuenta, descarga los datos o la información que desees conservar.',
        ],
        'confirm' => [
            'title' => '¿Seguro que quieres eliminar tu cuenta?',
        ],
        'confirm_full' => [
            'message' => '¿Seguro que quieres eliminar tu cuenta? Una vez que se elimina tu cuenta, todos sus recursos y datos se eliminan de forma permanente. Introduce tu contraseña para confirmar que quieres eliminar tu cuenta de forma permanente.',
        ],
        'confirm_password' => [
            'message' => 'Una vez que se elimina tu cuenta, todos sus recursos y datos se eliminan de forma permanente. Introduce tu contraseña para confirmar que quieres eliminar tu cuenta de forma permanente.',
        ],
        'legacy_warning' => [
            'help' => 'Después de eliminar tu cuenta, todos los datos y recursos se eliminan de forma permanente. Introduce tu contraseña para confirmar la eliminación.',
        ],
    ],
    'browser_sessions' => [
        'list' => [
            'title' => 'Sesiones del navegador',
            'help' => 'Administra y cierra tus sesiones activas en otros navegadores y dispositivos.',
        ],
        'description' => [
            'help' => 'Si es necesario, puedes cerrar todas tus otras sesiones del navegador en todos tus dispositivos. Algunas de tus sesiones recientes se enumeran a continuación; sin embargo, esta lista puede no ser exhaustiva. Si crees que tu cuenta ha sido comprometida, también deberías actualizar tu contraseña.',
        ],
        'unknown' => [
            'label' => 'Desconocido',
        ],
        'this_device' => [
            'label' => 'Este dispositivo',
        ],
        'last_active' => [
            'label' => 'Última actividad',
        ],
        'log_out_others' => [
            'label' => 'Cerrar las otras sesiones del navegador',
        ],
        'confirm' => [
            'message' => 'Introduce tu contraseña para confirmar que deseas cerrar tus otras sesiones del navegador en todos tus dispositivos.',
        ],
    ],
    'two_factor' => [
        'setup' => [
            'title' => 'Autenticación de dos factores',
            'help' => 'Añade seguridad adicional a tu cuenta mediante la autenticación de dos factores.',
        ],
        'state_finish' => [
            'title' => 'Termina de habilitar la autenticación de dos factores.',
        ],
        'state_enabled' => [
            'title' => 'Has habilitado la autenticación de dos factores.',
        ],
        'state_disabled' => [
            'title' => 'No has habilitado la autenticación de dos factores.',
        ],
        'intro' => [
            'help' => 'Cuando la autenticación de dos factores está habilitada, se te pedirá un token seguro y aleatorio durante el inicio de sesión. Puedes obtener este token desde la aplicación Google Authenticator de tu teléfono.',
        ],
        'scan_finish' => [
            'help' => 'Para terminar de habilitar la autenticación de dos factores, escanea el siguiente código QR con la aplicación de autenticación de tu teléfono o introduce la clave de configuración y el código OTP generado.',
        ],
        'scan_enabled' => [
            'help' => 'La autenticación de dos factores ya está habilitada. Escanea el siguiente código QR con la aplicación de autenticación de tu teléfono o introduce la clave de configuración.',
        ],
        'setup_key' => [
            'label' => 'Clave de configuración',
        ],
        'code' => [
            'label' => 'Código',
        ],
        'recovery_codes' => [
            'help' => 'Guarda estos códigos de recuperación en un gestor de contraseñas seguro. Pueden utilizarse para recuperar el acceso a tu cuenta si pierdes el dispositivo de autenticación de dos factores.',
        ],
        'enable' => [
            'label' => 'Habilitar',
        ],
        'recovery_regenerate' => [
            'label' => 'Regenerar códigos de recuperación',
        ],
        'recovery_show' => [
            'label' => 'Mostrar códigos de recuperación',
        ],
        'disable' => [
            'label' => 'Deshabilitar',
        ],
    ],
    'team' => [
        'settings' => [
            'title' => 'Ajustes del equipo',
        ],
        'create' => [
            'title' => 'Crear equipo',
            'help' => 'Crea un nuevo equipo para colaborar con otras personas en proyectos.',
        ],
        'details' => [
            'title' => 'Detalles del equipo',
        ],
        'owner' => [
            'label' => 'Propietario del equipo',
        ],
        'name' => [
            'label' => 'Nombre del equipo',
            'help' => 'El nombre del equipo y la información del propietario.',
        ],
        'delete' => [
            'label' => 'Eliminar equipo',
        ],
        'delete_summary' => [
            'help' => 'Elimina este equipo de forma permanente.',
        ],
        'delete_warning' => [
            'help' => 'Una vez que se elimina un equipo, todos sus recursos y datos se eliminan de forma permanente. Antes de eliminar este equipo, descarga los datos o la información sobre este equipo que desees conservar.',
        ],
        'delete_confirm' => [
            'message' => '¿Seguro que quieres eliminar este equipo? Una vez que se elimina un equipo, todos sus recursos y datos se eliminan de forma permanente.',
        ],
    ],
    'members' => [
        'add' => [
            'title' => 'Añadir miembro al equipo',
            'help' => 'Añade un nuevo miembro a tu equipo para que pueda colaborar contigo.',
        ],
        'add_email' => [
            'help' => 'Indica la dirección de correo electrónico de la persona que quieres añadir a este equipo.',
        ],
        'role' => [
            'label' => 'Rol',
        ],
        'added' => [
            'message' => 'Añadido.',
        ],
        'add_action' => [
            'label' => 'Añadir',
        ],
        'pending' => [
            'title' => 'Invitaciones pendientes al equipo',
            'help' => 'Estas personas han sido invitadas a tu equipo y han recibido un correo de invitación. Pueden unirse al equipo aceptando la invitación por correo electrónico.',
        ],
        'list' => [
            'title' => 'Miembros del equipo',
            'help' => 'Todas las personas que forman parte de este equipo.',
        ],
        'leave' => [
            'label' => 'Abandonar',
        ],
        'remove' => [
            'label' => 'Eliminar',
        ],
        'manage_role' => [
            'title' => 'Gestionar rol',
        ],
        'leave_team' => [
            'title' => 'Abandonar el equipo',
            'message' => '¿Seguro que quieres abandonar este equipo?',
        ],
        'remove_member' => [
            'title' => 'Eliminar miembro del equipo',
            'message' => '¿Seguro que quieres eliminar a esta persona del equipo?',
        ],
    ],
];
