<?php

declare(strict_types=1);

/*
 * Legal pages built from the municipality's text (privacy notice, legal notes, media policy; DCP-130/131). The real text is
 * supplied by the municipality: these strings are only the frame and the notice shown when the text is missing or incomplete.
 */
return [
    'home' => 'Home',
    'documents' => [
        'privacy' => ['title' => 'Privacy notice'],
        'legal_notes' => ['title' => 'Legal notes'],
        'media_policy' => ['title' => 'Media policy'],
        'terms_disclaimer' => ['title' => 'Terms and disclaimer'],
    ],
    'incomplete' => [
        'title' => 'Text not complete yet',
        'intro_missing' => 'The data controller still has to provide the following information before the service goes live.',
        'intro_present' => 'The text below is incomplete: the data controller still has to provide or complete the following information before the service goes live.',
        'document_missing' => 'The municipality has not provided the text of this document yet. The controller must publish it before the service goes live.',
    ],
];
