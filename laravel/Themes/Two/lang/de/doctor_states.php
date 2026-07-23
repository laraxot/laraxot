<?php

declare(strict_types=1);

return [
    'active' => [
        'label' => 'Aktiv',
        'color' => 'success',
        'description' => 'Benutzer ist aktiv und funktionsfähig im System',
        'icon' => 'heroicon-o-check-circle',
        'hex_color' => '#10b981',
    ],
    'inactive' => [
        'label' => 'Inaktiv',
        'color' => 'danger',
        'description' => 'Benutzer ist nicht aktiv, Zugang deaktiviert',
        'icon' => 'heroicon-o-x-circle',
        'hex_color' => '#ef4444',
    ],
    'pending' => [
        'label' => 'Ausstehend',
        'color' => 'warning',
        'description' => 'Registrierung wartet auf Genehmigung',
        'icon' => 'heroicon-o-clock',
        'hex_color' => '#f59e0b',
    ],
    'rejected' => [
        'label' => 'Abgelehnt',
        'color' => 'danger',
        'description' => 'Registrierung vom System abgelehnt',
        'icon' => 'heroicon-o-x-circle',
        'hex_color' => '#dc2626',
    ],
    'integration_requested' => [
        'label' => 'Integration angefordert',
        'color' => 'warning',
        'description' => 'Integrationsanfrage wartet auf Bearbeitung',
        'icon' => 'heroicon-o-arrow-path',
        'hex_color' => '#d97706',
    ],
    'integration_approved' => [
        'label' => 'Integration genehmigt',
        'color' => 'success',
        'description' => 'Integrationsanfrage wurde genehmigt',
        'icon' => 'heroicon-o-check-circle',
        'hex_color' => '#059669',
    ],
    'integration_rejected' => [
        'label' => 'Integration abgelehnt',
        'color' => 'danger',
        'description' => 'Integrationsanfrage wurde abgelehnt',
        'icon' => 'heroicon-o-x-circle',
        'hex_color' => '#dc2626',
    ],
    'integration_pending' => [
        'label' => 'Integration ausstehend',
        'color' => 'warning',
        'description' => 'Integration wartet auf Bearbeitung',
        'icon' => 'heroicon-o-hourglass',
        'hex_color' => '#d97706',
    ],
    'integration_completed' => [
        'label' => 'Integration abgeschlossen',
        'color' => 'success',
        'description' => 'Integrationsprozess erfolgreich abgeschlossen',
        'icon' => 'heroicon-o-check-circle',
        'hex_color' => '#10b981',
    ],
    'integration_cancelled' => [
        'label' => 'Integration storniert',
        'color' => 'danger',
        'description' => 'Integrationsprozess wurde storniert',
        'icon' => 'heroicon-o-no-symbol',
        'hex_color' => '#ef4444',
    ],
    'suspended' => [
        'label' => 'Gesperrt',
        'color' => 'danger',
        'description' => 'Benutzer vorübergehend vom System gesperrt',
        'icon' => 'heroicon-o-pause-circle',
        'hex_color' => '#dc2626',
    ],
];
