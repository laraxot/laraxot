<?php

declare(strict_types=1);

/*
 * Le pagine del pannello portano titolo ed etichetta di navigazione nel codice
 * (arrivano cosi' dal gestionale sorgente). Qui restano il gruppo di menu e le
 * voci del menu utente, che il generatore aveva riempito col nome della chiave.
 */
return [
    'navigation' => ['group' => 'Energy Broker'],
    'actions' => [
        'profile' => ['label' => 'Profilo', 'icon' => 'heroicon-o-user-circle', 'tooltip' => 'Apri il tuo profilo'],
        'logout' => ['label' => 'Esci', 'icon' => 'heroicon-o-arrow-right-on-rectangle', 'tooltip' => 'Chiudi la sessione'],
    ],
];
