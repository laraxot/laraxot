<?php

declare(strict_types=1);

return [
    'resource' => ['name' => 'Invio Notifica Push'],
    'navigation' => [
        'name' => 'Invio Notifica Push',
        'plural' => 'Invio Notifiche Push',
        'group' => ['name' => 'Sistema', 'description' => 'Funzionalità per l\'invio di notifiche push tramite Firebase'],
        'label' => 'Invio Notifiche Push',
        'icon' => 'notify-push-animated',
        'sort' => 51],
    'fields' => [
        'device_token' => ['label' => 'Token Dispositivo', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'type' => [
            'label' => 'Tipo',
            'options' => ['notification' => 'Notifica', 'data' => 'Dati', 'both' => 'Entrambi'],
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
            'placeholder' => 'type'],
        'title' => ['label' => 'Titolo', 'tooltip' => '', 'helper_text' => '', 'description' => '', 'placeholder' => 'title'],
        'body' => ['label' => 'Contenuto', 'tooltip' => '', 'helper_text' => '', 'description' => '', 'placeholder' => 'body'],
        'data' => ['label' => 'Dati Aggiuntivi', 'description' => 'Dati in formato JSON da inviare con la notifica', 'tooltip' => '', 'helper_text' => '', 'placeholder' => 'data'],
        'deviceToken' => ['label' => 'deviceToken', 'placeholder' => 'deviceToken', 'helper_text' => 'deviceToken', 'description' => 'deviceToken'],
        'name' => ['label' => 'name', 'placeholder' => 'name', 'helper_text' => 'name', 'description' => 'name'],
        'value' => ['label' => 'value', 'placeholder' => 'value', 'helper_text' => 'value', 'description' => 'value']],
    'actions' => [
        'send' => ['label' => 'Invia Notifica', 'success' => 'Notifica push inviata con successo', 'error' => 'Errore durante l\'invio della notifica push'],
        'preview' => ['label' => 'Anteprima'],
        'save' => ['label' => 'save', 'icon' => 'save', 'tooltip' => 'save'],
        'notificationFormActions' => ['label' => 'notificationFormActions', 'icon' => 'notificationFormActions', 'tooltip' => 'notificationFormActions']],
    'label' => 'Send Push Notification',
    'plural_label' => 'Send Push Notification (Plurale)'];
