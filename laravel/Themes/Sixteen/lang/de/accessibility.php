<?php

declare(strict_types=1);

/*
 * Erklärung zur Barrierefreiheit (Gesetz 4/2004 Art. 3-quater; DCP-132): Rahmen und feste Texte der Seite. Konformitätsstatus, Daten,
 * nicht barrierefreie Teile und Kontakte stehen NICHT hier: die Gemeinde liefert sie im JSON `accessibilita.index` (Block accessibility-statement).
 */
return [
    'home' => 'Startseite',
    'page' => ['title' => ['text' => 'Barrierefreiheitserklärung']],
    'title' => [
        'text' => 'Erklärung zur Barrierefreiheit',
        'label' => 'Erklärung zur Barrierefreiheit',
    ],
    'lead' => 'Erklärung nach Art. 3-quater des italienischen Gesetzes Nr. 4 vom 9. Januar 2004.',
    'incomplete' => [
        'title' => 'Erklärung noch nicht vollständig',
        'intro' => 'Die Gemeinde muss die folgenden Angaben vor dem Produktivbetrieb noch liefern. Bis dahin ist diese Erklärung nicht gültig.',
    ],
    'scope' => [
        'title' => 'Geltungsbereich der Erklärung',
        'commitment' => ':entity verpflichtet sich, diese Website im Einklang mit dem italienischen Gesetz Nr. 4 vom 9. Januar 2004, das die Richtlinie (EU) 2016/2102 umsetzt, barrierefrei zu gestalten.',
        'text' => 'Diese Erklärung gilt für die Website :url (öffentliche Seiten und persönlicher Bereich der Bürgerinnen und Bürger).',
    ],
    'status' => [
        'title' => 'Konformitätsstatus',
        'compliant' => 'Diese Website ist konform mit den Barrierefreiheitsanforderungen des italienischen Gesetzes Nr. 4 vom 9. Januar 2004.',
        'partially_compliant' => 'Diese Website ist teilweise konform mit den Barrierefreiheitsanforderungen des italienischen Gesetzes Nr. 4 vom 9. Januar 2004, aufgrund der unten aufgeführten Fälle von Nichtkonformität.',
        'non_compliant' => 'Diese Website ist nicht konform mit den Barrierefreiheitsanforderungen des italienischen Gesetzes Nr. 4 vom 9. Januar 2004. Die Fälle von Nichtkonformität sind unten aufgeführt.',
        'pending' => 'Noch nicht festgestellt: Die Gemeinde gibt den Status nach der Barrierefreiheitsprüfung an.',
        'reference' => 'Technische Grundlage: Norm UNI CEI EN 301 549 V3.2.1 (Abschnitt 9), die die Stufe AA der WCAG 2.1 verlangt, wie in den AgID-Leitlinien zur Barrierefreiheit von IT-Werkzeugen vorgesehen.',
    ],
    'content' => [
        'title' => 'Nicht barrierefreie Inhalte',
        'non_accessible' => 'Teile, die dem Gesetz nicht entsprechen',
        'disproportionate_burden' => 'Unverhältnismäßige Belastung',
        'out_of_scope' => 'Inhalte außerhalb des Anwendungsbereichs des Gesetzes',
        'reason' => 'Begründung',
        'none' => 'Die Gemeinde erklärt keine nicht barrierefreien Inhalte.',
        'pending' => 'Von der Gemeinde anzugeben.',
    ],
    'drafting' => [
        'title' => 'Erstellung der Erklärung',
        'method' => 'Bewertungsmethode',
        'methods' => ['self' => 'Selbstbewertung', 'third_party' => 'Bewertung durch Dritte'],
        'evaluation_date' => 'Datum der Bewertung',
        'published_at' => 'Veröffentlichungsdatum',
        'updated_at' => 'Letzte Aktualisierung',
        'pending' => 'Noch nicht angegeben',
        'agid' => 'Auf der Plattform der AgID erstellte Erklärung',
        'verified' => 'Was geprüft wurde',
        'not_verified' => 'Was nicht geprüft wurde',
    ],
    'feedback' => [
        'title' => 'Feedback und Kontakt',
        'intro' => 'Um ein Barrierefreiheitsproblem dieser Website zu melden oder Informationen anzufordern, nutzen Sie das Formular unten.',
        'jump' => 'Ein Barrierefreiheitsproblem melden',
        'email' => 'Sie können auch schreiben an',
        'pending' => 'Die Adresse für Meldungen wird von der Gemeinde angegeben.',
        'officer' => 'Verantwortliche Person für Barrierefreiheit',
    ],
    'procedure' => [
        'title' => 'Durchsetzungsverfahren',
        'text' => 'Erhalten Sie innerhalb von dreißig Tagen keine Antwort oder ist die Antwort nicht zufriedenstellend, können Sie das Durchsetzungsverfahren vor dem digitalen Bürgerbeauftragten bei der AgID einleiten (Gesetz 4/2004, Art. 3-quinquies).',
        'link' => 'Durchsetzungsverfahren (AgID)',
    ],
];
