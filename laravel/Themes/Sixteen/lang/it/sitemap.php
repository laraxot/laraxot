<?php

declare(strict_types=1);

return [
    'meta' => ['title' => 'Mappa del sito', 'description' => 'Esplora le pagine e i servizi pubblici di FixCity.', 'breadcrumb' => 'Mappa del sito'],
    'intro' => 'Trova rapidamente le pagine pubbliche e le attività disponibili.',
    'groups' => [
        ['title' => 'Segnalazioni', 'links' => [
            ['label' => 'Segnala un problema', 'path' => '/tickets/create'],
            ['label' => 'Elenco e mappa delle segnalazioni', 'path' => '/tickets'],
            ['label' => 'Segui una segnalazione', 'path' => '/tickets/track'],
            ['label' => 'Le mie pratiche', 'path' => '/area-personale/pratiche'],
        ]],
        ['title' => 'Informazioni', 'links' => [
            ['label' => 'Domande frequenti', 'path' => '/domande-frequenti'],
            ['label' => 'Privacy', 'path' => '/privacy'],
            ['label' => 'Note legali', 'path' => '/note-legali'],
            ['label' => 'Cookie policy', 'path' => '/cookie-policy'],
            ['label' => 'Accessibilità', 'path' => '/accessibilita'],
        ]],
        ['title' => 'Account', 'links' => [
            ['label' => 'Accedi', 'path' => '/auth/login'],
            ['label' => 'Crea un account', 'path' => '/auth/register'],
        ]],
    ],
];
