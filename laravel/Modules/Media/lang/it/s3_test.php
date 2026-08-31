<?php

declare(strict_types=1);

return [
    'navigation' => ['group' => 'Media'],
    'fields' => [
        'debug_output' => ['description' => 'Output di debug per test S3', 'label' => 'Output Debug', 'placeholder' => 'Risultati del test verranno mostrati qui', 'helper_text' => 'Informazioni di debug per la connessione S3', 'tooltip' => ''],
        'attachment' => ['label' => 'Allegato', 'placeholder' => 'Seleziona file da caricare', 'helper_text' => 'File da utilizzare per il test di caricamento', 'description' => 'File di test per verificare il caricamento su S3', 'tooltip' => ''],
    ],
    'actions' => [
        'sendEmail' => ['label' => 'Invia Email'],
        'clearResults' => ['label' => 'Cancella Risultati', 'icon' => 'clearResults', 'tooltip' => 'clearResults'],
        'debugConfig' => ['label' => 'Debug Configurazione', 'icon' => 'debugConfig', 'tooltip' => 'debugConfig'],
        'testFileOperations' => ['label' => 'Test Operazioni File', 'icon' => 'testFileOperations', 'tooltip' => 'testFileOperations'],
        'testCredentials' => ['label' => 'Test Credenziali', 'icon' => 'testCredentials', 'tooltip' => 'testCredentials'],
        'testS3Connection' => ['label' => 'Test Connessione S3', 'icon' => 'testS3Connection', 'tooltip' => 'testS3Connection'],
        'testPermissions' => ['label' => 'Test Permessi', 'icon' => 'testPermissions', 'tooltip' => 'testPermissions'],
        'testBucketPolicy' => ['label' => 'Test Policy Bucket', 'icon' => 'testBucketPolicy', 'tooltip' => 'testBucketPolicy'],
        'testCloudFront' => ['label' => 'Test CloudFront', 'icon' => 'testCloudFront', 'tooltip' => 'testCloudFront'],
        'test01' => ['label' => 'Test 01', 'icon' => 'test01', 'tooltip' => 'test01'],
    ],
    'label' => 'S3 Test',
    'plural_label' => 'S3 Test (Plurale)',
];
