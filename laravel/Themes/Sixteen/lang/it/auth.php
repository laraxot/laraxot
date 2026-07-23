<?php

declare(strict_types=1);

return [
    'login' => [
        'title' => 'Accesso ai servizi',
        'description' => 'Inserisci le tue credenziali per accedere a :service',
        'no_account' => 'Non hai un account?',
        'create_account' => 'Registrati',
        'remember' => 'Ricordami',
        'forgot_password' => 'Password dimenticata?',
        'submit' => 'Accedi',
        'help' => 'Hai bisogno di aiuto?',
        'email' => 'Indirizzo email',
        'password' => 'Password',
        'register' => 'Registrati',
        'or' => 'oppure',
        'security_title' => 'Connessione sicura',
        'security_message' => 'I tuoi dati sono protetti con crittografia SSL',
        'accessibility_info' => 'Questo servizio è accessibile secondo le linee guida WCAG 2.1 AA.',
        'accessibility_declaration' => 'Dichiarazione di accessibilità',
        'keyboard_navigation' => 'Usa Tab per navigare tra i campi e Invio per inviare il modulo.',
    ],
    'register' => [
        'title' => 'Registrazione',
        'description' => 'Crea il tuo account per accedere ai servizi',
        'name' => 'Nome completo',
        'email' => 'Indirizzo email',
        'password' => 'Password',
        'password_confirmation' => 'Conferma password',
        'submit' => 'Registrati',
        'already_registered' => 'Hai già un account?',
        'login' => 'Accedi',
    ],
    'failed' => 'Le credenziali fornite non corrispondono ai nostri record.',
    'password' => 'La password fornita non è corretta.',
    'throttle' => 'Troppi tentativi di accesso. Riprova tra :seconds secondi.',
    'general_error' => 'Si è verificato un errore. Riprova più tardi.',
    'unauthorized' => 'Non hai i permessi necessari per questa operazione.',

    // Logout
    'logout' => [
        'title' => 'Logout',
        'confirm_message' => 'Sei sicuro di voler effettuare il logout?',
        'confirm_button' => 'Conferma Logout',
        'cancel_button' => 'Annulla',
        'success_title' => 'Logout effettuato',
        'success_message' => 'Sei stato disconnesso con successo.',
        'error_title' => 'Errore durante il logout',
        'error_message' => 'Si è verificato un errore durante il logout.',
        'try_again' => 'Riprova',
        'back_to_home' => 'Torna alla Home',
    ],

    // Profilo
    'profile' => [
        'title' => 'Profilo',
        'settings' => 'Impostazioni',
        'information' => 'Informazioni Profilo',
        'update_password' => 'Aggiorna Password',
        'current_password' => 'Password Attuale',
        'new_password' => 'Nuova Password',
        'confirm_password' => 'Conferma Password',
        'save' => 'Salva',
        'update' => 'Aggiorna',
    ],

    // Reset Password
    'reset' => [
        'title' => 'Reimposta Password',
        'email' => 'Email',
        'password' => 'Nuova Password',
        'password_confirmation' => 'Conferma Password',
        'submit' => 'Reimposta Password',
        'link_sent' => 'Abbiamo inviato il link per il reset della password via email!',
        'reset_link' => 'Reimposta Password',
    ],

    // Verifica Email
    'verify' => [
        'title' => 'Verifica Email',
        'message' => 'Grazie per esserti registrato! Prima di iniziare, potresti verificare il tuo indirizzo email cliccando sul link che ti abbiamo appena inviato?',
        'resend' => 'Se non hai ricevuto l\'email, possiamo reinviarla.',
        'submit' => 'Reinvia email di verifica',
    ],

    // Navigazione
    'navigation' => [
        'open_menu' => 'Apri menu principale',
        'close_menu' => 'Chiudi menu principale',
        'home' => 'Home',
        'dashboard' => 'Dashboard',
        'profile' => 'Profilo',
        'settings' => 'Impostazioni',
    ],

    // Dropdown utente
    'user_dropdown' => [
        'manage_account' => 'Gestione Account',
        'profile' => 'Profilo',
        'settings' => 'Impostazioni',
        'logout' => 'Logout',
        'login_link' => 'Accedi',
        'register_link' => 'Registrati',
    ],

    // Messaggi di notifica
    'notifications' => [
        'login_success' => 'Accesso effettuato con successo',
        'login_error' => 'Errore durante il login',
        'logout_success' => 'Logout effettuato con successo',
        'logout_error' => 'Errore durante il logout',
        'validation_error' => 'Errore di validazione',
        'general_error' => 'Si è verificato un errore. Riprova più tardi.',
    ],
];
