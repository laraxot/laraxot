<?php

declare(strict_types=1);

<<<<<<< HEAD
<<<<<<< HEAD
return [
    'fields' => [
        'name' => [
            'label' => 'Nome',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'name.placeholder' => [
            'label' => 'Inserisci il nome del provider',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'name.helper_text' => [
            'label' => 'Il nome del provider social (es. Facebook, Google)',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'scopes' => [
            'label' => 'Ambiti',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'scopes.placeholder' => [
            'label' => 'Inserisci gli ambiti di accesso',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'scopes.helper_text' => [
            'label' => 'Gli ambiti di accesso richiesti dal provider',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'parameters' => [
            'label' => 'Parametri',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'parameters.placeholder' => [
            'label' => 'Inserisci i parametri aggiuntivi',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'parameters.helper_text' => [
            'label' => 'Parametri aggiuntivi per la configurazione',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'stateless' => [
            'label' => 'Senza stato',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'stateless.helper_text' => [
            'label' => 'Se il provider non mantiene lo stato della sessione',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'active' => [
            'label' => 'Attivo',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'active.helper_text' => [
            'label' => 'Se il provider è attualmente attivo',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'socialite' => [
            'label' => 'Socialite',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'socialite.helper_text' => [
            'label' => 'Se il provider usa Laravel Socialite',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'svg' => [
            'label' => 'SVG',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'svg.placeholder' => [
            'label' => 'Inserisci il codice SVG dell\'icona',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'svg.helper_text' => [
            'label' => 'L\'icona SVG del provider social',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
    ],
    'navigation' => [
        'label' => 'Missing Navigation Label',
        'plural_label' => 'Missing Navigation Plural Label',
        'group' => 'Missing Group',
        'icon' => 'heroicon-o-puzzle-piece',
        'sort' => 100,
    ],
    'label' => 'Missing Label',
    'plural_label' => 'Missing Plural label',
    'actions' => [
=======
=======
>>>>>>> 87273113 (.)

return [
    'fields' => [
        'name' => 'Nome',
        'name.placeholder' => 'Inserisci il nome del provider',
        'name.helper_text' => 'Il nome del provider social (es. Facebook, Google)',
        'scopes' => 'Ambiti',
        'scopes.placeholder' => 'Inserisci gli ambiti di accesso',
        'scopes.helper_text' => 'Gli ambiti di accesso richiesti dal provider',
        'parameters' => 'Parametri',
        'parameters.placeholder' => 'Inserisci i parametri aggiuntivi',
        'parameters.helper_text' => 'Parametri aggiuntivi per la configurazione',
        'stateless' => 'Senza stato',
        'stateless.helper_text' => 'Se il provider non mantiene lo stato della sessione',
        'active' => 'Attivo',
        'active.helper_text' => 'Se il provider è attualmente attivo',
        'socialite' => 'Socialite',
        'socialite.helper_text' => 'Se il provider usa Laravel Socialite',
        'svg' => 'SVG',
        'svg.placeholder' => 'Inserisci il codice SVG dell\'icona',
        'svg.helper_text' => 'L\'icona SVG del provider social',
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
return [
    'fields' => [
        'name' => [
            'label' => 'Nome',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'name.placeholder' => [
            'label' => 'Inserisci il nome del provider',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'name.helper_text' => [
            'label' => 'Il nome del provider social (es. Facebook, Google)',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'scopes' => [
            'label' => 'Ambiti',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'scopes.placeholder' => [
            'label' => 'Inserisci gli ambiti di accesso',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'scopes.helper_text' => [
            'label' => 'Gli ambiti di accesso richiesti dal provider',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'parameters' => [
            'label' => 'Parametri',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'parameters.placeholder' => [
            'label' => 'Inserisci i parametri aggiuntivi',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'parameters.helper_text' => [
            'label' => 'Parametri aggiuntivi per la configurazione',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'stateless' => [
            'label' => 'Senza stato',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'stateless.helper_text' => [
            'label' => 'Se il provider non mantiene lo stato della sessione',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'active' => [
            'label' => 'Attivo',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'active.helper_text' => [
            'label' => 'Se il provider è attualmente attivo',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'socialite' => [
            'label' => 'Socialite',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'socialite.helper_text' => [
            'label' => 'Se il provider usa Laravel Socialite',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'svg' => [
            'label' => 'SVG',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'svg.placeholder' => [
            'label' => 'Inserisci il codice SVG dell\'icona',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'svg.helper_text' => [
            'label' => 'L\'icona SVG del provider social',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
    ],
    'navigation' => [
        'label' => 'Missing Navigation Label',
        'plural_label' => 'Missing Navigation Plural Label',
        'group' => 'Missing Group',
        'icon' => 'heroicon-o-puzzle-piece',
        'sort' => 100,
    ],
    'label' => 'Missing Label',
    'plural_label' => 'Missing Plural label',
    'actions' => [
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    ],
];
