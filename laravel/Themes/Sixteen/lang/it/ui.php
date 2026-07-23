<?php

declare(strict_types=1);

return [
    // Login page
    'Login' => 'Accedi',
    'Accedi ai servizi' => 'Accedi ai servizi',
    'Utilizza le tue credenziali per accedere alla piattaforma' => 'Utilizza le tue credenziali per accedere alla piattaforma',
    'Inserisci le tue credenziali per continuare' => 'Inserisci le tue credenziali per continuare',
    'Non hai ancora un account?' => 'Non hai ancora un account?',
    'Crea il tuo account' => 'Crea il tuo account',
    'Oppure accedi con' => 'Oppure accedi con',
    'Entra con SPID' => 'Entra con SPID',
    'Entra con CIE 3.0' => 'Entra con CIE 3.0',
    'Prossimamente' => 'Prossimamente',

    // Support
    'Hai bisogno di aiuto?' => 'Hai bisogno di aiuto?',
    'Telefono' => 'Telefono',
    'Email' => 'Email',
    'Orari: Lun-Ven 9:00-17:00 | Sab 9:00-13:00' => 'Orari: Lun-Ven 9:00-17:00 | Sab 9:00-13:00',

    // Accessibility
    'Accessibilità' => 'Accessibilità',
    'Conforme a WCAG 2.1 AA' => 'Conforme a WCAG 2.1 AA',
    'Dichiarazione di accessibilità' => 'Dichiarazione di accessibilità',
    'Navigazione' => 'Navigazione',
    'Tab per spostarsi • Invio per confermare' => 'Tab per spostarsi • Invio per confermare',

    // Common UI
    'Continua' => 'Continua',
    'Annulla' => 'Annulla',
    'Conferma' => 'Conferma',
    'Chiudi' => 'Chiudi',
    'Apri' => 'Apri',
    'Salva' => 'Salva',
    'Indietro' => 'Indietro',
    'Avanti' => 'Avanti',
    'Caricamento...' => 'Caricamento...',
    'Errore' => 'Errore',
    'Successo' => 'Successo',
    'Avviso' => 'Avviso',
    'Informazione' => 'Informazione',

    // Dark mode and UI controls
    'dark_mode_toggle' => 'Cambia modalità scura',
    'light_mode' => 'Modalità chiara',
    'dark_mode' => 'Modalità scura',
    'language_switcher' => 'Cambia lingua',
    'search' => 'Cerca',
    'menu' => 'Menu',
    'close' => 'Chiudi',
    'open' => 'Apri',

    // Header
    'region_name' => 'Nome della Regione',
    'region_link_label' => 'Vai al portale - link esterno - apertura nuova scheda',
    'active_language' => 'Lingua attiva:',
    'personal_area' => "Accedi all'area personale",
    'follow_us' => 'Seguici su',
    'search_site' => 'Cerca',
    'search_site_aria' => 'Cerca nel sito',
    'open_close_menu' => 'Mostra/Nascondi la navigazione',
    'header_mobile_nav' => [
        'dialog_label' => 'Menu di navigazione del sito',
    ],
    'hide_navigation' => 'Nascondi la navigazione',
    'skip_to_content' => 'Vai ai contenuti',
    'skip_to_footer' => 'Vai al footer',

    // Header area personale (Design Comuni, utente autenticato) — chiavi a 5 elementi per label runtime
    'header_area_personale' => [
        'user_menu_toggle' => [
            'label' => '',
            'tooltip' => 'Apri o chiudi il menu del profilo utente',
            'placeholder' => '',
            'helper_text' => 'Comando mostra/nascondi dropdown nome utente',
            'description' => 'Aria/UX menu utente header slim',
        ],
        'avatar_alt' => [
            'label' => 'Avatar utente',
            'tooltip' => 'Immagine profilo mostrata accanto al nome',
            'placeholder' => '',
            'helper_text' => 'Testo alternativo per accessibilità',
            'description' => 'Alt immagine avatar nel pulsante utente',
        ],
        'my_services' => [
            'label' => 'I miei servizi',
            'tooltip' => 'Vai ai servizi personali del cittadino',
            'placeholder' => '',
            'helper_text' => 'Link alla pagina servizi di test CMS',
            'description' => 'Voce menu dropdown area personale',
        ],
        'my_practices' => [
            'label' => 'Le mie pratiche',
            'tooltip' => 'Vai all’elenco delle tue pratiche',
            'placeholder' => '',
            'helper_text' => 'Link alla pagina area personale di test',
            'description' => 'Voce menu dropdown area personale',
        ],
        'notifications' => [
            'label' => 'Notifiche',
            'tooltip' => 'Messaggi e aggiornamenti',
            'placeholder' => '',
            'helper_text' => 'Link alla sezione messaggi area personale',
            'description' => 'Voce menu dropdown area personale',
        ],
        'settings' => [
            'label' => 'Impostazioni',
            'tooltip' => 'Preferenze e impostazioni account',
            'placeholder' => '',
            'helper_text' => 'Link alla pagina impostazioni o area personale',
            'description' => 'Voce menu dropdown area personale',
        ],
        'logout' => [
            'label' => 'Esci',
            'tooltip' => 'Chiudi sessione e torna al sito pubblico',
            'placeholder' => '',
            'helper_text' => 'Azione POST logout applicativa',
            'description' => 'Pulsante di logout nel menu utente',
        ],
        'parity_demo_user' => [
            'label' => 'Giulia Rossi',
            'tooltip' => 'Nome dimostrato per parity HTML (pagina test area personale, ospite)',
            'placeholder' => '',
            'helper_text' => 'Allineato al contenuto della pagina tests/segnalazione-area-personale',
            'description' => 'Chrome header slim come kit Design Comuni senza sessione reale',
        ],
    ],

    // Feedback/rating
    'feedback_rate_stars' => 'Valuta da 1 a 5 stelle la pagina',
    'feedback_rate_5' => 'Valuta 5 stelle su 5',
    'feedback_rate_4' => 'Valuta 4 stelle su 5',
    'feedback_rate_3' => 'Valuta 3 stelle su 5',
    'feedback_rate_2' => 'Valuta 2 stelle su 5',
    'feedback_rate_1' => 'Valuta 1 stella su 5',
    'feedback_positive_q' => 'Quali sono stati gli aspetti che hai preferito?',
    'feedback_negative_q' => 'Dove hai incontrato le maggiori difficoltà?',
    'feedback_details_q' => 'Vuoi aggiungere altri dettagli?',
    'feedback_step_label' => 'Dettaglio',
    'feedback_max_chars' => 'Inserire massimo 200 caratteri',
    'feedback_back' => 'Indietro',
    'feedback_next' => 'Avanti',
    'feedback_pos_1' => 'Le indicazioni erano chiare',
    'feedback_pos_2' => 'Le indicazioni erano complete',
    'feedback_pos_3' => 'Capivo sempre che stavo procedendo correttamente',
    'feedback_pos_4' => 'Non ho avuto problemi tecnici',
    'feedback_pos_5' => 'Altro',
    'feedback_neg_1' => 'A volte le indicazioni non erano chiare',
    'feedback_neg_2' => 'A volte le indicazioni non erano complete',
    'feedback_neg_3' => 'A volte non capivo se stavo procedendo correttamente',
    'feedback_neg_4' => 'Ho avuto problemi tecnici',
    'feedback_neg_5' => 'Altro',

    // Governance/calendar
    'go_to_page' => 'Vai alla pagina',
    'events_title' => 'Eventi',

    // Topics / Argomenti
    'explore_topic' => 'Esplora argomento',
    'thematic_sites_title' => 'Siti tematici',
    'other_topics_title' => 'Altri argomenti',
    'visit_site' => 'Visita il sito:',

    // Contact
    'contact_municipality' => 'Contatta il comune',
    'city_issues' => 'Problemi in città',

    // Useful links
    'useful_links_title' => 'Link utili',
    'search_submit' => 'Invio',

    // Hero
    'highlighted_content' => 'Contenuti in evidenza',
];
