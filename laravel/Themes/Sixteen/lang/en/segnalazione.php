<?php

declare(strict_types=1);

return [
    // Common
    'next' => 'Next',
    'back' => 'Back',
    'submit' => 'Submit',
    'required' => 'required',
    'max_chars' => 'Max :max characters',

    // Privacy step
    'privacy' => [
        'title' => 'Privacy Policy',
        'description' => 'Read the policy before proceeding',
        'details' => 'For details on personal data processing, see the ',
        'link_label' => 'privacy policy.',
        'accept_label' => 'I have read and understood the privacy policy',
    ],

    // Dati step
    'dati' => [
        'title' => 'Report Details',
        'place_label' => 'Issue Location',
        'place_placeholder' => 'E.g.: Via Roma 1, Florence',
        'details_label' => 'Issue Details',
        'type_label' => 'Issue Type*',
        'type_select' => 'Select...',
        'types' => [
            'verde' => 'Public greenery and urban furniture',
            'illuminazione' => 'Public lighting',
            'strade' => 'Road maintenance',
            'rifiuti' => 'Waste management',
            'altro' => 'Other',
        ],
        'title_label' => 'Title',
        'title_placeholder' => 'Brief description of the problem',
        'description_label' => 'Description*',
        'description_placeholder' => 'Describe the issue...',
        'images_label' => 'Images',
        'images_hint' => 'Select one or more images',
        'no_file' => 'No file selected',
    ],

    // Riepilogo step
    'riepilogo' => [
        'title' => 'Report Summary',
        'warning_title' => 'Warning',
        'warning_text' => 'The information provided has declarative value. Please verify that it is correct.',
        'disservizio_section' => 'Issue',
        'contacts_section' => 'Contacts',
        'terms_label' => 'I confirm that the information is truthful and accept the',
        'terms_link' => 'terms of service',
        'submit_label' => 'Submit report',
    ],

    // Conferma step
    'conferma' => [
        'title' => 'Report Submitted',
        'subtitle' => 'Thank you, we have received your <strong>report :code.</strong>',
        'visibility' => 'It will be visible on the <a href=":url" class="t-primary">list of all reports</a> once taken charge by the administration.',
        'email_sent' => 'We have sent the summary to the email:<br><strong>:email</strong>',
        'download_receipt' => 'Download receipt (PDF 100KB)',
        'consult_request' => 'Consult the request',
        'in_area' => 'in your reserved area',
        'related_services' => 'Related Services',
    ],

    // Elenco
    'elenco' => [
        'title' => 'Reports List',
        'subtitle' => 'In the last 12 months, :count reports have been resolved.',
        'results' => ':count Results',
        'map_tab' => 'Map',
        'list_tab' => 'List',
        'make_report_title' => 'Make a Report',
        'make_report_text' => 'If you want to add a report, you can do so after authenticating with your SPID or CIE credentials.',
        'make_report_btn' => 'Report Issue',
        'load_more' => 'Load more reports',
        'rating_question' => 'How easy was it to use this service?',
        'rating_legend' => 'Rate from 1 to 5 stars',
    ],

    // Contacts
    'contacts' => [
        'title' => 'Contact the Municipality',
        'faq' => 'Read FAQs',
        'assistance' => 'Request Assistance',
        'phone' => 'Call toll-free number 05 0505',
        'appointment' => 'Book Appointment',
    ],
];
