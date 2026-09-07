<?php

declare(strict_types=1);

<<<<<<< HEAD
<<<<<<< HEAD
return [
    'navigation' => ['label' => 'Favicon', 'plural_label' => 'Favicon', 'group' => 'Aspetto', 'icon' => 'heroicon-o-photo', 'sort' => 5],
    'label' => 'Favicon',
    'plural_label' => 'Favicon',
    'fields' => [
        'background_color' => ['label' => 'Colore Sfondo', 'tooltip' => 'Colore di sfondo per l\'icona favicon', 'placeholder' => 'Seleziona il colore di sfondo', 'helper_text' => 'Colore di sfondo per l\'icona favicon', 'description' => 'Seleziona il colore di sfondo per l\'icona'],
        'background' => ['label' => 'Immagine Sfondo', 'tooltip' => 'Immagine di sfondo per il favicon', 'placeholder' => 'Carica un\'immagina per lo sfondo', 'helper_text' => 'Immagine di sfondo per il favicon', 'description' => 'Carica un\'immagina da usare come sfondo'],
        'overlay_color' => ['label' => 'Colore Overlay', 'tooltip' => 'Colore dell\'overlay da applicare', 'placeholder' => 'Seleziona il colore dell\'overlay', 'helper_text' => 'Colore dell\'overlay da applicare al favicon', 'description' => 'Seleziona il colore dell\'overlay'],
        'overlay_opacity' => ['label' => 'Opacità Overlay', 'tooltip' => 'Valore di opacità (0-100)', 'placeholder' => 'Inserisci l\'opacità (0-100)', 'helper_text' => 'Valore di opacità per l\'overlay (0-100)', 'description' => 'Opacità dell\'overlay tra 0 e 100'],
    ],
    'actions' => [
        'save' => ['label' => 'Salva', 'tooltip' => 'Salva le impostazioni del favicon', 'icon' => 'save'],
        'reset' => ['label' => 'Reimposta', 'tooltip' => 'Reimposta ai valori predefiniti'],
        'upload' => ['label' => 'Carica', 'tooltip' => 'Carica un\'immagina'],
        'remove' => ['label' => 'Rimuovi', 'tooltip' => 'Rimuovi l\'immagina caricata'],
    ],
    'messages' => ['saved' => 'Favicon salvato con successo', 'reset' => 'Impostazioni reimpostate', 'uploaded' => 'Immagine caricata con successo', 'removed' => 'Immagine rimossa con successo', 'error' => 'Si è verificato un errore'],
    'validation' => ['background_color_invalid' => 'Il colore di sfondo non è valido', 'overlay_color_invalid' => 'Il colore overlay non è valido', 'overlay_opacity_invalid' => 'L\'opacità deve essere un numero tra 0 e 100', 'image_required' => 'L\'immagina è obbligatoria', 'image_invalid' => 'L\'immagina non è valida'],
=======
=======
>>>>>>> 87273113 (.)

return [
    'fields' => [
        'background_color' => [
            'label' => 'background_color',
            'placeholder' => 'background_color',
            'helper_text' => 'background_color',
            'description' => 'background_color',
        ],
        'background' => [
            'label' => 'background',
            'placeholder' => 'background',
            'helper_text' => 'background',
            'description' => 'background',
        ],
        'overlay_color' => [
            'label' => 'overlay_color',
            'placeholder' => 'overlay_color',
            'helper_text' => 'overlay_color',
            'description' => 'overlay_color',
        ],
        'overlay_opacity' => [
            'label' => 'overlay_opacity',
            'placeholder' => 'overlay_opacity',
            'helper_text' => 'overlay_opacity',
            'description' => 'overlay_opacity',
        ],
    ],
    'actions' => [
        'updateAction' => [
            'label' => 'updateAction',
        ],
    ],
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
return [
    'navigation' => ['label' => 'Favicon', 'plural_label' => 'Favicon', 'group' => 'Aspetto', 'icon' => 'heroicon-o-photo', 'sort' => 5],
    'label' => 'Favicon',
    'plural_label' => 'Favicon',
    'fields' => [
        'background_color' => ['label' => 'Colore Sfondo', 'tooltip' => 'Colore di sfondo per l\'icona favicon', 'placeholder' => 'Seleziona il colore di sfondo', 'helper_text' => 'Colore di sfondo per l\'icona favicon', 'description' => 'Seleziona il colore di sfondo per l\'icona'],
        'background' => ['label' => 'Immagine Sfondo', 'tooltip' => 'Immagine di sfondo per il favicon', 'placeholder' => 'Carica un\'immagina per lo sfondo', 'helper_text' => 'Immagine di sfondo per il favicon', 'description' => 'Carica un\'immagina da usare come sfondo'],
        'overlay_color' => ['label' => 'Colore Overlay', 'tooltip' => 'Colore dell\'overlay da applicare', 'placeholder' => 'Seleziona il colore dell\'overlay', 'helper_text' => 'Colore dell\'overlay da applicare al favicon', 'description' => 'Seleziona il colore dell\'overlay'],
        'overlay_opacity' => ['label' => 'Opacità Overlay', 'tooltip' => 'Valore di opacità (0-100)', 'placeholder' => 'Inserisci l\'opacità (0-100)', 'helper_text' => 'Valore di opacità per l\'overlay (0-100)', 'description' => 'Opacità dell\'overlay tra 0 e 100'],
    ],
    'actions' => [
        'save' => ['label' => 'Salva', 'tooltip' => 'Salva le impostazioni del favicon', 'icon' => 'save'],
        'reset' => ['label' => 'Reimposta', 'tooltip' => 'Reimposta ai valori predefiniti'],
        'upload' => ['label' => 'Carica', 'tooltip' => 'Carica un\'immagina'],
        'remove' => ['label' => 'Rimuovi', 'tooltip' => 'Rimuovi l\'immagina caricata'],
    ],
    'messages' => ['saved' => 'Favicon salvato con successo', 'reset' => 'Impostazioni reimpostate', 'uploaded' => 'Immagine caricata con successo', 'removed' => 'Immagine rimossa con successo', 'error' => 'Si è verificato un errore'],
    'validation' => ['background_color_invalid' => 'Il colore di sfondo non è valido', 'overlay_color_invalid' => 'Il colore overlay non è valido', 'overlay_opacity_invalid' => 'L\'opacità deve essere un numero tra 0 e 100', 'image_required' => 'L\'immagina è obbligatoria', 'image_invalid' => 'L\'immagina non è valida'],
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
];
