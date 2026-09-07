<?php

declare(strict_types=1);

return [
<<<<<<< HEAD
<<<<<<< HEAD
    'navigation' => [
        'label' => 'Password Profilo',
        'plural_label' => 'Password Profilo',
        'group' => 'Profilo',
        'icon' => 'heroicon-o-lock-closed',
        'sort' => 12,
    ],
    'label' => 'Password Profilo',
    'plural_label' => 'Password Profilo',
    'fields' => [
        'current_password' => [
            'label' => 'Password Attuale',
            'tooltip' => 'Inserisci la password attuale',
            'placeholder' => 'Inserisci la password attuale',
            'helper_text' => 'La tua password attuale per verificare l\'identità',
            'description' => 'Password corrente dell\'utente',
        ],
        'new_password' => [
            'label' => 'Nuova Password',
            'tooltip' => 'Inserisci la nuova password',
            'placeholder' => 'Inserisci la nuova password',
            'helper_text' => 'Minimo 8 caratteri con lettere e numeri',
            'description' => 'Nuova password da impostare',
        ],
        'new_password_confirmation' => [
            'label' => 'Conferma Nuova Password',
            'tooltip' => 'Ripeti la nuova password per sicurezza',
            'placeholder' => 'Conferma la tua nuova password',
            'helper_text' => 'Devi inserire la stessa password per conferma',
            'description' => 'Inserisci nuovamente la nuova password per confermarla',
=======
=======
>>>>>>> 87273113 (.)
    'fields' => [
        'new_password_confirmation' => [
            'label' => 'Conferma nuova password',
            'placeholder' => 'Conferma la tua nuova password',
            'helper_text' => '',
            'description' => 'Inserisci nuovamente la nuova password per confermarla',
            'tooltip' => 'Ripeti la nuova password per sicurezza',
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    'navigation' => [
        'label' => 'Password Profilo',
        'plural_label' => 'Password Profilo',
        'group' => 'Profilo',
        'icon' => 'heroicon-o-lock-closed',
        'sort' => 12,
    ],
    'label' => 'Password Profilo',
    'plural_label' => 'Password Profilo',
    'fields' => [
        'current_password' => [
            'label' => 'Password Attuale',
            'tooltip' => 'Inserisci la password attuale',
            'placeholder' => 'Inserisci la password attuale',
            'helper_text' => 'La tua password attuale per verificare l\'identità',
            'description' => 'Password corrente dell\'utente',
        ],
        'new_password' => [
            'label' => 'Nuova Password',
            'tooltip' => 'Inserisci la nuova password',
            'placeholder' => 'Inserisci la nuova password',
            'helper_text' => 'Minimo 8 caratteri con lettere e numeri',
            'description' => 'Nuova password da impostare',
        ],
        'new_password_confirmation' => [
            'label' => 'Conferma Nuova Password',
            'tooltip' => 'Ripeti la nuova password per sicurezza',
            'placeholder' => 'Conferma la tua nuova password',
            'helper_text' => 'Devi inserire la stessa password per conferma',
            'description' => 'Inserisci nuovamente la nuova password per confermarla',
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            'icon' => 'heroicon-o-lock-closed',
            'color' => 'warning',
        ],
    ],
<<<<<<< HEAD
<<<<<<< HEAD
=======
=======
>>>>>>> 87273113 (.)
    'actions' => [
        'save' => [
            'label' => 'Salva Password',
            'tooltip' => 'Salva la nuova password',
            'helper_text' => 'Aggiorna la password del profilo',
            'description' => 'Azione per salvare',
        ],
        'cancel' => [
            'label' => 'Annulla',
            'tooltip' => 'Annulla l\'operazione',
            'helper_text' => 'Torna indietro senza salvare',
            'description' => 'Azione per annullare',
        ],
    ],
    'messages' => [
        'password_changed' => 'Password del profilo cambiata con successo',
        'password_mismatch' => 'Le password non coincidono',
        'error' => 'Si è verificato un errore',
    ],
<<<<<<< HEAD
=======
>>>>>>> f548be94 (.)
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
];
