<?php

declare(strict_types=1);

/**
 * Consent to the processing of personal data — step 1 of the reporting flow.
 * Design Comuni `segnalazione-01-privacy.html`: consent is step 1 in all seven
 * service flows, without exception.
 */
return [
    'title' => 'Privacy notice and consent',
    'intro' => 'To submit a report we process the data you send. You can continue only if you consent. The data is processed to answer the report and for as long as needed to meet legal obligations.',
    'points' => [
        'Data processed: first name, last name, email address and the location of the issue.',
        'Purpose: to answer the report and send updates on its status.',
        'Legal basis: your consent. You can withdraw it at any time.',
        'Recipients: staff of the office handling the report. We do not share data with third parties.',
        'Retention: for the period required by law.',
    ],
    'link' => '/en/privacy',
    'link_label' => 'Read the full privacy notice',
    'opens_new_window' => 'opens in a new window',
    'label' => 'I have read the privacy notice and consent to the processing of my personal data for the handling of this report.',
    'error' => 'To submit a report you must consent to data processing.',
    'action' => 'Continue',
];
