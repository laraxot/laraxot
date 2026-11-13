<?php

declare(strict_types=1);

/*
 * Pagine legali dal testo dell'ente (informativa privacy, note legali, media policy; DCP-130/131). Il testo vero lo fornisce il
 * Comune: queste stringhe sono solo la cornice e l'avviso mostrato quando il testo manca o e' incompleto.
 */
return [
    'home' => 'Home',
    'documents' => [
        'privacy' => ['title' => 'Informativa privacy'],
        'legal_notes' => ['title' => 'Note legali'],
        'media_policy' => ['title' => 'Media policy'],
        'terms_disclaimer' => ['title' => 'Termini e disclaimer'],
    ],
    'incomplete' => [
        'title' => 'Testo non ancora completo',
        'intro_missing' => 'Il titolare del trattamento deve ancora fornire i dati seguenti prima della messa in produzione.',
        'intro_present' => 'Il testo qui sotto è incompleto: il titolare del trattamento deve ancora fornire o completare i dati seguenti prima della messa in produzione.',
        'document_missing' => 'Il testo di questo documento non è ancora stato fornito dall’ente. Il titolare deve pubblicarlo prima della messa in produzione.',
    ],
];
