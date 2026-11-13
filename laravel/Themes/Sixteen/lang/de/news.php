<?php

declare(strict_types=1);

return [
    'meta' => ['title' => 'Nachrichten und Pressemitteilungen', 'description' => 'Nachrichten, Pressemitteilungen und Hinweise der Gemeinde.', 'breadcrumb' => 'Nachrichten'],
    'hero' => ['title' => 'Nachrichten und Pressemitteilungen', 'subtitle' => 'Offizielle Informationen aus der Stadt.'],
    'filters' => ['aria' => 'Nachrichtenfilter', 'all' => 'Alle Nachrichten', 'press' => 'Pressemitteilungen', 'notices' => 'Hinweise'],
    'latest' => ['title' => 'Neueste Nachrichten', 'subtitle' => 'Bleiben Sie über kommunale Neuigkeiten informiert.'],
    'search' => ['label' => 'Nachrichten suchen', 'placeholder' => 'Nachrichten suchen'],
    'read_more' => 'Mehr lesen',
    'index' => [
        'default_category' => 'Neuigkeiten',
        'default_event_category' => 'Veranstaltungen',
        'search_label' => 'Suchen',
        'search_placeholder' => 'Nach Stichwort suchen',
        'search_submit' => 'Absenden',
        'result_one' => '1 Meldung gefunden',
        'result_many' => ':count Meldungen gefunden',
        'feedback' => [
            'title' => 'Wie verständlich sind die Informationen auf dieser Seite?',
            'legend' => 'Bewerten Sie diese Seite mit 1 bis 5 Sternen',
            'star_label' => 'Bewertung: %d von 5 Sternen',
            'thanks' => 'Vielen Dank. Ihre Rückmeldung hilft uns, diesen Service zu verbessern.',
            'positive' => [
                'question' => 'Was hat Ihnen besonders gut gefallen?',
                'answers' => [
                    'clear' => 'Die Hinweise waren verständlich',
                    'complete' => 'Die Hinweise waren vollständig',
                    'progress' => 'Ich wusste jederzeit, ob ich richtig vorging',
                    'technical' => 'Es gab keine technischen Probleme',
                    'other' => 'Sonstiges',
                ],
            ],
            'negative' => [
                'question' => 'Wo hatten Sie die größten Schwierigkeiten?',
                'answers' => [
                    'clear' => 'Die Hinweise waren teilweise unverständlich',
                    'complete' => 'Die Hinweise waren teilweise unvollständig',
                    'progress' => 'Ich war mir teilweise unsicher, ob ich richtig vorging',
                    'technical' => 'Es gab technische Probleme',
                    'other' => 'Sonstiges',
                ],
            ],
            'detail' => [
                'question' => 'Möchten Sie weitere Einzelheiten hinzufügen?',
                'label' => 'Einzelheiten',
                'help' => 'Geben Sie höchstens 200 Zeichen ein',
            ],
            'back' => 'Zurück',
            'next' => 'Weiter',
        ],
    ],
    'items' => [
        'one' => ['category' => 'Pressemitteilung', 'date_iso' => '2026-09-01', 'date' => '1. September 2026', 'title' => 'Neue digitale Dienste für Bürger', 'body' => 'Neue Online-Angebote für kommunale Dienste sind verfügbar.'],
        'two' => ['category' => 'Hinweis', 'date_iso' => '2026-08-20', 'date' => '20. August 2026', 'title' => 'Aktualisierte Dienste in der Stadt', 'body' => 'Lesen Sie aktuelle Informationen für die Gemeinschaft.'],
        'three' => ['category' => 'Veranstaltung', 'date_iso' => '2026-08-05', 'date' => '5. August 2026', 'title' => 'Offene Veranstaltungen für die Gemeinschaft', 'body' => 'Entdecken Sie die nächsten Initiativen der Stadt.'],
    ],
];
