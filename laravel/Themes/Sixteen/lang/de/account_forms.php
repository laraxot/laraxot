<?php

declare(strict_types=1);

return [
    'profile' => [
        'page_title' => [
            'title' => 'Profil',
        ],
        'information' => [
            'title' => 'Profilinformationen',
            'help' => 'Aktualisieren Sie die Profilinformationen und die E-Mail-Adresse Ihres Kontos.',
        ],
        'photo' => [
            'label' => 'Foto',
        ],
        'photo_select' => [
            'label' => 'Neues Foto auswählen',
        ],
        'photo_remove' => [
            'label' => 'Foto entfernen',
        ],
        'email_unverified' => [
            'message' => 'Ihre E-Mail-Adresse ist nicht verifiziert.',
        ],
        'email_resend' => [
            'label' => 'Klicken Sie hier, um die Bestätigungs-E-Mail erneut zu senden.',
        ],
        'email_resent' => [
            'message' => 'Ein neuer Bestätigungslink wurde an Ihre E-Mail-Adresse gesendet.',
        ],
    ],
    'password' => [
        'update' => [
            'title' => 'Passwort aktualisieren',
            'help' => 'Stellen Sie sicher, dass Ihr Konto ein langes, zufälliges Passwort verwendet, um sicher zu bleiben.',
        ],
        'current' => [
            'label' => 'Aktuelles Passwort',
        ],
        'new' => [
            'label' => 'Neues Passwort',
        ],
        'confirm' => [
            'label' => 'Passwort bestätigen',
        ],
    ],
    'common' => [
        'password' => [
            'label' => 'Passwort',
            'placeholder' => 'Passwort',
        ],
        'cancel' => [
            'label' => 'Abbrechen',
        ],
        'save' => [
            'label' => 'Speichern',
        ],
        'saved' => [
            'message' => 'Gespeichert.',
        ],
        'done' => [
            'message' => 'Erledigt.',
        ],
        'confirm' => [
            'label' => 'Bestätigen',
        ],
        'create' => [
            'label' => 'Erstellen',
        ],
        'email' => [
            'label' => 'E-Mail',
        ],
        'name' => [
            'label' => 'Name',
        ],
        'update' => [
            'label' => 'Aktualisieren',
        ],
    ],
    'delete_account' => [
        'action' => [
            'label' => 'Konto löschen',
        ],
        'summary' => [
            'help' => 'Löschen Sie Ihr Konto dauerhaft.',
        ],
        'warning' => [
            'help' => 'Sobald Ihr Konto gelöscht wird, werden alle zugehörigen Ressourcen und Daten dauerhaft gelöscht. Laden Sie vor dem Löschen Ihres Kontos alle Daten oder Informationen herunter, die Sie behalten möchten.',
        ],
        'confirm' => [
            'title' => 'Sind Sie sicher, dass Sie Ihr Konto löschen möchten?',
        ],
        'confirm_full' => [
            'message' => 'Sind Sie sicher, dass Sie Ihr Konto löschen möchten? Sobald Ihr Konto gelöscht wird, werden alle zugehörigen Ressourcen und Daten dauerhaft gelöscht. Bitte geben Sie Ihr Passwort ein, um zu bestätigen, dass Sie Ihr Konto dauerhaft löschen möchten.',
        ],
        'confirm_password' => [
            'message' => 'Sobald Ihr Konto gelöscht wird, werden alle zugehörigen Ressourcen und Daten dauerhaft gelöscht. Bitte geben Sie Ihr Passwort ein, um zu bestätigen, dass Sie Ihr Konto dauerhaft löschen möchten.',
        ],
        'legacy_warning' => [
            'help' => 'Nach dem Löschen Ihres Kontos werden alle Daten und Ressourcen dauerhaft entfernt. Geben Sie Ihr Passwort ein, um das Löschen zu bestätigen.',
        ],
    ],
    'browser_sessions' => [
        'list' => [
            'title' => 'Browser-Sitzungen',
            'help' => 'Verwalten Sie Ihre aktiven Sitzungen in anderen Browsern und auf anderen Geräten und melden Sie sich dort ab.',
        ],
        'description' => [
            'help' => 'Bei Bedarf können Sie sich von allen anderen Browser-Sitzungen auf all Ihren Geräten abmelden. Einige Ihrer letzten Sitzungen sind unten aufgeführt; diese Liste ist jedoch möglicherweise nicht vollständig. Wenn Sie den Verdacht haben, dass Ihr Konto kompromittiert wurde, sollten Sie außerdem Ihr Passwort ändern.',
        ],
        'unknown' => [
            'label' => 'Unbekannt',
        ],
        'this_device' => [
            'label' => 'Dieses Gerät',
        ],
        'last_active' => [
            'label' => 'Zuletzt aktiv',
        ],
        'log_out_others' => [
            'label' => 'Andere Browser-Sitzungen abmelden',
        ],
        'confirm' => [
            'message' => 'Bitte geben Sie Ihr Passwort ein, um zu bestätigen, dass Sie sich von Ihren anderen Browser-Sitzungen auf all Ihren Geräten abmelden möchten.',
        ],
    ],
    'two_factor' => [
        'setup' => [
            'title' => 'Zwei-Faktor-Authentifizierung',
            'help' => 'Erhöhen Sie die Sicherheit Ihres Kontos mit der Zwei-Faktor-Authentifizierung.',
        ],
        'state_finish' => [
            'title' => 'Aktivierung der Zwei-Faktor-Authentifizierung abschließen.',
        ],
        'state_enabled' => [
            'title' => 'Sie haben die Zwei-Faktor-Authentifizierung aktiviert.',
        ],
        'state_disabled' => [
            'title' => 'Sie haben die Zwei-Faktor-Authentifizierung nicht aktiviert.',
        ],
        'intro' => [
            'help' => 'Wenn die Zwei-Faktor-Authentifizierung aktiviert ist, werden Sie bei der Anmeldung nach einem sicheren, zufälligen Token gefragt. Sie können dieses Token in der Google-Authenticator-App auf Ihrem Telefon abrufen.',
        ],
        'scan_finish' => [
            'help' => 'Um die Aktivierung der Zwei-Faktor-Authentifizierung abzuschließen, scannen Sie den folgenden QR-Code mit der Authentifizierungs-App Ihres Telefons oder geben Sie den Einrichtungsschlüssel und den generierten OTP-Code ein.',
        ],
        'scan_enabled' => [
            'help' => 'Die Zwei-Faktor-Authentifizierung ist jetzt aktiviert. Scannen Sie den folgenden QR-Code mit der Authentifizierungs-App Ihres Telefons oder geben Sie den Einrichtungsschlüssel ein.',
        ],
        'setup_key' => [
            'label' => 'Einrichtungsschlüssel',
        ],
        'code' => [
            'label' => 'Code',
        ],
        'recovery_codes' => [
            'help' => 'Bewahren Sie diese Wiederherstellungscodes in einem sicheren Passwortmanager auf. Mit ihnen können Sie den Zugriff auf Ihr Konto wiederherstellen, falls Ihr Gerät für die Zwei-Faktor-Authentifizierung verloren geht.',
        ],
        'enable' => [
            'label' => 'Aktivieren',
        ],
        'recovery_regenerate' => [
            'label' => 'Wiederherstellungscodes neu generieren',
        ],
        'recovery_show' => [
            'label' => 'Wiederherstellungscodes anzeigen',
        ],
        'disable' => [
            'label' => 'Deaktivieren',
        ],
    ],
    'team' => [
        'settings' => [
            'title' => 'Team-Einstellungen',
        ],
        'create' => [
            'title' => 'Team erstellen',
            'help' => 'Erstellen Sie ein neues Team, um mit anderen an Projekten zusammenzuarbeiten.',
        ],
        'details' => [
            'title' => 'Team-Details',
        ],
        'owner' => [
            'label' => 'Team-Eigentümer',
        ],
        'name' => [
            'label' => 'Team-Name',
            'help' => 'Name und Eigentümerinformationen des Teams.',
        ],
        'delete' => [
            'label' => 'Team löschen',
        ],
        'delete_summary' => [
            'help' => 'Dieses Team dauerhaft löschen.',
        ],
        'delete_warning' => [
            'help' => 'Sobald ein Team gelöscht wird, werden alle zugehörigen Ressourcen und Daten dauerhaft gelöscht. Laden Sie vor dem Löschen dieses Teams alle Daten oder Informationen zu diesem Team herunter, die Sie behalten möchten.',
        ],
        'delete_confirm' => [
            'message' => 'Sind Sie sicher, dass Sie dieses Team löschen möchten? Sobald ein Team gelöscht wird, werden alle zugehörigen Ressourcen und Daten dauerhaft gelöscht.',
        ],
    ],
    'members' => [
        'add' => [
            'title' => 'Teammitglied hinzufügen',
            'help' => 'Fügen Sie Ihrem Team ein neues Mitglied hinzu, damit es mit Ihnen zusammenarbeiten kann.',
        ],
        'add_email' => [
            'help' => 'Bitte geben Sie die E-Mail-Adresse der Person an, die Sie diesem Team hinzufügen möchten.',
        ],
        'role' => [
            'label' => 'Rolle',
        ],
        'added' => [
            'message' => 'Hinzugefügt.',
        ],
        'add_action' => [
            'label' => 'Hinzufügen',
        ],
        'pending' => [
            'title' => 'Ausstehende Team-Einladungen',
            'help' => 'Diese Personen wurden zu Ihrem Team eingeladen und haben eine Einladungs-E-Mail erhalten. Sie können dem Team beitreten, indem sie die Einladung per E-Mail annehmen.',
        ],
        'list' => [
            'title' => 'Teammitglieder',
            'help' => 'Alle Personen, die zu diesem Team gehören.',
        ],
        'leave' => [
            'label' => 'Verlassen',
        ],
        'remove' => [
            'label' => 'Entfernen',
        ],
        'manage_role' => [
            'title' => 'Rolle verwalten',
        ],
        'leave_team' => [
            'title' => 'Team verlassen',
            'message' => 'Sind Sie sicher, dass Sie dieses Team verlassen möchten?',
        ],
        'remove_member' => [
            'title' => 'Teammitglied entfernen',
            'message' => 'Sind Sie sicher, dass Sie diese Person aus dem Team entfernen möchten?',
        ],
    ],
];
