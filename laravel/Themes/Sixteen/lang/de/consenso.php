<?php

declare(strict_types=1);

/**
 * Einwilligung in die Verarbeitung personenbezogener Daten – Schritt 1 des Meldungsablaufs.
 * Design Comuni `segnalazione-01-privacy.html`: die Einwilligung ist in allen sieben
 * Serviceabläufen Schritt 1, ohne Ausnahme.
 */
return [
    'title' => 'Datenschutzhinweis und Einwilligung',
    'intro' => 'Zur Übermittlung einer Meldung verarbeiten wir die von Ihnen gesendeten Daten. Sie können nur fortfahren, wenn Sie einwilligen. Die Daten werden zur Beantwortung der Meldung und für die gesetzlich erforderliche Dauer verarbeitet.',
    'points' => [
        'Verarbeitete Daten: Vor- und Nachname, E-Mail-Adresse und Standort des Problems.',
        'Zweck: Beantwortung der Meldung und Übermittlung von Aktualisierungen.',
        'Rechtsgrundlage: Ihre Einwilligung. Sie können sie jederzeit widerrufen.',
        'Empfänger: Personal des zuständigen Amts. Eine Weitergabe an Dritte findet nicht statt.',
        'Speicherdauer: für die gesetzlich erforderliche Dauer.',
    ],
    'link' => '/de/privacy',
    'link_label' => 'Vollständigen Datenschutzhinweis lesen',
    'opens_new_window' => 'wird in einem neuen Fenster geöffnet',
    'label' => 'Ich habe den Datenschutzhinweis gelesen und willige in die Verarbeitung meiner personenbezogenen Daten zur Bearbeitung dieser Meldung ein.',
    'error' => 'Zur Übermittlung der Meldung müssen Sie in die Datenverarbeitung einwilligen.',
    'action' => 'Weiter',
];
