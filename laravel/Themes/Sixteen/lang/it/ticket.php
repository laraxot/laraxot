<?php

declare(strict_types=1);

return [
    // Common
    'next' => 'Avanti',
    'back' => 'Indietro',
    'submit' => 'Invia',
    'required' => 'obbligatorio',
    'max_chars' => 'Max :max caratteri',

    // Privacy step
    'privacy' => [
        'title' => 'Informativa privacy',
        'description' => 'Leggi l\'informativa prima di procedere',
        'details' => 'Per i dettagli sul trattamento dei dati personali consulta l\'',
        'link_label' => 'informativa sulla privacy.',
        'accept_label' => 'Ho letto e compreso l\'informativa sulla privacy',
    ],

    // Dati step
    'dati' => [
        'title' => 'Dati di segnalazione',
        'place_label' => 'Luogo del disservizio',
        'place_placeholder' => 'Es: Via Roma 1, Firenze',
        'details_label' => 'Dettagli disservizio',
        'type_label' => 'Tipo di disservizio*',
        'type_select' => 'Seleziona...',
        'types' => [
            'verde' => 'Verde pubblico e arredo urbano',
            'illuminazione' => 'Illuminazione pubblica',
            'strade' => 'Manutenzione strade',
            'rifiuti' => 'Gestione rifiuti',
            'altro' => 'Altro',
        ],
        'title_label' => 'Titolo',
        'title_placeholder' => 'Breve descrizione del problema',
        'description_label' => 'Descrizione*',
        'description_placeholder' => 'Descrivi il disservizio...',
        'images_label' => 'Immagini',
        'images_hint' => 'Seleziona una o più immagini',
        'no_file' => 'Nessun file selezionato',
    ],

    // Riepilogo step
    'riepilogo' => [
        'title' => 'Riepilogo segnalazione',
        'warning_title' => 'Attenzione',
        'warning_text' => 'Le informazioni fornite hanno valore di dichiarazione. Verifica che siano corrette.',
        'disservizio_section' => 'Disservizio',
        'contacts_section' => 'Contatti',
        'terms_label' => 'Confermo che le informazioni sono veritiere e accetto le',
        'terms_link' => 'condizioni di servizio',
        'submit_label' => 'Invia segnalazione',
    ],

    // Conferma step
    'conferma' => [
        'title' => 'Segnalazione inviata',
        'subtitle' => 'Grazie, abbiamo ricevuto la tua <strong>segnalazione :code.</strong>',
        'visibility' => 'Sarà visibile sulla <a href=":url" class="t-primary">lista di tutte le segnalazioni</a> una volta presa in carico dall\'amministrazione.',
        'email_sent' => 'Abbiamo inviato il riepilogo all\'email:<br><strong>:email</strong>',
        'download_receipt' => 'Scarica la ricevuta (PDF 100KB)',
        'consult_request' => 'Consulta la richiesta',
        'in_area' => 'nella tua area riservata',
        'related_services' => 'Servizi correlati',
    ],

    // Elenco
    'elenco' => [
        'title' => 'Elenco segnalazioni',
        'subtitle' => 'Negli ultimi 12 mesi sono state risolte :count segnalazioni.',
        'results' => ':count Risultati',
        'map_tab' => 'Mappa',
        'list_tab' => 'Elenco',
        'make_report_title' => 'Fai una segnalazione',
        'make_report_text' => 'Se vuoi aggiungere una segnalazione, puoi farlo dopo esserti autenticato con le tue credenziali SPID o CIE.',
        'make_report_btn' => 'Segnala disservizio',
        'load_more' => 'Carica altre segnalazioni',
        'rating_question' => 'Quanto è stato facile usare questo servizio?',
        'rating_legend' => 'Valuta da 1 a 5 stelle',
    ],

    // Contacts
    'contacts' => [
        'title' => 'Contatta il comune',
        'faq' => 'Leggi le domande frequenti',
        'assistance' => 'Richiedi assistenza',
        'phone' => 'Chiama il numero verde 05 0505',
        'appointment' => 'Prenota appuntamento',
    ],
];
