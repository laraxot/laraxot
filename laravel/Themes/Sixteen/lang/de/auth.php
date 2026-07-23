<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines - Sixteen Theme
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    // Allgemeine Authentifizierungsnachrichten
    'failed' => 'Diese Anmeldedaten stimmen nicht mit unseren Aufzeichnungen überein.',
    'password' => 'Das angegebene Passwort ist falsch.',
    'throttle' => 'Zu viele Anmeldeversuche. Bitte versuchen Sie es in :seconds Sekunden erneut.',
    'general_error' => 'Ein Fehler ist aufgetreten. Bitte versuchen Sie es später erneut.',
    'unauthorized' => 'Sie haben nicht die erforderlichen Berechtigungen für diese Operation.',

    // Anmeldung
    'login' => [
        'title' => 'Anmelden',
        'email' => 'E-Mail',
        'password' => 'Passwort',
        'remember_me' => 'Angemeldet bleiben',
        'forgot_password' => 'Passwort vergessen?',
        'submit' => 'Anmelden',
        'or' => 'oder',
        'create_account' => 'ein neues Konto erstellen',
        'link' => 'Anmelden',
        'success' => 'Anmeldung erfolgreich',
        'error' => 'Anmeldefehler',
        'error_message' => 'Bei der Anmeldung ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut.',
        'validation_error' => 'Validierungsfehler',
        'invalid_credentials' => 'Die angegebenen Anmeldedaten sind falsch.',
    ],

    // Registrierung
    'register' => [
        'title' => 'Registrieren',
        'name' => 'Name',
        'email' => 'E-Mail',
        'password' => 'Passwort',
        'password_confirmation' => 'Passwort bestätigen',
        'submit' => 'Registrieren',
        'already_registered' => 'Haben Sie bereits ein Konto?',
        'link' => 'Registrieren',
    ],

    // Abmeldung
    'logout' => [
        'title' => 'Abmelden',
        'confirm_message' => 'Sind Sie sicher, dass Sie sich abmelden möchten?',
        'confirm_button' => 'Abmeldung bestätigen',
        'cancel_button' => 'Abbrechen',
        'success_title' => 'Abgemeldet',
        'success_message' => 'Sie wurden erfolgreich abgemeldet.',
        'error_title' => 'Abmeldefehler',
        'error_message' => 'Bei der Abmeldung ist ein Fehler aufgetreten.',
        'try_again' => 'Erneut versuchen',
        'back_to_home' => 'Zurück zur Startseite',
    ],

    // Profil
    'profile' => [
        'title' => 'Profil',
        'settings' => 'Einstellungen',
        'information' => 'Profilinformationen',
        'update_password' => 'Passwort aktualisieren',
        'current_password' => 'Aktuelles Passwort',
        'new_password' => 'Neues Passwort',
        'confirm_password' => 'Passwort bestätigen',
        'save' => 'Speichern',
        'update' => 'Aktualisieren',
    ],

    // Passwort zurücksetzen
    'reset' => [
        'title' => 'Passwort zurücksetzen',
        'email' => 'E-Mail',
        'password' => 'Neues Passwort',
        'password_confirmation' => 'Passwort bestätigen',
        'submit' => 'Passwort zurücksetzen',
        'link_sent' => 'Wir haben Ihnen den Link zum Zurücksetzen des Passworts per E-Mail gesendet!',
        'reset_link' => 'Passwort zurücksetzen',
    ],

    // E-Mail-Verifizierung
    'verify' => [
        'title' => 'E-Mail-Adresse bestätigen',
        'message' => 'Vielen Dank für die Registrierung! Bevor Sie beginnen können, könnten Sie Ihre E-Mail-Adresse bestätigen, indem Sie auf den Link klicken, den wir Ihnen gerade per E-Mail gesendet haben?',
        'resend' => 'Wenn Sie die E-Mail nicht erhalten haben, können wir sie erneut senden.',
        'submit' => 'Bestätigungs-E-Mail erneut senden',
    ],

    // Navigation
    'navigation' => [
        'open_menu' => 'Hauptmenü öffnen',
        'close_menu' => 'Hauptmenü schließen',
        'home' => 'Startseite',
        'dashboard' => 'Dashboard',
        'profile' => 'Profil',
        'settings' => 'Einstellungen',
    ],

    // Benutzer-Dropdown
    'user_dropdown' => [
        'manage_account' => 'Konto verwalten',
        'profile' => 'Profil',
        'settings' => 'Einstellungen',
        'logout' => 'Abmelden',
        'login_link' => 'Anmelden',
        'register_link' => 'Registrieren',
    ],

    // Benachrichtigungsnachrichten
    'notifications' => [
        'login_success' => 'Anmeldung erfolgreich',
        'login_error' => 'Anmeldefehler',
        'logout_success' => 'Abmeldung erfolgreich',
        'logout_error' => 'Abmeldefehler',
        'validation_error' => 'Validierungsfehler',
        'general_error' => 'Ein Fehler ist aufgetreten. Bitte versuchen Sie es später erneut.',
    ],
];
